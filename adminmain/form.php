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
				<h2 class="headV">Add Trader</h2>

				<div class="span12 form-box" onTablet="span12" onDesktop="span12">

				<form method="post" action="">
				
				<h2 class="headF">Trader Info </h2>
				<div class="row-fluid">
					<div class="span4 input-box">
					    <label>Trader Name</label>
						<input type="text"> 
					</div>
					<div class="span4 input-box">
					    <label>Trader DOB</label>
						<input type="text"> 
					</div>
					<div class="span4 input-box">
					    <label>Trader Contact</label>
						<input type="text"> 
					</div>
				</div>

				<h2 class="headF">Trader Address </h2>
					 
				<div class="row-fluid">				
					<div class="span4 input-box">
						<label>Zip Code</label>
						<input type="text"> 
					</div>
					<div class="span4 input-box">
						<label>Country</label>
						<input type="text"> 
					</div>
					<div class="span4 input-box">
						<label>Address</label>
						<input type="text"> 
					</div>
				</div>
				<div class="row-fluid">		
					<div class="span4 input-box">
						<label>Comment here !!</label>
						<textarea> </textarea>
					</div>
				</div>	 


				<div class="submit-btn">
					<input class="add-btn" type="submit" value="Save Trader">
				</div>


				</form>
				</div>
		</div>
	
	<div class="clearfix"></div>

	
	<?php include('includes/footer.php') ?>