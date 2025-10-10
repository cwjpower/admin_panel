<?php
/*
 * Desc : 담벼락 게시글 추천
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-activity.php';
require_once FUNC_PATH . '/functions-book.php';

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
	$msg = '댓글을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$activity_id = $_POST['activityID'];

$activity_row = lps_get_activity( $activity_id );
$book_id = $activity_row['book_id'];

$my_book = lps_is_my_book( $user_id, $book_id );

if ( empty($my_book) ) {
	$code = 403;
	$msg = '구입하신 책만 추천하실 수 있습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 게시글 추천
$arr_result = lps_recommand_activity( $activity_id, $user_id );

$result = $arr_result['result'];
$count = $arr_result['count'];

if ( !empty($result) ) {
	$code = 501;
	$msg = '이미 추천하신 게시글입니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg', 'count');
echo json_encode( $json );
?>