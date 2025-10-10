<?php
/*
 * Desc : 결제
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-coupon.php';

wps_auth_mobile_redirect();

// 결제 대기중인 책 리스트
$book_pay = wps_get_user_meta( wps_get_current_user_id(), 'lps_user_book_pay' );
$paying_books = unserialize($book_pay);

if ( empty($paying_books) ) {
	lps_js_back( '결제하실 책이 존재하지 않습니다.' );
}

// 사용 가능한 쿠폰
$coupons = lps_get_valid_coupons();

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content mypage-area">
				<h2>구매하기<span class="keep-cash">보유캐시 <?php echo number_format($current_user_cash) ?></span></h2>
				<div class="buy-area">
					<div class="buy-list-conts">
						<h3>구매 상품 정보</h3>
						<ul class="buy-cont">
				<?php 
				if (!empty($paying_books)) {
					
					$total_money = $current_user_cash + $current_user_point;
					$total_cost = 0;
					
					foreach ($paying_books as $key => $val) {
						$book_rows = lps_get_book($val);
						$book_id = $book_rows['ID'];
						$book_title = $book_rows['book_title'];
						$sale_price = $book_rows['sale_price'];
						$dc_rate = $book_rows['discount_rate'];
						$dc_rate_label = $dc_rate == 0 ? '' : ' (' . $dc_rate . '% 할인)'; 
						$total_cost += $sale_price;
				?>
							<li>
								<?php echo $book_title ?> <?php echo $dc_rate_label ?>
								<span class="point-rate-<?php echo $dc_rate ?>"></span>
								<div class="buy-price"><?php echo number_format($sale_price) ?></div>
							</li>
				<?php 
					}
				}
				?>
						</ul>
					</div>
					<div class="buy-list-conts">
						<form id="form-item-buy">
						<h3>결제 정보</h3>
						<ul class="buy-cont">
							<li>총 주문금액
								<div class="buy-price" id="total_order"><?php echo number_format($total_cost) ?></div>
							</li>
							<li>사용 가능 쿠폰
								<div class="buy-price mycoupon-list">
									<select name="coupon_to_use" id="coupon_to_use">
										<option value="">- 쿠폰 리스트 목록 -</option>
							<?php 
							if ( !empty($coupons) ) {
								foreach ($coupons as $key => $val ) {
									$coupon_id = $val['ID'];
									$coupon_name = $val['coupon_name'];
									$coupon_type = $val['coupon_type'];
									$discount_type = $val['discount_type'];
									$discount_amount = $val['discount_amount'];
									$discount_rate = $val['discount_rate'];
									$item_price_min = $val['item_price_min'];
									$item_price_max = $val['item_price_max'];
									
									if ($item_price_min <= $total_cost) {
									
							?>
										<option value="<?php echo $coupon_id ?>"><?php echo $coupon_name ?> (<?php echo number_format($item_price_min) ?>원 이상 구매 시)</option>
							<?php 
									}
								}
								
							}
							?>
									</select>
								</div>
							</li>
							<li class="mypoint">Point 사용
								<div class="buy-price">
									<div class="mypoint-txt">보유 Point <?php echo number_format($current_user_point) ?></div>
									<input type="text" min="10" max="<?php echo $current_user_point ?>" name="using_point" class="numeric using_point" style="height: 30px; border: 1px solid #dfdfdf; border-radius: 3px; font-size: 13px;">
								</div>
							</li>
							<li class="mypoint">총 결제금액
								<div class="buy-price">
									<div class="buy-price-txt"><span id="total_paid"><?php echo number_format($total_cost) ?></span>원</div>
						<?php 
						if ($total_money < $total_cost) {
						?>
									<!-- 캐시 부족 -->
									<div class="cash-charge" style="display: block;">캐시가 부족합니다.<a href="../mypage/buy_cash.php" class="btn-cash-charge" data-ajax="false">충전</a></div>
						<?php 
						}
						?>
								</div>
							</li>
						</ul>
						</form>
					</div>
					<ul class="btn-buy">
						<li><a href="#" id="btn-item-buy" class="btn-skyblue">구매</a></li>
						<li><a href="#" class="btn-gray" data-rel="back">취소</a></li>
					</ul>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script src="<?php echo INC_URL ?>/js/ls-util.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.oLoader.min.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.number.min.js"></script>
		
		<script>
		$(function() {
			$(".numeric").number( true, 0 ).css("text-align", "right");

			var availMaxPoint = parseInt($(".using_point").attr("max"));
			var fixedAmount = 0; 
			
			$(".using_point").on("keyup blur input propertychange", function() {
				var thisVal = parseInt($(this).val());
				var totalPaidAmount = parseInt($("#total_order").text().replace(/\D/g, ""));
				if (thisVal > availMaxPoint) {
					alert( availMaxPoint + "원 이상은 입력하실 수 없습니다.");
					$(this).val( availMaxPoint );
				}
				if (thisVal > totalPaidAmount) {
					alert( totalPaidAmount + "원 이상은 입력하실 수 없습니다.");
					$(this).val( totalPaidAmount );
				}
			});

			// 포인트 사용
			$(".using_point").blur(function() {
				var thisVal = parseInt($(this).val());
				var totalPaidAmount = parseInt($("#total_order").text().replace(/\D/g, ""));
				if (isNaN(thisVal)) {
					thisVal = 0;
				}
				if (thisVal % 10 > 0) {
					alert("10원 미만 단위는 입력하실 수 없습니다.");
					$(this).val( Math.round(thisVal / 10) * 10 );
				}
				fixedAmount = totalPaidAmount - thisVal;
				$("#total_paid").html( numberFormat(fixedAmount) );
			});

			// 쿠폰 사용
			$("#coupon_to_use").change(function() {
				var couponID = $(this).val();
				showLoader();
				
				$.ajax({
					type : "POST",
					url : "./ajax/use-coupon.php",
					data : {
						"couponID" : couponID
					},
					dataType : "json",
					success : function(res) {
						hideLoader();
						if (res.code == "0") {
							setPayingCost( res.total_cost, res.coupon_id );
						} else {
							alert(res.msg);
						}
					}
				});
			});
			
			// 구매하기
			$("#btn-item-buy").click(function(e) {
				e.preventDefault();

				showLoader();
				
				$.ajax({
					type : "POST",
					url : "./pay-item.php",
					data : $("#form-item-buy").serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "../mypage/bookshelf.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});

			function setPayingCost( discount, coupon ) {
				$("#total_order, #total_paid").html( numberFormat(discount) );

				var pointAmount = parseInt($(".using_point").val());
				var totalPaidAmount = parseInt($("#total_order").text().replace(/\D/g, ""));

				if (isNaN(pointAmount)) {
					pointAmount = 0;
				}
				fixedAmount = totalPaidAmount - pointAmount;
				$("#total_paid").html( numberFormat(fixedAmount) );

				if ( coupon ) {
					$(".point-rate-0").html(" / <small>쿠폰 사용</small>");
				} else {
					$(".point-rate-0").html("");
				}
			}
			
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
