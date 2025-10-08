<?php
/*
 * Desc : 자주 묻는 질문
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-term.php';
require_once INC_PATH . '/classes/WpsPaginator.php';

// 질문유형
$faq_type_groups = wps_get_term_by_taxonomy('wps_category_faq');

// page number
$page = empty($_GET['page']) ? 1 : $_GET['page'];

// search
$sts = empty($_GET['grp']) ? '' : $_GET['grp'];
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
	$sql .= " AND p.post_type_secondary = ?";
	array_push( $sparam, $sts );
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
			p.post_content,
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
			p.post_type = 'faq_new'
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

		<style>
		.ui-collapsible-themed-content .ui-collapsible-content { border-top-width: 1px; }
		</style>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content service-area">
				<div class="sevice-menu-area">
					<div data-role="navbar">
						<ul class="sevice-menu">
							<li><a href="<?php echo MOBILE_URL ?>/service/cs.php" data-ajax="false"><img src="../img/service/service_m01_off.png" alt="">고객센터 안내</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false"><img src="../img/service/service_m02_off.png" title="공지사항">공지사항</a></li>
							<li><a href="#" class="ui-btn-active"><img src="../img/service/service_m03_on.png" title="자주 묻는 질문">자주 묻는 질문</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/qna.php" data-ajax="false"><img src="../img/service/service_m04_off.png" title="1:1 문의">1:1 문의</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false"><img src="../img/service/service_m05_off.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
				</div>
				<div class="sevice-conts">
					<h2>자주 묻는 질문</h2>
					<div class="sevice-faq-area">
						<ul class="sevice-faq-menu">
				<?php 
				if (!empty($faq_type_groups)) {
					foreach ($faq_type_groups as $key => $val) {
						$term_id = $val['term_id'];
						$tname = $val['name'];
				?>
							<li><a href="?grp=<?php echo $tname ?>" id="faq-term-<?php echo $term_id ?>" class="faq_type_group" data-ajax="false"><?php echo $tname ?></a></li>
				<?php
					}
				}
				?>
						</ul>
					</div>
					<div class="faq-search">
						<form id="search-form" data-ajax="false">
							<input type="text" name="q" value="<?php echo $q ?>" class="form-control">
							<button type="submit" class="btn-faq-search">검색</button>
						</form>
					</div>
					
					<div class="answer-area">
						<div data-role="collapsibleset" data-theme="a" data-content-theme="a">
			<?php
			if ( !empty($rows) ) {
				$list_no = $page == 1 ? $total_count : $total_count - (($page - 1) * $paginator->rows_per_page);

				foreach ( $rows as $key => $val ) {
					$post_id = $val['ID'];
					$post_title = htmlspecialchars($val['post_title']);
					$post_content = $val['post_content'];
					$post_date = $val['post_date'];
					$post_type_secondary = $val['post_type_secondary'];
					$post_type_area = lps_get_value_by_key($val['post_type_area'], $wps_notice_coverage);
					$post_view_count = number_format($val['post_view_count']);
					$post_order = $val['post_order'];
					
					if ( !empty($val['post_user_id']) ) {
						$user_data = wps_get_user_by( 'ID', $val['post_user_id'] );
						$post_name = $user_data['user_name'];
					}
					
					// 필독(Top) 여부
					$notice_label = empty($post_order) ? '' : '<span class="label label-warning">필독</span>';
					
			?>
							<div data-role="collapsible" style="margin: 20px 0;">
								<h3 style="margin-bottom: 7px;"><strong><?php echo $post_title ?></strong></h3>
								<p><?php echo $post_content ?></p>
							</div>
			<?php
					$list_no--;
				}
			}
			?>
						</div>
					</div>
					
				</div>
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
