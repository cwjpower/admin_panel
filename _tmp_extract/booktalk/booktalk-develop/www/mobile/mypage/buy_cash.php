<?php
/*
 * Desc : 캐시 충전하기
 */
require_once '../../wps-config.php';

wps_auth_mobile_redirect();

$user_id = wps_get_current_user_id();
$current_user_cash = wps_get_user_meta( $user_id, 'lps_user_total_cash' );

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<link rel="stylesheet" href="<?php echo INC_URL ?>/css/bootstrap.min.css">

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content mypage-area">
				<h2 style="font-size: 120%;">캐시 충전<div class="keep-cash">보유캐시 <?php echo number_format($current_user_cash) ?></div></h2>
				<div class="cash-area">
					<div class="cash-view-area">
						<strong>간편한 구매 그리고 포인트 적립까지!</strong>
						한번 충전하면 추가 결제 정보<br>입력 없이 구매를 한번에<br>할 수 있는 캐시! 
					</div>
					<form id="form-item-new" class="form-horizontal">
						<!-- 결제금액 -->
						<div class="cash-cont-area">
							<h3>결제금액</h3>
							<div class="cash-cont">
								
								<table id="pay-table" class="table table-hover">
									<thead>
										<tr>
											<th></th>
											<th>구매금액</th>
											<th>적립률</th>
											<th>북톡포인트 적립금액</th>
										</tr>
									</thead>
									<tbody>
							<?php 
							foreach ($wps_payment_amount_rate as $key => $val) {
								$checked = $key == 10000 ? 'checked' : '';
							?>
										<tr>
											<td>
												<input type="radio" name="pay_amount" class="pay_amount" value="<?php echo $key ?>" <?php echo $checked?>>
											</td>
											<td><b><?php echo number_format($key) ?></b><small>원</small></td>
											<td><?php echo $val * 100 ?><small>%</small></td>
											<td><?php echo number_format($key * $val) ?><small>원</small></td>
										</tr>
							<?php 
							}
							?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- 결제수단 -->
						<div class="cash-cont-area">
							<h3>결제수단</h3>
							<div class="cash-cont">
								<ul class="pay-type-list">
									<li>
										<label for="payPhone">휴대폰</label>
										<input type="radio" name="pay_method" value="pm_mobile" id="payPhone">
									</li>
									<li>
										<label for="payCreditCard">신용카드</label>
										<input type="radio" name="pay_method" value="pm_card" id="payCreditCard">
									</li>
									<li>
										<label for="payARS">ARS</label>
										<input type="radio" name="pay_method" value="pm_ars" id="payARS">
									</li>
									<li>
										<label for="payAccount1">계좌이체</label>
										<input type="radio" name="pay_method" value="pm_bank_transfer" id="payAccount1">
									</li>
									<li>
										<label for="payAccount2">가상계좌</label>
										<input type="radio" name="pay_method" value="pm_virtual_transfer" id="payAccount2">
									</li>
									<li class="pay-type-other">
										<input type="radio" name="pay_method" id="payOther">
										<select name="" id="payOther">
											<option value="">기타수단</option>
											<option value="">문화상품권</option>
											<option value="">도서상품권</option>
											<option value="">해피머니상품권</option>
											<option value="">게임문화상품권</option>
											<option value="">T-Money</option>
											<option value="">편의점결제</option>
										</select>
									</li>
								</ul>
							</div>
						</div>
						<!-- 휴대폰 결제
						<div class="cash-cont-area">
							<div class="pay-select-cont">
								<ul class="pay-tip">
									<li><span>상품명/제공기간</span>5,000 캐시/충전일로부터 5년</li>
									<li><span>결제 혜택</span>결제금액의 10% Point 지급</li>
									<li><span>제공방법 및 시기</span>결제완료 즉시 온라인으로 지급</li>
									<li><span>결제 대행사</span>(결제 대행사 업체명 표시)</li>
								</ul>
								<dl class="pay-frm">
									<dt>휴대폰</dt>
									<dd class="pay-frm-num">
										<div class="phone-num-inp">
											<select name="" id="q">
												<option value="SKT">SKT</option>
											</select>
											<select name="" id="">
												<option value="010">010</option>
											</select>
											<input type="text" maxlength="4">
											<input type="text" maxlength="4">
										</div>
									</dd>
								</dl> 
								<dl class="pay-frm mt">
									<dt>이메일</dt>
									<dd class="pay-email-inp">
										<input type="text" placeholder="현재 로그인한 계정 이메일 자동입력">
									</dd>
									<dd class="pay-ps">*사용 중인 이메일과 다를 경우 변경해주세요.</dd>
									<dd class="pay-ps">*숫자만 입력 가능합니다.</dd>
								</dl>
							</div>
						</div>
						<!-- 휴대폰 결제 End -->
						<!-- 신용카드 결제
						<div class="cash-cont-area">
							<div class="pay-select-cont">
								<ul class="pay-tip">
									<li><span>상품명/제공기간</span>5,000 캐시/충전일로부터 5년</li>
									<li><span>결제 혜택</span>결제금액의 10% Point 지급</li>
									<li><span>제공방법 및 시기</span>결제완료 즉시 온라인으로 지급</li>
								</ul>
								<dl class="pay-frm">
									<dt>신용카드</dt>
									<dd class="credit-card">
										<select name="" id="q">
											<option value="">카드선택</option>
										</select>
									</dd>
								</dl>
								<dl class="pay-frm">
									<dt>할부선택</dt>
									<dd class="credit-card">
										<select name="" id="q">
											<option value="">일시불</option>
										</select>
									</dd>
									<dd class="pay-ps">*결제하실 신용카드와 할부여부를 선택해주세요.</dd>
									<dd class="pay-ps">*5만원 이상인 경우 할부가 적용됩니다.</dd>
								</dl>
							</div>
						</div>
						<!-- 신용카드 결제 End -->
						<!-- ARS 결제 
						<div class="cash-cont-area">
							<div class="pay-select-cont">
								<ul class="pay-tip">
									<li><span>상품명/제공기간</span>5,000 캐시/충전일로부터 5년</li>
									<li><span>결제 혜택</span>결제금액의 10% Point 지급</li>
									<li><span>제공방법 및 시기</span>결제완료 즉시 온라인으로 지급</li>
								</ul>
								<div class="bubble-ars-area">
									<a href="#bubble2" id="bubble-ars" class="bubble-ars" data-rel="popup" class="ui-btn ui-btn-inline" data-position-to="bubble-ars">ARS 결제방법</a>
									<div data-role="popup" id="bubble2" class="ui-content bubble-box" data-arrow="t">
										<ol>
											<li class="txt-point">1. 결제정보 가입후 승인번호를 받습니다.</li>
											<li class="txt-point">2. 승인번호(4자리)를 걸려온 전화에 입력합니다.</li>
											<li class="txt-point">3. 완료되면 반드시[결제하기] 버튼을 눌러주셔야 결제가 완료됩니다.<span>결제요청 금액의 10% 부가세 별도청구</span></li>
										</ol>
									</div>
								</div>
								<dl class="pay-frm mt">
									<dt>이메일</dt>
									<dd class="pay-email-inp">
										<input type="text" placeholder="현재 로그인한 계정 이메일 자동입력">
									</dd>
									<dd class="pay-ps">*사용 중인 이메일과 다를 경우 변경해주세요.</dd>
								</dl>
							</div>
						</div>
						<!-- ARS 결제 End -->
						<!-- 계좌이체 결제
						<div class="cash-cont-area">
							<div class="pay-select-cont">
								<ul class="pay-tip">
									<li><span>상품명/제공기간</span>5,000 캐시/충전일로부터 5년</li>
									<li><span>결제 혜택</span>결제금액의 10% Point 지급</li>
									<li><span>제공방법 및 시기</span>결제완료 즉시 온라인으로 지급</li>
								</ul>
								<div class="account-transfer ">이용 가능한 은행 : 경남, 광주, 국민, 기업, 농협중앙회 등 거래 가능한 은행명 표시</div>
								<p class="account-transfer-ps pay-ps">*계좌이체는 본인 명의 계좌로만 결제가 가능합니다.</p>
							</div>
						</div>
						<!-- 계좌이체 결제 End -->
						<!-- 가상계좌 결제
						<div class="cash-cont-area">
							<div class="pay-select-cont">
								<ul class="pay-tip">
									<li><span>상품명/제공기간</span>5,000 캐시/충전일로부터 5년</li>
									<li><span>결제 혜택</span>결제금액의 10% Point 지급</li>
									<li><span>제공방법 및 시기</span>결제완료 즉시 온라인으로 지급</li>
								</ul>
								<dl class="pay-frm bank-account">
									<dt>예금주</dt>
									<dd class="comp">(주)비콘</dd>
								</dl>
								<dl class="pay-frm bank-account">
									<dt>예금자명</dt>
									<dd><input type="text"></dd>
								</dl>
								<dl class="pay-frm bank-account">
									<dt>입금은행</dt>
									<dd>
										<select name="" id="q">
											<option value="">은행선택</option>
										</select>
									</dd>
									<dd class="pay-ps">*입금자명을 정확하게 입력해주세요.</dd>
									<dd class="pay-ps">*입금은행을 선택해 주세요.</dd>
								</dl>
							</div>
						</div>
						<!-- 가상계좌 결제 End -->
						<!-- 기타수단 결제 
						<div class="cash-cont-area">
							<div class="pay-select-cont">
								<ul class="pay-tip">
									<li><span>상품명/제공기간</span>5,000 캐시/충전일로부터 5년</li>
									<li><span>결제 혜택</span>결제금액의 10% Point 지급</li>
									<li><span>제공방법 및 시기</span>결제완료 즉시 온라인으로 지급</li>
								</ul>
								<div class="pay-select-other">*결제하실 기타수단을 선택해주세요.</div>
							</div>
						</div>
						<!-- 기타수단 결제 End -->
						<!-- 유의사항/이용약관 동의 -->
						<ul class="pay-term-area">
							<li>
								<div class="agree"><a href="#">[유의사항 보기]</a></div>
								<label for="agree_notice">상기 결제내용 및 유의사항을 확인하였습니다.</label><input type="checkbox" id="agree_notice" name="agree_notice" value="1">
							</li>
							<li>
								<div class="agree"><a href="#">[약관보기]</a></div>
								<label for="agree_service">Booktalk 유료서비스 이용약관 동의</label><input type="checkbox" id="agree_service" name="agree_service" value="1">
							</li>
						</ul>
						<!-- 결제/취소 버튼 -->
						<ul class="btn-pay">
							<li><a href="#" class="btn-skyblue" id="btn-submit">결제</a></li>
							<li><a href="#" class="btn-gray" data-rel="back">취소</a></li>
						</ul>
					</form>
				</div>
				<!-- 결제 ERROR popup -->
				<div data-role="popup" id="payError" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">ERROR</div>
						<p class="popup-txt">결제내용 확인 및 이용약관에<br>동의하셔야 합니다.</p>
						<ul class="btn2-popup">
							<li style="width: 100%;"><a href="#" data-rel="back" class="btn-skyblue">확인</a></li>
						</ul>
					</div>
				</div>
				<!-- 결제 ERROR popup End -->
			</div>
			<!-- main End -->
		</div>
		
		<!-- Number -->
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.number.min.js"></script>
			
		<script>
		$(function() {
			// 결제금액 선택
			$("#pay-table tbody tr").click(function() {
				$("#pay-table tbody tr").removeClass("active");
				$(this).addClass("active");
				$(this).find(".pay_amount").prop("checked", true);
			});

			// 결제 submit
			$("#btn-submit").click(function(e) {
				e.preventDefault();
				$("#form-item-new").submit();
			});
			
			$("#form-item-new").submit(function(e) {
				e.preventDefault();

				if ( $(".pay_amount").is(":checked") == false ) {
					alert("충전금액을 선택/입력해 주십시오.");
					return false;
				}
				
				if ( $('input:radio[name="pay_method"]').is(":checked") == false ) {
					alert("결제수단을 선택해 주십시오.");
					return false;
				}

				if ($("#agree_notice").prop("checked") == false) {
					$("#payError").popup("open");
					return false;
				}
				if ($("#agree_service").prop("checked") == false) {
					$("#payError").popup("open");
					return false;
				}
				
				$.ajax({
					type : "POST",
					url : "./ajax/buy-cash.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "wallet_cash_charged.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
