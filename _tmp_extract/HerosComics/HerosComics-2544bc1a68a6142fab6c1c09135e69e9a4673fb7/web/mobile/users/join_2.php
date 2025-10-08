<?php
/*
 * Desc : 회원가입 : 약관동의
 */
require_once '../../wps-config.php';

if (empty($_SESSION['join']['agree_term'])) {
	wps_redirect('join_1.php');
}
if (empty($_SESSION['join']['agree_privacy'])) {
	wps_redirect('join_1.php');
}

$sns_email = '';
$sns_name = '';
$sns_gender = '';
$sns_picture = '';

if (!empty(@$_COOKIE['snsfb']['id'])) {
	$sns_email = filter_var(@$_COOKIE['snsfb']['email'], FILTER_VALIDATE_EMAIL) ? $_COOKIE['snsfb']['email']: '';
	$sns_name = @$_COOKIE['snsfb']['name'];
	$sns_gender = @$_COOKIE['snsfb']['gender'];
	$sns_picture = @$_COOKIE['snsfb']['picture'];
} else if (!empty(@$_COOKIE['snsgg']['id'])) {
	$sns_email = filter_var(@$_COOKIE['snsgg']['email'], FILTER_VALIDATE_EMAIL) ? $_COOKIE['snsgg']['email']: '';
	$sns_name = @$_COOKIE['snsgg']['name'];
	$sns_picture = @$_COOKIE['snsgg']['picture'];
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
				<h2 class="hide">북톡 회원가입</h2>
				<div class="sub-area">
					<form id="form-new-user">
						<div class="inp-box">
							<label for="user_login">계정</label><span class="inp-txt">Booktalk 로그인에 사용되는 이메일 계정입니다.</span>
							<input type="email" id="user_login" name="user_login" class="mb" value="<?php echo $sns_email ?>">
							<input type="hidden" id="valid_user_login" value="<?php echo empty($sns_email) ? '1' : '0' ?>">
							<span class="inp-warn" id="warn-user_login"></span>
							<span class="inp-ps">*계정 인증메일이 발송 됩니다.<br>사용하시는 이메일 주소를 입력해주세요.</span>
						</div>
						<div class="inp-box">
							<label for="user_pass">비밀번호</label><span class="inp-txt">영문소문자, 숫자, 특수문자 중 2가지 이상을 혼용해 주세요.</span>
							<input type="password" id="user_pass" name="user_pass" class="mb" maxlength="20">
							<span class="inp-warn" id="warn-user_pass"></span>
						</div>
						<div class="inp-box mt">
							<label for="user_pass2">비밀번호 확인</label>
							<div class="inp-check"><input type="password" id="user_pass2" name="user_pass2" maxlength="20"></div>
						</div>
						<div class="inp-box inp-checkID">
							<label for="display_name">닉네임</label><span class="inp-txt">Booktalk 채팅방 등에 사용되는 이름입니다.</span>
							<div class="inp-check"><input type="text" id="display_name" name="display_name" class="mb" placeholder="15자 이내로 작성해주세요." maxlength="15">
								<span class="inp-check-ok inp-id-ok hide"></span><button type="button" class="btn-checkID">중복확인</button>
							</div>
							<input type="hidden" id="valid_display_name" value="-1">
							<span class="inp-warn" id="warn-display_name"></span>
							<span class="inp-ps">*한번 등록한 닉네임은 변경이 안됩니다. 신중하게 선택하세요.</span>
						</div>
						<div class="mt50">
							<div class="inp-box">
								<label for="userName">이름</label>
								<input type="text" id="user_name" name="user_name" maxlength="25" value="<?php echo $sns_name ?>">
							</div>
							<div class="inp-box">
								<ul class="inp-birth">
									<li>
										<label for="yy">출생년도</label>
										<input type="number" id="yy" name="yy" class="numeric" maxlength="4" placeholder="예)1988" min="1900" max="<?php echo date('Y') ?>">
									</li>
									<li>
										<label for="mm">월</label>
										<input type="number" id="mm" name="mm" class="numeric" maxlength="2" min="1" max="12">
									</li>
									<li>
										<label for="dd">일</label>
										<input type="number" id="dd" name="dd" class="numeric" maxlength="2" min="1" max="31">
									</li>
								</ul>
							</div>
							<div class="inp-gender">
								<div class="inp-tit">성별</div>
								<ul>
						<?php 
						foreach ($wps_user_gender as $key => $val) {
							if ( !empty($val) ) {
								$checked = $sns_gender == $key ? 'checked' : '';
						?>
									<li><input type="radio" name="gender" id="g<?php echo $key ?>" value="<?php echo $key ?>" <?php echo $checked ?>><label for="<?php echo $key ?>"><?php echo $val ?></label></li>
						<?php 
							}
						}
						?>

								</ul>
							</div>
						</div>
					</form>
				</div>
				<div class="btn-join-next"><a class="btn-skyblue" id="btn-next-step">다음 단계</a></div>
			</div>
			<!-- main End -->
		</div>

		<!-- InputMask
		<script src="<?php echo ADMIN_URL ?>/js/jquery/input-mask/jquery.inputmask.js"></script> -->
			
		<script>
		$(function() {
			$("#btn-next-step").click(function() {
				if ( $("#user_login").val() == "" ) {
					alert("계정을 입력해 주십시오.");
					return;
				}
				if ( $.trim($("#user_pass").val()) == "" || $("#user_pass").val().length < 6 ) {
					alert("비밀번호를 6자 이상 입력해 주십시오.");
					$("#user_pass").focus();
					return;
				}
				if ( $("#user_pass").val() != $("#user_pass2").val() ) {
					$("#warn-user_pass").html("비밀번호가 일치하지 않습니다.");
					return;
				}
				var chkNumeric = $("#user_pass").val().search(/[0-9]/g);
			    var chkAlphabet = $("#user_pass").val().search(/[a-z]/ig);
			    var chkSpecial = $("#user_pass").val().search(/[^0-9a-zA-Z]/g);

			    if ( chkNumeric > -1 ) {
			    	if ( chkAlphabet < 0 && chkSpecial < 0 ) {
			    		$("#warn-user_pass").html('영문소문자, 숫자, 특수문자 중 2가지 이상을 혼용해서 사용해 주십시오.');
				    	return;
			    	}
				} else if ( chkAlphabet > -1 ) {
					if ( chkNumeric < 0 && chkSpecial < 0 ) {
						$("#warn-user_pass").html('영문소문자, 숫자, 특수문자 중 2가지 이상을 혼용해서 사용해 주십시오.');
				    	return;
			    	}
				} else if ( chkSpecial > -1 ) {
					if ( chkNumeric < 0 && chkAlphabet < 0 ) {
						$("#warn-user_pass").html('영문소문자, 숫자, 특수문자 중 2가지 이상을 혼용해서 사용해 주십시오.');
				    	return;
			    	}
				}

			    if ( $("#user_name").val() == "" ) {
					alert("이름을 입력해 주십시오.");
					return;
				}
			    if ( $("#yy").val() == "" ) {
					alert("출생년도를 입력해 주십시오.");
					return;
				}
			    if ( $("#mm").val() == "" ) {
					alert("월을 입력해 주십시오.");
					return;
				}
			    if ( $("#dd").val() == "" ) {
					alert("일을 입력해 주십시오.");
					return;
				}
			    if ( $('input[name="gender"]:checked').length == 0 ) {
					alert("성별을 선택해 주십시오.");
					return;
				}

			    if ( $("#valid_user_login").val() == "0" ) {
					alert("사용할 수 없는 계정입니다.");
					return;
				}
			    if ( $("#valid_display_name").val() == "-1" ) {
					alert("닉네임 중복확인을 클릭해 주십시오.");
					return;
				}
			    if ( $("#valid_display_name").val() == "0" ) {
					alert("사용할 수 없는 닉네임입니다.");
					return;
				}

				$("#form-new-user").submit();
			});

			$("#form-new-user").submit(function(e) {
				e.preventDefault();
				
				$.ajax({
					type : "POST",
					url : "./ajax/join-2-new.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "join_3.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});

			$(".numeric").on("keydown, keypress, keyup", function() {

				var thisID = $(this).attr("id");
				var thisLen = $(this).val().length
				var maxLen = $(this).attr("maxlength");
				
				if ( thisLen == maxLen ) {
					if (thisID == "yy") {
						$("#mm").focus();
					} else if (thisID == "mm") {
						$("#dd").focus();
					}
						
				}
			});

			// 계정 check
			$("#user_login").blur(function() {
				var userLogin = $.trim($(this).val());

				if (userLogin != "") {
					$.ajax({
						type : "POST",
						url : "./ajax/join-2-user_login-check.php",
						data : {
							"user_login" : userLogin
						},
						dataType : "json",
						success : function(res) {
							if (res.code != "0") {
								$("#warn-user_login").html(res.msg);
								$("#user_login").val("");
								$("#user_login").focus();
								$("#valid_user_login").val(0);
							} else {
								$("#valid_user_login").val(1);
							}
						}
					});
				}
			});

			// 닉네임 체크
			$(".btn-checkID").click(function() {
				var displayName = $("#display_name").val();
				var chkSpecial = /[^0-9a-zA-Z]/g;
				if (displayName == "") {
					alert("닉네임을 입력해 주십시오.");
				}
				if (chkSpecial.test(displayName)) {
// 					alert("닉네임은 영문 대소문자와 숫자만 사용할 수 있습니다.");
				}

				if (displayName != "") {
					$.ajax({
						type : "POST",
						url : "./ajax/join-2-display_name-check.php",
						data : {
							"display_name" : displayName
						},
						dataType : "json",
						success : function(res) {
							if (res.code != "0") {
								$("#warn-display_name").html(res.msg);
								$("#display_name").focus();
								$("#valid_display_name").val(0);
								$(".inp-id-ok").addClass("hide");
							} else {
								$("#warn-display_name").html("");
								$("#valid_display_name").val(1);
								$(".inp-id-ok").removeClass("hide");
								
							}
						}
					});
				}
			});
			
			// 계정 경고 메시지 감추기
			$("#user_login").on("keyup", function() {
				if ($(this).val().length > 2) {
					$("#warn-user_login").html("");
				}
			});
			// 비밀번호 경고 메시지 감추기
			$("#user_pass").on("keyup", function() {
				if ($(this).val().length > 3) {
					$("#warn-user_pass").html("");
				}
			});	

		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
