<?php
/*
 * Desc : 회원가입 : 약관동의
 */
require_once '../../wps-config.php';

$redirect = empty($_REQUEST['redirect']) ? base64_encode( MOBILE_URL ) : $_REQUEST['redirect'];

require_once MOBILE_PATH . '/mobile-header.php';

?>
		<!-- Google Sign-in -->
		<!-- script src="https://apis.google.com/js/platform.js" async defer></script> -->
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
		<script src="https://apis.google.com/js/api:client.js"></script>

		<script>
		// facebook SDK
		function statusChangeCallback(response) {
			if (response.status === 'connected') {
				// Logged into your app and Facebook.
		// 		FB.api('/me', {fields: 'email,first_name,last_name,picture,gender'}, function(response) {
		// 		});
			} else if (response.status === 'not_authorized') {
				// The person is logged into Facebook, but not your app.
			} else {
				// The person is not logged into Facebook, so we're not sure if they are logged into this app or not.
			}
		}
		
		function checkLoginState() {
			FB.getLoginStatus(function(response) {
				statusChangeCallback(response);
			});
		}
		
		window.fbAsyncInit = function() {
			FB.init({
				appId	: '119408608529355',		// 176815339435897 <- dev / 119408608529355 -> real
				xfbml	: true,	// parse social plugins on this page
				version	: 'v2.8'
			});
		
		// 	FB.getLoginStatus(function(response) {
		// 		statusChangeCallback(response);
		// 	});
		};
		
		// Load the SDK asynchronously
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		// /.facebook SDK
		
		// Google SDK
		function onSignIn(googleUser) {
// 			var profile = googleUser.getBasicProfile();

			var gdata = {
					id : profile.getId(),
					email : profile.getEmail(),
					name : profile.getName(),
					picture : profile.getImageUrl()
			};

			checkSNSUser('google', JSON.stringify(gdata));
			
// 			console.log("ID: " + profile.getId()); // Don't send this directly to your server!
// 			console.log('Full Name: ' + profile.getName());
// 			console.log('Given Name: ' + profile.getGivenName());
// 			console.log('Family Name: ' + profile.getFamilyName());
// 			console.log("Image URL: " + profile.getImageUrl());
// 			console.log("Email: " + profile.getEmail());
			 // The ID token you need to pass to your backend:
// 			var id_token = googleUser.getAuthResponse().id_token;
// 			console.log("ID Token: " + id_token);
		}

		
		</script>
		
		<script> <!--Google -->
		var googleUser = {};
		var startApp = function() {
			gapi.load('auth2', function(){
				// Retrieve the singleton for the GoogleAuth library and set up the client.
				auth2 = gapi.auth2.init({
					client_id: '1082609763302-i7mdug0np5l874jkd0h3k1tuj67tr7e7.apps.googleusercontent.com',
					cookiepolicy: 'single_host_origin',
					// Request scopes in addition to 'profile' and 'email'
					//scope: 'additional_scope'
				});
				attachSignin(document.getElementById('googleBtn'));
			});
		};

		function attachSignin(element) {
// 			alert(1);
// 			console.log(element.id);
			auth2.attachClickHandler(element, {},
				function(googleUser) {
					var gdata = {
							id : googleUser.getBasicProfile().getId(),
							email : googleUser.getBasicProfile().getEmail(),
							name : googleUser.getBasicProfile().getName(),
							picture : googleUser.getBasicProfile().getImageUrl()
					};
					checkSNSUser('google', JSON.stringify(gdata));
				}, function(error) {
					alert(JSON.stringify(error, undefined, 2));
				});
		}
		</script>
		<script>startApp();</script>
		


		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content login-area">
				<h2><img src="../img/common/logo_226.png" alt="BOOKtalk"></h2>
				<form id="user-login-form">
					<input type="hidden" name="redirect" value="<?php echo $redirect ?>">
					<div class="login-inp">
						<label for="user_login">이메일 주소</label><span class="login-error warn_login_id hide">존재하지 않는 이메일 주소입니다.</span>
						<input type="email" class="form-control loginE" name="user_login" id="user_login" placeholder="이메일 주소를 입력하세요" required>
					</div>
					<div class="login-inp">
						<label for="user_pass">비밀번호</label><span class="login-error warn_login_passwd hide">비밀번호를 잘못 입력하셨습니다.</span>
						<input type="password" class="form-control loginP" name="user_pass" id="user_pass" placeholder="비밀번호를 입력하세요" required>
					</div>
				</form>
				<div><a href="#" id="submit-form" class="btn-skyblue">로그인</a></div>
				<div class="btn-go-join"><a href="join_1.php" data-ajax="false">회원가입 하기</a></div>
				<div class="btn-lost">
					<!-- <a href="#">이메일 주소를 잊어버리셨나요?</a> -->
					<!-- <a href="#">비밀번호를 잊어버리셨나요?</a> -->
					<a href="forgot_id.php">이메일 주소 찾기</a>
					<a href="forgot_pw.php">비밀번호 찾기</a>
				</div>
				<ul class="btn-login-sns">
					<!-- <li><a href="#" class="facebook">페이스북 계정으로 로그인하기</a></li>
					<li><a href="#" class="google">구글플러스 계정으로<br>로그인하기</a></li>
					<li><a href="#" class="twitter">트위터 계정으로<br>로그인하기</a></li> -->
					<li><a href="#" class="facebook">페이스북<br>계정 로그인</a></li>
					<li><a href="#" id="googleBtn" class="google">구글플러스<br>계정 로그인</a></li>
				</ul>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#submit-form").click(function() {
				$("#user-login-form").submit();
			});

			$("#user-login-form").submit(function(e) {
				e.preventDefault();

				$(".login-error").addClass("hide");

				$.ajax({
					type : "POST",
					url : "./ajax/user-login.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if ( res.code == "0" ) {
							location.href = res.redirect;
						} else {
// 							alert( res.msg );
							if ( res.code > 500 && res.code < 600 ) {
								$(".warn_login_id").html( res.msg );
								$(".warn_login_id").removeClass("hide");
							} else if ( res.code > 600 ) {
								$(".warn_login_passwd").html( res.msg );
								$(".warn_login_passwd").removeClass("hide");
							}
						}
					}
				});
			});

			// 페이스북 로그인
			$(".facebook").click(function() {
				facebookLogin();
			});

			// 구글 로그인
// 			$document.on("click", ".buttonText, .customGPlusSignIn", function() {
// // 				alert(111);
// 				console.log( googleUser );
// 			});

			function facebookLogin() {
				FB.login(function(response) {
					if (response.authResponse) {
						FB.api('/me', {fields: 'email,first_name,last_name,picture,gender'}, function(response) {
							checkSNSUser('facebook', JSON.stringify(response));
						});
					} else {
						//user hit cancel button
// 						console.log('User cancelled login or did not fully authorize.');
					}
				}, {
					scope: 'public_profile,email'
				});
			}
		}); // $

		function checkSNSUser( sns, str ) {
			$.ajax({
				type : "POST",
				url : "./ajax/user-check-sns.php",
				data : {
					"sns" : sns,
					"data" : str
				},
				dataType : "json",
				success : function(res) {
					if (res.code == "0" ) {
						if (res.result) {
							location.href = "../";	// mobile main
						} else {
							location.href = "join_1.php";
						}
					} else {
						alert(res.msg);
					}
				}
			});
		}
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>