<?php
	require __DIR__ . '/../connection.php';
	require __DIR__ . '/../includes/constants.php';

	if(!isset($_GET["id"]))
	{
		echo "No user"; //404
		return;
	}

	// If submitted from edit form
	if(isset($_POST["edit-user-submit"])) {
		$user_image = $_FILES["user-image"];
		edit_user($_POST["id"], $_POST["name"], $_POST["email"], $_POST["username"], $_POST["contact"], $user_image, $USER_FILE_UPLOAD_LOCATION, $CONNECTION);
	}

	// If submitted from reset password form
	if(isset($_POST["reset-user-password"])) {
		reset_password($_POST["id"], $CONNECTION);
	}

	// If submitted from delete form
	if(isset($_POST["delete-user"])) {
		delete_user($_POST["id"], $CONNECTION);
		header("Location: users.php");
	}

	$user = get_user($_GET["id"], $CONNECTION);
	if($user == NULL)
	{
		echo "No user";
		return;
	}

	function delete_user($id, $connection) {
		$sqlString = "DELETE FROM nepbuy_users WHERE PK_USER_ID=$id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function reset_password($id, $connection) {
		$randomPassword = md5("random");
		$sqlString = "UPDATE nepbuy_users SET ".
					"PASSWORD='$randomPassword' WHERE PK_USER_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function edit_user($id, $name, $email, $username, $contact, $image, $upload_location, $connection) {
		$contact = ($contact == '' ? 'NULL' : $contact);

		if($image["name"] == '')
			$image_location = '';
		else
			$image_location = $upload_location . basename($image["name"]);

		move_uploaded_file($image["tmp_name"], $image_location);

		$sqlString = "UPDATE nepbuy_users SET ".
					"NAME='$name',EMAIL='$email',USERNAME='$username',CONTACT=$contact,PHOTO_LOCATION='$image_location' ".
					"WHERE PK_USER_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function get_user($id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_users WHERE PK_USER_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return oci_fetch_assoc($stid);
		}
	}
?>

<form method="post" enctype="multipart/form-data">
	<input name="id" type="hidden" value="<?php echo $user["PK_USER_ID"]; ?>">
	<input name="name" type="text" placeholder="Full Name" value="<?php echo $user["NAME"]; ?>" required/>
	<input name="email" type="text" placeholder="Email" value="<?php echo $user["EMAIL"]; ?>" required/>
	<input name="username" type="text" placeholder="Username" value="<?php echo $user["USERNAME"]; ?>" required/>
	<input name="contact" type="text" placeholder="Contact no." value="<?php echo $user["CONTACT"]; ?>">
	<img src="<?php echo $user['PHOTO_LOCATION']; ?>">
	<input type="file" name="user-image" accept=".png,.jpg,.jpeg,.bmp,.gif">
	<input name="edit-user-submit" type="submit" value="Edit user">
</form>

<form method="post">
	<input name="id" type="hidden" value="<?php echo $user["PK_USER_ID"]; ?>">
	<input name="reset-user-password" type="submit" value="Reset password">
</form>

<form method="post">
	<input name="id" type="hidden" value="<?php echo $user["PK_USER_ID"]; ?>">
	<input name="delete-user" type="submit" value="Delete">
</form>