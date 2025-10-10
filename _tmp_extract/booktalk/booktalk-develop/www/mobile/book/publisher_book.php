<?php
/*
 * Desc : 출판사 입점 도서
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
// require_once FUNC_PATH . '/functions-term.php';

if ( empty($_GET['id']) ) {
	lps_js_back( '출판사가 존재하지 않습니다.' );
}

$publisher_id = $_GET['id'];

if (empty($_GET['ob'])) {
	$ob = '1';
	$orderby = 'hit_count DESC';	// 조회 순
} else {
	$ob = $_GET['ob'];
	
	if ($ob == '1') {
		$orderby = 'hit_count DESC';	// 조회 순
	} else if ($ob == '2') {
		$orderby = 'order_count DESC';	// 주문 순
	} else if ($ob == '3') {
		$orderby = 'b.created_dt DESC';
	} else {
		$orderby = 'b.book_title ASC';
	}
}

$pub_books = lps_get_publisher_books( $publisher_id, $orderby );

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>출판사 입점 도서
					<div class="list-select-menu">
						<select name="lps_order" id="lps_order">
							<option value="1" <?php echo $ob == '1' ? 'selected' : '' ?>>누적 조회 순</option>
							<option value="2" <?php echo $ob == '2' ? 'selected' : '' ?>>누적 판매 순</option>
							<option value="3" <?php echo $ob == '3' ? 'selected' : '' ?>>최근 등록일 순</option>
							<option value="4" <?php echo $ob == '4' ? 'selected' : '' ?>>제목 순</option>
						</select>
					</div>
				</h2>
				<div class="publisher-area">
					<ul class="publisher-book-area"W>
			<?php
			// 상위 10개
			if ( !empty($pub_books) ) {
				foreach ( $pub_books as $key => $val ) {
					
					$book_id = $val['ID'];
					$publisher = $val['publisher'];
					$book_title = $val['book_title'];
					$author = $val['author'];
					$cover_img = $val['cover_img'];
					$created_dt = substr($val['created_dt'], 0, 10);
					$sale_price = $val['sale_price'];
			?>
						<li>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $cover_img ?>"></a>
							</div>
							<div class="publisher-book-list">
								<div class="publisher-book-tit ellipsis">
									<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><?php echo $book_title ?></a>
								</div>
								<div class="publisher-txt"><?php echo $author?> | <?php echo $publisher ?></div>
								<div class="publishe-book-price"><?php echo number_format($sale_price) ?>원</div>
							</div>
						</li>
			<?php 
				}
			}
			?>
						<li class="hide">
							<div class="book-img-box">
								<div class="icon-dc">10%</div><!-- 할인하는 책 -->
								<div class="book-opc"></div>
								<a href="#"><img class="book-img" src="../img/book_img/book_sample01.jpg" alt=""></a>
							</div>
							<div class="publisher-book-list">
								<a href="#">
									<div class="publisher-book-tit ellipsis">나미야 잡화점의 기적나미야 잡화점의 기적</div>
									<div class="publisher-txt">저자 | 출판사</div>
									<div class="publishe-book-price">8,000원</div>
								</a>
							</div>
						</li>
						<li class="hide">
							<div class="book-img-box">
								<div class="icon-new">New</div><!-- 새로 나온 책 -->
								<div class="book-opc"></div>
								<a href="#"><img class="book-img" src="../img/book_img/book_sample01.jpg" alt=""></a>
							</div>
							<div class="publisher-book-list">
								<a href="#">
									<div class="publisher-book-tit ellipsis">나미야 잡화점의 기적나미야 잡화점의 기적</div>
									<div class="publisher-txt">저자 | 출판사</div>
									<div class="publishe-book-price">8,000원</div>
								</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- main End -->
		
		</div>
		
		<script>
		$(function() {
			$("#lps_order").change(function() {
				var order = $(this).val();

				location.href = "?id=<?php echo $publisher_id ?>&ob=" + order; 
			});
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
