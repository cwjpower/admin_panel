<?php
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

if ( empty($_POST['user_pass_current']) ) {
	$code = 403;
	$msg = '현재 비밀번호를 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['user_pass']) ) {
	$code = 404;
	$msg = '새로운 비밀번호를 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['user_pass2']) ) {
	$code = 405;
	$msg = '확인용 새 비밀번호를 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$user_pass_current = wps_get_password($_POST['user_pass_current']);
$user_pass = $_POST['user_pass'];
$user_pass2 = $_POST['user_pass2'];

if ( strcmp($user_pass, $user_pass2) ) {
	$code = 501;
	$msg = '변경하실 새 비밀번호가 서로 일치하지 않습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 현재 비밀번호
$user_rows = wps_get_user_by( 'ID', $user_id );
$db_user_pass = $user_rows['user_pass'];

if ( strcmp($user_pass_current, $db_user_pass) ) {
	$code = 502;
	$msg = '현재 비밀번호를 잘못 입력하셨습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$result = wps_set_password( $user_pass, $user_id );

if ( empty($result) ) {
	$code = 501;
	$msg = '비밀번호를 변경하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact( 'code', 'msg' );
echo json_encode( $json );

?>