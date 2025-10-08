<?php
/*
 * Desc : 비밀번호 수정
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
			<div data-role="main" class="ui-content myinfo-area">
				<h2>개인정보 수정 &gt; 비밀번호 수정</h2>
				<form id="form-item-edit">
					<div class="myinfo-modify">
						<h3 class="myinfo-tit">비밀번호 수정</h3>
						<div class="myinfo-list-area">
							<ul class="myinfo-list-modify pw-list-modify">
								<li class="inp-box1">
									<div class="inp-tit"><label for="user_pass_current">현재 비밀번호</label></div>
									<div class="inp">
										<input type="password" id="user_pass_current" name="user_pass_current">
									</div>
								</li>
								<li class="inp-box1">
									<div class="inp-tit"><label for="user_pass">새 비밀번호</label></div>
									<div class="inp">
										<input type="password" id="user_pass" name="user_pass">
									</div>
								</li>
								<li class="inp-box1">
									<div class="inp-tit"><label for="user_pass2">새 비밀번호 확인</label></div>
									<div class="inp">
										<input type="password" id="user_pass2" name="user_pass2">
									</div>
								</li>
								<!--  
								<li class="inp-box">
									<div class="add-myinfo-tit"><label for="Pw">새 비밀번호 확인</label></div>
									<div class="add-myinfo user-auth-ok">
										<input type="password" id="Pw" class="">
									</div>
								</li>
								<li class="inp-box">
									<div class="add-myinfo-tit"><label for="newPw">새 비밀번호</label></div>
									<div class="add-myinfo user-auth-ok">
										<input type="password" id="newPw" class="">
									</div>
								</li>
								<li class="inp-box">
									<div class="add-myinfo-tit"><label for="chekNewPw">새 비밀번호 확인</label></div>
									<div class="add-myinfo user-auth-ok">
										<input type="password" id="chekNewPw" class="">
									</div>
								</li>-->
							</ul>
						</div>
					</div>
				</form>
				<ul class="btn-myinfo-modify">
					<li><a href="#" class="btn-gray" id="btn-submit">확인</a></li>
					<li><a href="#" class="btn-skyblue" data-rel="back">취소</a></li>
				</ul>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#btn-submit").click(function(e) {
				e.preventDefault();
				$("#form-item-edit").submit();
			});

			$("#form-item-edit").submit(function(e) {
				e.preventDefault();

				if ( $("#user_pass_current").val() == "" ) {
					alert("현재 비밀번호를 입력해 주십시오.");
					$("#user_pass_current").focus();
					return false;
				}

				if ( $.trim($("#user_pass").val()) == "" || $("#user_pass").val().length < 6 ) {
					alert("새로운 비밀번호를 6자 이상 입력해 주십시오.");
					$("#user_pass").focus();
					return false;
				}
				if ( $("#user_pass").val() != $("#user_pass2").val() ) {
					alert("비밀번호가 일치하지 않습니다.");
					return false;
				}
				var chkNumeric = $("#user_pass").val().search(/[0-9]/g);
			    var chkAlphabet = $("#user_pass").val().search(/[a-z]/ig);
			    var chkSpecial = $("#user_pass").val().search(/[^0-9a-zA-Z]/g);

			    if ( chkNumeric > -1 ) {
			    	if ( chkAlphabet < 0 && chkSpecial < 0 ) {
			    		alert('영문소문자, 숫자, 특수문자 중 2가지 이상을 혼용해서 사용해 주십시오.');
				    	return false;
			    	}
				} else if ( chkAlphabet > -1 ) {
					if ( chkNumeric < 0 && chkSpecial < 0 ) {
						alert('영문소문자, 숫자, 특수문자 중 2가지 이상을 혼용해서 사용해 주십시오.');
				    	return false;
			    	}
				} else if ( chkSpecial > -1 ) {
					if ( chkNumeric < 0 && chkAlphabet < 0 ) {
						alert('영문소문자, 숫자, 특수문자 중 2가지 이상을 혼용해서 사용해 주십시오.');
				    	return false;
			    	}
				}

			    $.ajax({
					type : "POST",
					url : "./ajax/user-passwd.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "user_index.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});
			
		}); // $
		
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
