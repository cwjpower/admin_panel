<?php
/*
 * Desc : 카트에 담기, 장바구니 담기
 * 		bookID 는 array
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

foreach ($_POST['bookID'] as $key => $val) {
	$result = lps_is_my_book( $user_id, $val );

	if ( !empty($result) ) {
		unset($_POST['bookID'][$key]);
	}
}

if ( empty($_POST['bookID']) ) {
	$code = 511;
	$msg = '이미 구매하신 책입니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 찜리스트에 책 등록
$result = lps_add_book_cart( $_POST['bookID'] );

$json = compact('code', 'msg', 'result');
echo json_encode( $json );
?>