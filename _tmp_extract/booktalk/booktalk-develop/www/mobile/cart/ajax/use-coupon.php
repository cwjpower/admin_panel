<?php
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-payment.php';
require_once FUNC_PATH . '/functions-coupon.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// if ( empty($_POST['couponID']) ) {
// 	$code = 408;
// 	$msg = '쿠폰을 선택해 주십시오.';
// 	$json = compact('code', 'msg');
// 	exit( json_encode($json) );
// }

$coupon_id = empty($_POST['couponID']) ? 0 : $_POST['couponID'];

// 쿠폰 사용 여부 검증


// 결제 대기중인 책 리스트
$book_pay = wps_get_user_meta( $user_id, 'lps_user_book_pay' );
$paying_books = unserialize($book_pay);

$total_item_cost = lps_get_total_item_cost( $user_id );
$coupon_dc = lps_get_total_coupon_discount( $user_id, $coupon_id );

$total_cost = $total_item_cost - $coupon_dc;

// foreach ($_POST['couponID'] as $key => $val) {
// 	$result = lps_is_my_book( $user_id, $val );

// 	if ( !empty($result) ) {
// 		unset($_POST['couponID'][$key]);
// 	}
// }

// if ( empty($_POST['couponID']) ) {
// 	$code = 511;
// 	$msg = '이미 구매하신 책입니다.';
// 	$json = compact('code', 'msg');
// 	exit( json_encode($json) );
// }

$json = compact('code', 'msg', 'total_cost', 'coupon_id');
echo json_encode( $json );



?>