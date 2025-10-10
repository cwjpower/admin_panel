<?php
/*
 * Desc : 내 서재, 구매한 책
 * 		TODO 정렬순서 중 장르/최근읽음 미처리
 */
require_once '../../wps-config.php';
require_once INC_PATH . '/classes/WpsPaginator.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();
error_log('user id ============='.$user_id);

// page number
$page = empty($_GET['page']) ? 1 : $_GET['page'];
$sparam = [];
$ob = 'bt';
$lt = empty($_GET['lt']) ? 'gal' : $_GET['lt'];

// 정렬순서
if (empty($_GET['ob'])) {
	$orderby = 'b.book_title ASC';
} else {
	$ob = $_GET['ob'];
	if (!strcmp($ob, 'lb')) {	// 구매일
		$orderby = 'o.created_dt DESC';
	} else if (!strcmp($ob, 'gr')) {	// 장르별
		$orderby = 'o.created_dt DESC';
	} else if (!strcmp($ob, 'lv')) {	// 최근 읽은
		$orderby = 'o.created_dt DESC';
	} else {	// bt 제목
		$orderby = 'b.book_title ASC';
	}
}

$query = "
	SELECT
		*
	FROM
		bt_order AS o
	INNER JOIN
		bt_order_item AS i
	INNER JOIN
		bt_books AS b
	WHERE
		o.user_id = '$user_id' AND
		o.order_id = i.order_id AND
		i.book_id = b.ID
	ORDER BY
		$orderby
";


$paginator = new WpsPaginator($wdb, $page, 1000);

$rows = $paginator->ls_init_pagination( $query, $sparam );

//$total_count = $paginator->ls_get_total_rows();

//$total_records = $paginator->ls_get_total_records();


require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content mypage-area">
				<h2>내 서재
					<ul class="my-library-view list-item">
						<li class="list_album"><img src="../img/common/icon_list_album.png" alt="앨범형"></li>
						<li class="list_board"><img src="../img/common/icon_list_board.png" alt="게시판형"></li>
					</ul>
				</h2>
				<div class="my-library-sel">
					<input type="hidden" id="lt" value="<?php echo $lt ?>">
					<select name="ob" id="order-by">
						<option value="bt" <?php echo strcmp($ob, 'bt') ? '' : 'selected'; ?>>제목</option>
						<option value="lb" <?php echo strcmp($ob, 'lb') ? '' : 'selected'; ?>>구매일</option>
						<option value="gr" <?php echo strcmp($ob, 'gr') ? '' : 'selected'; ?>>장르별</option>
						<option value="lv" <?php echo strcmp($ob, 'lv') ? '' : 'selected'; ?>>최근 읽은 순</option>
					</select>
				</div>
				<div id="list-album" class="list-album-area list-item-cont01" style="display:<?php echo strcmp($lt, 'gal') ? 'none' : 'block' ?>;">
					<ul class="list-album">
			<?php 
			if ( !empty($rows) ) {
				foreach ( $rows as $key => $val ) {

					$book_id = $val['ID'];
					$book_title = $val['book_title'];
					$cover_img = $val['cover_img'];
			?>
						<li>
							<div class="list-album-box">
								<div class="albumlist">
									<div class="book-opc"></div>
									<div class="damByeorak">담벼락</div>
									<a href="../book/book_activity.php?id=<?php echo $book_id ?>" data-ajax="false"><img src="<?php echo $cover_img ?>" alt="담벼락으로 이동"></a>
								</div>
								<div class="albumlist-booktit"><a href="../book/book_activity.php?id=<?php echo $book_id ?>"><?php echo $book_title ?></a></div>
							</div>
						</li>
			<?php 
				}
			}
			?>
					</ul>
				</div>
				<div id="list-board" class="list-board-area list-item-cont02" style="display:<?php echo strcmp($lt, 'list') ? 'none' : 'block' ?>;">
					<ul class="list-board">
			<?php 
			if ( !empty($rows) ) {
				foreach ( $rows as $key => $val ) {
					$book_id = $val['ID'];
					$book_title = $val['book_title'];
			?>
						<li><a href="../book/book_activity.php?id=<?php echo $book_id ?>"><?php echo $book_title ?></a></li>
			<?php 
				}
			}
			?>
					</ul>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			var  $listItem= $('.list-item li');
			
			$listItem.on('click', function(){
				$listItem.removeClass('on');

				if( $(this).index() == 0 ){
					$(".list-item-cont01").css("display","block");
					$(".list-item-cont02").css("display","none");
					$("#lt").val("gal");
				} else{
					$(".list-item-cont01").css("display","none");
					$(".list-item-cont02").css("display","block");
					$("#lt").val("list");
				}

				$(this).addClass('on');
			});

			// 정렬순
			$("#order-by").change(function() {
				location.href = "?ob=" + $(this).val() + "&lt=" + $("#lt").val();
			});		
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
