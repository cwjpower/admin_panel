<?php 
$nav_filename = basename( $_SERVER['PHP_SELF'] );
$nav_qs = $_SERVER['QUERY_STRING'];
?>
			<!-- Left side column. contains the logo and sidebar -->
			<aside class="main-sidebar">
				<!-- sidebar: style can be found in sidebar.less -->
				<section class="sidebar" style="height: auto;" id="scrollspy">
					<!-- Sidebar user panel -->
<?php 
require_once ADMIN_PATH . '/sidebar_profile.php';
?>
					<!-- sidebar menu: : style can be found in sidebar.less -->
					<ul class="sidebar-menu">
						<li class="header">통계</li>
						<li <?php echo strpos($nav_filename, 'by_period.php') === false ? '' : 'class="active"'; ?>>
							<a href="<?php echo ADMIN_URL ?>/statistics/by_period.php"><i class="fa fa-circle-o"></i> 기간별</a>
						</li>
						<li <?php echo strpos($nav_filename, 'by_book.php') === false ? '' : 'class="active"'; ?>>
							<a href="<?php echo ADMIN_URL ?>/statistics/by_book.php"><i class="fa fa-circle-o"></i> 도서별</a>
						</li>
						<li <?php echo strpos($nav_filename, 'by_user.php') === false ? '' : 'class="active"'; ?>>
							<a href="<?php echo ADMIN_URL ?>/statistics/by_user.php"><i class="fa fa-circle-o"></i> 회원별</a>
						</li>								
					</ul>
				</section>
				<!-- /.sidebar -->
			</aside>
			<!-- /.main-sidebar -->
			