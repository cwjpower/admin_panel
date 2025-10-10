<?php
/*
 * Desc : 도서 신청
 */
require_once '../../wps-config.php';
require_once INC_PATH . '/classes/WpsPaginator.php';

$post_type = 'ask_book';
$user_id = wps_get_current_user_id();

// page number
$page = empty($_GET['page']) ? 1 : $_GET['page'];

// search
$q = empty($_GET['q']) ? '' : trim($_GET['q']);
$sql = '';
$sparam = array( 's', $post_type );

if ( !empty($q) ) {
	$sql = " AND ( post_content LIKE ? OR post_title LIKE ? ) ";
	$sparam = array( 'sss', $post_type, '%'. $q .'%', '%'. $q .'%' );
}

$query = "
		SELECT
			*
		FROM
			bt_posts_qnas
		WHERE
			post_type = ?
			$sql
		ORDER BY
			ID DESC
";
$paginator = new WpsPaginator($wdb, $page);
$rows = $paginator->ls_init_pagination( $query, $sparam );
$total_count = $paginator->ls_get_total_rows();

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
							<li><a href="#" class="ui-btn-active"><img src="../img/service/service_m05_on.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
				</div>
				<div class="sevice-conts">
					<h2>도서 신청</h2>
					<div class="service-tb-area">
						<form id="item-list-form">
							<div class="btn-tb-write"><a href="<?php echo MOBILE_URL ?>/service/ask_book_new.php" data-ajax="false">작성</a></div>
							<div class="service-tb">
								<ul>
									<li class="tb-header">
										<div class="fl help-tb-txt">제목<!-- label for="rentalChkAll">제목</label><input type="checkbox" id="rentalChkAll"> --></div>
										<div class="fl">처리상태</div>
									</li>
						<?php
						if ( !empty($rows) ) {
							$list_no = $page == 1 ? $total_count : $total_count - (($page - 1) * $paginator->rows_per_page);
	
							foreach ( $rows as $key => $val ) {
								$post_id = $val['ID'];
								$post_name = $val['post_name'];
								$post_title = htmlspecialchars($val['post_title']);
								$post_date = $val['post_date'];
								$post_date_label = substr($post_date, 0, 10);
								$post_status = $val['post_status'];
								
								if ( !strcmp($post_status, 'waiting') ) {
									$reply_icon = '<button type="button" class="btn-standby">대기중</button>';
								} else {
									$reply_icon = '<button type="button" class="btn-finish">답변완료</button>';
								}
						?>
									<li>
										<div class="fl help-tb-txt">
											<!-- input type="checkbox" name="qna_ids[]" value="<?php echo $post_id ?>" class="qna_lists"> -->
											<a href="ask_book_view.php?pid=<?php echo $post_id ?>" data-ajax="false"><?php echo $post_title ?></a>
										</div>
										<div class="fl btn-response"><?php echo $reply_icon ?></div>
									</li>
						<?php
								$list_no--;
							}
						}
						?>
								</ul>
							</div>
						</form>
						<!-- div class="btn-help-del btn-tb-write"><a href="#" id="btn-delete-qna">삭제</a></div> -->
						
						<!-- board-paging -->
						<?php echo $paginator->ls_mobile_pagination_link( 'board-paging' ); ?>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#rentalChkAll").click(function() {
				$(".qna_lists").prop("checked", $(this).prop("checked"));
			});

			// 찜목록 삭제 "확인" 버튼 클릭
			$("#btn-delete-qna").click(function(e) {
				e.preventDefault();
				
				var chkLength = $(".qna_lists:checked").length;

				if (chkLength == 0) {
					alert("삭제할 도서신청 목록을 선택해 주십시오.");
					return;
				}

				$.ajax({
					type : "POST",
					url : "./ajax/qna-delete.php",
					data : $("#item-list-form").serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.reload();
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
