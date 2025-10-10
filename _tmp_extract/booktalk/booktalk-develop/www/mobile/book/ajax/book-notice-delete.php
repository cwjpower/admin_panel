<?php
/*
 * Desc : 담벼락의 공지글 삭제
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-activity.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['postID']) ) {
	$code = 402;
	$msg = '게시글을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$post_id = $_POST['postID'];

// 게시글 등록
$result = wps_delete_post( $post_id, $user_id );

if ( empty($result) ) {
	$code = 501;
	$msg = '삭제하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>