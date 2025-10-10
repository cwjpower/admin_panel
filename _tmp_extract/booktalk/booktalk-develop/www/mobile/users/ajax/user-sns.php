<?php
/*
 * Desc : SNS 연결관리 업데이트
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
if ( empty($_POST['snsType']) ) {
	$code = 411;
	$msg = 'SNS를 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['snsAction']) ) {
	$code = 411;
	$msg = 'SNS 처립방식을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$sns_type = $_POST['snsType'];
$sns_action = !strcmp($_POST['snsAction'], 'true') ? 'on' : 'off';

$meta_key = 'wps_user_sns_' . $sns_type;

wps_update_user_meta( $user_id, $meta_key, $sns_action );

$json = compact('code', 'msg');
echo json_encode( $json );
?>