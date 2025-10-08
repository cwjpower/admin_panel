<?php
/*
 * Desc : 베스트 > 장르별
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-fancytree.php';

// 책 1차 카테고리
$book_cate_first = wps_fancytree_root_node_by_name('wps_category_books');

$first_genre = $book_cate_first[0]['term_id'];

$genre = empty($_GET['genre']) ? $first_genre : $_GET['genre'];

$option_name = 'lps_best_rank_3000';
$best_rank_book = wps_get_option( $option_name );		// 관리자 등록 책
$book_ids = unserialize($best_rank_book);
$best_books = lps_get_best_genre_books( $genre, $book_ids );

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>베스트</h2>
				<div class="best-seller-area">
					<ul class="m-best-seller">
						<li><a href="best_steady.php" data-ajax="false" data-transition="none">스테디셀러</a></li>
						<li><a href="best_genre.php" data-ajax="false" class="ui-btn-active ui-state-persist" data-transition="none">장르별</a></li>
						<li><a href="best_period.php" data-ajax="false" data-transition="none">기간별</a></li>
					</ul>
					<div class="best-seller-box">
						<div class="best-seller-select">
							<form action="form-search">
								<select name="genre" id="genre">
						<?php 
						if (!empty($book_cate_first)) {
							foreach ($book_cate_first as $key => $val) {
								$tid = $val['term_id'];
								$tname = $val['name'];
								$selected = $tid == $genre ? 'selected' : '';
						?>
									<option value="<?php echo $tid ?>" <?php echo $selected ?>><?php echo $tname ?></option>
						<?php 
							}
						}
						?>
								</select>
							</form>
						</div>
						<ul class="best-seller-list">
				<?php
				if ( !empty($best_books) ) {
					foreach ( $best_books as $key => $val ) {
						$book_id = $val['ID'];
						$publisher = $val['publisher'];
						$book_title = $val['book_title'];
						$author = $val['author'];
						$cover_img = $val['cover_img'];
						$sale_price = $val['sale_price'];
				?>
							<li>
								<div class="best-seller">
									<div class="best-seller-l">
										<span class="ranking-num"><?php echo $key + 1 ?></span>
										<div class="best-list book-img-box">
											<div class="book-opc"></div>
											<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false">
												<img class="book-img" src="<?php echo $cover_img ?>">
											</a>
										</div>
									</div>
									<div class="best-seller-r">
										<div class="ranking-tit ellipsis">
											<a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><?php echo $book_title ?></a>
										</div>
										<div><?php echo $author ?></div>
										<div>구매가 : <span class="txt-279cf5"><?php echo number_format($sale_price) ?></span>원</div>
										<div><?php echo $publisher ?></div>
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
			</div>
			<!-- main End -->
		</div>
		<!-- m-best01 End -->	
		<!-- m-best02 -->
		<div data-role="page" id="m-best02">
			<!-- panel -->
			<div data-role="panel" id="overlayPanel" data-display="overlay" data-position="right">
				<div class="panel-area user-logout"><!-- display:none; -->
					<div class="panel-header">
						<h2>전체 메뉴 목록</h2>
						<a href="#pageone" data-rel="close" class="btn-panel-close">Close</a>
					</div>
					<div class="panel-login-area">
						<p class="login-txt">로그인이 필요합니다.</p>
						<a class="btn-panel-login" href="#">로그인<span class="gnb_login"></span></a>
					</div>
					<div class="gnb-area">
						<h3>서비스 메뉴</h3>
						<ul class="gnb-menu">
							<li><a href="#">공지사항</a><span class="arrow">&gt;</span></li>
							<li><a href="#">이벤트</a><span class="arrow">&gt;</span></li>
							<li><a href="#">무료도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">인기도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">추천도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">도서신청</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="panel-bottom">
						<a class="service-center"><span class="icon-service"></span><span>고객센터</span></a>
						<a class="advice"><span class="icon-advice"></span><span>건의사항</span></a>
						<div class="btn-app"><a href="#">App 다운로드</a></div>
					</div>
				</div>
				<div class="panel-area user-login"><!-- display:block; -->
					<div class="panel-header">
						<h2>전체 메뉴 목록</h2>
						<a href="#pageone" data-rel="close" class="btn-panel-close">Close</a>
					</div>
					<div class="user-img-area">
						<div class="user-img-bg">
							<div class="user-img"><img src="../img/common/user_img.png" alt=""></div>
						</div>
						<div class="user-info">
							<div class="user-id">사용자 아이디</div>
							<div class="user-msg">프로필메세지</div>
						</div>
					</div>
					<div class="s-menu-area">
						<ul>
							<li><a href="#">내 서재</a></li>
							<li><a href="#">찜 목록</a></li>
							<li><a href="#">장바구니</a></li>
						</ul>	
					</div>
					<div class="my-pocket-area">
						<h3>내 지갑</h3>
						<ul class="pocket-list">
							<li><strong>15,000</strong><span>캐시</span></li>
							<li><strong>1,000</strong><span>포인트</span></li>
							<li><strong>0</strong><span>쿠폰</span></li>
							<li><strong>0</strong><span>대여권</span></li>
						</ul>
					</div>
					<div class="readbooks-list">
						<h3>최근 읽은 책</h3>
						<!-- 읽은 책 없습니다. -->
						<div class="opct-area">
							<div class="opct">최근 읽은 책이 없습니다.</div>
						</div>
						<!-- 읽은 책 3권 보이기 -->
						<ul class="view-book view3-book">
							<li><a href="#"><img src="../img/book_img/book_sample01.jpg" alt="읽은 책 제목1"></a></li>
							<li><a href="#"><img src="../img/book_img/book_sample01.jpg" alt="읽은 책 제목2"></a></li>
							<li><a href="#"><img src="../img/book_img/book_sample01.jpg" alt="읽은 책 제목3"></a></li>
						</ul>
					</div>
					<div class="my-page">
						<h3>마이페이지</h3>
						<ul>
							<li><a href="#">개인정보수정</a><span class="arrow">&gt;</span></li>
							<li><a href="#">통계</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="gnb-area">
						<h3>서비스 메뉴</h3>
						<ul class="gnb-menu">
							<li><a href="#">공지사항</a><span class="arrow">&gt;</span></li>
							<li><a href="#">이벤트</a><span class="arrow">&gt;</span></li>
							<li><a href="#">무료도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">인기도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">추천도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">도서신청</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="panel-bottom">
						<a class="service-center"><span class="icon-service"></span><span>고객센터</span></a>
						<a class="advice"><span class="icon-advice"></span><span>건의사항</span></a>
						<div class="btn-app"><a href="#">App 다운로드</a></div>
					</div>
				</div>
			</div>
			<!-- panel End -->
			<!-- header -->
			<div data-role="header">
				<!-- header-search -->
				<div class="header-search">
					<div class="header-btn-left btn-forward"><a href="#" ></a></div>
					<div class="frm-search-area">
						<form action="">
							<div class="frm-search">
								<input type="text" placeholder="검색어 입력"><span class="search-del">입력 내용 삭제</span>
							</div>
							<button class="search">search</button><button class="close">닫기</button>
						</form>
					</div>
					<div class="header-btn-right">
						 <a href="#overlayPanel" class="ui-btn-inline btn-panel"></a>
					</div>
				</div>
				<!-- header-search End -->
			</div>
			<!-- header End -->
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>베스트</h2>
				<div class="best-seller-area">
					<ul class="m-best-seller">
						<li><a href="#m-best01" data-transition="none">기간별</a></li>
						<li><a href="#m-best02" class="ui-btn-active ui-state-persist" data-transition="none">장르별</a></li>
						<li><a href="#m-best03" data-transition="none">스테디셀러</a></li>
					</ul>
					<div class="best-seller-box">
						<div class="best-seller-select">
							<form action="">
								<select name="" id="">
									<option value="">소설</option>
									<option value="">판타지</option>
									<option value="">자기계발</option>
								</select>
							</form>
						</div>
						<ul class="best-seller-list">
							<li>
								<a href="#">
									<div class="best-seller">
										<div class="best-seller-l">
											<span class="ranking-num">1</span>
											<div class="best-list book-img-box">
												<div class="book-opc"></div>
												<img class="book-img" src="../img/book_img/book_sample01.jpg" alt="">
											</div>
										</div>
										<div class="best-seller-r">
											<div class="ranking-tit ellipsis">풀꽃도 꽃이다풀꽃도 꽃이다풀꽃도 꽃이다풀꽃도 꽃이다풀꽃도 꽃이다.1(양장본 HardCover)</div>
											<div>저자 한강</div>
											<div>구매가 : <span class="txt-279cf5">8,000</span>원</div>
											<div>출판사 에세이</div>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="best-seller">
										<div class="best-seller-l">
											<span class="ranking-num">2</span>
											<div class="best-list book-img-box">
												<div class="book-opc"></div>
												<img class="book-img" src="../img/book_img/book_sample01.jpg" alt="">
											</div>
										</div>
										<div class="best-seller-r">
											<div class="ranking-tit ellipsis">풀꽃도 꽃이다</div>
											<div>저자 한강</div>
											<div>구매가 : <span class="txt-279cf5">8,000</span>원</div>
											<div>출판사 에세이</div>
										</div>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		<!-- m-best02 End -->	
		<!-- m-best03 -->
		<div data-role="page" id="m-best03">
		<!-- panel -->
			<div data-role="panel" id="overlayPanel" data-display="overlay" data-position="right">
				<div class="panel-area user-logout"><!-- display:none; -->
					<div class="panel-header">
						<h2>전체 메뉴 목록</h2>
						<a href="#pageone" data-rel="close" class="btn-panel-close">Close</a>
					</div>
					<div class="panel-login-area">
						<p class="login-txt">로그인이 필요합니다.</p>
						<a class="btn-panel-login" href="#">로그인<span class="gnb_login"></span></a>
					</div>
					<div class="gnb-area">
						<h3>서비스 메뉴</h3>
						<ul class="gnb-menu">
							<li><a href="#">공지사항</a><span class="arrow">&gt;</span></li>
							<li><a href="#">이벤트</a><span class="arrow">&gt;</span></li>
							<li><a href="#">무료도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">인기도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">추천도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">도서신청</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="panel-bottom">
						<a class="service-center"><span class="icon-service"></span><span>고객센터</span></a>
						<a class="advice"><span class="icon-advice"></span><span>건의사항</span></a>
						<div class="btn-app"><a href="#">App 다운로드</a></div>
					</div>
				</div>
				<div class="panel-area user-login"><!-- display:block; -->
					<div class="panel-header">
						<h2>전체 메뉴 목록</h2>
						<a href="#pageone" data-rel="close" class="btn-panel-close">Close</a>
					</div>
					<div class="user-img-area">
						<div class="user-img-bg">
							<div class="user-img"><img src="../img/common/user_img.png" alt=""></div>
						</div>
						<div class="user-info">
							<div class="user-id">사용자 아이디</div>
							<div class="user-msg">프로필메세지</div>
						</div>
					</div>
					<div class="s-menu-area">
						<ul>
							<li><a href="#">내 서재</a></li>
							<li><a href="#">찜 목록</a></li>
							<li><a href="#">장바구니</a></li>
						</ul>	
					</div>
					<div class="my-pocket-area">
						<h3>내 지갑</h3>
						<ul class="pocket-list">
							<li><strong>15,000</strong><span>캐시</span></li>
							<li><strong>1,000</strong><span>포인트</span></li>
							<li><strong>0</strong><span>쿠폰</span></li>
							<li><strong>0</strong><span>대여권</span></li>
						</ul>
					</div>
					<div class="readbooks-list">
						<h3>최근 읽은 책</h3>
						<!-- 읽은 책 없습니다. -->
						<div class="opct-area">
							<div class="opct">최근 읽은 책이 없습니다.</div>
						</div>
						<!-- 읽은 책 3권 보이기 -->
						<ul class="view-book view3-book">
							<li><a href="#"><img src="../img/book_img/book_sample01.jpg" alt="읽은 책 제목1"></a></li>
							<li><a href="#"><img src="../img/book_img/book_sample01.jpg" alt="읽은 책 제목2"></a></li>
							<li><a href="#"><img src="../img/book_img/book_sample01.jpg" alt="읽은 책 제목3"></a></li>
						</ul>
					</div>
					<div class="my-page">
						<h3>마이페이지</h3>
						<ul>
							<li><a href="#">개인정보수정</a><span class="arrow">&gt;</span></li>
							<li><a href="#">통계</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="gnb-area">
						<h3>서비스 메뉴</h3>
						<ul class="gnb-menu">
							<li><a href="#">공지사항</a><span class="arrow">&gt;</span></li>
							<li><a href="#">이벤트</a><span class="arrow">&gt;</span></li>
							<li><a href="#">무료도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">인기도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">추천도서</a><span class="arrow">&gt;</span></li>
							<li><a href="#">도서신청</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="panel-bottom">
						<a class="service-center"><span class="icon-service"></span><span>고객센터</span></a>
						<a class="advice"><span class="icon-advice"></span><span>건의사항</span></a>
						<div class="btn-app"><a href="#">App 다운로드</a></div>
					</div>
				</div>
			</div>
			<!-- panel End -->
			<!-- header -->
			<div data-role="header">
				<!-- header-search -->
				<div class="header-search">
					<div class="header-btn-left btn-forward"><a href="#" ></a></div>
					<div class="frm-search-area">
						<form action="">
							<div class="frm-search">
								<input type="text" placeholder="검색어 입력"><span class="search-del">입력 내용 삭제</span>
							</div>
							<button class="search">search</button><button class="close">닫기</button>
						</form>
					</div>
					<div class="header-btn-right">
						 <a href="#overlayPanel" class="ui-btn-inline btn-panel"></a>
					</div>
				</div>
				<!-- header-search End -->
			</div>
			<!-- header End -->
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<h2>베스트</h2>
				<div class="best-seller-area">
					<ul class="m-best-seller">
						<li><a href="#m-best01" data-transition="none">기간별</a></li>
						<li><a href="#m-best02" data-transition="none">장르별</a></li>
						<li><a href="#m-best03" class="ui-btn-active ui-state-persist" data-transition="none">스테디셀러</a></li>
					</ul>
					<div class="best-seller-box">
						<div class="best-seller-select">
							<form action="">
								<select name="" id="">
									<option value="">일간</option>
									<option value="">주간</option>
									<option value="">월간</option>
								</select>
							</form>
						</div>
						<ul class="best-seller-list">
							<li>
								<a href="#">
									<div class="best-seller">
										<div class="best-seller-l">
											<span class="ranking-num">1</span>
											<div class="best-list book-img-box">
												<div class="book-opc"></div>
												<img class="book-img" src="../img/book_img/book_sample01.jpg" alt="">
											</div>
										</div>
										<div class="best-seller-r">
											<div class="ranking-tit ellipsis">풀꽃도 꽃이다풀꽃도 꽃이다풀꽃도 꽃이다풀꽃도 꽃이다풀꽃도 꽃이다.1(양장본 HardCover)</div>
											<div>저자 한강</div>
											<div>구매가 : <span class="txt-279cf5">8,000</span>원</div>
											<div>출판사 에세이</div>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="#">
									<div class="best-seller">
										<div class="best-seller-l">
											<span class="ranking-num">2</span>
											<div class="best-list book-img-box">
												<div class="book-opc"></div>
												<img class="book-img" src="../img/book_img/book_sample01.jpg" alt="">
											</div>
										</div>
										<div class="best-seller-r">
											<div class="ranking-tit ellipsis">풀꽃도 꽃이다</div>
											<div>저자 한강</div>
											<div>구매가 : <span class="txt-279cf5">8,000</span>원</div>
											<div>출판사 에세이</div>
										</div>
									</div>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- main End -->
		
		</div>
		
		<script>
		$(function() {
			$("#genre").change(function() {
				var genre = $(this).val();
				location.href = "?genre=" + genre;
			});
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
