<?php
/*
 * Desc : 프로필 사진 업로드 및 업데이트
 *
 */
require_once '../../../wps-config.php';
require_once INC_PATH . '/classes/WpsThumbnail.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();
if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 삭제
if (!empty($_POST['fileDelete'])) {
	if (!strcmp($_POST['fileDelete'], 'profile_photo')) {	// 프로필 사진
		
		$user_avatar = wps_get_user_meta($user_id, 'wps_user_avatar');
		
		$user_avatar_path = str_replace(UPLOAD_URL, UPLOAD_PATH, $user_avatar);
		unlink($user_avatar_path);
		
// 		var_dump($user_avatar_path); exit;
		
		wps_update_user_meta($user_id, 'wps_user_avatar', "");
	}
} else {	// 등록, 수정
	if (empty($_POST['eleName'])) {
		$file_element = 'attachment';
	} else {
		$file_element = str_replace(array('[', ']'), '', $_POST['eleName']);		// attachment[] -> attachment
	}
	
	$upload_dir = UPLOAD_PATH . '/tmp';
	if ( !is_dir($upload_dir) ) {
		mkdir($upload_dir, 0777, true);
	}
	
	$img_files = array( 'jpg', 'jpeg', 'gif', 'png' );
	$able_thumb = 1;
	
	$wps_thumbnail = new WpsThumbnail();
	
	$file_ext = strtolower(pathinfo( $_FILES[$file_element]['name'], PATHINFO_EXTENSION ));
	
	if ( in_array($file_ext, $img_files) ) {
		$new_file_name = wps_make_rand() . '.' . $file_ext;
		$able_thumb = 1;
	} else {
		$code = 403;
		$msg = '프로필 사진으로 사용할 수 없는 파일 포멧입니다.';
		$json = compact('code', 'msg');
		exit( json_encode($json) );
	}
	
	$upload_path = $upload_dir . '/' . $new_file_name;
	$result = move_uploaded_file( $_FILES[$file_element]['tmp_name'], $upload_path );
	
	if ( $result ) {
		$ym = date('Ym');
		
	// 	$file_url = UPLOAD_URL . '/tmp/' . $new_file_name;
		$file_path = UPLOAD_PATH . '/tmp/' . $new_file_name;
	// 	$file_name = $_FILES[$file_element]['name'];
	
		// Thumbnail
		if (empty($_POST['thumbW'])) {
			$thumb_suffix = '-200x200';
			$thumb_width = 200;
			$thumb_height = 200;
		} else {
			$tsw = $_POST['thumbW'];
			$tsh = $_POST['thumbH'];
			$thumb_suffix = '-' . $tsw . 'x' . $tsh;
			$thumb_width = $tsw;
			$thumb_height = $tsh;
		}
		
		$thumb_name = $wps_thumbnail->resize_image( $file_path, $thumb_suffix, $thumb_width, $thumb_height );
		$thumb_path = UPLOAD_PATH . '/tmp/' . $thumb_name;
		
		$user_profile_path = UPLOAD_PATH . '/user_avatar/' . $ym . '/' . $thumb_name;
		$user_profile_url = UPLOAD_URL . '/user_avatar/' . $ym . '/' . $thumb_name;
		
		rename($thumb_path, $user_profile_path);
		
		unlink($file_path);
		
		if (!strcmp($file_element, 'profile_photo')) {
			wps_update_user_meta($user_id, 'wps_user_avatar', $user_profile_url);
		} else if (!strcmp($file_element, 'profile_photo_bg')) {
			wps_update_user_meta($user_id, 'wps_user_profile_bg', $user_profile_url);
		}
		
	} else {
		$code = 505;
		$msg = '파일을 업로드할 수 없습니다. 관리자에게 문의해 주십시오.';
	}
}

$json = compact('code', 'msg', 'user_profile_url');
echo json_encode( $json );

?>