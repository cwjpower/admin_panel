<?php
/*
 * Desc : 회원가입 : 최종 등록
 */
require_once '../../../wps-config.php';

$code = 0;
$msg = '';

foreach ($_POST as $key => $val) {
	$_SESSION['join'][$key] = $val;
}

if ( empty($_SESSION['join']['user_pass']) ) {
	$code = 414;
	$msg = '비밀번호를 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_SESSION['join']['user_login']) ) {
	$code = 411;
	$msg = '계정(이메일)을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_SESSION['join']['display_name']) ) {
	$code = 417;
	$msg = '닉네임을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_SESSION['join']['user_name']) ) {
	$code = 412;
	$msg = '이름을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( !empty($_SESSION['join']['optional_info']) ) {
	if ( empty($_SESSION['join']['mobile']) ) {
		$code = 415;
		$msg = '휴대폰번호를 입력해 주십시오.';
		$json = compact('code', 'msg');
		exit( json_encode($json) );
	}
	if ( empty($_SESSION['join']['residence']) ) {
		$code = 416;
		$msg = '거주지를 입력해 주십시오.';
		$json = compact('code', 'msg');
		exit( json_encode($json) );
	}
} else {
	$_SESSION['join']['residence'] = 0;
}

// 회원등록
$user_id = lps_join_user();

$_SESSION['join']['user_id'] = $user_id;

if (!empty($user_id)) {
// 	unset($_SESSION['join']);
	
	// facebook Login : 최초 회원가입 시 user meta에 facebook seq id 저장
	if (!empty($_COOKIE['snsfb']['id'])) {	// faceboook
		wps_update_user_meta($user_id, 'wps_sns_fb_id', $_COOKIE['snsfb']['id']);
		
// 		unset($_COOKIE['snsfb']);
		
		setcookie( 'snsfb[id]', null, -1, '/' );
		setcookie( 'snsfb[email]', null, -1, '/' );
		setcookie( 'snsfb[name]', null, -1, '/' );
		setcookie( 'snsfb[gender]', null, -1, '/' );
		setcookie( 'snsfb[picture]', null, -1, '/' );
		
	} else if (!empty($_COOKIE['snsgg']['id'])) {	// google
		wps_update_user_meta($user_id, 'wps_sns_google_id', $_COOKIE['snsgg']['id']);
		
// 		unset($_COOKIE['snsgg']);
		
		setcookie( 'snsgg[id]', null, -1, '/' );
		setcookie( 'snsgg[email]', null, -1, '/' );
		setcookie( 'snsgg[name]', null, -1, '/' );
		setcookie( 'snsgg[picture]', null, -1, '/' );
	}
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>