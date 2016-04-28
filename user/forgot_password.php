<?php
	require __DIR__ . '/../connection.php';

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
<form method="post">
	<input name="email-or-username" type="text" value="" />
	<input name="reset-password" type="submit" value="Reset password">
</form>