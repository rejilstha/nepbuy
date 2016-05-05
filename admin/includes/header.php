<?php 
	require_once __DIR__ . '/../../connection.php';

	$user_id = $_SESSION["user_session"];

	function get_user($user, $connection) {
		$sqlString = "SELECT * FROM nepbuy_users WHERE PK_USER_ID=$user";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
		return oci_fetch_assoc($stid);
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>Nepbuy Admin Panel</title>
	<meta name="description" content="Nepbuy Admin Panel">
	<!-- end: Meta -->
	
	<!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- end: Mobile Specific -->
	
	<!-- start: CSS -->
	<link id="bootstrap-style" href="/nepbuy/css/bootstrap.min.css" rel="stylesheet">
	<link href="/nepbuy/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="/nepbuy/css/font-awesome.min.css" rel="stylesheet">
	<link id="base-style" href="/nepbuy/css/style.css" rel="stylesheet">
	<link id="base-style-responsive" href="/nepbuy/css/style-responsive.css" rel="stylesheet">
	<link id="base-style-responsive" href="/nepbuy/css/main-style.css" rel="stylesheet">
	<link id="base-style" href="/nepbuy/css/jquery.dataTables.min.css" rel="stylesheet">

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
	<!-- end: CSS -->
	

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<link id="ie-style" href="css/ie.css" rel="stylesheet">
	<![endif]-->
	
	<!--[if IE 9]>
		<link id="ie9style" href="css/ie9.css" rel="stylesheet">
	<![endif]-->
		
	<!-- start: Favicon -->
	<link rel="shortcut icon" href="/nepbuy/img/favicon.ico">
	<!-- end: Favicon -->
	
		
		
		
</head>

<body>


		<!-- start: Header -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href=""><span>Nep Buy</span></a>
								
				<!-- start: Header Menu -->
				<div class="nav-no-collapse header-nav">
					<ul class="nav pull-right">
			
						<!-- start: User Dropdown -->
						<li class="dropdown">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="white user"></i> 
								<?php
									if(!preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $user_id)) {
										$user = get_user($user_id, $CONNECTION);
										echo $user["NAME"]; 
									}
								?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="dropdown-menu-title">
 									<span>Settings</span>
								</li>
								<li>
									<form method="post" action="/nepbuy/admin/index.php">
										<button name="admin-logout-submit" style="background:#FF8E35;color:#FFF;min-height:40px;width:100%" type="submit"><i class="icon-off">Logout</i></button>
									</form>
								</li>
							</ul>
						</li>
						<!-- end: User Dropdown -->
					</ul>
				</div>
				<!-- end: Header Menu -->
				
			</div>
		</div>
	</div>
	<!-- start: Header -->