<?php
	include("../connection.php");
	include("../includes/header.php");

	$current_user = $_SESSION["user_session"];

	if(!is_logged_in($current_user)) {
		header("Location: /nepbuy/login.php?status=1");
	}

	$logged_in_user = get_user($current_user, $CONNECTION);

	function is_logged_in($current_user) {
		// Session variable not set
		if(!isset($current_user)) {
			return false;
		}
		// Check for guid
		else if(preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $current_user)) {
			return false;
		}
		return true;
	}

	function get_user($user, $connection) {
		$sqlString = "SELECT * FROM nepbuy_users WHERE PK_USER_ID=".$user;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		return oci_fetch_assoc($stid);
	}
?>
<section id="profileD">
	
		<div class="container profileinfo">
			

			<div class="col-sm-3">
				<img src="<?php echo $logged_in_user['PHOTO_LOCATION']; ?>" width="200px" height="200px">
			</div>
			<div class="col-sm-8">
				
				<div class="profile-info">
					<h2>Profile Information</h2>
					<ul class="profile-ul">
						<li><span href="#" class="">Name: <?php echo $logged_in_user["NAME"]; ?> </span></li>
						<li><span href="#" class="">Username: <?php echo $logged_in_user["USERNAME"]; ?> </span></li>
						<li><span href="#" class="">Email: <?php echo $logged_in_user["EMAIL"]; ?> </span></li>
						<li><span href="#" class="">Phone: <?php echo $logged_in_user["CONTACT"]; ?> </span></li>
						<li><a class="edit-btn" href="/nepbuy/account/edit_profile.php"><i class="fa fa-edit"></i>Edit Profile</a></li>
					</ul>
				</div>
			
			</div>	
		</div>
</section>
<!-- hero page -->
		<section id="hero-page-profile">
			<div class="row">
			 <div class="container">
				<div class="col-sm-8">
					<h2 class="title">Account Information</h2>
					<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
				</div>
				<div class="col-sm-4 gears">
					<i class="fa fa-gears"></i>
				</div>
			</div>
				
			</div>            
		</section>		
		<!-- hero page  -->