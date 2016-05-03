<?php
	include("connection.php");
	//include("includes/header.php");


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

<div>
	<form method="post" action="users.php">
		<input name="username" type="text" value="" placeholder="Username" required>
		<input name="email" type="email" value="" placeholder="Email" required>
		<select name="role" required>
			<?php
				foreach ($roles as $role) {
					?>
					<option value="<?php echo $role["PK_ROLE_ID"]; ?>"><?php echo $role["NAME"]; ?></option>
				 <?php 
				} 
				?>
		</select>
		<input type="submit" name="create-user-submit" value="Create user">
	</form>
</div>

<?php
	include("includes/footer.php");
?>