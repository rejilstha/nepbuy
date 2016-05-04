<?php 
	require __DIR__ .'/../connection.php';
	include('includes/header.php');

	if(isset($_POST["admin-login-submit"])) {
		$loggedin_user = login($_POST["username"], $_POST["password"], $CONNECTION);
		if($loggedin_user == -1){
			return; // (403 Unauthorized)
		}
	}
	else if(isset($_POST["admin-logout-submit"])) {
		//Logout
		$_SESSION["user_session"] = trim(com_create_guid(), '{}');
		header("Location: /nepbuy/admin/login.php");
	}
	else {
		if(!(require __DIR__ . '/admin_access.php')) {
			return;
		}
	}

	function login($username, $password, $connection) {
		$password = md5($password);
		$sqlString = "SELECT COUNT(*) AS COUNT FROM nepbuy_users u ".
					"JOIN nepbuy_user_roles ur ON u.PK_USER_ID=ur.FK_USER_ID ".
					"JOIN nepbuy_roles r ON ur.FK_ROLE_ID=r.PK_ROLE_ID ".
					"WHERE r.NAME='Admin' AND (u.USERNAME='$username' OR u.EMAIL='$username') AND u.PASSWORD='$password'";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			if(oci_fetch_assoc($stid)["COUNT"] > 0) {
				$sqlString = "SELECT u.PK_USER_ID AS PK_USER_ID FROM nepbuy_users u ".
					"JOIN nepbuy_user_roles ur ON u.PK_USER_ID=ur.FK_USER_ID ".
					"JOIN nepbuy_roles r ON ur.FK_ROLE_ID=r.PK_ROLE_ID ".
					"WHERE r.NAME='Admin' AND (u.USERNAME='$username' OR u.EMAIL='$username') AND u.PASSWORD='$password'";

				$stid = oci_parse($connection, $sqlString);
				oci_execute($stid);
				$user_id = oci_fetch_assoc($stid)["PK_USER_ID"];
				$_SESSION["user_session"] = $user_id;

				return $user_id;
			}
			else {
				return -1;
			}
		}
		return -1;
	}

?>

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