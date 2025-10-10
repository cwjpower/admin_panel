<?php
/*
 * Desc : 큐레이팅 등록
 */
require_once '../../wps-config.php';
// require_once FUNC_PATH . '/functions-page.php';
require_once FUNC_PATH . '/functions-book.php';

wps_auth_mobile_redirect();

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<style>
		.gray { background-color: #eee; }
		</style>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<div class="curating-header">
					<h2>큐레이팅</h2>
				</div>
				<div class="curating-frm-area">
					<form id="item-new-form">
						<div class="curating-frm">
							<ul>
								<li class="curating-frm-top">
									<div class="curating-frm-box">
										<label for="cuTit">큐레이팅 제목</label>
										<input type="text" id="curation_title" name="curation_title" maxlength="30" placeholder="제목입력" required>
									</div>
								</li>
								<li>
									<div class="curating-frm-box btn-file">
										<label for="cuImg">이미지 등록</label>
										<div class="cu-inp"><input type="text" id="cuImg"><a href="#" id="select-file" class="btn-curating-img">파일선택</a></div>
										<input type="file" name="attachment[]" class="hide">
									</div>
									<ul id="preview-attachment" class="list-group"></ul>
								</li>
								<li>
									<div class="curating-frm-box">
										<label for="cuSearch">책 찾기</label>
										<div class="cu-inp"><input type="text" id="b_q" placeholder="제목을 입력하세요.">
											<button type="button" class="btn-curating-search" id="search-book-btn">찾기</button>
										</div>
									</div>
									<div class="curating-frm-box curating-booklist">
										<div id="curation-found"></div>
										<button type="button" class="btn-curating-add" id="add-book-btn">추가</button>
									</div>
								</li>
								<li>
									<div class="curating-frm-box">
										<div class="booklist-del-tit">등록 예정 도서
											<button type="button" class="btn-select-del" id="remove-book-btn">선택 삭제</button>
										</div>
										<div class="curating-booklist-del" style="min-height: 100px;">
											<ul id="pkg_panel"></ul>
											<input type="hidden" id="pkg_books" name="pkg_books">
										</div>
									</div>
									
								</li>
							</ul>
						</div>
						<ul class="btn-curating">
							<li><a href="#" class="btn-skyblue" id="btn-submit">등록</a></li>
							<li><a href="#" class="btn-gray" id="btn-cancel">취소</a></li>
						</ul>
					</form>
				</div>
			</div>
			<!-- main End -->
		
		</div>
		
		<!-- jQuery Form plugin -->
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.form.min.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.serializeObject.min.js"></script>
		
		<script src="<?php echo INC_URL ?>/js/ls-util.js"></script>
		<script src="<?php echo INC_URL ?>/js/jquery/jquery.oLoader.min.js"></script>
		
		<script>
		$(function() {
			$("#btn-submit").click(function(e) {
				e.preventDefault();
				$("#item-new-form").submit();
			});

			$("#btn-cancel").click(function(e) {
				e.preventDefault();
				history.back();
			});

			$("#select-file").click(function() {
				$('input[name="attachment[]"]').click();
			});
			
			$("#item-new-form").submit(function(e) {
				e.preventDefault();

				var bookArr = [];

				if ($(".pre-curation").length == 0) {
					alert("큐레이팅에 추가할 책이 없습니다.");
					return false;
				}
				if ($("#preview-attachment li").length == 0) {
					alert("표지 이미지 파일을 선택해 주십시오.");
					return false;
				}
				if ($('input[name="curation_title"]').val() == "") {
					alert("큐레이팅 제목을 입력해 주십시오.");
					return false;
				}
				if ($(".pre-curation").length < 1) {
					alert("큐레이팅에 추가할 책이 없습니다.");
					return false;
				}

				$(".pre-curation").each(function(idx) {
					bookArr.push($(this).val());
				});

				$("#pkg_books").val( bookArr );

				showLoader();

				$.ajax({
					type : "POST",
					url : "./ajax/curation-new.php",
					data : $(this).serialize(),
					dataType : "json",
					success : function(res) {
						hideLoader();
						if ( res.code == "0" ) {
							location.href = "curation_list.php";
						} else {
							alert( res.msg );
						}
					}
				});
			});
			
			$('input[name="attachment[]"]').change(function() {
				var size = this.files[0].size;
				var fext = this.files[0].name.split('.').pop().toLowerCase();
//					var fname = this.files[0].name;

				if ( size > 1048576 ) {
					alert( "업로드할 수 있는 이미지 파일 용량은 1MB입니다.");
					$(this).val("");
					return;
				}

				if ( fext !=  'gif' && fext != 'jpg' && fext != 'jpeg' && fext != 'png' ) {
					alert('확장자가 gif, jpg, png인 이미지 파일만 첨부해 주십시오.');
					$(this).val("");
					return;
				}

				showLoader();

				$("#item-new-form").ajaxSubmit({
					type : "POST",
					url : "<?php echo INC_URL ?>/lib/upload-attachment.php",
					dataType : "json",
					success: function(xhr) {
						hideLoader();
						if ( xhr.code == "0" ) {
							for ( var i = 0; i < xhr.file_url.length; i++ ) {
								uploadedFiles =  '<li class="list-group-item" style="margin: 10px;">' +
													'<input type="hidden" name="file_path[]" value="' + xhr.file_path[i] + '">' +
													'<input type="hidden" name="file_url[]" value="' + xhr.file_url[i] + '">' +
													'<input type="hidden" name="file_name[]" value="' + xhr.file_name[i] + '">' +
													'<div class="btn-gray delete-tmp" style="cursor: pointer; margin-bottom: 10px;">이미지 삭제</div>' +
													'<img src="' + xhr.thumb_url[i] +
														'" title="' + xhr.file_name[i] +
														'" class="preivew-attachment" ' +
														'style="border: 1px solid #eeeeee; max-width: 100%;">' +
												'</li>';
							}
							$("#preview-attachment").append( uploadedFiles );
							$(".btn-file").fadeOut();
						} else {
							alert( xhr.msg );
						}
					}
				});
			});

			// File Deletion
			$(document).on("click", "#preview-attachment .delete-tmp", function() {
				var file = $(this).parent().find('input[name="file_path[]"]').val();
//					if ( confirm("파일을 삭제하시겠습니까?") ) {
					$.ajax({
						type : "POST",
						url : "<?php echo INC_URL ?>/lib/delete-attachment.php",
						data : {
							"filePath" : file
						},
						dataType : "json",
						success : function(res) {
							$("#preview-attachment li").each(function(idx) {
								var file = $(this).find('input[name="file_path[]"]').val();
								if ( file == res.file_path ) {
									$(this).fadeOut("slow", function() { $(this).remove(); });
								}
							});
							if ( res.code != "0" ) {
								alert( res.msg );
							}
							$(".btn-file").fadeIn();
						}
					});
//					}
			});

			// 책 추가
			$("#add-book-btn").click(function() {

				var founded = $("#curation-found dl").length;
				var grayclass = $(".gray").length;

				if ( founded == 0 ) {
					alert("겸색된 책이 없습니다.");
					return;
				}
				if ( grayclass == 0 ) {
					alert("검색된 책 리스트에서 큐레이팅에 추가하실 책를 선택해 주십시오.");
					return;
				}

				$(".gray").each(function(idx) {
					var id = $(this).attr("id").replace(/\D/g, "");
					var btitle = $(this).find("dt").html();
					var dup = 0;

					if ($(".pre-curation").length > 0) {
						$(".pre-curation").each(function(idx) {
							if (id == $(this).val()) {
								dup = 1;
							}
						});
					}

					if (dup == 0) {
						$("#pkg_panel").append('<li id="gs-' + id + '"><label>' + btitle + '<input class="pre-curation" type="checkbox" value="' + id + '""></label></li>');
						$(this).remove();
					} else {
						alert("이미 추가된 책입니다.");
						return;
					}
				});
			});

			// 책 삭제
			$(document).on("click", "#remove-book-btn", function() {

				var checked = $(".pre-curation:checked").length;

				if ( checked == 0 ) {
					alert("삭제할 책를 선택해 주십시오.");
				} else {
					$(".pre-curation:checked").each(function(idx) {
						$(this).parent().remove();
					});
				}
			});

			// 책 검색
			$("#search-book-btn").click(function(e) {
				searchBook();
			});

			// 책 검색
			$("#b_q").keypress(function(e) {
				if (e.which == 13) {
					e.preventDefault();
					searchBook();
				}
			});

			// 검색한 책 클릭 시 색상 반전
			$(document).on("click", "#curation-found dl", function() {

				if ($(this).hasClass("gray")) {
					$(this).removeClass("gray");
				} else {
					$(this).addClass("gray");
				}
// 				console.log( $(this).attr("id") );
			});
			
			function searchBook() {
				var bt = $.trim($("#b_q").val());

				if (bt == "") {
					alert("검색하실 책 제목을 입력해 주십시오.");
					return;
				}
				

				$.ajax({
					type : "POST",
					url : "./ajax/search-book.php",
					data : {
						"q" : bt
					},
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							$("#curation-found").empty().append(res.result);
						} else {
							alert(res.msg);
						}
					}
				});
			}

		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
