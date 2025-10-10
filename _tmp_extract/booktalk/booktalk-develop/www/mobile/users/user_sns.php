<?php
/*
 * Desc : SNS 연결관리
 */
require_once '../../wps-config.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();

$facebook = wps_get_user_meta( $user_id, 'wps_user_sns_facebook' );
$twitter = wps_get_user_meta( $user_id, 'wps_user_sns_twitter' );

if (empty($facebook)) {
	$facebook = 'off';
}
if (empty($twitter)) {
	$twitter = 'off';
}

$facebook_label = !strcmp($facebook, 'on') ? '설정' : '해제';
$twitter_label = !strcmp($twitter, 'on') ? '설정' : '해제';

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content myinfo-area">
				<h2>개인정보 수정 &gt; SNS 연결 관리</h2>
				<div class="myinfo-modify">
					<h3 class="myinfo-tit">SNS 연결 관리</h3>
					<div class="myinfo-list-area">
						<ul class="myinfo-sns">
							<li class="sns-<?php echo $facebook ?>"><span id="facebook"><?php echo $facebook_label ?></span></li>
							<li class="sns-<?php echo $twitter ?>"><span id="twitter"><?php echo $twitter_label ?></span></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#facebook, #twitter").click(function() {
				if ($(this).parent().hasClass("sns-on")) {
					$(this).parent().removeClass("sns-on");
					$(this).text("해제");
				} else {
					$(this).parent().addClass("sns-on");
					$(this).text("설정");
				}

				$.ajax({
					type : "POST",
					url : "./ajax/user-sns.php",
					data : {
						"snsType" : $(this).attr("id"),
						"snsAction" : $(this).parent().hasClass("sns-on")
					},
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
