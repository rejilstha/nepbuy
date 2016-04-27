<?php
	include("connection.php");
	//include("includes/header.php");


	$traders = get_traders($CONNECTION);

	function get_traders($connection) {
		$trader_id = get_trader_id($connection);

		$sqlString = "SELECT u.PK_USER_ID,u.NAME FROM nepbuy_user_roles ur ".
					"JOIN nepbuy_users u ON u.PK_USER_ID=ur.FK_USER_ID ".
					"WHERE FK_ROLE_ID=".$trader_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$traders = array();
		while($trader = oci_fetch_assoc($stid)) {
			array_push($traders, $trader);
		}
		return $traders;
	}

	function get_trader_id($connection) {
		$sqlString = "SELECT PK_ROLE_ID FROM nepbuy_roles WHERE NAME='Trader'";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		return oci_fetch_assoc($stid)["PK_ROLE_ID"];
	}
?>

<div>
	<form method="post" action="shops.php">
		<input name="name" type="text" value="" placeholder="Name of the shop" required>
		<input name="location" type="text" value="" placeholder="Location">
		<select name="trader" required>
			<?php
				foreach ($traders as $trader) {
					?>
					<option value="<?php echo $trader["PK_USER_ID"]; ?>"><?php echo $trader["NAME"]; ?></option>
				 <?php 
				} 
				?>
		</select>
		<input type="submit" name="create-shop-submit" value="Create shop">
	</form>
</div>

<?php
	include("includes/footer.php");
?>