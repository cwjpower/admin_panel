<?php
/*
 * Desc : 결제 완료, App과의 경로를 일치시키기 위해서. DRM zip 파일
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-payment.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-coupon.php';
require_once INC_PATH . '/lib/pclzip.lib.php';
require_once INC_PATH . '/classes/HZip.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 주문할 책이 있는 지 확인
$book_pay = wps_get_user_meta( $user_id, 'lps_user_book_pay' );
if (empty($book_pay)) {
	$code = 407;
	$msg = '주문할 책이 없습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 사용할 포인트
$using_point = empty($_POST['using_point']) ? 0 : $_POST['using_point'];
// 회원포인트
$user_point = wps_get_user_meta($user_id, 'lps_user_total_point');

if ($using_point > $user_point) {
	$code = 413;
	$msg = '사용하실 포인트가 보유하신 포인트를 초과했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 회원의 모든 현금성 자산
$user_money = lps_get_total_user_money( $user_id );

// 쿠폰
$coupon_id = empty($_POST['coupon_to_use']) ? 0 : $_POST['coupon_to_use'];
$coupon_dc = lps_get_total_coupon_discount( $user_id, $coupon_id );

// 회원이 결제할 책들의 가격 합계, 포인트 결제 금액은 차감한다.
$total_item_cost = lps_get_total_item_cost( $user_id );
$total_cost = $total_item_cost - $using_point - $coupon_dc;

if ($user_money < $total_cost) {
	$code = 410;
	$msg = '결제에 사용하실 금액이 부족합니다. 충전 후 이용해 주시기 바랍니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 주문 완료
$result = lps_add_order( $user_id );

if (empty($result)) {
	$code = 501;
	$msg = '주문하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg', 'result');
echo json_encode( $json );
?>