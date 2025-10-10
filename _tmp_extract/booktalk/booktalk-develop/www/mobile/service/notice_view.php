<?php
/*
 * 2016.11.30	softsyw
 * Desc : 공지사항 상세
 */
require_once '../../wps-config.php';

if ( empty($_GET['pid'] )) {
	lps_js_back( '게시글이 존재하지 않습니다.' );
}
$post_id = $_GET['pid'];

$post_row = wps_get_post( $post_id );
$post_type = $post_row['post_type'];
$post_label = $wps_post_type[$post_type];
$post_title = htmlspecialchars( $post_row['post_title'] );
$post_content = $post_row['post_content'];
$post_name = $post_row['post_name'];
$post_order = $post_row['post_order'];
$post_user_id = $post_row['post_user_id'];
$post_modified = $post_row['post_modified'];
$post_type_area = lps_get_value_by_key($post_row['post_type_area'], $wps_notice_coverage);

$user_rows = wps_get_user( $post_user_id );
$user_login = @$user_rows['user_login'];
$user_name = @$user_rows['user_name'];

$posts_meta = wps_get_post_meta($post_id);

$attachment = wps_get_meta_value_by_key($posts_meta, 'wps-post-attachment');

if (!empty($attachment)) {
	$attachment = unserialize($attachment);
	$attachment_link = '<a href="' . INC_URL . '/lib/download-post-attachment.php?pid=' . $post_id . '&key=0">'. $attachment[0]['file_name'] .'</a>';
} else {
	$attachment_link = '';
}

$post_view_count = empty($posts_meta['post_view_count']) ? 0 : number_format($posts_meta['post_view_count']);

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
							<li><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false" class="ui-btn-active"><img src="../img/service/service_m02_on.png" title="공지사항">공지사항</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/faq.php" data-ajax="false"><img src="../img/service/service_m03_off.png" title="자주 묻는 질문">자주 묻는 질문</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/qna.php" data-ajax="false"><img src="../img/service/service_m04_off.png" title="1:1 문의">1:1 문의</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false"><img src="../img/service/service_m05_off.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
				</div>
				<div class="notice-tit">공지사항</div>
				<div class="sevice-conts">
					<ul class="notice-cont-area">
						<li class="notice-cont-tit"><?php echo $post_title ?></li>
						<li>
							<div><?php echo $post_content ?></div>
							<div><?php echo $attachment_link ?></div>
						</li>
					</ul>
					<!-- ul class="page-nav">
						<li><div><strong>이전글</strong> ㅣ <a href="#">이전글 내용</a></div></li>
						<li><div><strong>다음글</strong> ㅣ <a href="#">다음글 내용</a></div></li>
					</ul> -->
					<div><a href="<?php echo MOBILE_URL ?>/service/notice_list.php" data-ajax="false" class="btn-skyblue">목록보기</a></div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			// 게시글 삭제 확인
			$(document).on("click", "#btn-activity-delete-confirm", function() {
				$.ajax({
					type : "POST",
					url : "./ajax/book-notice-delete.php",
					data : {
						"postID" : "<?php echo $post_id ?>"
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "book_activity.php?id=<?php echo $book_id ?>";
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
