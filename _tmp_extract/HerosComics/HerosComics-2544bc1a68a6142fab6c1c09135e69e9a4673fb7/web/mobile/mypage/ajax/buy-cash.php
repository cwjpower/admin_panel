<?php
/*
 * Desc : 결제
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-payment.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['agree_notice']) ) {
	$code = 401;
	$msg = '결제 유의사항에 동의해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['agree_service']) ) {
	$code = 402;
	$msg = '유료서비스 이용 약관에 동의해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['pay_amount']) ) {
	$code = 410;
	$msg = '충전 금액을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['pay_method']) ) {
	$code = 411;
	$msg = '결제수단을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$result = lps_add_user_payment_list( $wps_payment_amount_rate );

$json = compact('code', 'msg', 'result');
echo json_encode( $json );
?>