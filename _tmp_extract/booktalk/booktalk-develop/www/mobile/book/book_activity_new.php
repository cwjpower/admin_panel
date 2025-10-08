<?php
/*
 * 2016.10.31	softsyw
 * Desc : 책 담벼락
 * 		책을 구매한 회원과 관리자만 등록. 관리자는 공지사항도 등록함.
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
// require_once FUNC_PATH . '/functions-activity.php';

wps_auth_mobile_redirect();

if ( empty($_GET['id']) ) {
	lps_js_back( '도서 아이디가 존재하지 않습니다.' );
}

$book_id = $_GET['id'];
$book_rows = lps_get_book($book_id);
$user_id = wps_get_current_user_id();

$book_owner = lps_is_book_owner($book_id, $user_id);

if (!lps_has_book_user($book_id, $user_id) && !$book_owner) {
	lps_js_back('구매한 회원만 작성하실 수 있습니다.');
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
				<div class="board-write-area">
					<form id="form-item-new">
						<input type="hidden" id="book_id" name="book_id" value="<?php echo $book_id ?>">
						<!-- 관리자 -->
						<ul class="board-write-menu manager">
				<?php 
				if ($book_owner) {
				?>
							<li><label for="freeBoard">자유 담벼락</label><input type="radio" id="freeBoard" name="object_type" value="activity" checked></li>
							<li><label for="noticeBoard">공지사항</label><input type="radio" id="noticeBoard" name="object_type" value="post"></li>
				<?php 
				}
				?>
						</ul>
						<!-- 관리자 End -->
						<!-- 일반회원 -->
						<div class="board-write-menu member">자유 담벼락</div>
						<!-- 일반회원 End -->
						<div class="board-write-cont">
							<dl>
								<dt>제목<span class="text-count-title">(0/30)</span></dt>
								<dd><input type="text" id="act_title" name="act_title" maxlength="30" placeholder="제목입력"></dd>
							</dl>
							<dl>
								<dt>내용<span class="text-count-content">(0/1000)</span></dt>
								<dd>
									<textarea name="act_content" id="act_content" cols="30" rows="10" maxlength="1000" placeholder="내용작성"></textarea>
								</dd>
							</dl>
							<dl class="file-upload">
								<!-- dt>파일 첨부<a href="#fileUpload" class="btn-file-upload" data-rel="popup" data-position-to="window">파일선택</a></dt>-->
								<dt>
									파일 첨부<a href="#" id="select_file" class="btn-file-upload">파일선택</a>
									<input type="file" name="attachment[]" id="attachment_file" class="hide" multiple>
								</dt>
								<dd>파일 제한 2MB, 최대 3개까지 등록 가능 </dd>
							</dl>
							
							<ul id="attachment_preview" style="list-style-type: none; padding-top: 10px;"></ul>
							
							<ul class="btn-write-signup">
								<li><a href="#" id="btn-submit" class="btn-skyblue">등록</a></li>
								<li><a href="#" class="btn-gray" data-rel="back">취소</a></li>
							</ul>
						</div>
					</form>
				</div>
				<!-- 첨부 파일 선택 popup -->
				<div data-role="popup" id="fileUpload" class="ui-content" data-overlay-theme="b">
					<div class="popup-area">
						<div class="popup-tit">첨부 파일 선택</div>
						<ul class="popup-list">
							<li>앨범에서 사진 선택</li>
							<li>카메라</li>
						</ul>
						<div class="btn-popup"><a href="#" data-rel="back" class="btn-skyblue">확인</a></div>
					</div>
				</div>
				<!-- 첨부 파일 선택 popup End -->
			</div>
			<!-- main End -->
		</div>
		
		<!-- Form(file) -->
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.form.min.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.serializeObject.min.js"></script>
		
		<script>
		$(function() {

			$("#btn-submit").click(function() {
				$("#form-item-new").submit();
			});

			$("#form-item-new").submit(function(e) {
				e.preventDefault();

				var bookID = $("#book_id").val();

				$.ajax({
					type : "POST",
					url : "./ajax/book-activity-new.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "./book_activity.php?id=" + bookID;
						} else {
							alert(res.msg);
						}
					}
				});
			});

			// File
			// Trigger
			$("#select_file").click(function() {
				$("#attachment_file").click();
			});
			// File Upload
			$("#attachment_file").change(function() {		
// 				showLoader();
				$("#select_file").html("업로드중입니다...");
				$("#select_file").prop("disabled", true);

				$("#form-item-new").ajaxSubmit({
					type : "POST",
					url : "<?php echo INC_URL ?>/lib/upload-attachment.php",
					dataType : "json",
					success: function(xhr) {
						$("#select_file").html("파일첨부");
						$("#select_file").prop("disabled", false);
// 						hideLoader();
						if ( xhr.code == "0" ) {
							var uploadedFiles = $("#attachment_preview").html();
							for ( var i = 0; i < xhr.file_url.length; i++ ) {
								uploadedFiles +=  '<li class="list-group-item">' +
														'<input type="hidden" name="file_path[]" value="' + xhr.file_path[i] + '">' +
														'<input type="hidden" name="file_url[]" value="' + xhr.file_url[i] + '">' +
														'<input type="hidden" name="file_name[]" value="' + xhr.file_name[i] + '">' +
														'&nbsp; <button type="button" class="delete-tmp">삭제</button> &nbsp; ' +
														'<span class="attached_files" title="' + xhr.file_path[i]  +'">' + xhr.file_name[i] + '</span> &nbsp; ' +
													'</li>';
							}
							$("#attachment_preview").html( uploadedFiles );
							$("#attachment_file").val("");
// 							$("#attachment_file").fadeOut();
						} else {
							alert( xhr.msg );
						}
					}
				});
			});

			// File Deletion
			$(document).on("click", "#attachment_preview .delete-tmp", function() {
				var file = $(this).parent().find('input[name="file_path[]"]').val();
					if ( confirm("파일을 삭제하시겠습니까?") ) {
					$.ajax({
						type : "POST",
						url : "<?php echo INC_URL ?>/lib/delete-attachment.php",
						data : {
							"filePath" : file
						},
						dataType : "json",
						success : function(res) {
							$("#attachment_preview li").each(function(idx) {
								var file = $(this).find('input[name="file_path[]"]').val();
								if ( file == res.file_path ) {
									$(this).fadeOut("slow", function() { $(this).remove(); });
								}
							});
							if ( res.code != "0" ) {
								alert( res.msg );
							}
							$('input[name="attachment[]"]').val("");
							$(".btn-file").fadeIn();
						}
					});
				}
			});
			
			
			/* 제목 */
			$("#act_title").on("click keyup keypress input propertychange", function() {
				limitBytes( $(this), 30, $(".text-count-title") );
			});
			/* 내용 */
			$("#act_content").on("click keyup keypress input propertychange", function() {
				limitBytes( $(this), 1000, $(".text-count-content") );
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
