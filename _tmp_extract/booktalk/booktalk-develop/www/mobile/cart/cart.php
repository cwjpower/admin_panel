<?php
/*
 * Desc : 장바구니
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();
$usercart = wps_get_user_meta($user_id, 'lps_user_cart');
$usercart_unserial = unserialize($usercart);

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content mypage-area">
				<h2>장바구니</h2>
				<div class="my-library-allsel">
					<label for="allChk">전체선택</label>
					<input type="checkbox" id="allChk">
					<div class="chk-book-count">선택된 도서 <span id="selected-book-number">0</span>권</div>
				</div>
				<div class="list-type list-album-area">
					<form id="form-item-list">
					<ul class="list-album">
			<?php 
			if (!empty($usercart_unserial)) {
				foreach ($usercart_unserial as $key => $val) {
					$book_rows = lps_get_book($val);
					$book_id = $book_rows['ID'];
					$book_title = $book_rows['book_title'];
					$cover_img = $book_rows['cover_img'];
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
			?>
					</ul> 
					</form>
				</div>
				<ul class="btn-my-cart">
					<li><a href="#" id="btn-item-buy" class="btn-green">구매하기</a></li>
					<li><a href="#myCartDel" id="btn-del-from-cart" data-rel="popup" data-position-to="window" class="btn-pink">장바구니에서 삭제</a></li>
				</ul>
				<!-- 장바구니에서 삭제 popup -->
				<div data-role="popup" id="myCartDel" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">장바구니에서 삭제</div>
						<p class="popup-txt">선택된 책을 장바구니에서<br>삭제하시겠습니까?</p>
						<ul class="btn2-popup">
							<li><a href="#" class="btn-skyblue" id="btn-delete-cart">확인</a></li>
							<li><a href="#" data-rel="back" class="btn-gray">취소</a></li>
						</ul>
					</div>
				</div>
				<!-- 장바구니에서 삭제 popup End -->
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#allChk").click(function() {
				$(".wish_books").prop("checked", $(this).prop("checked"));
			});

			// 구매하기
			$("#btn-item-buy").click(function(e) {
				e.preventDefault();

				var chkLength = $(".wish_books:checked").length;

				if (chkLength == 0) {
					alert("구매하실 책을 선택해 주십시오.");
					return;
				}

				$.ajax({
					type : "POST",
					url : "./ajax/add-buy-item.php",
					data : $("#form-item-list").serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "pay.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});
			
			// 장바구니에서 삭제 버튼 클릭
			$("#btn-del-from-cart").click(function(e) {
				e.preventDefault();
				
				var chkLength = $(".wish_books:checked").length;

				if (chkLength == 0) {
					alert("장바구니에서 삭제할 책을 선택해 주십시오.");
					return;
				}
				$("#myCartDel").popup("open");
			});
			
			// 장바구니 삭제 "확인" 버튼 클릭
			$("#btn-delete-cart").click(function(e) {
				e.preventDefault();
				
				var chkLength = $(".wish_books:checked").length;

				if (chkLength == 0) {
					alert("장바구니에서 삭제할 책을 선택해 주십시오.");
					return;
				}

				$.ajax({
					type : "POST",
					url : "./ajax/cart-delete-item.php",
					data : $("#form-item-list").serialize(),
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
