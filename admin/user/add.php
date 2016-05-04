<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
require __DIR__ ."/../admin_access.php";

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
				<form method="post" action="index.php?type=<?php echo $type; ?>" enctype="multipart/form-data">
					<div class="form-group">
						<label for="name">Full name</label>
						<input class="form-control" name="name" type="text" placeholder="Full Name" value="" required/>
					</div>
					<div class="form-group">
						<label for="username">Username</label>
						<input class="form-control" name="username" type="text" value="" placeholder="Username" required>
					</div>
					<div class="form-group">
						<label for="email">Email Address</label>
						<input class="form-control" name="email" type="email" value="" placeholder="Email" required>
					</div>
					<div class="form-group">
						<label for="contact">Contact no.</label>
						<input class="form-control" name="contact" type="text" placeholder="Contact no." value="">
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
					<div class="submit-btn">
						<input class="add-btn" type="submit" name="create-user-submit" value="Create user">
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