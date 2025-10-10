<?php
/*
 * Desc : 커뮤니티 랭킹 더보기
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

$best_rank_book = wps_get_option( 'lps_best_rank_1000' );
$best_books = unserialize($best_rank_book);

$slice = 20;
$start = $page * $slice;
$end = $start + $slice;

$best_slice = array_slice($best_books, $start, $slice);

$total_count = count($best_books);
$is_next = $total_count > $end ? 'Y' : 'N';

$next_page = $page + 1;

$list = '';

if (!empty($best_slice)) {
	foreach ($best_slice as $key => $val) {
		$book_rows = lps_get_book($val);
			
		$book_id = $book_rows['ID'];
		$publisher = $book_rows['publisher'];
		$book_title = $book_rows['book_title'];
		$author = $book_rows['author'];
		$cover_img = $book_rows['cover_img'];
		$sale_price = $book_rows['sale_price'];
		
		$book_url = MOBILE_URL . '/book/book.php?id=' . $book_id;
		
		$number = $page * $slice + $key + 1;
		
		$list .=<<<EOD
		
			<li>
				<div class="ranking-box">
					<div class="ranking-l">
						<span class="ranking-num">$number</span>
						<div class="rankinglist book-img-box">
							<div class="book-opc"></div>
							<a href="$book_url" data-ajax="false">
								<img class="book-img" src="$cover_img" title="표지">
							</a>
						</div>
					</div>
					<div class="ranking-r">
						<div class="ranking-tit ellipsis">
							<a href="$book_url" data-ajax="false">$book_title</a>
						</div>
						<div class="score">저자<span>$author</span></div>
						<!-- div class="score">점수<span>12,345점</span></div>-->
					</div>
				</div>
			</li>
EOD;
		
	}
}

$json = compact('code', 'msg', 'list', 'next_page', 'is_next');
echo json_encode( $json );


?>