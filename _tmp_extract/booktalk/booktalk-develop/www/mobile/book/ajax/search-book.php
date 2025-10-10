<?php
/*
 * 2016.11.23		softsyw
 * Desc : 책 검색, 큐레이션을 위한 검색. 모든 책을 검색
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';

$code = 0;
$msg = '';

if ( empty($_POST['q']) ) {
	$code = 410;
	$msg = '책 제목을  입력해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$str = $_POST['q'];

$book = lps_search_approved_books($str);	// 승인완료된 모든 책 검색

$result = '';

if (!empty($book)) {
	foreach ($book as $key => $val) {
		$bid = $val['ID'];
		$btitle = $val['book_title'];
		$author = $val['author'];
// 		$result .= '<option value="' . $bid . '">' . $btitle . '(' . $author . ')' . '</option>';
		
		$result .=<<<EOD
					<dl id="bookk-$bid">
						<dt>$btitle</dt>
						<dd>$author</dd>
					</dl>
EOD;
		
	}
}

$json = compact('code', 'msg', 'result');
echo json_encode( $json );

?>