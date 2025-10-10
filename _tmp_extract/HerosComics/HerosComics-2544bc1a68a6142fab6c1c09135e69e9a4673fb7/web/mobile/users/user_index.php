<?php
/*
 * Desc : 개인정보 수정 메인
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
				<h2>개인정보 수정</h2>
				<div class="myinfo-modify">
					<h3 class="myinfo-tit"><?php echo wps_get_current_user_display_name() ?>
						<span class="myinfo-tit-txt">(<?php echo wps_get_current_user_login() ?>)</span>
					</h3>
					<div class="myinfo-menu-area">
						<ul>
							<li class="myinfo-m01"><a href="user_edit.php" data-ajax="false">회원 정보 수정</a></li>
							<li class="myinfo-m02"><a href="user_passwd.php" data-ajax="false">비밀번호 수정</a></li>
							<li class="myinfo-m03"><a href="user_profile.php" data-ajax="false">프로필 수정</a></li>
							<li class="myinfo-m04"><a href="user_sns.php" data-ajax="false">sns 연결 관리</a></li>
							<li class="myinfo-m05"><a href="user_post.php">내 글 관리</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
