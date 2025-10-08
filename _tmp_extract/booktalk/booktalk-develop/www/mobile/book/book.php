<?php
/*
 * Desc : 책 정보 , html : book_information.html
 * 	FB : https://developers.facebook.com/docs/plugins/share-button/#example
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-term.php';

if ( empty($_GET['id']) ) {
	lps_js_back( '도서 아이디가 존재하지 않습니다.' );
}

$book_id = $_GET['id'];
$book_rows = lps_get_book($book_id);

$fb_url = MOBILE_URL . '/book/book.php?id=' . $book_id;	// facebook 내보내기용

$is_pkg = $book_rows['is_pkg'];
$book_title = $book_rows['book_title'];
$author = $book_rows['author'];
$publisher = $book_rows['publisher'];
$isbn = $book_rows['isbn'];
$normal_price = $book_rows['normal_price'];
$sale_price = $book_rows['sale_price'];
$upload_type = $book_rows['upload_type'];
$period_from = $book_rows['period_from'];
$period_to = $book_rows['period_to'];
$book_status = $book_rows['book_status'];
$user_id = $book_rows['user_id'];
$created_dt = $book_rows['created_dt'];
$discount_rate = $normal_price ? number_format(100 - ($sale_price / $normal_price * 100)) . '% 할인 +' : '';

$epub_path = $book_rows['epub_path'];
$epub_name = $book_rows['epub_name'];
$cover_img = $book_rows['cover_img'];

$category_first = $book_rows['category_first'];
$category_second = $book_rows['category_second'];
$category_third = $book_rows['category_third'];

$book_meta = lps_get_book_meta($book_id);

$introduction_book = empty($book_meta['lps_introduction_book']) ? '' : $book_meta['lps_introduction_book'];
$introduction_author = empty($book_meta['lps_introduction_author']) ? '' : $book_meta['lps_introduction_author'];
$publisher_review = empty($book_meta['lps_publisher_review']) ? '' : $book_meta['lps_publisher_review'];
$book_table = empty($book_meta['lps_book_table']) ? '' : $book_meta['lps_book_table'];
$lps_res_accept_new = empty($book_meta['lps_res_accept_new']) ? '': unserialize($book_meta['lps_res_accept_new']);
if ($lps_res_accept_new) {
	$book_new_accept_date = date('Y-m-d H:i:s', $lps_res_accept_new['accept_dt']);
} else {
	$book_new_accept_date = '';
}

if ( $book_status != '3000' ) {
	lps_js_back( '죄송합니다. 현재 사용할 수 없는 책입니다.' );
}

// 함께 구매한 책
$books_cart = lps_get_books_by_cart( $book_id );

// require_once MOBILE_PATH . '/mobile-header.php';

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="google-signin-client_id" content="1082609763302-i7mdug0np5l874jkd0h3k1tuj67tr7e7.apps.googleusercontent.com">
		
		<meta property="og:url"           content="<?php echo $fb_url ?>" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="BOOKTALK.WORLD" />
		<meta property="og:description"   content="<?php echo $book_title ?>" />
		<meta property="og:image"         content="<?php echo $cover_img ?>" />
		
		<title>BOOK Talk</title>
		<link rel="shortcut icon" href="favicon.ico">
		<!-- css -->
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/jquery.mobile-1.4.5.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/globals.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/myinfo.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/mypage.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/main.css">
		<link rel="stylesheet" href="<?php echo MOBILE_URL ?>/css/board.css">
		<!-- js -->
		<script src="<?php echo INC_URL ?>/js/jquery.min.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery.mobile-1.4.5.min.js"></script>
	</head>
	<body>
	

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content book-info-area">
				<input type="hidden" id="book_id" value="<?php echo $book_id ?>">
				<h2 class="hide">책 정보</h2>
				<div class="book-box">
					<div class="book-view-top">
						<h3 class="book-view-tit"><?php echo $book_title ?></h3>
						<ul class="book-view-list">
							<li>장르 : <?php echo wps_get_term_name($category_third) ?></li>
							<li>저자 : <?php echo $author ?></li>
							<!-- li>옮긴이 : 홍길순</li> -->
							<li>출판사 : <?php echo $publisher ?></li>
							<!-- li>출간일 : 20161210</li> -->
							<li>e-ISBN : <?php echo $isbn ?></li>
						</ul>
					</div>
					<div class="book-view-cont">
						<div class="book-view-img">
							<div style="float: right;">
								<!-- div class="fb-share-button" data-href="<?php echo $fb_url ?>" data-layout="button"></div> -->
								<a href="#" id="facebook-share"><img src="<?php echo MOBILE_URL ?>/img/common/icon_sns_facebook.png" style="height: 25px;"></a>
							</div>
							<div class="book-img-box">
								<div class="book-opc"></div>
								<img class="book-img" src="<?php echo $cover_img ?>" alt="책 표지">
							</div>
						</div>
						<ul class="btn-view-cont">
							<li><a href="#" class="btn-skyblue">미리 보기</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/book/book_activity.php?id=<?php echo $book_id ?>" data-ajax="false" class="btn-green">담벼락</a></li>
							<li><a href="#" class="btn-pink" id="btn-new-wishlist">찜하기</a></li>
						</ul>
					</div>
				</div>		
				<div class="book-box">
					<ul class="plrtb10 price-area">
						<li>정가 <?php echo number_format($normal_price) ?>원</li>
						<li class="price-point">판매가 <span>(<?php echo $discount_rate ?> ??? point 적립)</span></li>
						<li class="price">
							<div class="price-dc"><?php echo number_format($sale_price) ?>원</div>
							<div class="btn-buy-r">
								<a href="#" id="btn-item-buy" class="btn-info-buy">바로 구매</a>
								<a href="#myLibraryCart" id="btn-item-cart" data-rel="popup" class="btn-info-cart">장바구니 담기</a>
							</div>
						</li>
					</ul>
					
					<!-- 장바구니 popup -->
					<div data-role="popup" id="myLibraryCart" class="ui-content" data-overlay-theme="b">
						<div class="popup-area">
							<div class="popup-tit">장바구니</div>
							<p class="popup-txt">장바구니에 담겼습니다.<br><br>지금 바로 장바구니로<br>이동하시겠습니까?</p>
							<ul class="btn2-popup">
								<li><a href="../cart/cart.php" class="btn-skyblue" data-ajax="false">확인</a></li>
								<li><a href="#" data-rel="back" class="btn-gray">취소</a></li>
							</ul>
						</div>
					</div>
					<!-- 장바구니 popup End -->
		
				</div>
				<div>
					<!-- div class="book-info-conts">
						<h3>상제정보</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">『따옴표 다이어리』는 씩씩하고 유쾌한 매력으로 많은 이들에게 긍정의 에너지를 전하고 있는 인기 블로거, ‘다람쥐뽕’이 기획한 다이어리북이다. 그녀에게는 스무 살 때부터 15년 간, 1년에 한 권씩 꾸준히 써내려간 다이어리가 있다. 신문에서 스크랩한 기사를 붙이거나 책을 읽다 </div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">더보기</button>
					</div> -->
					<div class="book-info-conts">
						<h3>책 소개</h3>
						<div class="book-info-cont">
							<div class="book-info-txt"><?php echo $introduction_book ?></div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">더보기</button>
					</div>
					<div class="book-info-conts">
						<h3>저자 소개</h3>
						<div class="book-info-cont">
							<div class="book-info-txt"><?php echo $introduction_author ?></div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">더보기</button>
					</div>
					<div class="book-info-conts">
						<h3>출판사 서평</h3>
						<div class="book-info-cont">
							<div class="book-info-txt"><?php echo $publisher_review ?></div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">더보기</button>
					</div>
		<?php 
		if (!wps_get_current_user_id()) {
		?>
					<div class="book-info-conts">
						<h3>이 책을 산 친구</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">(로그인이 필요한 서비스입니다.)</div>
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">더보기</button>
					</div>
		<?php 
		} else {
		?>
					<div class="book-info-conts">
						<h3>이 책을 산 친구</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">
								<h4>황희 연개소문 강감찬  * 미구현</h4>
							</div> 
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">더보기</button>
					</div>
		<?php 
		}
		?>
					<div class="book-info-conts">
						<h3>커뮤니티 랭킹</h3>
						<div class="book-info-cont">
							<div class="book-info-txt">
								<h4>점수 - 1,322점<br>
								순위 - 전체 15위 / 장르 6위 * 미구현</h4>
							</div> 
						</div>
						<button class="btn-txt-more btn-more-down btn-txt">더보기</button>
					</div>
					<div class="book-info-conts">
						<h3>함께 구매한 책</h3>
						<div class="buy-together-box">
							<ul class="buy-together-book">
					<?php 
					if (!empty($books_cart)) {
						foreach ($books_cart as $key => $val) {
							$wb_id = $val['book_id'];
							$withb = lps_get_book($wb_id);
							
							$wb_title = $withb['book_title'];
							$wb_cover = $withb['cover_img'];
							
					?>
								<li>
									<div class="book-img-pt">
										<div class="book-img-box">
											<div class="book-opc"></div>
											<a href="book.php?id=<?php echo $wb_id ?>" data-ajax="false"><img class="book-img" src="<?php echo $wb_cover ?>" alt="표지"></a>
										</div>
										<div class="book-name ellipsis"><a href="book.php?id=<?php echo $wb_id ?>" data-ajax="false"><?php echo $wb_title ?></a></div>
									</div>
								</li>
					<?php 
						}
					}
					?>
							</ul>
						</div>
						<button class="btn-txt-more2 btn-more-down">더보기</button>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$(".btn-txt-more").click(function(){
				$(this).toggleClass("btn-more-up");
				$(this).text(function(i, v) {
					if (v == "더보기") {
						$(this).parent().find(".book-info-txt").css("height", "auto");
						return "접기";
					} else {
						$(this).parent().find(".book-info-txt").css("height", "54px");
						return "더보기";
					}
// 				   return v === '더보기' ? '접기' : '더보기'
				});
			});
			
			$(".btn-txt-more2").click(function(){
				$(this).toggleClass("btn-more-up");
				$(this).text(function(i, v) {
					if (v == "더보기") {
						$(this).parent().find(".buy-together-box").css("height", "auto");
						return "접기";
					} else {
						$(this).parent().find(".buy-together-box").css("height", "141px");
						return "더보기";
					}
				});
			});

			$("#btn-new-wishlist").click(function(e) {
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "./ajax/book-new-wishlist.php",
					data : {
						"bookID" : $("#book_id").val()
					},
					dataType : "json",
					success : function(res) {
						if (res.code != "0") {
							alert(res.msg);
						} else {
							if (res.result === true) {
// 								alert("찜리스트에 등록했습니다.");
								if (confirm("찜리스트에 등록했습니다.\n찜리스트로 이동하시겠습니까?")) {
									location.href = "../mypage/wishlist.php";
								}
							} else {
// 								alert("이미 찜리스트에 등록된 책입니다.");
								if (confirm("이미 찜리스트에 등록된 책입니다.\n찜리스트로 이동하시겠습니까?")) {
									location.href = "../mypage/wishlist.php";
								}
							}
						}
					}
				});
			});

			// 바로 구매하기
			$("#btn-item-buy").click(function(e) {
				e.preventDefault();

				$.ajax({
					type : "POST",
					url : "../cart/ajax/add-buy-item.php",
					data : {
						"bookID[]" : $("#book_id").val()
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "../cart/pay.php";
						} else {
							alert(res.msg);
						}
					}
				});
				
			});

			// 장바구니 담기
			$("#btn-item-cart").click(function(e) {
				e.preventDefault();

				$.ajax({
					type : "POST",
					url : "../cart/ajax/cart-new-item.php",
					data : {
						"bookID[]" : $("#book_id").val()
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							$("#myLibraryCart").popup("open");
						} else {
							alert(res.msg);
						}
					}
				});
				
			});

// 			// facebook button click
			$("#facebook-share").click(function() {
				shareFB();
			});

		}); // $

		window.fbAsyncInit = function () {
		    FB.init({
		        appId	: "<?php echo $wps_fb_app_id ?>",
		        xfbml	: true,
		        version	: "v2.8"
		    });
		};

		(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/ko_KR/sdk.js";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

		function shareFB() {
			FB.ui({
	            method: 'share',
	            display: 'popup',
	            href: '<?php echo $fb_url ?>',
	            caption: 'BOOKTalk.WORLD',
	        }, function (response) {
	            if (response === null) {
// 	                console.log('post was not shared');
	            } else {
// 	                console.log('post was shared');

	                $.ajax({
						type : "POST",
						url : "./ajax/share-sns.php",
						data : {
							"bookID" : $("#book_id").val()
						},
						dataType : "json",
						success : function(res) {
							if (res.code != "0") {
								alert(res.msg);
							} else {
							}
						}
					});
	            }
	        });
		}
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
