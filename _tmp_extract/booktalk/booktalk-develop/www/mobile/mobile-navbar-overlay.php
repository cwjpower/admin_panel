			<!-- panel -->
			<div data-role="panel" id="overlayPanel" data-display="overlay" data-position="right">
		<?php 
		if ( !wps_get_current_user_id() ) {		// 로그인 전
		?>
				<div class="panel-area user-login"><!-- display:none; -->
					<div class="panel-header">
						<h2>전체 메뉴 목록</h2>
						<a href="#pageone" data-rel="close" class="btn-panel-close">Close</a>
					</div>
					<div class="panel-login-area">
						<p class="login-txt">로그인이 필요합니다.</p>
						<a class="btn-panel-login" href="<?php echo MOBILE_URL ?>/users/user_login.php" data-ajax="false">로그인<span class="gnb_login"></span></a>
					</div>
					<div class="gnb-area">
						<h3>서비스 메뉴</h3>
						<ul class="gnb-menu">
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false">공지사항</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/" data-ajax="false">이벤트</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/free.php" data-ajax="false">무료도서</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/best_steady.php" data-ajax="false">인기도서</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/recommand.php" data-ajax="false">추천도서</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false">도서신청</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="panel-bottom">
						<a class="service-center" href="<?php echo MOBILE_URL ?>/service/cs.php" data-ajax="false"><span class="icon-service"></span><span>고객센터</span></a>
						<a class="advice" href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><span class="icon-advice"></span><span>건의사항</span></a>
						<div class="btn-app"><a href="#">App 다운로드</a></div>
					</div>
				</div>
		<?php 
		} else {	// 로그인 후
			$cuser_id = wps_get_current_user_id();
			$_umeta = wps_get_user_meta( $cuser_id );
			$current_user_cash = @$_umeta['lps_user_total_cash'];
			$current_user_point = @$_umeta['lps_user_total_point'];
			
			$_profile = lps_get_user_profile( $cuser_id );
			
			// 최근 읽은 책
			$_book_read = lps_get_last_read_3books($cuser_id);
		?>
				<div class="panel-area user-login"><!-- display:block; -->
					<div class="panel-header">
						<h2>전체 메뉴 목록</h2>
						<a href="#pageone" data-rel="close" class="btn-panel-close">Close</a>
					</div>
					<div class="user-img-area">
						<div class="user-img-bg">
							<div class="user-img"><img src="<?php echo $_profile['profile_avatar'] ?>" alt="Profile Photo"></div>
						</div>
						<div class="user-info">
							<div class="user-id"><?php echo wps_get_current_user_display_name() ?></div>
							<div class="user-msg"><?php echo $_profile['profile_msg'] ?></div>
							<div class="hide"><a href="<?php echo MOBILE_URL ?>/users/user_logout.php" data-ajax="false">로그아웃</a></div>
						</div>
					</div>
					<div class="s-menu-area">
						<ul>
							<li><a href="<?php echo MOBILE_URL ?>/mypage/bookshelf.php" data-ajax="false">내 서재</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/mypage/wishlist.php" data-ajax="false">찜 목록</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/cart/cart.php" data-ajax="false">장바구니</a></li>
						</ul>	
					</div>
					<div class="my-pocket-area">
						<h3><a href="<?php echo MOBILE_URL ?>/mypage/wallet_index.php" data-ajax="false">내 지갑</a></h3>
						<ul class="pocket-list">
							<li><strong><?php echo number_format($current_user_cash) ?></strong><span>캐시</span></li>
							<li><strong><?php echo number_format($current_user_point) ?></strong><span>포인트</span></li>
							<li><strong>0</strong><span>쿠폰</span></li>
							<li><strong>0</strong><span>대여권</span></li>
						</ul>
					</div>
					<div class="readbooks-list">
						<h3>최근 읽은 책</h3>
			<?php 
			if (empty($_book_read)) {
			?>
						<!-- 읽은 책 없습니다. -->
						<div class="opct-area">
							<div class="opct">최근 읽은 책이 없습니다.</div>
						</div>
			<?php 
			} else {
			?>
						<!-- 읽은 책 3권 보이기 -->
						<ul class="view-book view3-book">
				<?php 
				foreach ($_book_read as $ck => $cv) {
				?>
							<li><a href="<?php echo MOBILE_URL ?>/book/book.php?id=<?php echo $cv['ID'] ?>" data-ajax="false"><img src="<?php echo $cv['cover_img'] ?>" title="<?php echo $cv['book_title'] ?>"></a></li>
				<?php 
				}
				?>
						</ul>
			<?php 
			}
			?>
					</div>
					<div class="my-page">
						<h3>마이페이지</h3>
						<ul>
							<li><a href="<?php echo MOBILE_URL ?>/users/user_index.php" data-ajax="false">개인정보수정</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/users/a.php" data-ajax="false">통계</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="gnb-area">
						<h3>서비스 메뉴</h3>
						<ul class="gnb-menu">
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false">공지사항</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/" data-ajax="false">이벤트</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/free.php" data-ajax="false">무료도서</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/best_steady.php" data-ajax="false">인기도서</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/recommand.php" data-ajax="false">추천도서</a><span class="arrow">&gt;</span></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false">도서신청</a><span class="arrow">&gt;</span></li>
						</ul>
					</div>
					<div class="panel-bottom">
						<a class="service-center" href="<?php echo MOBILE_URL ?>/service/cs.php" data-ajax="false"><span class="icon-service"></span><span>고객센터</span></a>
						<a class="advice" href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><span class="icon-advice"></span><span>건의사항</span></a>
						<div class="btn-app"><a href="#" data-ajax="false">App 다운로드</a></div>
						<div class="btn-app"><a href="<?php echo MOBILE_URL ?>/users/user_logout.php" data-ajax="false">로그아웃</a></div>
					</div>
				</div>
		<?php 
		}
		?>
			</div>
			<!-- panel End -->
			<!-- header -->
			<div data-role="header">
				<!-- logo -->
				<div class="header-top" style="display: <?php echo empty($glk) ? 'block' : 'none'; ?>;">
					<div class="header-btn-left btn-forward"><a href="#" data-rel="back"></a></div>
					<h1><a href="<?php echo MOBILE_URL ?>" data-ajax="false"><img src="<?php echo MOBILE_URL ?>/img/common/logo.png" alt="BOOKtalk"></a></h1>
					<div class="header-btn-right">
						<span class="btn-search"></span>
						 <a href="#overlayPanel" class="ui-btn-inline btn-panel"></a>
					</div>
				</div>
				<!-- logo End -->
				<!-- Only Main -->
				<!-- header-search -->
				<div class="header-search" style="display: <?php echo empty($glk) ? 'none' : 'block'; ?>;">
					<div class="header-btn-left btn-forward"><a href="#" ></a></div>
					<div class="frm-search-area">
						<form id="form-search-global" action="<?php echo MOBILE_URL ?>/book/search.php" data-ajax="false">
							<div class="frm-search">
								<input type="text" name="glk" id="glk" placeholder="검색어 입력" value="<?php echo empty($glk) ? '' : $glk ?>"><span class="search-del">입력 내용 삭제</span>
							</div>
							<button type="submit" class="search">search</button><button type="button" class="close lps-btn-close">닫기</button>
						</form>
					</div>
					<div class="header-btn-right">
						 <a href="#overlayPanel" class="ui-btn-inline btn-panel"></a>
					</div>
				</div>
				<!-- header-search End -->
<?php 
$this_page = basename($_SERVER['PHP_SELF']);

$cm1 = stripos($this_page, 'today_new.php') === false ? '' : 'class="ui-link ui-btn ui-btn-active"';
$cm2 = stripos($this_page, 'best_steady.php') === false ? '' : 'class="ui-link ui-btn ui-btn-active"';
$cm3 = stripos($this_page, 'recommand.php') === false ? '' : 'class="ui-link ui-btn ui-btn-active"';
$cm4 = stripos($this_page, 'genre.php') === false ? '' : 'class="ui-link ui-btn ui-btn-active"';
$cm5 = stripos($this_page, 'free.php') === false ? '' : 'class="ui-link ui-btn ui-btn-active"';
?>
				
				
				<div data-role="navbar" class="navbar-item">
					<ul>
						<li><a href="<?php echo MOBILE_URL ?>/book/today_new.php" data-ajax="false" <?php echo $cm1 ?>>신간</a></li>
						<li><a href="<?php echo MOBILE_URL ?>/book/best_steady.php" data-ajax="false" <?php echo $cm2 ?>>베스트</a></li>
						<li><a href="<?php echo MOBILE_URL ?>/book/recommand.php" data-ajax="false" <?php echo $cm3 ?>>추천</a></li>
						<li><a href="<?php echo MOBILE_URL ?>/book/genre.php" data-ajax="false" <?php echo $cm4 ?>>장르별</a></li>
						<li><a href="<?php echo MOBILE_URL ?>/book/free.php" data-ajax="false" <?php echo $cm5 ?>>무료</a></li>
					</ul>
				</div>
				<!-- /. Only Main -->

			</div>
			<!-- header End -->