<?php
	require_once __DIR__ . "/connection.php";

	if(!isset($_SESSION["user_session"])) {
		header("Location: /nepbuy/account/login.php?status=1");
	}
	elseif (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"])) {
	  // User session is a guid and user isn't registered
		header("Location: /nepbuy/account/login.php?status=1");
	} else {
		// Check if trader
		$user = $_SESSION["user_session"];

		if(!is_trader($user, $CONNECTION)) {
			header('HTTP/1.0 403 Forbidden');
			header("Location: /nepbuy/403.html");
			exit;
		}
	}

	return false;

	function is_trader($user_id, $connection) {
		$sqlString = "SELECT COUNT(*) AS COUNT FROM nepbuy_user_roles ur ".
					"JOIN nepbuy_roles r ON r.PK_ROLE_ID=ur.FK_ROLE_ID ".
					"WHERE (r.NAME='Trader' OR r.NAME='Admin') AND ur.FK_USER_ID=$user_id";

		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			if(oci_fetch_assoc($stid)["COUNT"] > 0) {
				return true;
			}
		}

		return false;
	}
?>