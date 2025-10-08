<?php
/*
 * Desc : 찜목록에서 선택 책 삭제
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
	$code = 410;
	$msg = '찜 목록에서 제외할 책을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$book_id = $_POST['bookID'];

$meta_key = 'lps_user_wishlist';

$wishlist = wps_get_user_meta($user_id, $meta_key);
$wishlist_unserial = unserialize($wishlist);

foreach ($wishlist_unserial as $key => $val) {
	if (in_array($val, $book_id)) {
		unset($wishlist_unserial[$key]);
	}
}
$serialized = serialize($wishlist_unserial);

wps_update_user_meta($user_id, $meta_key, $serialized);

$json = compact('code', 'msg');
echo json_encode( $json );
?>