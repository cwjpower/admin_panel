<?php
/*
 * Desc : SNS 연결관리
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
			<div data-role="main" class="ui-content myinfo-area">
				<h2>개인정보 수정 &gt; 내 글 관리</h2>
				<div class="myinfo-modify">
					<h3 class="myinfo-tit">내 글 관리</h3>
					<div class="myinfo-list-area">
						<ul class="board-box">
							<li>
								<div class="board-tit">전체 담벼락</div><div class="board-num">10004개</div>
							</li>
							<li>
								<div class="board-tit">게시글</div><div class="board-num">40개</div>
							</li>
							<li>
								<div class="board-tit">댓글 수</div><div class="board-num">4개</div>
							</li>
						</ul>
					</div>
					<div class="tb-board-list">
						<div class="inp-box board-list-num">
							<select>
								<option value="10">10개</option>
								<option value="20">20개</option>
								<option value="50">50개</option>
							</select>
						</div>
						<table class="tb-board" summary="내 글의 담벼락 이름, 게시글 수, 댓글 수를 나타낸 양식">
							<colgroup>
								<col width="*">
								<col width="25%">
								<col width="20%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col" class="book-tit">담벼락 이름</th>
									<th scope="col">게시글 수</th>
									<th scope="col">댓글 수</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="book-tit"><a href="#">책 제목1</a></td>
									<td><a href="#board-popup" data-rel="popup" data-position-to="window">10000</a></td>
									<td><a href="#board-popup" data-rel="popup" data-position-to="window">2</a></td>
								</tr>
								<tr>
									<td class="book-tit"><a href="#">책 제목2</a></td>
									<td><a href="#board-popup" data-rel="popup" data-position-to="window">4</a></td>
									<td><a href="#board-popup" data-rel="popup" data-position-to="window">3</a></td>
								</tr>
							</tbody>
						</table>
						<ul class="tb-paging">
							<li><a href="#">◀</a></li>
							<li class="paging-on"><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li><a href="#">▶</a></li>
						</ul>
						<div data-role="popup" id="board-popup" class="ui-content" data-overlay-theme="b">
							<div class="popup-area board-popup">
								<ul>
									<li><a href="#">07-01 작성한 게시글 제목</a></li>
									<li><a href="#">07-01 작성한 게시글 제목</a><a href="#" class="board-Ripple">[10]</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
