<?php
/*
 * Desc : 프로필 수정
 */
require_once '../../wps-config.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();

$umeta = wps_get_user_meta( $user_id );

$profile_msg = @$umeta['wps_user_profile_msg'];
$profile_avatar = @$umeta['wps_user_avatar'];
if (empty($profile_avatar)) {
	$profile_avatar = IMG_URL . '/common/photo-default.png';
}

$profile_photo_bg = @$umeta['wps_user_profile_bg'];
if (empty($profile_photo_bg)) {
	$profile_photo_bg = IMG_URL . '/common/photo-default.png';
}

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content myinfo-area">
				<h2>개인정보 수정 &gt; 프로필 수정</h2>
				<form id="form-edit-profile">
					<div class="myinfo-modify">
						<h3 class="myinfo-tit">프로필 수정</h3>
						<div class="myinfo-list-area profile-area">
							<div id="wps-profile-pane">
								<div class="user-photo-area">
									<div class="user-photo-img"><img src="<?php echo $profile_avatar ?>" alt="Profile Photo"></div>
								</div>
							</div>
							<ul class="myinfo-list-modify">
								<li class="inp-box1">
									<div class="inp-tit"><label for="profile_photoBg">프로필 배경</label></div>
									<div class="inp">
										<input type="text" id="profile_photo_bg_txt" readonly>
										<input type="file" id="profile_photo_bg" name="profile_photo_bg" class="hide">
									</div>
									<button type="button" class="btn-photo" id="btn-profile-bg">변경</button>
								</li>
								<li class="inp-box1">
									<div class="inp-tit"><label for="profile_photo_txt">프로필 사진</label></div>
									<div class="inp">
										<input type="text" id="profile_photo_txt" readonly>
										<input type="file" id="profile_photo" name="profile_photo" class="hide">
									</div>
									<button type="button" class="btn-photo" id="btn-profile-avatar">변경</button>
								</li>
								<li class="textarea-box">
									<div class="inp-tit"><label for="userMsg">프로필 메세지</label><span class="txt-count">(0/60)</span></div>
									<div>
										<input name="message" id="message" placeholder="안녕하세요" style="height: 40px;" value="<?php echo $profile_msg ?>">
										<!-- textarea name="message" id="message" cols="30" rows="10" placeholder="안녕하세요"></textarea> -->
									</div>
								</li>
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
		
		<!-- Form(file) -->
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.form.min.js"></script>
			
		<script>
		$(function() {
			// 프로필배경 초기 로딩
			$("#wps-profile-pane").css({
				"width" : "100%",
				"background-image" : "url('<?php echo $profile_photo_bg ?>')",
				"background-repeat" : "no-repeat"
			});
			//$("#wps-profile-pane").css("background-image", "url('<?php echo $profile_photo_bg ?>')");

			limitBytes( $("#message").val(), 60 );	// 프로필 메시지 byte 표시
			
			$("#btn-submit").click(function() {
				$("#form-edit-profile").submit();
			});

			$("#form-edit-profile").submit(function(e) {
				e.preventDefault();
				
				$.ajax({
					type : "POST",
					url : "./ajax/user-profile.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "./user_index.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});
			
			/* message */
			$("#message").on("click keyup keypress input propertychange", function() {
				limitBytes( $("#message").val(), 60 );
			});

			// 프로필사진 변경
			$("#btn-profile-avatar").click(function() {
				$("#profile_photo").click();
			});
			
			// 프로필배경 변경
			$("#btn-profile-bg").click(function() {
				$("#profile_photo_bg").click();
			});

			// 프로필사진
			$('input[name="profile_photo"]').change(function() {
				var size = this.files[0].size;
				var fext = this.files[0].name.split('.').pop().toLowerCase();

				if ( size > 2097152 ) {
					alert( "업로드할 수 있는 이미지 파일 크기는 2MB입니다.");
					$(this).val("");
					return;
				}

				if ( fext !=  'gif' && fext != 'jpg' && fext != 'png' ) {
					alert('확장자가 gif, jpg, png인 이미지 파일만 첨부해 주십시오.');
					$(this).val("");
					return;
				}

				$("#form-edit-profile").ajaxSubmit({
					type : "POST",
					url : "./ajax/user-profile-photo.php",
					data : {
						"eleName" : $(this).attr("name")
					},
					dataType : "json",
					success: function(xhr) {
						if ( xhr.code == "0" ) {
							var profilePhoto = '<img src="' + xhr.user_profile_url + '">';
							$(".user-photo-img").html(profilePhoto);
						} else {
							alert( xhr.msg );
						}
					}
				});
				$('input[name="profile_photo"]').val("");
			});

			// 프로필배경
			$('input[name="profile_photo_bg"]').change(function() {
				var size = this.files[0].size;
				var fext = this.files[0].name.split('.').pop().toLowerCase();

				if ( size > 2097152 ) {
					alert( "업로드할 수 있는 이미지 파일 크기는 2MB입니다.");
					$(this).val("");
					return;
				}

				if ( fext !=  'gif' && fext != 'jpg' && fext != 'png' ) {
					alert('확장자가 gif, jpg, png인 이미지 파일만 첨부해 주십시오.');
					$(this).val("");
					return;
				}

				$("#form-edit-profile").ajaxSubmit({
					type : "POST",
					url : "./ajax/user-profile-photo.php",
					data : {
						"eleName" : $(this).attr("name"),
						"thumbW" : 1080, 
						"thumbH" : 880 
					},
					dataType : "json",
					success: function(xhr) {
						if ( xhr.code == "0" ) {
							$("#wps-profile-pane").css("background-image", "url('" + xhr.user_profile_url + "')");
						} else {
							alert( xhr.msg );
						}
					}
				});
				$('input[name="profile_photo"]').val("");
			});

			// 프로필사진 삭제
			$(document).on("click", ".user-photo-img img", function() {
				if ( $(this).attr("src").indexOf("/images/") > -1 ) {
					return;
				}
				
				if ( confirm("프로필 사진을 삭제하시겠습니까?") ) {
					$.ajax({
						type : "POST",
						url : "./ajax/user-profile-photo.php",
						data : {
							"fileDelete" : "profile_photo"
						},
						dataType : "json",
						success : function(res) {
							if ( res.code == "0" ) {
								var profilePhoto = '<img src="<?php echo IMG_URL ?>/common/photo-default.png">';
								$(".user-photo-img").html(profilePhoto);
							} else {
								alert( res.msg );
							}
						}
					});
				}
			});
		});

		function limitBytes(sendMsg, maxBytes) {
			var chr = "", chrLength = 0, validMsgLength = 0, validChrLength = 0, validMsg = "", bytesVal = "";
			
			for ( i = 0; i < sendMsg.length; i++ ) {
				chr = sendMsg.charAt(i);
				if (escape(chr).length > 4) {
// 					chrLength += 2;
					chrLength ++;
				} else if (chr != "\r") {		// %0D%0A
					chrLength++;
				}
				if ( chrLength <= maxBytes ) {
					validMsgLength = i + 1;
					validChrLength = chrLength;
				}
			}
			if ( chrLength > maxBytes ) {
				alert( maxBytes +"바이트 이상의 메세지는 작성하실 수 없습니다.");
				validMsg = sendMsg.substr(0, validMsgLength);
				$("#message").val( validMsg );
				bytesVal = "(" + validChrLength + "/"+ maxBytes + ")";
			} else {
				bytesVal = "(" + chrLength + "/"+ maxBytes + ")";
			}
			
			if ( $(".txt-count").length > 0 ) {
				$(".txt-count").html( bytesVal );
			}
		}
		</script>
		

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
