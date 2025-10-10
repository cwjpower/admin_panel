<?php
/*
 * Desc : 회원정보 수정
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

$accept_dm = empty($_POST['accept_dm']) ? 'N' : 'Y';

// 사용자 업데이트
$result = lps_update_user_optional();

if (!empty($result)) {
	wps_update_user_meta( $user_id, 'wps_user_accept_dm', $accept_dm );
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>