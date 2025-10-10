<?php
/*
 * Desc : 장르별
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();

// 내가 많이 읽은 장르의 다른 책
$genre_row = lps_get_my_most_read_genre( $user_id );
$genre_id = @$genre_row['category_second'];
$most_genre_book = lps_get_my_most_genre_books( $genre_id, 4 );

// 내가 많이 읽은 작가의 다른 책
$author_row = lps_get_my_most_read_author( $user_id );
$author = @$author_row['author'];
$most_author_book = lps_get_my_most_author_books( $author, 4 );

// 내가 많이 읽은 출판사의 다른 책
$publisher_row = lps_get_my_most_read_publisher( $user_id );
$publisher = @$publisher_row['publisher'];
$most_publisher_book = lps_get_my_most_publisher_books( $publisher, 4 );

// 내가 아직 잃지 않은 단행본

// 내 친구들이 많이 읽은 책
$friend = lps_get_my_friends( $user_id );
if (!empty($friend)) {
	$most_friend_book = lps_get_my_friends_books( $friend, 4 );
}

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>추천</h2>
				<div class="booklist-view-area">
					<div class="advice-tit">
						내가 많이 읽은 장르의 다른 책
						<a href="#" class="btn-advicelist-more">더보기</a>
					</div>
					<ul class="booklist-view publisher-book-area">
			<?php 
			if (!empty($most_genre_book)) {
				foreach ($most_genre_book as $key => $val) {
					$book_id = $val['ID'];
					$publisher = $val['publisher'];
					$book_title = $val['book_title'];
					$author = $val['author'];
					$cover_img = $val['cover_img'];
					$sale_price = $val['sale_price'];
			?>
						<li>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false">
									<img class="book-img" src="<?php echo $cover_img ?>">
								</a>
							</div>
							<div class="publisher-book-list">
								<div class="publisher-book-tit ellipsis"><?php echo $book_title ?></div>
								<div class="publisher-txt"><?php echo $author ?> | <?php echo $publisher ?></div>
								<div class="publishe-book-price"><?php echo number_format($sale_price) ?>원</div>
							</div>
						</li>
			<?php 
				}
			}
			?>
					</ul>
				</div>
				<div class="booklist-view-area">
					<div class="advice-tit">
						내가 많이 읽은 작가의 다른 책
						<a href="#" class="btn-advicelist-more">더보기</a>
					</div>
					<ul class="booklist-view publisher-book-area">
			<?php 
			if (!empty($most_author_book)) {
				foreach ($most_author_book as $key => $val) {
					$book_id = $val['ID'];
					$publisher = $val['publisher'];
					$book_title = $val['book_title'];
					$author = $val['author'];
					$cover_img = $val['cover_img'];
					$sale_price = $val['sale_price'];
			?>
						<li>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false">
									<img class="book-img" src="<?php echo $cover_img ?>">
								</a>
							</div>
							<div class="publisher-book-list">
								<div class="publisher-book-tit ellipsis"><?php echo $book_title ?></div>
								<div class="publisher-txt"><?php echo $author ?> | <?php echo $publisher ?></div>
								<div class="publishe-book-price"><?php echo number_format($sale_price) ?>원</div>
							</div>
						</li>
			<?php 
				}
			}
			?>
					</ul>
				</div>
				<div class="booklist-view-area">
					<div class="advice-tit">
						내가 많이 읽은 출판사의 다른 책
						<a href="#" class="btn-advicelist-more">더보기</a>
					</div>
					<ul class="booklist-view publisher-book-area">
			<?php 
			if (!empty($most_publisher_book)) {
				foreach ($most_publisher_book as $key => $val) {
					$book_id = $val['ID'];
					$publisher = $val['publisher'];
					$book_title = $val['book_title'];
					$publisher = $val['publisher'];
					$cover_img = $val['cover_img'];
					$sale_price = $val['sale_price'];
			?>
						<li>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false">
									<img class="book-img" src="<?php echo $cover_img ?>">
								</a>
							</div>
							<div class="publisher-book-list">
								<div class="publisher-book-tit ellipsis"><?php echo $book_title ?></div>
								<div class="publisher-txt"><?php echo $publisher ?> | <?php echo $publisher ?></div>
								<div class="publishe-book-price"><?php echo number_format($sale_price) ?>원</div>
							</div>
						</li>
			<?php 
				}
			}
			?>
					</ul>
				</div>
				<!-- div class="booklist-view-area">
					<div class="advice-tit">
						내가 아직 잃지 않은 단행본
						<a href="#" class="btn-advicelist-more">더보기</a>
					</div>
					<ul class="booklist-view publisher-book-area">
						<li>
							<div class="book-img-box">
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
						<li>
							<div class="book-img-box">
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
						<li>
							<div class="book-img-box">
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
						<li>
							<div class="book-img-box">
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
				</div> -->
				<div class="booklist-view-area">
					<div class="advice-tit">
						내 친구들이 많이 읽은 책
						<a href="#" class="btn-advicelist-more">더보기</a>
					</div>
					<ul class="booklist-view publisher-book-area">
			<?php 
			if (!empty($most_friend_book)) {
				foreach ($most_friend_book as $key => $val) {
					$book_id = $val['ID'];
					$publisher = $val['publisher'];
					$book_title = $val['book_title'];
					$publisher = $val['publisher'];
					$cover_img = $val['cover_img'];
					$sale_price = $val['sale_price'];
			?>
						<li>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false">
									<img class="book-img" src="<?php echo $cover_img ?>">
								</a>
							</div>
							<div class="publisher-book-list">
								<div class="publisher-book-tit ellipsis"><?php echo $book_title ?></div>
								<div class="publisher-txt"><?php echo $publisher ?> | <?php echo $publisher ?></div>
								<div class="publishe-book-price"><?php echo number_format($sale_price) ?>원</div>
							</div>
						</li>
			<?php 
				}
			}
			?>
					</ul>
				</div>
			</div>
			<!-- main End -->
		
		</div>
		
		<script>
		$(function() {
			$("#view-more").click(function(e) {
// 				e.preventDefault();
				var page = $(this).attr("title");
				var nextPage = parseInt(page) + 1;

				$.ajax({
					type : "POST",
					url : "./ajax/get-more-today-book.php",
					data : {
						"page" : page
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							var newList = $(res.list).hide().fadeIn(1000);
							$(".booklist-view").append( newList );
							$("#view-more").attr("title", res.next_page);

							if (res.is_next == "N") {
								$(".btn-newbook-more").fadeOut("slow");
							}
						} else {
							alert(res.msg);
						}
					}
				});
			});
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
