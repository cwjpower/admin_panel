<?php
/*
 * Desc : SNS 공유하기, 공유하기 버튼 클릭으로 공유완료
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

if ( empty($_POST['bookID']) ) {
	$code = 402;
	$msg = '책을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$book_id = $_POST['bookID'];

// SNS에 공유함.
lps_share_book_sns( $user_id, $book_id );

$json = compact('code', 'msg');
echo json_encode( $json );
?>