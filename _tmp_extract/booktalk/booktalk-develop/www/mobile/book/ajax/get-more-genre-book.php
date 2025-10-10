<?php
/*
 * Desc : 장르별 더 보기
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
$category = empty($_POST['category']) ? '' : $_POST['category'];

$genre_result = lps_get_genre_book_part($page, $category);

$genre_part = $genre_result['results'];
$next_page = $genre_result['page'];
$is_next = $genre_result['is_next'];

$total_count = $genre_result['total_count'];

// var_dump($is_next);

$list = '';

if (!empty($genre_part)) {
	foreach ($genre_part as $key => $val) {
		$book_id = $val['ID'];
		$publisher = $val['publisher'];
		$book_title = $val['book_title'];
		$author = $val['author'];
		$cover_img = $val['cover_img'];
		$sale_price = $val['sale_price'];
		
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