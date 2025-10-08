<?php
/*
 * Desc : 보유쿠폰 등록
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
				<div class="num-input-area">
					<strong class="num-input-txt">쿠폰 번호를 입력하세요</strong>
					<form action="">
						<div class="num-input">
							<input type="text" maxlength="20" placeholder="일련번호 20자리를 '-'없이 입력하세요">
							<a href="#couponPop" data-rel="popup" data-position-to="window" class="btn-num-register btn-green">등록하기</a>
						</div>
					</form>
					<p class="num-input-error numipt-error01">유효하지 않는 번호 입니다.</p>
					<p class="num-input-error numipt-error02">이미 사용한 번호 입니다.</p>
					<p class="num-input-error numipt-error03">사용 기간이 만료된 번호 입니다.</p>
					<ul class="num-input-ps">
						<li>- 쿠폰 등록 후, 보유 쿠폰에서 사용조건, 기간 등을 확인하세요</li>
						<li>- 쿠폰은 등록 시 바로 사용할 수 있습니다</li>
						<li>- 알파벳과 숫자를 명확히 구분하여 입력하세요</li>
						<li>- 쿠폰 등록시 “-” (하이폰)은 생략해서 입력하세요</li>
					</ul>
				</div>
				<!-- 쿠폰 등록 popup -->
				<div data-role="popup" id="couponPop" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">쿠폰 등록</div>
						<p class="popup-txt">쿠폰 등록이 완료 되었습니다.</p>
						<ul class="btn-popup">
							<li><a href="#" class="btn-skyblue">확인</a></li>
						</ul>
					</div>
				</div>
				<!-- 쿠폰 등록 popup End -->
			</div>
			<!-- main End -->
		</div>
		
		<script>
		
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
