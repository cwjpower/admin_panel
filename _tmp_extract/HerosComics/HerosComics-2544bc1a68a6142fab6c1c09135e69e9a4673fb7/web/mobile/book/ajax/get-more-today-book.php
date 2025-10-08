<?php
/*
 * Desc : 오늘의 신간 전체 페이지에서 더 보기 기능
 */
require_once '../../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';

$code = 0;
$msg = '';

if ( empty($_POST['page']) ) {
	$code = 408;
	$msg = '더 보기를 클릭해 주십시오.';
	$json = compact('code', 'msg');
	exit( json_encode($json) );
}

$page = $_POST['page'];

$todays_result = lps_get_todays_new_book_part($page);

$todays_new = $todays_result['today_slice'];
$next_page = $todays_result['page'];
$is_next = $todays_result['is_next'];

$list = '';

if (!empty($todays_new)) {
	foreach ($todays_new as $key => $val) {
		$book_rows = lps_get_book($val);
			
		$book_id = $book_rows['ID'];
		$publisher = $book_rows['publisher'];
		$book_title = $book_rows['book_title'];
		$author = $book_rows['author'];
		$cover_img = $book_rows['cover_img'];
		$sale_price = $book_rows['sale_price'];
		
		$book_url = MOBILE_URL . '/book/book.php?id=' . $book_id;
		$sale_price = number_format($sale_price);
		
		$list .=<<<EOD
			<li>
				<div class="book-img-box">
					<div class="book-opc"></div>
					<a href="$book_url" data-ajax="false">
						<img class="book-img" src="$cover_img">
					</a>
				</div>
				<div class="publisher-book-list">
					<div class="publisher-book-tit ellipsis">$book_title</div>
					<div class="publisher-txt">$author | $publisher</div>
					<div class="publishe-book-price">$sale_price 원</div>
				</div>
			</li>
EOD;
		
	}
}

$json = compact('code', 'msg', 'list', 'next_page', 'is_next');
echo json_encode( $json );


?>