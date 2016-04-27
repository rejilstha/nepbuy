<?php
	include("connection.php");

	$current_user = $_SESSION["user_session"];

	if(!is_logged_in($current_user)) {
		header("Location: login.php?status=1");
	}

	$logged_in_user = get_user($current_user, $CONNECTION);

	if(isset($_POST["change-password"])) {

		if(!check_old_password($logged_in_user["PK_USER_ID"], $_POST["current-password"], $CONNECTION)) {
			echo "Wrong current password";
		}
		else if(!check_new_confirm_password($_POST["new-password"], $_POST["confirm-password"], $CONNECTION)) {
			echo "New password doesn't match";
		}
		else {
			$sqlString = "UPDATE nepbuy_users SET PASSWORD='".md5($_POST["confirm-password"]).
			"' WHERE PK_USER_ID=".$logged_in_user["PK_USER_ID"];
			$stid = oci_parse($CONNECTION, $sqlString);
			oci_execute($stid);
		}
	}

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

	function check_old_password($logged_in_user, $current_password, $connection) {
		$sqlString = "SELECT PASSWORD FROM nepbuy_users WHERE PK_USER_ID=".$logged_in_user;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$password = oci_fetch_assoc($stid)["PASSWORD"];
		$current_password = md5($current_password);

		if($password == $current_password)
			return true;
		return false;
	}

	function check_new_confirm_password($new_password, $confirm_password, $connection) {
		if($new_password != '' && ($new_password == $confirm_password))
			return true;
		else
			return false;
	}
?>

<div>
	Name:
	<?php echo $logged_in_user["NAME"]; ?>
</div>
<div>
	Phone number:
	<?php echo $logged_in_user["CONTACT"]; ?>
</div>

<form method="post">
	<input name="current-password" placeholder="Current Password" type="password">
	<input name="new-password" placeholder="New Password" type="password">
	<input name="confirm-password" placeholder="Confirm Password" type="password">
	<input name="change-password" type="submit" value="Change password">
</form>