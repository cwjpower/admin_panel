<?php
/*
 * Desc : 찜하기
 */
require_once '../../../wps-config.php';
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

if ( empty($_POST['bookID']) ) {
	$code = 408;
	$msg = '책을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$book_id = $_POST['bookID'];

$result = lps_is_my_book( $user_id, $book_id );

if ( !empty($result) ) {
	$code = 511;
	$msg = '이미 구매하신 책입니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 찜리스트에 책 등록
$result = lps_add_book_wishlist( $book_id );

$json = compact('code', 'msg', 'result');
echo json_encode( $json );
?>