<?php include('includes/header.php') ?>


	
		<div class="container-fluid-full">
		<div class="row-fluid">
		
       <?php include('includes/nav.php') ?>
			
			
			
			<!-- start: Content -->
		<div id="content" class="span10">				
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.html">Home</a>
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="#">Dashboard</a></li>
			</ul>

			<div class="row-fluid">
				<div class="span4" onTablet="span6" onDesktop="span3">
				  <div class="dash-report borderClr-o">
						<h1><i class="icon-dashboard"></i> Users</h1>
						<p>Total: 400</p>					
				  </div>
				</div>		
				<div class="span4" onTablet="span6" onDesktop="span3">
				  <div class="dash-report borderClr-r">
						<h1><i class="icon-bar-chart "></i> Orders</h1>
						<p>Total: 300</p>
				  </div>				
				</div>
				<div class="span4" onTablet="span6" onDesktop="span3">
				  <div class="dash-report borderClr-dr">
						<h1><i class="icon-bar-chart "></i> Orders</h1>
						<p>Total: 300</p>
				  </div>				
				</div>
		</div>
	
	<div class="clearfix"></div>

	
	<?php include('includes/footer.php') ?>