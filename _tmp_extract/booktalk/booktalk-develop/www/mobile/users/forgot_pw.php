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
				<h2>비밀번호 찾기</h2>
				<div class="find-pw">
					<form id="form-find-item">
						<div class="pw-inp">
							<label for="user_name">이름</label>
							<input type="text" id="user_name" name="user_name">
						</div>
						<div class="pw-inp">
							<label for="user_login">계정 이메일</label>
							<input type="text" id="user_login" name="user_login">
							<div class="pw-inp-ps">임시 비밀번호를 계정 이메일로 전송합니다.<br>로그인 후 새 비밀번호로 변경해주세요.</div>
						</div>
						<div><a href="#erreo-userInfo" id="btn-find" data-rel="popup" data-position-to="window" class="btn-skyblue">찾기</a></div>	
					</form>
				</div>
				<p class="service-center-tel">고객센터 번호 <br>02-1234-5678</p>
				<!-- 회원정보 popup -->
				<div data-role="popup" id="erreo-userInfo" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">ERROR</div>
						<p class="popup-txt">회원정보를 찾을 수 없습니다.</p>
						<div class="btn-popup"><a href="#" data-rel="back" class="btn-skyblue">확인</a></div>
					</div>
				</div>
				<!-- 회원정보 popup End -->
				<!-- 이메일 popup 
				<div data-role="popup" id="erreo-email" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">ERROR</div>
						<p class="popup-txt">이메일 주소를 찾을 수 없습니다.</p>
						<div class="btn-popup"><a href="#" data-rel="back" class="btn-skyblue">확인</a></div>
					</div>
				</div>
				<!-- 이메일 popup End -->
			</div>
			<!-- main End -->
		</div>
	
		<script>
		$(function() {
			$("#btn-find").click(function(e) {
				e.preventDefault();

				$.ajax({
					type : "POST",
					url : "./ajax/forgot-pw.php",
					data : $("#form-find-item").serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "forgot_ok.php";
						} else {
							$(".popup-txt").html(res.msg);
							$("#erreo-userInfo").popup("open");
						}
					}
				});
			});
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
