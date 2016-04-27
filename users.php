<?php
	include("connection.php");

	if(isset($_POST["create-user-submit"])) {
		add_user($_POST["username"],$_POST["email"], $_POST["role"], $CONNECTION);
	}

	function add_user($username, $email, $role, $connection) {
		// Generate random password
		$random_password = 'random';

		// Add the user to the users table.
		$sqlString = "INSERT INTO nepbuy_users(NAME,CONTACT,EMAIL,PASSWORD) VALUES('".$username."',12345678900,'".$email."','".$random_password."')";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		// Get newly added user id.
		$sqlString = "SELECT PK_USER_ID FROM nepbuy_users WHERE NAME='".$username."'";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$added_user_id = oci_fetch_assoc($stid);

		// Add the role to the new user.
		$sqlString = "INSERT INTO nepbuy_user_roles(FK_USER_ID,FK_ROLE_ID) VALUES(".$added_user_id["PK_USER_ID"].','.$role.")";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	


}

	$sqlusr = "SELECT * FROM nepbuy_users ORDER BY PK_USER_ID";
		$stid1 = oci_parse($CONNECTION, $sqlusr);
		if(oci_execute($stid1)>0){
		while($row = oci_fetch_assoc($stid1)){
		//echo $sqlusr;exit;
		echo $row['NAME'];
		echo $row['CONTACT'];
		echo $row['EMAIL'];

				}
			}
?>