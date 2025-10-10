<?php
/*
 * Desc : 1:1문의 등록 (회원)
 */
require_once '../../../wps-config.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( !strcmp($_POST['post_type'], 'qna_new') ) {
	if ( empty($user_id) ) {
		$code = 400;
		$msg = '로그인 후 이용해 주십시오.';
		$json = compact('code', 'msg');
		exit( json_encode($json) );
	}
}

if ( !strcmp($_POST['post_type'], 'qna_new') ) {
	if ( empty($_POST['post_term_id']) ) {
		$code = 402;
		$msg = '카테고리를 선택해 주십시오.';
		$json = compact('code', 'msg');
		exit( json_encode($json) );
	}
}

if ( empty($_POST['post_title']) ) {
	$code = 411;
	$msg = '제목을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['post_content']) ) {
	$code = 412;
	$msg = '내용을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$post_id = wps_add_post_question();

if ( empty($post_id) ) {
	$code = 501;
	$msg = '등록하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg');
echo json_encode( $json );

?>