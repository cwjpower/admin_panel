<?php
/*
 * Desc : 회원가입 : 약관동의
 */
require_once '../../../wps-config.php';

$code = 0;
$msg = '';

if ( empty($_POST['agree_term']) ) {
	$code = 414;
	$msg = '이용약관을 체크해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['agree_privacy']) ) {
	$code = 411;
	$msg = '개인정보취급방침을 체크해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

foreach ($_POST as $key => $val) {
	$_SESSION['join'][$key] = $val;
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>