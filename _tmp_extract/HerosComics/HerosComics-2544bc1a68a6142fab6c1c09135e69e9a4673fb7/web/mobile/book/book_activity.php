<?php
/*
 * 2016.10.31	softsyw
 * Desc : 책 담벼락
 */
require_once '../../wps-config.php';
require_once INC_PATH . '/classes/WpsPaginator.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-activity.php';

if ( empty($_GET['id']) ) {
	lps_js_back( '도서 아이디가 존재하지 않습니다.' );
}

$book_id = intval($_GET['id']);
$book_rows = lps_get_book($book_id);

$book_title = $book_rows['book_title'];

// community notice
$notice_all = lps_get_all_community_notice($book_id);

// page number
$page = empty($_GET['page']) ? 1 : $_GET['page'];

// search
$qa = empty($_GET['qa']) ? '' : trim($_GET['qa']);
$q = !isset($_GET['q']) ? '' : trim($_GET['q']);
$sql = '';
$pph = '';
$sparam = [];

// Simple search
if ( !empty($q) && !empty($qa) ) {
	if ( !strcmp($qa, 'user_name') ) {
		$sql = " AND u.display_name LIKE ?";
		array_push( $sparam, '%' . $q . '%' );
	} else if ( !strcmp($qa, 'subject') ) {
		$sql = " AND a.subject LIKE ?";
		array_push( $sparam, '%' . $q . '%' );
	} else {	// all
		$sql = " AND a.subject LIKE ? OR a.content LIKE ? ";
		array_push( $sparam, '%' . $q . '%', '%' . $q . '%' );
	}
}

// Positional placeholder ?
if ( !empty($sql) ) {
	$pph_count = substr_count($sql, '?');
	for ( $i = 0; $i < $pph_count; $i++ ) {
		$pph .= 's';
	}
}

if (!empty($pph)) {
	array_unshift($sparam, $pph);
}

// 내가 차단한 회원의 글은 감춘다.
$banned_user_ids = implode(',', lps_get_banned_user_ids( wps_get_current_user_id() ));
// filter sql
if (empty($banned_user_ids)) {
	$filter_sql = '';
} else {
	$filter_sql = " AND a.user_id NOT IN ($banned_user_ids) ";
}

$query = "
		SELECT
			*
		FROM
			bt_activity AS a
		LEFT JOIN
			bt_users AS u
		ON
			a.user_id = u.ID
		WHERE
			a.component = 'activity' AND
			a.type = 'activity_update' AND
			a.book_id = '$book_id' AND
			a.is_deleted = '0'
			$filter_sql
			$sql
		ORDER BY
			a.id DESC
";
$paginator = new WpsPaginator($wdb, $page);
$rows = $paginator->ls_init_pagination( $query, $sparam );
$total_count = $paginator->ls_get_total_rows();

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content board-area">
				<div class="board-header">
					<ul>
						<li class="board-tit">담벼락</li>
						<li class=""><?php echo $book_title ?></li>
						<li class="bord-lnb">
							<a href="book_activity_new.php?id=<?php echo $book_id ?>" data-ajax="false" class="lnb01"><img src="<?php echo MOBILE_URL ?>/img/board/btn_pen.png" alt="글쓰기"></a>
							<a href="#" id="btn-search" class="lnb02"><img src="<?php echo MOBILE_URL ?>/img/board/btn_search.png" alt="검색"></a>
						</li>
					</ul>
				</div>
				<div class="board-header-frm <?php echo empty($q) ? 'hide' : ''; ?>">
					<form action="<?php echo $_SERVER['PHP_SELF'] ?>" data-ajax="false">
						<input type="hidden" name="id" value="<?php echo $book_id ?>">
						<div class="board-list-select">
							<select name="qa">
								<option value="user_name" <?php echo strcmp($qa, 'user_name') ? '' : 'selected'; ?>>작성자</option>
								<option value="subject" <?php echo strcmp($qa, 'subject') ? '' : 'selected'; ?>>제목</option>
								<option value="all" <?php echo strcmp($qa, 'all') ? '' : 'selected'; ?>>제목 + 내용</option>
							</select>
						</div>
						<div class="board-list-inp">
							<input type="text" name="q" id="keyword" value="<?php echo $q ?>" placeholder="검색어 입력"><span class="search-del">입력 내용 삭제</span>
						</div>
						<button type="submit" class="search">search</button><button id="" type="button" class="close">닫기</button>
					</form>
				</div>
				<div class="board-list-area">
					<ul class="board-list">
			<?php 
			// 공지사항 
			$curdate = date('Y-m-d');
			
			if (!empty($notice_all)) {
				foreach ($notice_all as $key => $val) {
					$post_id = $val['ID'];
					$post_title = $val['post_title'];
					$post_date = substr($val['post_date'], 0, 10);
					$post_name = $val['post_name'];
					$view_count = wps_get_post_meta($post_id, 'wps_post_view_count');
					if (empty($view_count)) {
						$view_count = 0;
					}
					
					$post_date_label = strcmp($curdate, $post_date) ? date('Y.m.d', strtotime($val['post_date'])) : substr($val['post_date'], 11, 5);
			?>
						<li id="notice-<?php echo $post_id ?>" class="board-list-top notice_list">
							<div class="list-hot">공지</div>
							<div class="board-notice">
								<div class="board-list-tit"><?php echo $post_title ?></div>
								<div><?php echo $post_name ?> | <?php echo $post_date_label ?> | <?php echo number_format($view_count) ?></div>
							</div>
							<div class="board-list-arrow"></div>
						</li>
			<?php 
				}
			}
			?>
			
			<?php 
			// 담벼락 게시글
			if (!empty($rows)) {
				foreach ($rows as $key => $val) {
					$act_id = $val['id'];
					$user_id = $val['user_id'];
					$subject = $val['subject'];
					$created = substr($val['created_dt'], 0, 10);
					$count_hit = $val['count_hit'];
					$count_like = $val['count_like'];
					$count_comment = $val['count_comment'];
					
					$like_icon = $count_like > 9 ? 'icon-up-on' : 'icon-up-off';
					$created_label = strcmp($curdate, $created) ? date('Y.m.d', strtotime($val['created_dt'])) : substr($val['created_dt'], 11, 5);
					
					$user_rows = wps_get_user($user_id);
					$user_name = $user_rows['display_name'];
					$user_level = $user_rows['user_level'];
					
					// 회원 레벨에 따른 색상
					if ($user_level == '3' || $user_level == '6') {
						$user_color = 'txt-ff862d';
					} else if ($user_level == '7') {
						$user_color = 'txt-3c9cff';
					} else {
						$user_color = 'txt-3d3d3d';
					}
					
					$act_attach = lps_get_activity_meta($act_id, 'wps-community-attachment');
					$attach_file = empty($act_attach) ? '' : '<img class="img-file" src="' . MOBILE_URL . '/img/board/file.png" alt="">';
			?>
						<li id="activity-<?php echo $act_id ?>" class="activity_list <?php echo $user_color ?>">
							<div class="board-list-tit"><span><?php echo $subject ?></span>[<?php echo number_format($count_comment) ?>]</div>
							<div><?php echo $user_name ?> | <?php echo $created_label ?> | <?php echo number_format($count_hit) ?><?php echo $attach_file ?></div>
							<div class="<?php echo $like_icon ?>">[<?php echo number_format($count_like) ?>]</div>
							<div class="board-list-arrow"></div>
						</li>
			<?php 
				}
			}
			?>

					</ul>
					<!-- btn-top -->
					<div class="btn-top"><a href="#top"><img src="<?php echo MOBILE_URL ?>/img/board/btn_top.png" alt="위로가기"></a></div>
				</div>
				<!-- board-paging 
				<ul class="board-paging">
					<li><a href="#">처음</a></li>
					<li class="prev"><a href="#">앞으로</a></li>
					<li class="paging-on"><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li class="next"><a href="#">뒤로</a></li>
				</ul>-->
				
				<?php echo $paginator->ls_mobile_pagination_link( 'board-paging' ); ?>
				
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#btn-search").click(function() {
				$(".board-header-frm").removeClass("hide");
			});
			$(".close").click(function() {
				$(".board-header-frm").addClass("hide");
			});
			$(".search-del").click(function() {
				$("#keyword").val("");
			});

			// 공지사항 읽기
			$(".notice_list").click(function() {
				var id = $(this).attr("id").replace(/\D/g, "");
				location.href = "./book_notice_view.php?id=" + id + "&bid=<?php echo $book_id ?>";
			});
			
			// 게시글 읽기
			$(document).on("click", ".activity_list", function() {
// 			$(".activity_list").click(function() {
				var id = $(this).attr("id").replace(/\D/g, "");
				location.href = "./book_activity_view.php?id=" + id;
			});
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
