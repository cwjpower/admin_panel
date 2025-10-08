<?php
/*
 * Desc : 담벼락 게시글에 댓글 등록
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

if ( empty($_POST['activity_id']) ) {
	$code = 402;
	$msg = '게시글을 선택해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

if ( empty($_POST['comment']) ) {
	$code = 404;
	$msg = '댓글 내용을 입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

// 게시글 등록
$result = lps_add_activity_comment();

if ( empty($result) ) {
	$code = 501;
	$msg = '등록하지 못했습니다.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$com_rows = lps_get_activity_comment( $result );

// $author = $com_rows['comment_author'];
$created = date('Y.m.d | A H:i', strtotime($com_rows['comment_date']));
$content = nl2br($com_rows['comment_content']);

$list =<<<EOD

<li id="comment-$result">
	<div class="ripple-arae">
		<dl>
			<dt class="my-ripple">나</dt>
			<dd>$content</dd>
			<dd><em>$created</em></dd>
			<dd class="ripple-del"><a href="#reppleDel" class="btn-ripple-del btn-comment-delete" data-rel="popup" data-position-to="window">댓글삭제</a></dd>
		</dl>
	</div>
</li>

EOD;

$json = compact('code', 'msg', 'list');
echo json_encode( $json );
?>