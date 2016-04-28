<?php
	require __DIR__ . '/../connection.php';

	$user_id = $_SESSION["user_session"];

	if (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"])) {
	  // User session is a guid and user isn't registered
		header("Location: login.php?status=1");
	}

	if(!is_trader_or_admin($user_id, $CONNECTION)) {
		echo "Access denied";
		return;
	}

	$products = get_products($user_id, $CONNECTION);

	function is_trader_or_admin($user_id, $connection) {
		$sqlString = "SELECT COUNT(*) AS COUNT FROM nepbuy_user_roles ur ".
					"JOIN nepbuy_roles r ON ur.FK_ROLE_ID=r.PK_ROLE_ID ".
					"WHERE ur.FK_USER_ID=$user_id AND (r.NAME='Trader' OR r.NAME='Admin')";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid)) {
			return oci_fetch_assoc($stid)["COUNT"] > 0;
		}
		return false;
	}

	function get_products($user_id, $connection) {
		$sqlString = "SELECT p.* FROM nepbuy_products p ".
					"JOIN nepbuy_shops s ON p.FK_SHOP_ID=s.PK_SHOP_ID ".
					"WHERE s.FK_USER_ID=$user_id";

		$products = array();

		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			while($product = oci_fetch_assoc($stid)) {
				array_push($products, $product);
			}
		}

		return $products;
	}
?>

<div>
	<table>
		<thead>
			<th>Product name</th>
			<th>Stock available</th>
		</thead>
		<tbody>
			<?php
			$total = 0;
			foreach ($products as $product) {
				?>
				<tr>
					<td><?php echo $product["NAME"];?></td>
					<td><?php echo $product["STOCK_AVAILABLE"];?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>