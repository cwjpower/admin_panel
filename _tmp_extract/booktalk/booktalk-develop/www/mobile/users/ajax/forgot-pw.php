<?php
/*
 * Desc : 비밀번호 찾기
 */
require_once '../../../wps-config.php';

$code = 0;
$msg = '';

if ( empty($_POST['user_name']) ) {
	$code = 402;
	$msg = '이름을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( empty($_POST['user_login']) ) {
	$code = 401;
	$msg = '계정 이메일을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$user_login = $_POST['user_login'];
$user_name = $_POST['user_name'];

// ID 중심으로 검증
$user_rows = wps_get_user_by( 'user_login', $user_login );

if (empty($user_rows)) {
	$code = 501;
	$msg = '이메일 주소를 찾을 수 없습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( strcmp($user_name, $user_rows['user_name']) ) {
	$code = 502;
	$msg = '회원정보를 찾을 수 없습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( $user_rows['user_status'] == '1' ) {
	$code = 504;
	$msg = '차단된 회원입니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}
if ( $user_rows['user_status'] == '4' ) {
	$code = 504;
	$msg = '탈퇴하신 회원입니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$user_id = $user_rows['ID'];

$temp_password = mt_rand(100000, 890000);

wps_set_password($temp_password, $user_id);

$subject = '[북톡] 임시 비밀번호를 안내해 드립니다.';
$from = SITE_FROM_EMAIL;

// Email Body
$email_contents =<<<EOD
	<div style="padding: 20px; border: 3px solid #337AB7; font-size: 12px;">
		<p>고객님께서 문의하신 비밃번호에 대해 알려드립니다.</p>
		<p>안녕하세요. {$user_name}님</p>
		<p>고객님의 임시 비밀번호는 아래와 같습니다.</p>
		<p></p>
		<p>비밃번호 : <b> $temp_password </b>
		</p>
		<p>비밀번호 재분실 방지를 위해, 임시 비밀번호로 로그인 하신 후 새로운 비밀번호로 변경하여 이용하시기 바랍니다.</p>
		<p>(비밀번호는 관리자도 알 수 없도록 암호화되어 있습니다.)</p>
		<p>감사합니다.</p>
	</div>
EOD;

$mail_result = lps_send_mail($user_login, $subject, $email_contents, $from);

if ( empty($mail_result) ) {
	$code = 512;
	$msg = '메일을 전송하지 못했습니다.<br> 관리자에게 문의해 주시기 바랍니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg');
echo json_encode( $json );

?>