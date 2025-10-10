<?php
/*
 * Desc : 비밀번호 찾기
 */
require_once '../../wps-config.php';

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
			<!-- header -->
		<?php 
		require_once MOBILE_PATH . '/mobile-navbar.php';
		?>
			<!-- header End -->
			<!-- main -->
			<div data-role="main" class="ui-content pw-area">
				<h2 class="hide">비밀번호 찾기</h2>
				<div class="find-pw">
					<div class="user-pw">
						<strong>임시 비밀번호를 계정 이메일로<br>전송하였습니다.</strong>
						로그인 후 새 비밀번호를 변경하여 주세요.
					</div>
					<div><a href="#" class="btn-skyblue">Home 화면으로 가기</a></div>	
				</div>
				<p class="service-center-tel">고객센터 번호 <br>02-1234-5678</p>
			</div>
			<!-- main End -->
		</div>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
