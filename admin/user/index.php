<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
if(!(require __DIR__ . '/../admin_access.php')) {
	return;
}

$type = isset($_GET["type"]) ? $_GET["type"] : "Customer";

	// Submitted from the add user.
if(isset($_POST["create-user-submit"])) {
	$user_image = $_FILES["user-image"];
	add_user($_POST["name"], $_POST["email"], $_POST["username"], $_POST["role"], $_POST["contact"], $user_image, $USER_FILE_UPLOAD_LOCATION, $CONNECTION);
}

function add_user($name, $email, $username, $role, $contact, $image, $upload_location, $connection) {
		// Generate random password
	$random_password = 'random';
	$contact = ($contact == '' ? 'NULL' : $contact);

	if($image["name"] == '') {

		// Add the user to the users table.
		$sqlString = "INSERT INTO nepbuy_users(NAME,CONTACT,EMAIL,USERNAME,PASSWORD) VALUES('$name',$contact,'$email','$username',$random_password')";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	} else {
		$location = "../../uploads/users/". basename($image["name"]);
		$image_location = $upload_location . basename($image["name"]);
		move_uploaded_file($image["tmp_name"], $location);
		
		// Add the user to the users table.
		$sqlString = "INSERT INTO nepbuy_users(NAME,CONTACT,EMAIL,PASSWORD,PHOTO_LOCATION) VALUES('$name',$contact,'$email','$username',$random_password','$image_location')";
		$stid = oci_parse($connection, $sqlString);
	}

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


$sqlusr = "SELECT u.* FROM nepbuy_users u ".
		"JOIN nepbuy_user_roles ur ON u.PK_USER_ID=ur.FK_USER_ID ".
		"JOIN nepbuy_roles r ON r.PK_ROLE_ID=ur.FK_ROLE_ID ".
		"WHERE r.NAME='$type' ORDER BY u.PK_USER_ID";
$stid1 = oci_parse($CONNECTION, $sqlusr);
if(oci_execute($stid1) > 0) {
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
					<li><a href="#"><?php echo $type."s"; ?></a></li>
				</ul>

				<div class="row-fluid">
					<div class="span12" onTablet="span12" onDesktop="span12">
					<h2 class="headV">List <?php echo $type."s"; ?></h2>
					<a class="add-btn" href="add.php?type=<?php echo $type; ?>"><i class="icon-plus"></i> Add New</a>
					<table class="table table-striped">
						<tr>
							<th>Name</th>
							<th>Contact</th>
							<th>Email</th>
							<th></th>
						</tr>
						<?php
						while($row = oci_fetch_assoc($stid1)) {
							?>
							<tr>
								<td><?php echo $row['NAME']; ?></td>
								<td><?php echo $row['CONTACT']; ?></td>
								<td><?php echo $row['EMAIL']; ?></td>
								<td><a class="btn btn-default" href="edit.php?id=<?php echo $row["PK_USER_ID"];?>&type=<?php echo $type; ?>"><i class="icon-edit"></i> Edit</a></td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php
}
require __DIR__ ."/../includes/footer.php";
?>