<?php
/*
 * Desc : 큐레이션 등록
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-page.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['curation_title']) ) {
	$code = 402;
	$msg = '큐레이션 제목을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['file_path']) ) {
	$code = 412;
	$msg = '표지 이미지를 등록해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['pkg_books']) ) {
	$code = 401;
	$msg = '큐레이션에 추가할 책을  선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$_POST['pkg_books'] = explode(',', $_POST['pkg_books']);
$_POST['curation_status'] = '3000';

// 큐레이션 등록
$result = lps_add_curation();

if ( empty($result) ) {
	$code = 501;
	$msg = '등록하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>