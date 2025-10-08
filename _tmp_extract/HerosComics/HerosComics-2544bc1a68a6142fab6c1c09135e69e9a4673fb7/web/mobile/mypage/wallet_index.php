<?php
/*
 * Desc : 내 지갑 메인 #20
 */
require_once '../../wps-config.php';

wps_auth_mobile_redirect();

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content mypage-area">
				<h2>내 지갑</h2>
				<div class="mypage-menu-area">
					<p class="user-id"><?php echo wps_get_current_user_display_name() ?></p>
					<ul class="mypage-menu">
						<li>
							<a href="<?php echo MOBILE_URL ?>/mypage/wallet_cash.php" data-ajax="false">
								<div class="mypage-menu-bg mypage-menu01">
									<div class="mypage-menu-tit">캐시<span class="mypage-menu-num"><?php echo number_format($current_user_cash) ?> 원</span></div>
								</div>
							</a>
						</li>
						<li>
							<a href="<?php echo MOBILE_URL ?>/mypage/wallet_point.php" data-ajax="false">
								<div class="mypage-menu-bg mypage-menu02">
									<div class="mypage-menu-tit">포인트<span class="mypage-menu-num"><?php echo number_format($current_user_point) ?> 원</span></div>
								</div>
							</a>
						</li>
						<li>
							<a href="<?php echo MOBILE_URL ?>/mypage/wallet_coupon.php" data-ajax="false">
								<div class="mypage-menu-bg mypage-menu03">
									<div class="mypage-menu-tit">쿠폰<span class="mypage-menu-num">원</span></div>
								</div>
							</a>
						</li>
						<li>
							<a href="<?php echo MOBILE_URL ?>/mypage/wallet_borrowing.php" data-ajax="false">
								<div class="mypage-menu-bg mypage-menu04">
									<div class="mypage-menu-tit">대여권<span class="mypage-menu-num">원</span></div>
								</div>
							</a>
						</li>						
					</ul>
					<div class="btn-charge"><a href="<?php echo MOBILE_URL ?>/mypage/buy_cash.php" data-ajax="false" class="btn-skyblue">캐시 충전하기</a></div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
