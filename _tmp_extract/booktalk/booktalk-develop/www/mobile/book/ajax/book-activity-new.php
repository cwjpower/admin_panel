<?php
/*
 * Desc : 담벼락 게시글 등록
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-activity.php';

$code = 0;
$msg = '';

$user_id = wps_get_current_user_id();

if ( empty($user_id) ) {
	$code = 400;
	$msg = '로그인 후 이용해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['book_id']) ) {
	$code = 402;
	$msg = '책을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['act_title']) ) {
	$code = 403;
	$msg = '제목을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['act_content']) ) {
	$code = 404;
	$msg = '내용을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// if ( empty($_POST['object_type']) ) {
// 	$code = 405;
// 	$msg = '게시글의 유형을 선택해 주십시오.';
// 	$json = compact('code', 'msg');
// 	exit( json_encode($json) );
// }

$object_type = empty($_POST['object_type']) ? 'activity' : $_POST['object_type'];

if (!strcmp($object_type, 'activity')) {
	// 게시글 등록
	$result = lps_add_activity();
	
} else {
	// 공지사항 등록
	$_POST['post_type'] = 'notice_new';
	$_POST['post_title'] = $_POST['act_title'];
	$_POST['post_content'] = $_POST['act_content'];
	$notice_book = $_POST['book_id'];
	
	$result = wps_add_post();
	$post_id = $result;
	
	if (!empty($post_id)) {
		$meta_value = serialize(compact('notice_book'));
		wps_update_post_meta($post_id, 'wps_notice_books', $meta_value);
		
		// File Attachment
		if ( $post_id && !empty($_POST['file_path']) ) {
			$yyyymm = date('Ym');
			$upload_dir = UPLOAD_PATH . '/board/' . $post_id . '/' . $yyyymm;
			$upload_url = UPLOAD_URL . '/board/' . $post_id . '/' . $yyyymm;
			$meta_value = array();
		
			if ( !is_dir($upload_dir) ) {
				mkdir($upload_dir, 0777, true);
			}
		
			foreach ( $_POST['file_path'] as $key => $val ) {
				$file_ext = strtolower(pathinfo( $val, PATHINFO_EXTENSION ));
				$file_name = basename($_POST['file_name'][$key]);
		
				if ( in_array($file_ext, unserialize(WPS_IMAGE_EXT)) ) {
					$new_file_name = wps_make_rand() . '.' . $file_ext;
				} else {
					$new_file_name = wps_make_rand();
				}
		
				$new_val['file_path'] = $upload_dir . '/' . $new_file_name;
				$new_val['file_url'] = $upload_url . '/' . $new_file_name;
				$new_val['file_name'] = $file_name;
				$result = rename( $_POST['file_path'][$key], $new_val['file_path'] );
				array_push($meta_value, $new_val);
			}
		
			$meta_value = serialize( $meta_value );
			wps_update_post_meta( $post_id, 'wps-post-attachment', $meta_value );
		}
	}
}

if ( empty($result) ) {
	$code = 501;
	$msg = '등록하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$json = compact('code', 'msg');
echo json_encode( $json );
?>