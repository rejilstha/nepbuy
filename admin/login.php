<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>Bootstrap Metro Dashboard by Dennis Ji for ARM demo</title>
	<meta name="description" content="Bootstrap Metro Dashboard">
	<meta name="author" content="Dennis Ji">
	<meta name="keyword" content="Metro, Metro UI, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	<link id="bootstrap-style" href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="../css/font-awesome.min.css" rel="stylesheet">
	<link id="base-style" href="../css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="../css/style-responsive.css" rel="stylesheet">
	<link id="base-style-responsive" href="../css/main-style.css" rel="stylesheet">
	<link id="base-style" href="../css/jquery.dataTables.min.css" rel="stylesheet">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!-- end: CSS -->
	

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="../css/ie.css" rel="stylesheet">
	<![endif]-->
	
	<!--[if IE 9]>
		<link id="ie9style" href="../css/ie9.css" rel="stylesheet">
	<![endif]-->
		
	<!-- start: Favicon -->
	<link rel="shortcut icon" href="../img/favicon.ico">
	<!-- end: Favicon -->
	
		
	<style type="text/css">
			body { background:#fff !important; }
			.login-box input{
				border-radius: 0px !important;
				margin-left:5px;
			}
		</style>		
		
</head>

<body>


	
	<!-- start: Header -->
		<div class="container-fluid-full">
		<div class="row-fluid">					
				<div class="login-box">
				
					<h2>Login to your account</h2>

							<form class="" action="index.php" method="post">
						
								<span class="add-on"><i class="icon-user"></i></span>
								<input class="" name="username" type="text" placeholder="username"/><br>
							
								<span class="add-on"><i class="icon-lock"></i></span>
								<input class="" name="password" type="password" placeholder="password"/>
							
								<button name="admin-login-submit" type="submit" class="btn-login">Login</button>
							
							</form>
					
				</div><!--login-box-->
			</div><!--row fluid-->
			</div><!--/.fluid-container-->
	
	
	<?php include('includes/footer.php') ?>