<?php
/*
 * Desc : 프로필 메시지 저장
 */
require_once '../../../wps-config.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$message = empty($_POST['message']) ? '' : $_POST['message'];

wps_update_user_meta( $user_id, 'wps_user_profile_msg', $message );

$json = compact('code', 'msg');
echo json_encode( $json );
?>