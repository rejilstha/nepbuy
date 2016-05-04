<?php
require __DIR__ . '/../connection.php';
require __DIR__ . '/../includes/constants.php';
include('../includes/header.php');

$current_user = $_SESSION["user_session"];

if(!is_logged_in($current_user)) {
	header("Location: /nepbuy/login.php?status=1");
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

// If submitted from edit form
if(isset($_POST["edit-user-submit"])) {
	$user_image = $_FILES["user-image"];
	edit_user($_POST["id"], $_POST["name"], $_POST["email"], $_POST["contact"], $user_image, $USER_FILE_UPLOAD_LOCATION, $CONNECTION);
}

$user = get_user($current_user, $CONNECTION);

if(isset($_POST["change-password"])) {

	if(!check_old_password($user["PK_USER_ID"], $_POST["current-password"], $CONNECTION)) {
		echo "Wrong current password";
	}
	else if(!check_new_confirm_password($_POST["new-password"], $_POST["confirm-password"], $CONNECTION)) {
		echo "New password doesn't match";
	}
	else {
		$sqlString = "UPDATE nepbuy_users SET PASSWORD='".md5($_POST["confirm-password"]).
		"' WHERE PK_USER_ID=".$user["PK_USER_ID"];
		$stid = oci_parse($CONNECTION, $sqlString);
		oci_execute($stid);
	}
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

function edit_user($id, $name, $email, $contact, $image, $upload_location, $connection) {
	$contact = ($contact == '' ? 'NULL' : $contact);

	if($image["name"] == '')
	{
		$sqlString = "UPDATE nepbuy_users SET ".
		"NAME='$name',EMAIL='$email',CONTACT=$contact ".
		"WHERE PK_USER_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}
	else
	{
		$image_location = $upload_location . basename($image["name"]);
		move_uploaded_file($image["tmp_name"], $image_location);

		$sqlString = "UPDATE nepbuy_users SET ".
		"NAME='$name',EMAIL='$email',CONTACT=$contact,PHOTO_LOCATION='$image_location' ".
		"WHERE PK_USER_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}
}

function get_user($id, $connection) {
	$sqlString = "SELECT * FROM nepbuy_users WHERE PK_USER_ID = $id";
	$stid = oci_parse($connection, $sqlString);
	if(oci_execute($stid) > 0) {
		return oci_fetch_assoc($stid);
	}
}

?>

<!-- hero page -->
<section id="profileD">
	
	<div class="container profileinfo">


		<div class="col-sm-3">
			<img src="<?php echo $user['PHOTO_LOCATION']; ?>" width="200px" height="200px">
		</div>
		<div class="col-sm-8">

			<div class="profile-info">
				<h2>Profile Information Edit</h2>
				<div class="col-sm-6">
					<form method="post" enctype="multipart/form-data">
						<input name="id" type="hidden" value="<?php echo $user["PK_USER_ID"]; ?>">
						<div>
							Name: <input required class="inputfield" name="name" type="text" placeholder="Full Name" value="<?php echo $user["NAME"]; ?>" required/>
						</div>
						<div>
							Email: <input required class="inputfield" name="email" type="text" placeholder="Email" value="<?php echo $user["EMAIL"]; ?>" required/>
						</div>
						<div>
							Contact no: <input class="inputfield" name="contact" type="text" placeholder="Contact no." value="<?php echo $user["CONTACT"]; ?>">
						</div>
						<div>
							Photo: <input class="inputfield" type="file" name="user-image" accept=".png,.jpg,.jpeg,.bmp,.gif">
						</div>
						<input class="btn-submit" name="edit-user-submit" type="submit" value="Save"/>
					</form>
				</div>
				<div class="col-sm-6">
					<form method="post">
						<div>
							Current password: <input required class="inputfield" name="current-password" placeholder="Current Password" type="password">
						</div>
						<div>
							New password: <input required class="inputfield" name="new-password" placeholder="New Password" type="password">
						</div>
						<div>
							Confirm password: <input required class="inputfield" name="confirm-password" placeholder="Confirm Password" type="password">
						</div>
						<div>
							<input class="btn-submit" name="change-password" type="submit" value="Change password">
						</div>
					</form>
				</div>
			</div>
			
		</div>	
	</div>	
</div>   


</section>		
<!-- hero page  -->


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

<?php include('../includes/footer.php'); ?>


