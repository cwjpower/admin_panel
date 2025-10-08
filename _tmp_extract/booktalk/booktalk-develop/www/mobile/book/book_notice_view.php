<?php
/*
 * 2016.11.03	softsyw
 * Desc : 책 담벼락의 공지사항 상세
 */
require_once '../../wps-config.php';

if ( empty($_GET['id']) ) {
	lps_js_back( '게시글이 존재하지 않습니다.' );
}

$user_id = wps_get_current_user_id();

$post_id = intval($_GET['id']);
$book_id = intval($_GET['bid']);

// for post : notice_new
$post_rows = wps_get_post($post_id);

$post_title = $post_rows['post_title'];
$post_content = $post_rows['post_content'];
$post_user_id = $post_rows['post_user_id'];
$post_date = date('Y.m.d', strtotime($post_rows['post_date']));
$count_hit = lps_update_post_view_count($post_id);

$user_rows = wps_get_user( $post_user_id );
$user_name = $user_rows['user_name'];

// for activity meta
$attachment = '';
$act_attach = wps_get_post_meta($post_id, 'wps-post-attachment');
if (!empty($act_attach)) {
	$unserial = unserialize($act_attach);
	if (!empty($unserial[0])) {
		foreach ($unserial as $key => $val) {
			$attachment .= '<li><a href="' . INC_URL . '/lib/download-post-attachment.php?pid=' . $post_id . '&key=' . $key . '" data-ajax="false">' . $val['file_name'] . '</a></li>';
		}
	}
}

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content board-area">
				<div class="board-header">
					<ul>
						<li class="board-tit">담벼락</li>
					</ul>
				</div>
				
				<div class="board-write-view-area">
					<ul class="board-write-list">
						<li>
							<div class="board-write-top">
								<div class="board-write-tit"><?php echo $post_title ?></div>
								<div><?php echo $user_name ?> | <?php echo $post_date ?> | <?php echo number_format($count_hit) ?></div>
								<ul><?php echo $attachment ?></ul>
					<?php 
					if (lps_is_post_author($post_id, $user_id)) {
					?>
								<a href="#boardDel" class="btn-write-del" data-rel="popup" data-position-to="window">삭제</a>
					<?php 
					}
					?>
							</div>
						</li>
						<li>
							<div class="board-write-view">
								<div>
									<?php echo $post_content ?>
								</div>
							</div>
						</li>
					</ul>
					
					<!-- btn-top -->
					<ul class="btn-repple">
						<li><a href="#" class="btn-gray" data-rel="back">취소</a></li>
					</ul>
				</div>
				
				<!-- 게시글 삭제 popup -->
				<div data-role="popup" id="boardDel" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">게시글 삭제</div>
						<p class="popup-txt">게시글을 삭제하시겠습니까?</p>
						<ul class="btn2-popup">
							<li><a href="#" id="btn-activity-delete-confirm" class="btn-skyblue">확인</a></li>
							<li><a href="#" data-rel="back" class="btn-gray">취소</a></li>
						</ul>
					</div>
				</div>
				<!-- 게시글 삭제 popup End -->
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
