<?php
/*
 * Desc : 회원가입 : 약관동의
 */
require_once '../../wps-config.php';

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
			<div class="join-welCome-area">
				<h1><img src="<?php echo MOBILE_URL ?>/img/common/logo_226.png" alt="BOOKtalk"></h1>
				독자들과 함께 만드는 책<br>BOOKtalk<br><br><br>회원가입이 완료 되었습니다.
			</div>
			<div class="btn-go-home"><a href="#" id="go-home" class="btn-skyblue">Home 화면으로 가기</a></div>
		</div>
			
		<script>
		$(function() {
			$("#go-home").click(function(e) {
				e.preventDefault();
				location.href = "login_pass.php";
			});
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
