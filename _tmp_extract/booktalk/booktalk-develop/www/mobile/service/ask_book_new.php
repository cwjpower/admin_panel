<?php
/*
 * Desc : 도서 신청
 */
require_once '../../wps-config.php';

wps_auth_mobile_redirect();

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content service-area">
				<div class="sevice-menu-area">
					<div data-role="navbar">
						<ul class="sevice-menu">
							<li><a href="<?php echo MOBILE_URL ?>/service/cs.php" data-ajax="false"><img src="../img/service/service_m01_off.png" alt="">고객센터 안내</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false"><img src="../img/service/service_m02_off.png" title="공지사항">공지사항</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/faq.php" data-ajax="false"><img src="../img/service/service_m03_off.png" title="자주 묻는 질문">자주 묻는 질문</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/qna.php" data-ajax="false"><img src="../img/service/service_m04_off.png" title="1:1 문의">1:1 문의</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false" class="ui-btn-active"><img src="../img/service/service_m05_on.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
				</div>
				<div class="sevice-conts">
					<h2>도서 신청</h2>
					<form id="form-item-new">
						<input type="hidden" name="post_type" value="ask_book">
						<div class="service-frm-area">
							<ul>
								<li>
									<div class="service-frm-tit"><label for="rentalTit">제목</label></div>
									<div class="service-frm-txt"><input type="text" id="post_title" name="post_title" maxlength="30"></div>
								</li>
								<li>
									<div class="service-frm-tit"><label for="rentalTxt">내용</label></div>
									<div class="service-frm-txt"><textarea name="post_content" id="post_content" cols="30" rows="10" maxlength="1000"></textarea></div>
								</li>
							</ul>
						</div>
						<ul class="btn-service-frm">
							<li><a href="#" id="btn-submit" class="btn-skyblue">등록</a></li>
							<li><a href="#" id="btn-cancel" class="btn-gray">취소</a></li>
						</ul>
					</form>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#btn-submit").click(function() {
				$("#form-item-new").submit();
			});

			$("#form-item-new").submit(function(e) {
				e.preventDefault();

				$.ajax({
					type : "POST",
					url : "./ajax/qna-new.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "ask_book.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
