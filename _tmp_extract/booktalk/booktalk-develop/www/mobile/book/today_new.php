<?php
/*
 * Desc : 오늘의 신간
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
// require_once FUNC_PATH . '/functions-term.php';

// 오늘의신간
$todays_result = lps_get_todays_new_book_part(0);

$todays_new = $todays_result['today_slice'];
$is_next = $todays_result['is_next'];

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>오늘의 신간</h2>
				<div class="booklist-view-area">
					<div class="newbook-date">
						오늘(<?php echo date('Y-m-d') ?>)
						<!-- a href="#" class="btn-all-newbook">전체보기</a> -->
					</div>
					<ul class="booklist-view publisher-book-area">
					
			<?php
			if ( !empty($todays_new) ) {
				foreach ( $todays_new as $key => $val ) {
					
					$book_rows = lps_get_book($val);
					
					$book_id = $book_rows['ID'];
					$publisher = $book_rows['publisher'];
					$book_title = $book_rows['book_title'];
					$author = $book_rows['author'];
					$cover_img = $book_rows['cover_img'];
					$sale_price = $book_rows['sale_price'];
// 					$created_dt = substr($book_rows['created_dt'], 0, 10);
					
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
					
			<?php 
			if (!strcmp($is_next, 'Y')) {
			?>
					<div class="btn-newbook-more"><a href="#" id="view-more" class="btn-skyblue" title="1">더 보기 +</a></div>
			<?php 
			}
			?>
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
