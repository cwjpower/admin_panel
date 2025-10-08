<?php
/*
 * Desc : 회원가입 : 정보 입력
 */
require_once '../../../wps-config.php';

$code = 0;
$msg = '';

if ( empty($_POST['user_pass']) ) {
	$code = 414;
	$msg = '비밀번호를 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['user_login']) ) {
	$code = 411;
	$msg = '계정(이메일)을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['display_name']) ) {
	$code = 417;
	$msg = '닉네임을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['user_name']) ) {
	$code = 412;
	$msg = '이름을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$user_login = $_POST['user_login'];	// email

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

foreach ($_POST as $key => $val) {
	$_SESSION['join'][$key] = $val;
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>