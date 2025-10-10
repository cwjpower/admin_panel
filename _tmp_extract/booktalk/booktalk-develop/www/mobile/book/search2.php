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
				<h2>출판사 검색결과</h2>
				<ul class="m-search">
			    	<li><a href="search.php?glk=<?php echo $glk ?>" data-ajax="false">도서(<?php echo $search_books_count ?>)</a></li>
			    	<li><a href="search2.php?glk=<?php echo $glk ?>" class="ui-btn-active ui-state-persist" data-ajax="false">출판사(<?php echo $search_publishers_count ?>)</a></li>
			    	<li><a href="search3.php?glk=<?php echo $glk ?>" data-ajax="false">작가(<?php echo $search_authors_count ?>)</a></li>
			    	<li><a href="search4.php?glk=<?php echo $glk ?>" data-ajax="false">큐레이팅(<?php echo $search_curations_count ?>)</a></li>
			    </ul>
<?php 
if (!empty($search_publishers)) {
	foreach ($search_publishers as $key => $val) {
		$user_id = $val['ID'];
		$user_name = $val['user_name'];
		
		$book_rows = lps_get_books_by_user($user_id);
		$book_count = empty($book_rows) ? 0 : count($book_rows);
?>

			    <div class="book-search-view">
			    	<div data-role="collapsible" data-collapsed="true">
						<h1 class="publisher-tit"><?php echo $user_name ?>
							<div class="book-search-r book-arrow-down">등록 책 보기[<?php echo $book_count ?>]</div>
						</h1>
						<ul class="book-search-name">
				<?php 
				if ($book_count > 0) {
					foreach ($book_rows as $k => $v) {
						$book_id = $v['ID'];
						$book_title = $v['book_title'];
				?>
							<li><a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><?php echo $book_title ?></a></li>
				<?php 
					}
				}
				?>
						</ul>
					</div>			    	
			    </div>
<?php 
	}
}
?>			    

			    
			</div>
			<!-- main End -->
		</div>
		<!-- m-search01 End -->

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
