<?php
/*
 * Desc : 대여권
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
				<div class="mypage-cont">
					<div class="get-view">
						<h3>보유 대여권</h3>
						<div class="get-count">사용 가능한 대여권 <span>0</span>장</div>
					</div>
					<div class="btn-register">
						<a href="<?php echo MOBILE_URL ?>/mypage/wallet_borrowing_new.php" class="btn-green" data-ajax="false">대여권 등록</a>
					</div>
					<ul class="get-list">
						<li class="no-data">보유한 대여권이 없습니다.</li>
						<li>
							<div class="pr get-txt">어린왕자 - 24시간 대여권 </div>
							<div class="pr get-date">사용 날짜 | <span class="txt-a5ca71">2016.08.05</span> 00:00(자정)</div>
							<div class="get-list-r use">사용 가능</div>
						</li>
						<li>
							<div class="pr get-txt">어린왕자 - 24시간 대여권 </div>
							<div class="pr get-date">사용만료 날짜 | <span class="txt-a5ca71">2016.08.05</span> 00:00(자정)</div>
							<div class="get-list-r expired">만료</div>
						</li>
						<li>
							<div class="pr get-txt">어린왕자 - 24시간 대여권 </div>
							<div class="pr get-date">사용만료 날짜 | <span class="txt-a5ca71">2016.08.05</span> 00:00(자정)</div>
							<div class="pr get-date">대여 기간 | <span class="txt-a5ca71">2016.08.05</span> 00:00 ~ <span class="txt-a5ca71">2016.08.05</span> 00:00</div>
							<div class="get-list-r finish">사용완료</div>
						</li>
						<li>
							<div class="pr get-txt">어린왕자 - 24시간 대여권 </div>
							<div class="pr get-date">사용
							만료 날짜 | <span class="txt-a5ca71">2016.08.05</span> 00:00(자정)</div>
							<div class="pr get-date">대여 기간 | <span class="txt-a5ca71">2016.08.05</span> 00:00 ~ <span class="txt-a5ca71">2016.08.05</span> 00:00</div>
							<div class="get-list-r">사용중</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
