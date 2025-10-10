<?php
/*
 * Desc : 검색 결과
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-page.php';

$glk = empty($_GET['glk']) ? '' : trim($_GET['glk']);

$search_books = lps_search_book_by_keyword( $glk );
$search_books_count = count($search_books);

$search_publishers = lps_search_publisher_by_keyword( $glk );
$search_publishers_count = count($search_publishers);

$search_authors = lps_search_author_by_keyword( $glk );
$search_authors_count = count($search_authors);

$search_curations = lps_search_curation_by_keyword( $glk );
$search_curations_count = count($search_curations);

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>			
		
			<!-- main -->
			<div data-role="main" class="ui-content book-search-area">
				<h2>도서명 검색결과</h2>
				<ul class="m-search">
			    	<li><a href="search.php?glk=<?php echo $glk ?>" class="ui-btn-active ui-state-persist" data-ajax="false">도서(<?php echo $search_books_count ?>)</a></li>
			    	<li><a href="search2.php?glk=<?php echo $glk ?>" data-ajax="false">출판사(<?php echo $search_publishers_count ?>)</a></li>
			    	<li><a href="search3.php?glk=<?php echo $glk ?>" data-ajax="false">작가(<?php echo $search_authors_count ?>)</a></li>
			    	<li><a href="search4.php?glk=<?php echo $glk ?>" data-ajax="false">큐레이팅(<?php echo $search_curations_count ?>)</a></li>
			    </ul>
			    <div class="book-search-box book-search-view">
			    	<ul class="book-view-area">

<?php
if (!empty($search_books)) {
	foreach ($search_books as $key => $val) {
		$bid = $val['ID'];
		$btitle = $val['book_title'];
		$bcover = $val['cover_img'];
		$bauthor = $val['author'];
?>	
						<li>
							<div class="book-view">
								<div class="book-pic">
									<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $bid ?>" data-ajax="false"><img src="<?php echo $bcover ?>" alt="책 표지"></a>
								</div>
								<div class="book-txt">
									<span class="book-tit"><a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $bid ?>" data-ajax="false"><?php echo $btitle ?></a></span>
									<span class="book-writer"><?php echo $bauthor ?></span>
								</div>
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
		<!-- m-search01 End -->


<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
