<?php
/*
 * Desc : Mobile Web 회원 계정 check
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-user.php';

$code = 0;
$msg = '';

if ( empty($_POST['user_login']) ) {
	$code = 404;
	$msg = '계정을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$user_login = $_POST['user_login'];

if (!filter_var($user_login, FILTER_VALIDATE_EMAIL)) {
	$code = 4112;
	$msg = '이메일 주소 형식이 아닙니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if (lps_check_user_login($user_login)) {
	$code = 4113;
	$msg = '이미 사용하고 있는 이메일입니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg');
echo json_encode( $json );

?>