<?php
/*
 * Desc : 큐레이팅 리스트
 * 		독자들의 큐레이션 리스트만 보인다. 관리자 것은 안 보인다. 메인하고 구성이 다르다.
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-page.php';

$curations = lps_get_user_curations();

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<div class="curating-header">
					<h2>큐레이팅</h2>
					<div class="curating-header-r">
						<a href="<?php echo MOBILE_URL ?>/book/curation_new.php" data-ajax="false" class="btn-curating-thema">테마등록</a>
						<div class="curating-header-select">
							<form action="">
								<select name="" id="">
									<option value="">등록일 순</option>
									<option value="">누적 조회순</option>
								</select>
							</form>
						</div>
					</div>
				</div>
				<div class="curating-area">
					<ul class="curating-list-area">
			<?php 
			if (!empty($curations)) {
				foreach ($curations as $key => $val) {
					$cid = $val['ID'];
					$ctitle = $val['curation_title'];
					$cimg = unserialize($val['cover_img']);
						$cover = $cimg['file_url'];
					$cmeta = unserialize($val['curation_meta']);
						$book_count = count($cmeta);
					$cuserid = $val['user_id'];
					$user_row = wps_get_user($cuserid);
						$user_name = $user_row['display_name'];
					$user_avatar = wps_get_user_meta($cuserid, 'wps_user_avatar');
					if (empty($user_avatar)) {
						$user_avatar = INC_URL . '/images/common/photo-default.png';
					}
					$hit_count = $val['hit_count'];
			?>
						<li>
							<div class="curating-box">
								<div class="curating-top">
									<div>
										<a href="curation_view.php?id=<?php echo $cid ?>" data-ajax="false"><img src="<?php echo $cover ?>" style="width: 100%; height: 125px;"></a>
									</div>
									<div class="ellipsis curating-tit">
										<a href="curation_view.php?id=<?php echo $cid ?>" data-ajax="false"><?php echo $ctitle ?></a>
									</div>
								</div>
								<div class="curating-bottom">
									<div class="curating-user-img"><img src="<?php echo $user_avatar?>" title="프로필사진"></div>
									<div class="curating-user-book">
										<span class="writer"><?php echo $user_name ?></span>
										<span><span class="curating-num">포함된 책</span> <?php echo number_format($book_count) ?>권</span>
										<span><span class="curating-num">조회수</span> <?php echo number_format($hit_count) ?></span>
									</div>
								</div>
							</div>
						</li>
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
			
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
