<?php
/*
 * Desc : 담벼락 게시글 삭제
 * 		실제 삭제가 이뤄지는 것이 아니라 is_deleted 를  0 -> 1 로 업데이트한다.
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

if ( empty($_POST['activityID']) ) {
	$code = 402;
	$msg = '게시글을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$activity_id = $_POST['activityID'];

// 게시글 상태를 삭제로 변경
$result = lps_delete_status_activity( $activity_id, $user_id );

if ( empty($result) ) {
	$code = 501;
	$msg = '삭제하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>