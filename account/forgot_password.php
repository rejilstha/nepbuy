<?php
	require __DIR__ . '/../connection.php';
	include("../includes/header.php");

	if(isset($_POST["reset-password"])) {
		reset_password($_POST["email-or-username"], $CONNECTION);
	}

	function reset_password($email_username, $connection) {
		if(exists_email_or_username($email_username, $connection)) {
			$id = get_user_id($email_username, $connection);

			// Reset password with random
			$randomPassword = md5("random");
			$sqlString = "UPDATE nepbuy_users SET ".
						"PASSWORD='$randomPassword' WHERE PK_USER_ID = $id";
			$stid = oci_parse($connection, $sqlString);
			oci_execute($stid);

			echo "Your password has been reset";
		}
		else {
			echo "Email or username doesn't exist";
			return;
		}
	}

	function exists_email_or_username($email_username, $connection) {
		$sqlString =  "SELECT COUNT(*) as COUNT FROM nepbuy_users WHERE ( USERNAME='$email_username' OR EMAIL='$email_username' )";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return oci_fetch_assoc($stid)["COUNT"] != 0;
		}
	}

	function get_user_id($email_username, $connection) {
		$sqlString =  "SELECT PK_USER_ID FROM nepbuy_users WHERE ( USERNAME='$email_username' OR EMAIL='$email_username' )";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return oci_fetch_assoc($stid)["PK_USER_ID"];
		}
	}
?>
<section id="hero-page1">
	<div class="row">
	 <div class="container">
		<div class="col-sm-12">

		<div class="col-sm-4">
			<img src="/nepbuy/images/img/veg.png" width="100%" >
		</div>
		<div class="col-sm-8">
			<h2 class="title">NepBuy Introduction</h2>
			<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
		</div>	
		</div>

		
	</div>
		
	</div>            
</section>	
<form method="post">
	<input name="email-or-username" placeholder="Email or username" type="text" value="" />
	<input name="reset-password" type="submit" value="Reset password">
</form>

<?php include("../includes/footer.php"); ?>