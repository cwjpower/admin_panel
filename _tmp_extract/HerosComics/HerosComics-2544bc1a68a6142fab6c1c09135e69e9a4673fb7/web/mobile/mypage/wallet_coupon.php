<?php
/*
 * Desc : 보유쿠폰
 * 	TODO : 페이징 UI 없음, 쿠폰 설명 항목 UI 없음.
 */
require_once '../../wps-config.php';
require_once INC_PATH . '/classes/WpsPaginator.php';
require_once FUNC_PATH . '/functions-coupon.php';

wps_auth_mobile_redirect();

// page number
$page = empty($_GET['page']) ? 1 : $_GET['page'];
$sparam = null;

$query = "
		SELECT
			c.ID,
			c.coupon_name,
			c.coupon_type,
			c.coupon_desc,
			c.period_from,
			c.period_to,
			c.discount_type,
			c.discount_rate,
			c.discount_amount,
			c.item_price_min,
			c.item_price_max,
			c.created_dt,
			o.coupon_code
		FROM
			bt_coupon AS c
		LEFT JOIN
			bt_order AS o
		ON
			c.ID = o.coupon_code
		WHERE 
			1
		ORDER BY
			c.ID DESC
";
$paginator = new WpsPaginator($wdb, $page, 100);
$rows = $paginator->ls_init_pagination( $query, $sparam );
$total_count = $paginator->ls_get_total_rows();

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
					<div class="get-view">
						<h3>북톡 쿠폰</h3>
						<div class="get-count hide">사용 가능한 쿠폰 <span>0</span>장</div>
					</div>
					<!--div class="btn-register">
						<a href="<?php echo MOBILE_URL ?>/mypage/wallet_coupon_new.php" class="btn-green" data-ajax="false">쿠폰 등록</a>
					</div>  -->
					<ul class="get-list">
			<?php
			if ( !empty($rows) ) {
				$list_no = $page == 1 ? $total_count : $total_count - (($page - 1) * $paginator->rows_per_page);
				$ymd = date('Y-m-d');
				
				foreach ( $rows as $key => $val ) {
					$ID = $val['ID'];
					$coupon_name = $val['coupon_name'];
					
					$coupon_type = $val['coupon_type'];
					$coupon_desc = $val['coupon_desc'];
					$period_from = $val['period_from'];
					$period_to = $val['period_to'];
					$discount_type = $val['discount_type'];
					$discount_amount = $val['discount_amount'];
					$discount_rate = $val['discount_rate'];
					$item_price_min = $val['item_price_min'];
					$item_price_max = $val['item_price_max'];
					$created_dt = $val['created_dt'];
					$coupon_code = $val['coupon_code'];
					
					$period_label = !empty($period_to) && $ymd > $period_to ? '<span class="label label-default">기간만료</span>' : '';
					
					if (!empty($period_to)) {
						if ($ymd > $period_to) {
							$period_label = '<div class="get-list-r expired">기간만료</div>'; 
						} else {
							$period_label = '<div class="get-list-r use">사용가능</div>';
						}
					} else {
						$period_label = '<div class="get-list-r use">사용가능</div>';
					}
					
					if ( !empty($coupon_code) ) {
						$period_label = '<div class="get-list-r finish">사용완료</div>';
					}
			?>
						<li>
							<div class="pr get-txt"><?php echo $coupon_name ?></div>
							<div class="pr get-date">유효기간 | <span class="txt-a5ca71"><?php echo $period_from ?> ~ <?php echo $period_to ?></span></div>
							<?php echo $period_label ?> <?php echo $coupon_code ?>
						</li>
			<?php
					$list_no--;
				}
			} else {
			?>
						<li class="no-data">보유한 쿠폰이 없습니다.</li>
			<?php 
			}
			?>
					</ul>
				</div>
			</div>
			<!-- main End -->
		</div>
		
		<script>
		
		</script>

<?php 
require_once MOBILE_PATH . '/mobile-footer.php';
?>
