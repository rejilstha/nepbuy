<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
if(!(require __DIR__ . '/../admin_access.php')) {
	return;
}
require __DIR__ . '/../../includes/constants.php';

if(!isset($_GET["id"]))
{
	echo "No user"; //404
	return;
}

$type = isset($_GET["type"]) ? $_GET["type"] : "Customer";
$roles = get_roles($CONNECTION);

function get_roles($connection) {
	$sqlString = "SELECT * FROM nepbuy_roles";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$roles = array();
	while($role = oci_fetch_assoc($stid)) {
		array_push($roles, $role);
	}
	return $roles;
}

// If submitted from edit form
if(isset($_POST["edit-user-submit"])) {
	$user_image = $_FILES["user-image"];
	edit_user($_POST["id"], $_POST["name"], $_POST["email"], $_POST["username"], $_POST["role"], $_POST["contact"], $user_image, $USER_FILE_UPLOAD_LOCATION, $CONNECTION);
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

function edit_user($id, $name, $email, $username, $role, $contact, $image, $upload_location, $connection) {
	$contact = ($contact == '' ? 'NULL' : $contact);

	if($image["name"] == '') {
		$sqlString = "UPDATE nepbuy_users SET ".
				"NAME='$name',EMAIL='$email',CONTACT=$contact ".
				"WHERE PK_USER_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

	}
	else {
		$location = "../../uploads/users/". basename($image["name"]);
		$image_location = $upload_location . basename($image["name"]);
		move_uploaded_file($image["tmp_name"], $location);

		$sqlString = "UPDATE nepbuy_users SET ".
		"NAME='$name',EMAIL='$email',USERNAME='$username',CONTACT=$contact,PHOTO_LOCATION='$image_location' ".
		"WHERE PK_USER_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	// Edit the role of the user.
	$sqlString = "UPDATE nepbuy_user_roles SET FK_ROLE_ID=$role WHERE FK_USER_ID=$id";
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

<div class="container-fluid-full">
	<div class="row-fluid">

		<?php require __DIR__.'/../includes/nav.php'; ?>

		<!-- start: Content -->
		<div id="content" class="span10">				
			<ul class="breadcrumb">
				<li>
					<i class="icon-home"></i>
					<a href="index.html">Home</a>
					<i class="icon-angle-right"></i>
				</li>
				<li><a href="index.php"><?php echo $type; ?></a></li>
			</ul>

			<div class="row-fluid">
				<img src="<?php echo $user['PHOTO_LOCATION']; ?>" height="200px">
			</div>

			<div class="row-fluid">
				<form method="post" enctype="multipart/form-data">
					<input name="id" type="hidden" value="<?php echo $user["PK_USER_ID"]; ?>">
					<div class="form-group">
						<label for="name">Full name</label>
						<input class="form-control" name="name" type="text" placeholder="Full Name" value="<?php echo $user["NAME"]; ?>" required/>
					</div>
					<div class="form-group">
						<label for="email">Email Address</label>
						<input class="form-control" name="email" type="email" placeholder="Email" value="<?php echo $user["EMAIL"]; ?>" required/>
					</div>
					<div class="form-group">
						<label for="username">Username</label>
						<input class="form-control" name="username" type="text" placeholder="Username" value="<?php echo $user["USERNAME"]; ?>" required/>
					</div>
					<div class="form-group">
						<label for="contact">Contact no.</label>
						<input class="form-control" name="contact" type="text" placeholder="Contact no." value="<?php echo $user["CONTACT"]; ?>">
					</div>
					<div class="form-group">
						<label for="file">Photo</label>
						<input class="form-control" type="file" name="user-image" accept=".png,.jpg,.jpeg,.bmp,.gif">
					</div>
					<div class="form-group">
						<label for="role">Role</label>
						<select class="form-control" name="role" required>
							<?php
							foreach ($roles as $role) {
								if($type == $role["NAME"]) {
									?>
									<option selected value="<?php echo $role["PK_ROLE_ID"]; ?>"><?php echo $role["NAME"]; ?></option>	
									<?php
								} else {
									?>
									<option value="<?php echo $role["PK_ROLE_ID"]; ?>"><?php echo $role["NAME"]; ?></option>	
									<?php
								}
							} 
							?>
						</select>
					</div>
					<input class="add-btn" name="edit-user-submit" type="submit" value="Edit user">
				</form>

				<form method="post">
					<input name="id" type="hidden" value="<?php echo $user["PK_USER_ID"]; ?>">
					<input class="btn btn-default" name="reset-user-password" type="submit" value="Reset password">
				</form>

				<form method="post">
					<input name="id" type="hidden" value="<?php echo $user["PK_USER_ID"]; ?>">
					<div class="submit-btn">
						<input class="btn btn-danger" name="delete-user" type="submit" value="Delete">
						<a class="btn btn-default" href="index.php?type=<?php echo $type; ?>">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
require __DIR__ ."/../includes/footer.php";
?>