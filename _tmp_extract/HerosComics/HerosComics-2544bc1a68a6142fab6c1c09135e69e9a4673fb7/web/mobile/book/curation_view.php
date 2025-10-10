<?php
/*
 * Desc : 큐레이팅 상세
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-book.php';
require_once FUNC_PATH . '/functions-page.php';

if ( empty($_GET['id']) ) {
	lps_js_back( '큐레이션을  선택해 주십시오.' );
}

$curation_id = intval($_GET['id']);

lps_update_curation_hit( $curation_id );

$crow = lps_get_curation_by_id( $curation_id );

$crt_title = $crow['curation_title'];
$crt_img = unserialize($crow['cover_img']);
	$cover = $crt_img['file_url'];
$crt_meta = unserialize($crow['curation_meta']);
$crt_userid = $crow['user_id'];

$user_row = wps_get_user($crt_userid);
$display_name = $user_row['display_name'];

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content main-cont-area">
				<div class="curating-header">
					<h2>큐레이팅</h2>
				</div>
				<div class="curating-cont-area">
					<div class="curation-visual-header">
							<h3><?php echo $crt_title ?></h3>
						</div>
					<img src="<?php echo $cover ?>" style="width: 100%;">
					<!-- div class="curation-cont-visual" style="background:url('<?php echo $cover ?>'); background-repeat: no-repeat; height: 300px;"> -->
					</div>
					<div class="user-nickname"><?php echo $display_name ?></div>
					<form id="form-item-list">
						<ul class="curation-conts">
				<?php 
				if (!empty($crt_meta)) {
					foreach ($crt_meta as $key => $val) {
						$withb = lps_get_book($val);
							
						$wb_title = $withb['book_title'];
						$wb_cover = $withb['cover_img'];
						$wb_author = $withb['author'];
						$wb_publisher = $withb['publisher'];
						$wb_normal_price = $withb['normal_price'];
						$wb_sale_price = $withb['sale_price'];
				?>
							<li>
								<div class="curation-cont-box">
									<div class="curation-box-top">
										<div class="cu-chk"><input type="checkbox" name="bookID[]" class="wish_books" value="<?php echo $val ?>"></div>
										<div class="book-img-box">
											<div class="book-opc"></div>
											<a href="#"><img class="book-img" src="<?php echo $wb_cover ?>" title="표지이미지"></a>
										</div>
										<div class="ellipsis curation-book-tit"><?php echo $wb_title ?></div>
									</div>
									<div class="curation-box-bottom">
										<dl>
											<dt>저자</dt>
											<dd><?php echo $wb_author ?></dd>
										</dl>
										<dl>
											<dt>출판사</dt>
											<dd><?php echo $wb_publisher ?></dd>
										</dl>
										<dl>
											<dt>정가</dt>
											<dd><?php echo number_format($wb_normal_price) ?></dd>
										</dl>
										<dl class="txt-1a8ee7">
											<dt>판매가</dt>
											<dd><?php echo number_format($wb_sale_price) ?></dd>
										</dl>
									</div>
								</div>
							</li>
				<?php 
					}
				}
				?>
						</ul>
						<div class="cu-allchk-area">
							<div>
								<input type="checkbox" id="cuAllChk"><label for="cuAllChk">전체선택</label>
								<div class="cu-allchk-count">선택된 도서 <span class="txt-1a8ee7" id="selected-book-number">0</span>권</div>
							</div>
						</div>
						<div><a href="#" id="btn-item-buy" class="btn-skyblue">선택 물품 구매하기</a></div>
					</form>
				</div>
			</div>
			<!-- main End -->
		
		</div>
		
		<script>
		$(function() {
			$("#cuAllChk").click(function() {
				$(".wish_books").prop("checked", $(this).prop("checked"));
			});
			// 선택된 도서
			$(".wish_books, #cuAllChk").click(function() {
				getCheckedBooks();
			});

			// 구매하기
			$("#btn-item-buy").click(function(e) {
				e.preventDefault();

				var chkLength = $(".wish_books:checked").length;

				if (chkLength == 0) {
					alert("구매하실 책을 선택해 주십시오.");
					return;
				}

				$.ajax({
					type : "POST",
					url : "../cart/ajax/add-buy-item.php",
					data : $("#form-item-list").serialize(),
					dataType : "json",
					success : function(res) {
						if (res.code == "0") {
							location.href = "../cart/pay.php";
						} else {
							alert(res.msg);
						}
					}
				});
			});

			function getCheckedBooks() {
				$("#selected-book-number").html( $(".wish_books:checked").length );
			}
		}); // $
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
