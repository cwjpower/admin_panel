<?php
/*
 * Desc : 커뮤니티 랭킹
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';

// 커뮤니티 랭킹 from page관리 베스트(랭킹)
$best_rank_book = wps_get_option( 'lps_best_rank_1000' );
$best_books = unserialize($best_rank_book);
$slice_count = 20;
$total_count = count($best_books);

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>커뮤니티 랭킹</h2>
				<div class="community-area">
					<ul class="ranking-list">
			<?php
			if ( !empty($best_books) ) {
				$i = 1;
				foreach ( $best_books as $key => $val ) {
					if ($key < $slice_count) {	// 최초는 20개 까지만
					
						$book_rows = lps_get_book($val);
						
						$book_id = $book_rows['ID'];
						$publisher = $book_rows['publisher'];
						$book_title = $book_rows['book_title'];
						$author = $book_rows['author'];
						$cover_img = $book_rows['cover_img'];
						$created_dt = substr($book_rows['created_dt'], 0, 10);
			?>
						<li>
							<div class="ranking-box">
								<div class="ranking-l">
									<span class="ranking-num"><?php echo $i ?></span>
									<div class="rankinglist book-img-box">
										<div class="book-opc"></div>
										<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false">
											<img class="book-img" src="<?php echo $cover_img ?>" title="표지">
										</a>
									</div>
								</div>
								<div class="ranking-r">
									<div class="ranking-tit ellipsis">
										<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><?php echo $book_title ?></a>
									</div>
									<div class="score">저자<span><?php echo $author ?></span></div>
									<!-- div class="score">점수<span>12,345점</span></div>-->
								</div>
							</div>
						</li>
			<?php 
					}
					$i++;
				}
			}
			?>
					</ul>
					
			<?php 
			if ($slice_count < $total_count) {
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
					url : "./ajax/get-more-community-rank.php",
					data : {
						"page" : page
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							var newList = $(res.list).hide().fadeIn(1000);
							$(".ranking-list").append( newList );
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
