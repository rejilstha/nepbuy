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
				<div class="span12" onTablet="span12" onDesktop="span12">
				<h2 class="headV">List Categories</h2><a class="add-btn" href="form.php"><i class="icon-plus"></i> Add New</a>
				<table class="table table-striped datatable">
							  <thead>
								  <tr>
									  <th>Categories</th>
								                                    
								  </tr>
							  </thead>   
							  <tbody>
								<tr>
									<td>Butcher</td>
									                                     
								</tr><tr>
									<td>Bakery</td>
									                                     
								</tr>  
								<tr>
									<td>Green Grocer</td>
									                                     
								</tr>
								<tr>
									<td>Fish Monger</td>
									                                     
								</tr>
								<tr>
									<td>Delicatessen</td>
									                                     
								</tr>

							  </tbody>
						 </table>  
				</div>
		</div>
	
	<div class="clearfix"></div>

	
	<?php include('includes/footer.php') ?>