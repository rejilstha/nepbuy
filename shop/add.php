<?php
	require __DIR__ . "/../connection.php";
	require __DIR__ . "/../includes/constants.php";
	//include("includes/header.php");

	if(isset($_POST["create-shop-submit"])) {
		add_shop(
			$_POST["name"], $_POST["location"], $_POST["trader"], $MAX_SHOPS_ALLOWED, $CONNECTION);
	}

	function add_shop(
		$shop_name, $location, $trader, $max_shops_allowed, $connection) {

		$sqlString = "SELECT COUNT(*) as COUNT FROM nepbuy_shops";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			$count = oci_fetch_assoc($stid)["COUNT"];
			if($count == $max_shops_allowed) {
				echo "Max shop limit reached.";
				return;
			}
		}

		$sqlString = "INSERT INTO nepbuy_shops(NAME,LOCATION,FK_USER_ID) VALUES('".
					$shop_name."','".$location."',".$trader.")";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

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
	<form method="post" action="add.php">
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
	//include(__DIR__."/../includes/footer.php");
?>