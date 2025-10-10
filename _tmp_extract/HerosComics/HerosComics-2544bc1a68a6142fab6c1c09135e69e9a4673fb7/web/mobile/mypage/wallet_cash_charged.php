<?php
/*
 * Desc : 캐시 충전 내역
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

$cash_logs = lps_get_cash_charged_logs( $period );

require_once MOBILE_PATH . '/mobile-header.php';

$pay_period = [1, 3, 6, 12];

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
						<li><a href="wallet_cash.php" data-ajax="false">사용 내역</a></li>
						<li class="on">충전 내역</li>
					</ul>
					<div class="list-item-cont01">
						<p>최근 <?php echo $period ?>개월 캐시 충전 내역 조회</p>
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
							<li class="no-data">충전 내역이 없습니다.</li>
				<?php 
				} else {
					foreach ($cash_logs as $key => $val) {
						$cash = $val['cash_used'];
						$created_date = date('Y년 m월 d일', strtotime($val['created_dt']));
						$created_time = date('H:i:s', strtotime($val['created_dt']));
						$meta = unserialize($val['cash_comment']);
							$paymethod = $wps_payment_method[$meta['payment_method']];
				?>
							<li>
								<div class="history history-date"><?php echo $created_date ?><span class="history-time"><?php echo $created_time ?></span><span class="history-list-r txt-3c9cff">충전</span></div>
								<div class="history history-txt"><?php echo $paymethod ?> 충전<span class="history-list-r"><?php echo number_format($cash) ?>캐시</span></div>
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
