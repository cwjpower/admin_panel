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
				<h2>큐레이팅 검색결과</h2>
				<ul class="m-search">
			    	<li><a href="search.php?glk=<?php echo $glk ?>" data-ajax="false">도서(<?php echo $search_books_count ?>)</a></li>
			    	<li><a href="search2.php?glk=<?php echo $glk ?>" data-ajax="false">출판사(<?php echo $search_publishers_count ?>)</a></li>
			    	<li><a href="search3.php?glk=<?php echo $glk ?>" data-ajax="false">작가(<?php echo $search_authors_count ?>)</a></li>
			    	<li><a href="search4.php?glk=<?php echo $glk ?>" class="ui-btn-active ui-state-persist" data-ajax="false">큐레이팅(<?php echo $search_curations_count ?>)</a></li>
			    </ul>
			    <div class="book-search-box book-search-view">
			    	<ul class="curating-area">
			<?php 
			if (!empty($search_curations)) {
				foreach ($search_curations as $key => $val) {
					$cid = $val['ID'];
					$ctitle = $val['curation_title'];
					$cimg = unserialize($val['cover_img']);
					$cover = $cimg['file_url'];
					$created_dt = substr($val['created_dt'], 0, 10);
					$cmeta = unserialize($val['curation_meta']);
					$cuserid = $val['user_id'];
					$user_row = wps_get_user($cuserid);
					$user_name = $user_row['user_name'];
					$user_avatar = wps_get_user_meta($cuserid, 'wps_user_avatar');
					if (empty($user_avatar)) {
						$user_avatar = INC_URL . '/images/common/photo-default.png';
					}
			?>
						<li>
							<div class="curating-img"><a href="curation_view.php?id=<?php echo $cid ?>" data-ajax="false"><img src="<?php echo $cover ?>" title="큐레이션 표지"></a></div>
							<div class="curating-txt">
								<a href="curation_view.php?id=<?php echo $cid ?>" data-ajax="false"><strong><?php echo $ctitle ?></strong></a>
								<span><?php echo $user_name ?> ㅣ <?php echo $created_dt ?></span>
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
