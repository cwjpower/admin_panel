<?php
/*
 * 2016.11.30	softsyw
 * Desc : 공지사항 for Mobile web
 */
require_once '../../wps-config.php';
require_once INC_PATH . '/classes/WpsPaginator.php';


$post_type = 'notice_new';

// page number
$page = empty($_GET['page']) ? 1 : $_GET['page'];

// search
$sts = empty($_GET['status']) ? '' : $_GET['status'];
$qa = empty($_GET['qa']) ? '' : trim($_GET['qa']);
$q = empty($_GET['q']) ? '' : trim($_GET['q']);
$sql = '';
$pph = '';
$sparam = [];

// Simple search
if ( empty($qa) ) {
	if ( !empty($q) ) {
		$sql = " AND ( p.post_content LIKE ? OR p.post_title LIKE ? OR p.post_name LIKE ? ) ";
		array_push( $sparam, '%'.$q.'%', '%'.$q.'%', '%'.$q.'%' );
	}
} else {
	if ( !empty($q) ) {
		if ( !strcmp($qa, 'isbn') ) {
			$sql = " AND $qa = ?";
			array_push( $sparam, $q );
		} else {
			$sql = " AND $qa LIKE ?";
			array_push( $sparam, '%'.$q.'%' );
		}
	}
}

if ($sts) {
	$sql .= " AND p.post_type_area LIKE ?";
	array_push( $sparam, '%'.$sts.'%' );
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

$query = "
		SELECT
			p.ID,
			p.post_name,
			p.post_date,
			p.post_title,
			p.post_parent,
			p.post_status,
			p.post_user_id,
			p.post_email,
			p.post_order,
			p.post_type,
			p.post_type_secondary,
			p.post_type_area,
			p.post_modified,
			m.meta_value AS post_view_count
		FROM
			bt_posts AS p
		LEFT JOIN
			bt_posts_meta AS m
		ON
			p.ID = m.post_id AND
			m.meta_key = 'post_view_count'
		WHERE
			p.post_type = '$post_type' AND
			p.post_type_area LIKE '%web%'
			$sql
		ORDER BY
			p.post_order DESC,
			p.post_modified DESC
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
				<div class="sevice-menu-area">
					<div data-role="navbar">
						<ul class="sevice-menu">
							<li><a href="<?php echo MOBILE_URL ?>/service/cs.php" data-ajax="false"><img src="../img/service/service_m01_off.png" alt="">고객센터 안내</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false" class="ui-btn-active"><img src="../img/service/service_m02_on.png" title="공지사항">공지사항</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/faq.php" data-ajax="false"><img src="../img/service/service_m03_off.png" title="자주 묻는 질문">자주 묻는 질문</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/qna.php" data-ajax="false"><img src="../img/service/service_m04_off.png" title="1:1 문의">1:1 문의</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false"><img src="../img/service/service_m05_off.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
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
			if ( !empty($rows) ) {
				$list_no = $page == 1 ? $total_count : $total_count - (($page - 1) * $paginator->rows_per_page);

				foreach ( $rows as $key => $val ) {
					$post_id = $val['ID'];
					$post_title = htmlspecialchars($val['post_title']);
					$post_date = $val['post_date'];
					$post_date_label = substr($post_date, 0, 10);
					$post_type_secondary = $val['post_type_secondary'];
					$post_type_area = empty($val['post_type_area']) ? '' : lps_get_value_by_key($val['post_type_area'], $wps_notice_coverage);
					$post_view_count = number_format($val['post_view_count']);
					$post_order = $val['post_order'];
					
					if ( !empty($val['post_user_id']) ) {
						$user_data = wps_get_user_by( 'ID', $val['post_user_id'] );
						$post_name = $user_data['user_name'];
					}
					
					// 필독(Top) 여부
					$notice_label = empty($post_order) ? '' : '<div class="list-hot">필독</div>';
					
			?>
						<li id="notice-<?php echo $post_id ?>" class="board-list-top notice_list">
							<?php echo $notice_label ?>
							<div class="board-notice">
								<div> <a href="notice_view.php?pid=<?php echo $post_id ?>" data-ajax="false" style="font-size: 15px;"><?php echo $post_title ?></a></div>
								<div class="board-list-tit"><?php echo $post_name ?> | <?php echo $post_date_label ?></div>
							</div>
							<div class="board-list-arrow"></div>
						</li>
			<?php
					$list_no--;
				}
			}
			?>
					</ul>
					<!-- btn-top -->
					<div class="btn-top"><a href="#top"><img src="<?php echo MOBILE_URL ?>/img/board/btn_top.png" alt="위로가기"></a></div>
				</div>
				
				<?php echo $paginator->ls_mobile_pagination_link( 'board-paging' ); ?>
				
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
