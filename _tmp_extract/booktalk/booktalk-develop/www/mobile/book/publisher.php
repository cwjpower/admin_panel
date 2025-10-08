<?php
/*
 * Desc : 입점 출판사
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-page.php';

// 출판사 입점도서 from page관리 admin
$publisher_rows = lps_get_publishers();

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>출판사 입점 도서</h2>
				<div class="publisher-area">
					<h3 class="publisher-tit">입점 출판사</h3>
					<ul class="publisher-list">
			<?php
			if ( !empty($publisher_rows) ) {
				foreach ( $publisher_rows as $key => $val ) {
					$pb_id = $val['ID'];
					$publisher_id = $val['publisher_id'];
					
					$users = wps_get_user_by('ID', $publisher_id);
					$user_name = $users['user_name'];
					$avatar = lps_get_user_avatar($publisher_id);
			?>
						<li>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<a href="<?php echo MOBILE_URL ?>/book/publisher_book.php?id=<?php echo $pb_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $avatar ?>"></a>
							</div>
							<div class="publisher-name ellipsis">
								<a href="<?php echo MOBILE_URL ?>/book/publisher_book.php?id=<?php echo $pb_id ?>" data-ajax="false"><?php echo $user_name ?></a>
							</div>
						</li>
			<?php 
				}
			}
			?>
					</ul>
					<div class="list-line"></div>
				</div>
			</div>
			<!-- main End -->
		
		</div>
		
		<script>
		$(function() {
			$("#lps_order").change(function() {
				var order = $(this).val();

				location.href = "?id=<?php echo $publisher_id ?>&ob=" + order; 
			});
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
