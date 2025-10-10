<?php
/*
 * Desc : SNS 계정 회원 여부 확인
 * 	- Facebook : facebook uid를 wps_sns_fb_id 에 저장
 *  - Google : wps_sns_google_id 에 저장
 * 		
 */
require_once '../../../wps-config.php';

$code = 0;
$msg = '';

if ( empty($_POST['sns']) ) {
	$code = 401;
	$msg = 'SNS를 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$sns = $_POST['sns'];	// facebook, google, twitter

if (!strcmp($sns, 'facebook')) {	// facebook
	if ( empty($_POST['data']) ) {
		$code = 402;
		$msg = 'Facebook API 정보를 입력해 주십시오.';
		$json = compact('code', 'msg');
		exit( json_encode($json) );
	}
	
	// parse facebook callback data
	$fb_data = json_decode($_POST['data']);
	
	$fb_id = $fb_data->id;
	$fb_email = empty($fb_data->email) ? 'x' : $fb_data->email;		// to avoid 'deleted'
	$fb_name = @$fb_data->last_name . '' . @$fb_data->first_name;
	$fb_gender = !strcmp(@$fb_data->gender, 'male') ? '1' : '2';
	$fb_picture = @$fb_data->picture->data->url;

	// facebook UID로 회원 가입 여부 확인하여 user ID 값 조회
	$user_id = lps_get_user_id_by_key_val( 'wps_sns_fb_id', $fb_id );
	
	if (empty($user_id)) {		// 회원 없음
		$result = 0;
		
		setcookie( 'snsfb[id]', $fb_id, time() + 3600, '/' );
		setcookie( 'snsfb[email]', $fb_email, time() + 3600, '/' );
		setcookie( 'snsfb[name]', $fb_name, time() + 3600, '/' );
		setcookie( 'snsfb[gender]', $fb_gender, time() + 3600, '/' );
		setcookie( 'snsfb[picture]', $fb_picture, time() + 3600, '/' );
	} else {
		$result = 1;
		
		$user_row = wps_get_user($user_id);
		
		if ( $user_row['user_status'] == '1' ) {
			$code = 503;
			$msg = '차단된 회원입니다.';
			$json = compact('code', 'msg');
			exit( json_encode($json) );
		}
		if ( $user_row['user_status'] == '4' ) {
			$code = 504;
			$msg = '탈퇴하신 회원입니다.';
			$json = compact('code', 'msg');
			exit( json_encode($json) );
		}
		
		// 중복 로그인 방지를 위해 기존 인증세션 파기
		// $session_id = wps_get_user_meta( $user_id, 'wps_session_id' );
		// if ( strcmp($session_id, session_id()) ) {
		// 	$wsession->sessDestroy( $session_id );
		// }
		
		// 인증세션 생성
		$user_level = wps_get_user_meta( $user_id, 'wps_user_level' );
		$_SESSION['login']['userid'] = $user_id;
		$_SESSION['login']['user_login'] = $user_row['user_login'];
		$_SESSION['login']['user_name'] = $user_row['user_name'];
		$_SESSION['login']['display_name'] = $user_row['display_name'];
		$_SESSION['login']['user_level'] = $user_level;
		
		// 세션 업데이트
		wps_update_user_meta( $user_id, 'wps_session_id', session_id() );
	}
} else if (!strcmp($sns, 'google')) {
	if ( empty($_POST['data']) ) {
		$code = 402;
		$msg = 'Google API 정보를 입력해 주십시오.';
		$json = compact('code', 'msg');
		exit( json_encode($json) );
	}
	
	// parse google callback data
	$gg_data = json_decode($_POST['data']);
// 	var_dump($gg_data);
	
	$gg_id = $gg_data->id;
	$gg_email = $gg_data->email;
	$gg_name = $gg_data->name;
	$gg_picture = $gg_data->picture;
	
	// google UID로 회원 가입 여부 확인하여 user ID 값 조회
	$user_id = lps_get_user_id_by_key_val( 'wps_sns_google_id', $gg_id );
	
	if (empty($user_id)) {		// 회원 없음
		$result = 0;
	
		setcookie( 'snsgg[id]', $gg_id, time() + 3600, '/' );
		setcookie( 'snsgg[email]', $gg_email, time() + 3600, '/' );
		setcookie( 'snsgg[name]', $gg_name, time() + 3600, '/' );
		setcookie( 'snsgg[picture]', $gg_picture, time() + 3600, '/' );
	} else {
		$result = 1;
	
		$user_row = wps_get_user($user_id);
	
		if ( $user_row['user_status'] == '1' ) {
			$code = 503;
			$msg = '차단된 회원입니다.';
			$json = compact('code', 'msg');
			exit( json_encode($json) );
		}
		if ( $user_row['user_status'] == '4' ) {
			$code = 504;
			$msg = '탈퇴하신 회원입니다.';
			$json = compact('code', 'msg');
			exit( json_encode($json) );
		}
	
		// 중복 로그인 방지를 위해 기존 인증세션 파기
		// $session_id = wps_get_user_meta( $user_id, 'wps_session_id' );
		// if ( strcmp($session_id, session_id()) ) {
		// 	$wsession->sessDestroy( $session_id );
		// }
	
		// 인증세션 생성
		$user_level = wps_get_user_meta( $user_id, 'wps_user_level' );
		$_SESSION['login']['userid'] = $user_id;
		$_SESSION['login']['user_login'] = $user_row['user_login'];
		$_SESSION['login']['user_name'] = $user_row['user_name'];
		$_SESSION['login']['display_name'] = $user_row['display_name'];
		$_SESSION['login']['user_level'] = $user_level;
	
		// 세션 업데이트
		wps_update_user_meta( $user_id, 'wps_session_id', session_id() );
	}
}

$json = compact( 'code', 'msg', 'result', 'redirect' );
echo json_encode( $json );

?>