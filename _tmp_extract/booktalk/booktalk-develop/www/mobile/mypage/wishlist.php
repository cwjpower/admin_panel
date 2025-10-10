<?php
/*
 * Desc : 찜 목록
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();
$wishlist = wps_get_user_meta($user_id, 'lps_user_wishlist');
$wishlist_unserial = unserialize($wishlist);

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content mypage-area">
				<h2>찜 목록</h2>
				<div class="my-library-allsel">
					<label for="allChk">전체선택</label>
					<input type="checkbox" id="allChk">
					<div class="chk-book-count">선택된 도서 <span id="selected-book-number">0</span>권</div>
				</div>
				<div class="list-type list-album-area">
					<form id="item-list-form">
					<ul class="list-album">
			<?php 
			if (!empty($wishlist_unserial)) {
				foreach ($wishlist_unserial as $key => $val) {
					$book_rows = lps_get_book($val);
					$book_id = $book_rows['ID'];
					$book_status = $book_rows['book_status'];
					$book_title = $book_rows['book_title'];
					$cover_img = $book_rows['cover_img'];
					
					echo $book_status;
					echo "<br>";
					
					if ( $book_status == 3000 ){
			?>
						<li>
							<div class="list-album-box">
								<div class="library-chk"><input type="checkbox" name="bookID[]" class="wish_books" value="<?php echo $book_id ?>"></div>
								<div class="albumlist">
									<div class="book-opc"></div>
									<a href="../book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><img src="<?php echo $cover_img ?>" alt=""></a>
								</div>
								<div class="albumlist-booktit"><a href="../book/book.php?id=<?php echo $book_id ?>" data-ajax="false"><?php echo $book_title ?></a></div>
							</div>
						</li>
			<?php 
					}
				}
			}
			?>
					</ul> 
					</form>
				</div>
				<ul class="btn-my-library">
					<li style="width: 50%;"><a href="#myLibraryCart" id="btn-item-cart" data-rel="popup" data-position-to="window" class="btn-skyblue">장바구니 담기</a></li>
					<!-- li><a href="#myLibraryOut" data-rel="popup" data-position-to="window" class="btn-green">내보내기</a></li> -->
					<li style="width: 50%;"><a href="#myLibraryDel" id="btn-del-from-wish" data-rel="popup" data-position-to="window" class="btn-pink">찜 목록에서 삭제</a></li>
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
				<!-- 내보내기 popup -->
				<div data-role="popup" id="myLibraryOut" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">내보낼 SNS 선택</div>
						<form action="">
							<ul class="sns-popup">
								<li>
									<input type="checkbox" name="snsGo" id="snsFacebook">
									<label for="snsFacebook"><img src="../img/common/icon_sns_facebook.png" alt="Facebook 아이콘" onclick="if(navigator.appVersion.indexOf('MSIE' != -1){naver2.click()})"></label>
								</li>
								<li>
									<input type="checkbox" name="snsGo" id="snsTwitter">
									<label for="snsTwitter"><img src="../img/common/icon_sns_twitter.png" alt="Twitter 아이콘"></label>
								</li>
							</ul>
						</form>
						<ul class="btn2-popup">
							<li><a href="#" class="btn-skyblue">확인</a></li>
							<li><a href="#" data-rel="back" class="btn-gray">취소</a></li>
						</ul>
					</div>
				</div>
				<!-- 내보내기 popup End -->
				<!-- 찜 목록에서 삭제 popup -->
				<div data-role="popup" id="myLibraryDel" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">찜 목록에서 삭제</div>
						<p class="popup-txt">선택된 책을 찜 목록에서<br>삭제하시겠습니까?</p>
						<ul class="btn2-popup">
							<li><a href="#" class="btn-skyblue" id="btn-delete-wishlist">확인</a></li>
							<li><a href="#" data-rel="back" class="btn-gray">취소</a></li>
						</ul>
					</div>
				</div>
				<!-- 찜 목록에서 삭제 popup End -->
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#allChk").click(function() {
				$(".wish_books").prop("checked", $(this).prop("checked"));
			});

			// 장바구니 담기
			$("#btn-item-cart").click(function(e) {
				e.preventDefault();

				var chkLength = $(".wish_books:checked").length;

				if (chkLength == 0) {
					alert("책을 선택해 주십시오.");
					return;
				}

				$.ajax({
					type : "POST",
					url : "../cart/ajax/cart-new-item.php",
					data : $("#item-list-form").serialize(),
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
			
			// 찜목록에서 삭제 버튼 클릭
			$("#btn-del-from-wish").click(function(e) {
				e.preventDefault();
				
				var chkLength = $(".wish_books:checked").length;

				if (chkLength == 0) {
					alert("찜목록에서 삭제할 책을 선택해 주십시오.");
					return;
				}
				$("#myLibraryDel").popup("open");
			});
			
			// 찜목록 삭제 "확인" 버튼 클릭
			$("#btn-delete-wishlist").click(function(e) {
				e.preventDefault();
				
				var chkLength = $(".wish_books:checked").length;

				if (chkLength == 0) {
					alert("찜목록에서 삭제할 책을 선택해 주십시오.");
					return;
				}

				$.ajax({
					type : "POST",
					url : "./ajax/wishlist-delete.php",
					data : $("#item-list-form").serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.reload();
						} else {
							hideLoader();
							alert(res.msg);
						}
					}
				});
			});

			// 선택된 도서
			$(".wish_books, #allChk").click(function() {
				getCheckedBooks();
			});

			function getCheckedBooks() {
				$("#selected-book-number").html( $(".wish_books:checked").length );
			}
			
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
