<?php
	include("connection.php");
	include("includes/header.php");

	if(isset($_POST["signup"])){
		saveuser($_POST["username"], $_POST["emailAddress"], $_POST["password"], $_POST["contact"], $CONNECTION);
	}
	else if(isset($_POST["login"])) {
		login($_POST["username-email"], $_POST["password"], $CONNECTION);
	}

	function saveuser($username, $emailAddress, $password, $contact, $connection) {
		$sqlString = "INSERT INTO nepbuy_users(NAME,CONTACT,EMAIL,PASSWORD) VALUES('".$username."',".$contact.",'".$emailAddress."','".md5($password)."')";
		$stid = oci_parse($connection, $sqlString);
		$result = oci_execute($stid);

		//Todo: Send confirmation email
	}

	function login($username_email, $password, $connection) {
		$check_sql_string = "SELECT COUNT(*) as COUNT FROM nepbuy_users WHERE ( USERNAME='".$username_email."' OR EMAIL='".$username_email."' ) AND PASSWORD='".md5($password)."'";
		$chk_st_id = oci_parse($connection, $check_sql_string);
		oci_execute($chk_st_id);
		$count = oci_fetch_assoc($chk_st_id);

		// Check if any rows exist
		if($count['COUNT'] == 0) {
			echo "Error login";
		}
		else {
			echo "Login success";

			//add user id to session
			$check_sql_string = "SELECT PK_USER_ID FROM nepbuy_users WHERE USERNAME='".$username_email."' OR EMAIL='".$username_email."'";
			$chk_st_id = oci_parse($connection, $check_sql_string);
			oci_execute($chk_st_id);
			$user = oci_fetch_assoc($chk_st_id);
			
			// Update cart to the logged in user if 
			// items were added to the cart anonymously
			update_cart($user["PK_USER_ID"], $connection);

			// Update session variable for the logged in user.
			$_SESSION["user_session"]=$user["PK_USER_ID"];
		}
	}

	function update_cart($user_id, $connection) {
		$sqlString = "UPDATE nepbuy_carts SET USER_SESSION = '".$user_id."' WHERE USER_SESSION='".$_SESSION["user_session"]."'";
		$stid = oci_parse($connection, $sqlString);
		$result = oci_execute($stid);
		if($result)
			echo "Updated cart";
	}
?>
<section  id="hero-page1">
	<div>
		This is body.
	</div>
</section>

<?php include("includes/footer.php"); ?>