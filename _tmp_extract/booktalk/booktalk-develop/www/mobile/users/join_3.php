<?php
/*
 * Desc : 회원가입 : 약관동의
 */
require_once '../../wps-config.php';

if (empty($_SESSION['join']['user_login'])) {
	wps_redirect('join_1.php');
}
if (empty($_SESSION['join']['user_name'])) {
	wps_redirect('join_1.php');
}
if (empty($_SESSION['join']['user_pass'])) {
	wps_redirect('join_1.php');
}

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
			<!-- header -->
		<?php 
		require_once MOBILE_PATH . '/mobile-navbar.php';
		?>
			<!-- header End -->
			<!-- main -->
			<div data-role="main" class="ui-content join-area">
				<h2 class="join-tit">선택 입력사항</h2>
				<div class="sub-area">
					<form id="form-new-user">
						<input type="hidden" name="optional_info" id="optional_info" value="0">
						<div class="inp-box inp-authno-msg">
							<label for="mobile">휴대폰번호</label><span class="inp-txt">“-” 없이 입력하세요. </span>
							<input type="text" id="mobile" name="mobile" class="mb" maxlength="20"><button type="button" class="btn-authno-msg" id="btn-req-auth">인증번호 요청</button>
							<span class="inp-ps">*비상연락용 및 쿠폰, 이벤트 프로모션에 사용됩니다.</span>
						</div>
						<div class="inp-box inp-authno hide">
							<label for="auth_code">인증번호</label><span class="inp-txt">4자리 숫자. </span>
							<input type="number" id="auth_code" class="mb" maxlength="6"><button class="btn-authno">인증확인</button><button class="btn-re-authno">인증번호 재전송</button>
							<span class="inp-ps">*인증번호를 못 받으신 경우 인증번호 재전송을 눌러주세요.</span>
						</div>
						<div class="inp-box">
							<label for="residence">거주지</label>
							<select id="residence" name="residence">
				<?php 
				foreach ($wps_user_residence_area as $key => $val) {
					if ( !empty($val) ) {
				?>
								<option value="<?php echo $key ?>"><?php echo $val ?></option>
				<?php 
					}
				}
				?>
							</select>
						</div>
					</form>
				</div>
				<ul class="btn-auth">
					<li><a href="#joinAuthno" data-rel="popup" data-position-to="window" class="btn-gray">건너뛰기</a></li>
					<li><a href="#" class="btn-skyblue" id="btn-join-ok">입력완료</a></li>
				</ul>
				<!-- 선택 입력사항 popup -->
				<div data-role="popup" id="joinAuthno" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">선택 입력사항</div>
						<p class="popup-txt">해당 항목이 작성되어 있지 않으면,<br>통계 서비스를 이용하실 수 없습니다.</p>
						<ul class="btn2-popup">
							<li><a href="#" class="btn-skyblue" id="btn-skip-optional">확인</a></li>
							<li><a href="#" data-rel="back" class="btn-gray">취소</a></li>
						</ul>
					</div>
				</div>
				<!-- 선택 입력사항 popup End -->
			</div>
			<!-- main End -->
		</div>
		
		<script src="<?php echo INC_URL ?>/js/ls-util.js"></script>
		<!-- Numeric hyphen -->
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.numeric.min.js"></script>
			
		<script>
		$(function() {
			$("#mobile").numeric();

			// 인증번호 요청
			$("#btn-req-auth").click(function() {
				$(".inp-authno").removeClass("hide");
			});

			// 건너뛰기
			$("#btn-skip-optional").click(function() {
				$("#optional_info").val(0);
				$("#form-new-user").submit();
			});

			// 입력완료
			$("#btn-join-ok").click(function() {
				if ( $("#mobile").val() == "" ) {
					alert("휴대전화번호를 입력해 주십시오.");
					return;
				}
				if ( $("#residence").val() == "" ) {
					alert("거주지를 선택해 주십시오.");
					return;
				}
				$("#optional_info").val(1);
						
				$("#form-new-user").submit();
			});

			$("#form-new-user").submit(function(e) {
				e.preventDefault();
				
				$.ajax({
					type : "POST",
					url : "./ajax/join-3-new.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "join_welcome.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
