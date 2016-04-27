<?php
	include("connection.php");

	// Submitted from the add shop.
	if(isset($_POST["create-shop-submit"])) {
		add_shop(
			$_POST["name"], $_POST["location"], $_POST["trader"], $CONNECTION);
	}

	function add_shop(
		$shop_name, $location, $trader, $connection) {

		$sqlString = "INSERT INTO nepbuy_shops(NAME,LOCATION,FK_USER_ID) VALUES('".
					$shop_name."','".$location."',".$trader.")";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function get_shop_trader($trader, $connection) {
		$sqlString = "SELECT * FROM nepbuy_users WHERE PK_USER_ID=".$trader;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		return oci_fetch_assoc($stid);
	}


	$sqlString = "SELECT * FROM nepbuy_shops ORDER BY PK_SHOP_ID";
	$stid = oci_parse($CONNECTION, $sqlString);
	if(oci_execute($stid) > 0) {
		?>
		<table>
			<tr>
				<th>Name</th>
				<th>Location</th>
				<th>Shop Owner</th>
			</tr>
			<?php
			while($row = oci_fetch_assoc($stid)) {
				$trader = get_shop_trader($row['FK_USER_ID'], $CONNECTION);
			?>
				<tr>
					<td><?php echo $row['NAME']; ?></td>
					<td><?php echo $row['LOCATION']; ?></td>
					<td><?php echo $trader['NAME']; ?></td>
				</tr>
			<?php
			}
			?>
		</table>
	<?php
	}
?>