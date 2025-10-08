<?php
/*
 * Desc : API 내 소식, 담벼락에 새로운 댓글이 등록된 게시글
 * 	method : GET
 */
require_once '../../wps-config.php';

$code = 0;
$msg = '';

if ( empty($_GET['uid']) ) {
	$code = 401;
	$msg = '회원의 UID가 필요합니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$user_id = $_GET['uid'];

$query = "
		SELECT
			a.id AS ID,
		    a.subject,
		    a.created_dt,
			COUNT(*) AS unread,
		    b.cover_img
		FROM
			bt_activity AS a
		LEFT JOIN
			bt_activity_comment AS c
		ON
			a.id = c.activity_id
		LEFT JOIN
			bt_books AS b
		ON
			a.book_id = b.ID
		WHERE
			a.user_id = ? AND
			c.comment_read = 0
		GROUP BY
			a.id
";
$stmt = $wdb->prepare( $query );
$stmt->bind_param( 'i', $user_id );
$stmt->execute();
$LIST = $wdb->get_results($stmt);

$json = compact( 'code', 'msg', 'LIST' );
echo json_encode( $json );

?>