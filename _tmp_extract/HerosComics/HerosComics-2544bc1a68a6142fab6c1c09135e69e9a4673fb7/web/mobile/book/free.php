<?php
/*
 * Desc : 무료
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-fancytree.php';

// 무료
$genre_result = lps_get_free_book_part(0);

$genre_part = $genre_result['results'];
$is_next = $genre_result['is_next'];

// 책 1차 카테고리
$book_cate_first = wps_fancytree_root_node_by_name('wps_category_books');

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area book-listview-area">
				<form id="form-search">
					<div class="book-listview-header">
						<h2>무료</h2>
						<div class="select-header-rarea">
							<select name="category_first" id="category_first">
								<option value="">-전체보기-</option>
					<?php 
					if (!empty($book_cate_first)) {
						foreach ($book_cate_first as $key => $val) {
							$tid = $val['term_id'];
							$tname = $val['name'];
					?>
								<option value="<?php echo $tid ?>"><?php echo $tname ?></option>
					<?php 
						}
					}
					?>
							</select>
							<select name="category_second" id="category_second">
							</select>
						</div>
					</div>
					<div class="booklist-view-area">
						<div class="select-view-rarea">
							<select name="" id="">
								<!-- option value="">인기 순</option> -->
								<option value="">최근 등록 순</option>
								<!-- option value="">커뮤니티 랭킹 순</option> -->
							</select>
						</div>
						<ul class="booklist-view publisher-book-area">
				<?php
				if ( !empty($genre_part) ) {
					foreach ( $genre_part as $key => $val ) {
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
			<?php 
			if (!strcmp($is_next, 'Y')) {
			?>
						<div class="btn-newbook-more"><a href="#" id="view-more" class="btn-skyblue" title="1">더 보기</a></div>
			<?php 
			}
			?>
					</div>
				</form>
			</div>
			<!-- main End -->
		
		</div>
		
		<script>
		$(function() {
			$("#view-more").click(function(e) {
// 				e.preventDefault();
				var page = $(this).attr("title");
				var nextPage = parseInt(page) + 1;
				var secondCate = $("#category_second").val();

				$.ajax({
					type : "POST",
					url : "./ajax/get-more-free-book.php",
					data : {
						"page" : page,
						"category" : secondCate
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

			// category first
			$("#category_first").change(function() {
				var id = $(this).val();

				$("#category_second option").remove();
				
				if ( id == "" ) {
					return;
				}
				
				$.ajax({
					type : "POST",
					url : "./ajax/get-second-category.php",
					data : {
						"id" : id
					},
					dataType : "json",
					success : function(res) {
						if (res.code != "0") {
							alert(res.msg);
						} else {
							
							$("#category_second").html(res.lists);
						}
					}
				});
			});

			// category second
			$("#category_second").change(function() {
				var secondCate = $(this).val();
				
				$.ajax({
					type : "POST",
					url : "./ajax/get-more-free-book.php",
					data : {
						"page" : 1,
						"category" : secondCate
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							var newList = $(res.list).hide().fadeIn(1000);
							$(".booklist-view").html( newList );
							$("#view-more").attr("title", 2);

							if (res.is_next == "N") {
								$(".btn-newbook-more").fadeOut("slow");
							} else {
								$(".btn-newbook-more").fadeIn();
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
