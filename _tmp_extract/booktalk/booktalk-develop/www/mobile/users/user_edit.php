<?php
/*
 * Desc : 개인정보 수정
 */
require_once '../../wps-config.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();

$user_row = wps_get_user_by( 'ID', $user_id );

$user_login = $user_row['user_login'];
$user_name = $user_row['user_name'];
$user_pass = $user_row['user_pass'];
// $user_email = $user_row['user_email'];
$user_registered = $user_row['user_registered'];
$user_status = $user_row['user_status'];
$user_status_label = $wps_user_status[$user_status];
$user_level = $user_row['user_level'];
$user_level_label = $wps_user_level[$user_level];
$display_name = $user_row['display_name'];
$mobile = $user_row['mobile'];
$birthday = $user_row['birthday'];
$gender = $user_row['gender'];
$gender_label = $wps_user_gender[$gender];
$join_path = $user_row['join_path'];
$join_path_label = empty($join_path) ? '' : $wps_user_join_path[$join_path];
$last_login_dt = $user_row['last_login_dt'];
$residence = $user_row['residence'];
$residence_label = empty($residence) ? '' : $wps_user_residence_area[$residence];
$last_school = $user_row['last_school'];
$last_school_label = empty($last_school) ? '' : $wps_user_last_school[$last_school];

$birth = explode('-', $birthday);

$user_meta = wps_get_user_meta( $user_id );

$um_accept_dm = @$user_meta['wps_user_accept_dm'];
$dm_checked = !strcmp($um_accept_dm, 'Y') ? 'checked' : '';

if ($user_status == 4) {
	lps_js_back( '탈퇴처리한 회원은 수정하실 수 없습니다.' );
}

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content myinfo-area">
				<h2>개인정보 수정 &gt; 회원정보 수정</h2>
				<form id="form-edit-user">
					<div class="myinfo-modify">
						<h3 class="myinfo-tit">기본정보</h3>
						<div class="myinfo-list-area">
							<ul class="myinfo-list">
								<li>
									<span class="myinfo-list-tit">계정</span><?php echo $user_login ?>
									<div class="event-email-chk"><input type="checkbox" id="userMail" name="accept_dm" <?php echo $dm_checked ?>><label for="userMail">이벤트 및 프로모션 안내 메일 수신 동의</label></div>
								</li>
								<li>
									<span class="myinfo-list-tit">닉네임</span><?php echo $display_name ?>
								</li>
								<li>
									<span class="myinfo-list-tit">이름</span><?php echo $user_name ?>
								</li>
							</ul>
						</div>
					</div>
					<div class="myinfo-modify">
						<h3 class="myinfo-tit">추가정보
							<span class="myinfo-list-ps">*항목을 입력해야 통계 서비스 사용이 가능합니다.</span>
						</h3>
						<div class="myinfo-list-area">
							<ul class="myinfo-list-modify">
								<!-- 본인인증 -->
								<li class="inp-box1">
									<div class="inp-tit"><label for="mobile">연락처</label></div>
									<div class="inp">
										<input type="text" id="mobile" name="mobile" value="<?php echo $mobile ?>">
									</div>
									<button class="btn-tel-auth">본인인증 하기</button>
								</li>
								<li class="inp-box1">
									<div class="inp-tit"><label for="userTel2">연락처</label></div>
									<div class="inp inp-auth-ok">
										<input type="text" id="userTel2">
									</div>
									<div class="auth-ok">본인인증 완료</div>
								</li>
								<li class="inp-box1">
									<div class="inp-tit select-tit">생년월일</div>
									<div class="select-birth">
										<select name="birth_y">
											<option value="">-선택-</option>
							<?php 
							for ($i = date('Y'); $i > 1900; $i--) {
								$selected = $i == $birth[0] ? 'selected' : '';
							?>
											<option value="<?php echo $i ?>" <?php echo $selected ?>><?php echo $i ?></option>
							<?php 
							}
							?>
										</select>
										<select name="birth_m">
											<option value="">-선택-</option>
							<?php 
							for ($i = 1; $i <= 12; $i++) {
								$j = $i < 10 ? '0' . $i : $i;
								$selected = $i == $birth[1] ? 'selected' : '';
							?>
											<option value="<?php echo $j ?>" <?php echo $selected ?>><?php echo $i ?></option>
							<?php 
							}
							?>
										</select>
										<select name="birth_d">
											<option value="">-선택-</option>
							<?php 
							for ($i = 1; $i <= 31; $i++) {
								$j = $i < 10 ? '0' . $i : $i;
								$selected = $i == $birth[2] ? 'selected' : '';
							?>
											<option value="<?php echo $j ?>" <?php echo $selected ?>><?php echo $i ?></option>
							<?php 
							}
							?>
										</select>
									</div>
								</li>
								<li class="inp-box1 inp-gender">
									<div class="inp-tit">성별</div>
									<div class="select-box">
										<ul>
								<?php 
								foreach ($wps_user_gender as $key => $val) {
									if ( !empty($val) ) {
										$checked = $key == $gender ? 'checked' : '';
								?>
											<li><input type="radio" name="gender" id="g-<?php echo $key ?>" value="<?php echo $key ?>" <?php echo $checked ?>><label for="g-<?php echo $key ?>"><?php echo $val ?></label></li>
								<?php 
									}
								}
								?>
										</ul>
									</div>
								</li>
								<li class="inp-box1">
									<div class="inp-tit select-tit">거주지</div>
									<div class="select-box">
										<select name="residence">
											<option value="">-선택-</option>
							<?php 
							foreach ($wps_user_residence_area as $key => $val) {
								if ( !empty($val) ) {
									$selected = $key == $residence ? 'selected' : '';
							?>
											<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $val ?></option>
							<?php 
								}
							}
							?>
										</select>
									</div>
								</li>
								<li class="inp-box1">
									<div class="inp-tit select-tit">학력</div>
									<div class="select-box">
										<select name="last_school">
											<option value="">-선택-</option>
							<?php 
							foreach ($wps_user_last_school as $key => $val) {
								if ( !empty($val) ) {
									$selected = $key == $last_school ? 'selected' : '';
							?>
											<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $val ?></option>
							<?php 
								}
							}
							?>
										</select>
									</div>
								</li>
								
								<!--  
								<li class="inp-box">
									<div class="add-myinfo-tit"><label for="mobile">연락처</label></div>
									<div class="add-myinfo">
										<input type="text" id="mobile"><button class="btn-tel-auth">본인인증 하기</button>
									</div>
								</li>
								
								<li class="inp-box">
									<div class="add-myinfo-tit"><label for="userTel2">연락처</label></div>
									<div class="add-myinfo user-auth-ok">
										<input type="text" id="userTel2" class="">
										<div class="auth-ok">본인인증 완료</div>
									</div>
								</li>
								
								<li class="inp-box">
									<div class="add-myinfo-tit">생년월일</div>
									<div class="add-myinfo select-birth">
										<select>
											<option value="">2016</option>
										</select>
										<select>
											<option value="">1</option>
										</select>
										<select>
											<option value="">1</option>
										</select>
									</div>
								</li>
								<li class="inp-gender">
									<div class="add-myinfo-tit">성별</div>
									<ul>
										<li><input type="radio" name="sex" id="man"><label for="man">남자</label></li>
										<li><input type="radio" name="sex" id="woman"><label for="woman">여자</label></li>
									</ul>
								</li>
								<li class="inp-box">
									<div class="add-myinfo-tit">거주지</div>
									<div class="add-myinfo">
										<select>
											<option value="">서울특별시</option>
										</select>
									</div>
								</li>
								<li class="inp-box">
									<div class="add-myinfo-tit">학력</div>
									<div class="add-myinfo">
										<select>
											<option value="">대졸</option>
										</select>
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
			$("#btn-submit").click(function() {
				console.log( 1);
				$("#form-edit-user").submit();
			});

			$("#form-edit-user").submit(function(e) {
				e.preventDefault();
				
				$.ajax({
					type : "POST",
					url : "./ajax/user-edit.php",
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
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
