<?php
/*
 * 2016.12.21		softsyw
 * Desc : 선택한 게시글 삭제
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

if ( empty($_POST['qna_ids']) ) {
	$code = 410;
	$msg = '문의글을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$count = 0;

foreach ($_POST['qna_ids'] as $key => $val) {
	if(wps_delete_post_qnas($val, $user_id)) {
		$count++;
	}
}

$json = compact('code', 'msg', 'count');
echo json_encode( $json );
?>