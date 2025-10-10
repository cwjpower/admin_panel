<?php
/*
 * 2016.11.01	softsyw
 * Desc : 책 담벼락 본문  APP #28
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-activity.php';

if ( empty($_GET['id']) ) {
	lps_js_back( '게시글이 존재하지 않습니다.' );
}

$user_id = wps_get_current_user_id();

$activity_id = intval($_GET['id']);

lps_update_activity_view_count($activity_id);	// update view count

// for activity
$act_rows = lps_get_activity($activity_id);

$is_deleted = $act_rows['is_deleted'];

if ( $is_deleted ) {
	lps_js_back( '삭제된 게시글입니다.' );
}

$book_id = $act_rows['book_id'];
$act_title = $act_rows['subject'];
$act_content = $act_rows['content'];
$act_userid = $act_rows['user_id'];		// 게시글 작성자
$created_dt = date('Y.m.d', strtotime($act_rows['created_dt']));
$count_hit = $act_rows['count_hit'];
$count_like = $act_rows['count_like'];

if ($user_id == $act_userid) {	// 본인의 게시글을 조회 시엔 내 소식 개수를 위한 댓글 정보를 업데이트한다.
	lps_update_activity_comment_read($activity_id);		// 게시글에 딸린 댓글 읽음 처리
}

$user_rows = wps_get_user( $act_userid );
$user_name = $user_rows['display_name'];

// for activity meta
$attachment = '';
$act_attach = lps_get_activity_meta($activity_id, 'wps-community-attachment');
if (!empty($act_attach)) {
	$unserial = unserialize($act_attach);
	if (!empty($unserial[0])) {
		foreach ($unserial as $key => $val) {
			$attachment .= '<li><a href="' . INC_URL . '/lib/download-community-attachment.php?aid=' . $activity_id . '&key=' . $key . '" data-ajax="false">' . $val['file_name'] . '</a></li>';
		}
	}
}

// for comment of activity
$act_comments =  lps_get_activity_comments( $activity_id );

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
								<div class="board-write-tit"><?php echo $act_title ?></div>
								<div><?php echo $user_name ?> | <?php echo $created_dt ?> | <?php echo number_format($count_hit) ?></div>
								<ul><?php echo $attachment ?></ul>
					<?php 
					if (lps_is_activity_author($activity_id, $user_id)) {
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
									<?php echo $act_content ?>
								</div>
								<div class="btn-write-area">
									<button class="btn-rep-write">댓글쓰기</button>
						<?php 
						if (!lps_check_recommand_activity($activity_id, $user_id)) {
						?>
									<button id="btn-activity-recommand" class="btn-up">추천하기[<?php echo $count_like ?>]</button>
						<?php 
						} else {
						?>
									<button id="btn-activity-recommand" class="btn-up btn-up-off">추천하기[<?php echo $count_like ?>]</button>
						<?php 
						}
						?>
								</div>
							</div>
						</li>
						
			<?php
			if (!empty($act_comments)) {
				
				$banned_users = lps_get_banned_user_ids($user_id);
				
				foreach ($act_comments as $key => $val) {
					$comment_id = $val['comment_id'];
					$com_user_id = $val['comment_user_id'];
					$com_user_level = $val['comment_user_level'];
// 					$blocked = $val['comment_blocked'];
					$created = date('Y.m.d | A H:i', strtotime($val['comment_date']));
					$content = nl2br($val['comment_content']);
					
					// 내가 올린 글
					if ($user_id == $com_user_id) {
						$author = '<dt class="my-ripple">나</dt>';
						$isme = 1;
					} else {
						$author = '<dt>' . $val['comment_author'] . '</dt>';
						$isme = 0;
					}
					
					// 차단
					if (in_array($com_user_id, $banned_users)) {
						$blocked = '1';
					} else {
						$blocked = '0';
					}
					
					if ($blocked == '0') {
			?>
						<li id="comment-<?php echo $comment_id ?>">
							<div class="ripple-arae">
								<dl>
									<?php echo $author ?>
									<dd><?php echo $content ?></dd>
									<dd><em><?php echo $created ?></em></dd>
						<?php 
						if ($isme) {
						?>
									<dd class="ripple-del"><a href="#reppleDel" class="btn-ripple-del btn-comment-delete" data-rel="popup" data-position-to="window">댓글삭제</a></dd>
						<?php 
						}
						?>
								</dl>
							</div>
						</li>
			<?php 
					} else {	// blocked
			?>
						<li id="comment-<?php echo $comment_id ?>">
							<div class="ripple-arae">
								<dl>
									<?php echo $author ?>
									<dd class="banned-content">차단된 유저입니다.</dd>
									<dd class="banned-content-view hide"><?php echo $content ?></dd>
									<dd><em><?php echo $created ?></em></dd>
									<dd class="cut-off view-banned-comment">차단 유저글<br>보기</dd>
								</dl>
							</div>
						</li>
			<?php
					}
			
				}
			}
			?>
					</ul>
					<div class="board-ripple-write">
						<span class="nick-name">나</span>
						<div class="board-ripple-textarea">
							<div class="text-count">(0/200)</div>
							<form id="form-comment">
								<input type="hidden" id="activity_id" name="activity_id" value="<?php echo $activity_id ?>">
								<textarea name="comment" id="comment" cols="30" rows="10" placeholder="입력영역" maxlength="200"></textarea>
							</form>
						</div>
					</div>
					<!-- btn-top -->
					<div class="btn-top"><a href="#top" data-ajax="false"><img src="../img/board/btn_top.png" alt="위로가기"></a></div>
					<ul class="btn-repple">
						<li><a href="#" id="btn-submit" class="btn-skyblue">등록</a></li>
						<li><a href="#" class="btn-gray" data-rel="back">취소</a></li>
					</ul>
				</div>
				<!-- board-paging 
				<ul class="board-paging">
					<li><a href="#">처음</a></li>
					<li class="prev"><a href="#">앞으로</a></li>
					<li class="paging-on"><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li class="next"><a href="#">뒤로</a></li>
				</ul>-->
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
				<!-- 댓글 삭제 popup -->
				<div data-role="popup" id="reppleDel" class="ui-content" data-overlay-theme="b">
					<input type="hidden" id="btn-comment-delete-id">
					<div class="popup-area">
						<div class="popup-tit">댓글 삭제</div>
						<p class="popup-txt">댓글을 삭제하시겠습니까?</p>
						<ul class="btn2-popup">
							<li><a href="#" id="btn-comment-delete-confirm" class="btn-skyblue">확인</a></li>
							<li><a href="#" data-rel="back" class="btn-gray">취소</a></li>
						</ul>
					</div>
				</div>
				<!-- 댓글 삭제 popup End -->
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {

			$(".btn-rep-write").click(function() {
				$("#comment").focus();
			});

			$("#btn-submit").click(function(e) {
				e.preventDefault();
				$("#form-comment").submit();
			});

			// 댓글 등록
			$("#form-comment").submit(function(e) {
				e.preventDefault();

				if ( $.trim($("#comment").val()) == "" ) {
					alert("댓글 내용을 입력해 주십시오.");
					return false;
				}

				$.ajax({
					type : "POST",
					url : "./ajax/book-activity-comment-new.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							$(".board-write-list li").last().append( res.list );
							$("#comment").val("");
						} else {
							alert(res.msg);
						}
					}
				});
				
			});

			// 댓글삭제 클릭
			$(document).on("click", ".btn-comment-delete", function() {
				var id = $(this).closest("li").attr("id").replace(/\D/g, "");
				$("#btn-comment-delete-id").val(id);
			});

			// 댓글삭제 확인
			$(document).on("click", "#btn-comment-delete-confirm", function() {
				var id = $("#btn-comment-delete-id").val();
				
				$.ajax({
					type : "POST",
					url : "./ajax/book-activity-comment-delete.php",
					data : {
						"commentID" : id
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							$("#comment-" + id).fadeOut().remove();
							$("#reppleDel").popup("close");
						} else {
							alert(res.msg);
						}
					}
				});
			});

			// 게시글 삭제 확인
			$(document).on("click", "#btn-activity-delete-confirm", function() {
				var id = $("#activity_id").val();
				
				$.ajax({
					type : "POST",
					url : "./ajax/book-activity-delete.php",
					data : {
						"activityID" : id
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

			// 차단 유저글 보기
			$(document).on("click", ".view-banned-comment", function() {
				var id = $(this).closest("li").attr("id").replace(/\D/g, "");
				var content = $(this).parent().find(".banned-content-view").html();
				$(this).parent().find(".banned-content").html( content );
				$(this).fadeOut();
			});
			
			
			// 추천하기
			$("#btn-activity-recommand").click(function() {

				if ($(this).hasClass("btn-up-off")) {
					alert("이미 추천하신 게시글입니다.");
					return;
				}
				
				$.ajax({
					type : "POST",
					url : "./ajax/book-activity-recommand.php",
					data : {
						"activityID" : $("#activity_id").val()
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							$("#btn-activity-recommand").addClass("btn-up-off");
							$("#btn-activity-recommand").html("추천하기[" + res.count + "]");
						} else {
							alert(res.msg);
						}
					}
				});
			});

			/* 내용 */
			$("#comment").on("click keyup keypress input propertychange", function() {
				limitBytes( $(this), 200, $(".text-count") );
			});
			
		}); // $

		function limitBytes(sendMsg, maxBytes, display) {
			var chr = "", chrLength = 0, validMsgLength = 0, validChrLength = 0, validMsg = "", bytesVal = "";
			
			for ( i = 0; i < sendMsg.val().length; i++ ) {
				chr = sendMsg.val().charAt(i);
				if (escape(chr).length > 4) {
// 					chrLength += 2;
					chrLength ++;
				} else if (chr != "\r") {		// %0D%0A
					chrLength++;
				}
				if ( chrLength <= maxBytes ) {
					validMsgLength = i + 1;
					validChrLength = chrLength;
				}
			}
			if ( chrLength > maxBytes ) {
				alert( maxBytes +"바이트 이상의 메세지는 작성하실 수 없습니다.");
				validMsg = sendMsg.val().substr(0, validMsgLength);
				sendMsg.val( validMsg );
				bytesVal = "(" + validChrLength + "/"+ maxBytes + ")";
			} else {
				bytesVal = "(" + chrLength + "/"+ maxBytes + ")";
			}

			display.html( bytesVal );
			
		}
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
