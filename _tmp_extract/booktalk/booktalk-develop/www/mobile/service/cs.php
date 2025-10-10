<?php
/*
 * Desc : 고객센터
 */
require_once '../../wps-config.php';

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
							<li><a href="#" class="ui-btn-active"><img src="../img/service/service_m01_on.png" alt="">고객센터 안내</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false"><img src="../img/service/service_m02_off.png" title="공지사항">공지사항</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/faq.php" data-ajax="false"><img src="../img/service/service_m03_off.png" title="자주 묻는 질문">자주 묻는 질문</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/qna.php" data-ajax="false"><img src="../img/service/service_m04_off.png" title="1:1 문의">1:1 문의</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false"><img src="../img/service/service_m05_off.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
				</div>
				<div class="sevice-conts">
					<h2>고객센터 안내</h2>
					<div class="sevice-guide-area">
						<p class="sevice-tel-num">전화상담안내 <span class="txt-0b80e0">070 - 8832 - 9375</span></p>
						<p>&middot; 평일 10:00 ~ 18:00 / 주말, 공휴일 휴무</p>
					</div>
					<div  class="btn-sevice-terms">
						<ul>
							<li><a href="<?php echo MOBILE_URL ?>/service/terms.php" data-ajax="false">이용 약관 보기</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/privacy.php" data-ajax="false">개인정보 취급 방침</a></li>
							<li><a href="#">사업자 정보 확인</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
