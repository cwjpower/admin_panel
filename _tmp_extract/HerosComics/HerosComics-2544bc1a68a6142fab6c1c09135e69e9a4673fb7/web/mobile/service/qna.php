<?php
/*
 * Desc : 1:1 문의 등록
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-term.php';

wps_auth_mobile_redirect();

// 질문유형
$faq_type_groups = wps_get_term_by_taxonomy('wps_category_faq');

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
							<li><a href="#" class="ui-btn-active"><img src="../img/service/service_m04_on.png" title="1:1 문의">1:1 문의</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/ask_book.php" data-ajax="false"><img src="../img/service/service_m05_off.png" title="도서 신청">도서 신청</a></li>
							<li><a href="<?php echo MOBILE_URL ?>/service/feedback.php" data-ajax="false"><img src="../img/service/service_m06_off.png" title="건의사항">건의사항</a></li>
						</ul>
					</div>
				</div>
				<div class="sevice-conts">
					<h2>1:1 문의</h2>
					<form id="form-item-new">
						<input type="hidden" name="post_type" value="qna_new">
						<div class="sevice-help-area">
							<ul class="help-menu list-item">
								<li class="on">문의하기</li>
								<li><a href="qna_list.php" data-ajax="false">문의내역</a></li>
							</ul>
							<div class="help-item-cont01">
								<div class="help-cont-area">
									<ul>
										<li>
											<div class="help-qtit"><label for="helpCateg">카테고리</label></div>
											<div class="help-qtxt">
												<select name="post_term_id" id="post_term_id">
									<?php 
									if (!empty($faq_type_groups)) {
										foreach ($faq_type_groups as $key => $val) {
											$term_id = $val['term_id'];
											$tname = $val['name'];
									?>
												<option value="<?php echo $term_id ?>"><?php echo $tname ?></option>
									<?php
										}
									}
									?>
												</select>
											</div>
										</li>
										<li>
											<div class="help-qtit"><label for="helpTit">제목</label></div>
											<div class="help-qtxt"><input type="text" id="post_title" name="post_title" maxlength="30"></div>
										</li>
										<li>
											<div class="help-qtit"><label for="helpCont">내용</label></div>
											<div class="help-qtxt"><textarea name="post_content" id="post_content" cols="30" rows="10" maxlength="1000"></textarea></div>
										</li>
										<li>
											<div class="help-qtit"><label for="helpFile">파일첨부</label></div>
											<div class="help-qtxt help-file-inp">
											
												<ul id="attachment_preview" style="list-style-type: none; padding-top: 10px;"></ul>
											
												<input type="file" name="attachment[]" id="attachment_file" class="hide">
												<button type="button" id="select_file" class="btn-help-file">찾기</button>
											</div>
										</li>
									</ul>
								</div>
								<ul class="btn-help">
									<li><a href="#" id="btn-submit" class="btn-skyblue">등록</a></li>
									<li><a href="#" id="btn-cancel" class="btn-gray">취소</a></li>
								</ul>
							</div>
						</div>
					</form>
				</div>
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

				$.ajax({
					type : "POST",
					url : "./ajax/qna-new.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "qna_list.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});

			$("#btn-cancel").click(function() {
// 				$("#form-item-new").reset();
				document.getElementById("form-item-new").reset();
				$("#attachment_preview").html( "" );
				$("#attachment_file").val("");
				$("#select_file").fadeIn();
			});

			// File
			// Trigger
			$("#select_file").click(function() {
				$("#attachment_file").click();
			});
			// File Upload
			$("#attachment_file").change(function() {		
// 				showLoader();
				$("#select_file").fadeOut();
// 				$("#select_file").html("업로드중입니다...");
// 				$("#select_file").prop("disabled", true);

				$("#form-item-new").ajaxSubmit({
					type : "POST",
					url : "<?php echo INC_URL ?>/lib/upload-attachment.php",
					dataType : "json",
					success: function(xhr) {
// 						$("#select_file").html("파일첨부");
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
							$("#select_file").fadeIn();
						}
					});
				}
			});
			
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
