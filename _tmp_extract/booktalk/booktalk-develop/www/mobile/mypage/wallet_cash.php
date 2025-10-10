<?php
/*
 * Desc : 캐시 사용 내역
 */
require_once '../../wps-config.php';
require_once FUNC_PATH . '/functions-payment.php';

wps_auth_mobile_redirect();

if (empty($_GET['pd'])) {
	$period = 1;
} else {
	$period = $_GET['pd'];
}

$period_label = date('y.m.d', time() - 86400 * 30 * $period) . '~' . date('y.m.d');

$cash_logs = lps_get_cash_used_logs( $period );

require_once MOBILE_PATH . '/mobile-header.php';

$pay_period = [1, 3, 6, 12];

require_once MOBILE_PATH . '/mobile-header.php';

?>

		<div data-role="page">
<?php 
require_once MOBILE_PATH . '/mobile-navbar-overlay.php';
?>
			<!-- main -->
			<div data-role="main" class="ui-content mypage-area">
				<h2>내 지갑</h2>
				<div class="mypage-cont">
					<h3 class="history-tit">캐시 사용 및 충전 내역 조회</h3>
					<ul class="history-menu list-item">
						<li class="on">사용 내역</li>
						<li><a href="wallet_cash_charged.php" data-ajax="false">충전 내역</a></li>
					</ul>
					<div class="list-item-cont01">
						<p>최근  <?php echo $period ?>개월 캐시 사용 내역 조회</p>
						<div class="sel-month">
							<select name="pay_period" id="pay_period">
					<?php 
					foreach ($pay_period as $val) {
						$selected = $period == $val ? 'selected' : '';
					?>
								<option value="<?php echo $val ?>" <?php echo $selected ?>><?php echo $val ?>개월</option>
					<?php 
					}
					?>
							</select>
							<span class="date"><?php echo $period_label ?></span>
						</div>
						<ul class="history-list">
				<?php 
				if (empty($cash_logs)) {
				?>
							<li class="no-data">사용 내역이 없습니다.</li>
				<?php 
				} else {
					foreach ($cash_logs as $key => $val) {
						$cash = $val['cash_used'];
						$created_date = date('Y년 m월 d일', strtotime($val['created_dt']));
						$created_time = date('H:i:s', strtotime($val['created_dt']));
						$meta = unserialize($val['cash_comment']);
							$order_id = $meta['order_id'];
							
							$order_row = lps_get_order_summary($order_id);
								$book_title = $order_row['book_title'];
								$total_count = $order_row['total_count'];
								
								$summary = $total_count < 2 ? $book_title : $book_title . ' 외 ' . ($total_count - 1) . '권';
// 							$paymethod = @$wps_payment_method[$meta['payment_method']];
				?>
							<li>
								<div class="history history-date"><?php echo $created_date ?><span class="history-time"><?php echo $created_time ?></span><span class="history-list-r">사용</span></div>
								<div class="history history-txt"><?php echo $summary ?> - 구매<span class="history-list-r"><?php echo number_format($cash) ?>캐시</span></div>
							</li>
				<?php 
					}
				}
				?>
						</ul>
					</div>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		$(function() {
			$("#pay_period").change(function(e) {
				location.href = "?pd=" + $(this).val();
			});
		});
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
