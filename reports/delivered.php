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

	$interval = 7;
	$completed_orders = get_completed_orders($user_id, $interval, $CONNECTION);

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

	function get_completed_orders($user_id, $interval, $connection) {
		$sqlString = "SELECT o.PK_ORDER_ID,o.PRODUCT_QUANTITY, TO_CHAR(o.ORDERED_DATE, 'MM/DD/YYYY') AS ORDERED_DATE, TO_CHAR(o.DELIVERED_DATE, 'MM/DD/YYYY') AS DELIVERED_DATE, o.STATUS".
				",p.NAME AS PRODUCT_NAME, p.PRICE AS PRODUCT_PRICE, cd.NAME AS COLLECTION_DAY, cs.START_TIME, cs.END_TIME FROM nepbuy_orders o ".
					"JOIN nepbuy_products p ON o.FK_PRODUCT_ID=p.PK_PRODUCT_ID ".
					"JOIN nepbuy_collection_days_slots cds ON o.FK_COLLECTION_DAY_SLOT_ID=cds.PK_COLLECTION_DAY_SLOT_ID ".
					"JOIN nepbuy_collection_days cd ON cds.FK_COLLECTION_DAY_ID=cd.PK_COLLECTION_DAY_ID ".
					"JOIN nepbuy_collection_slots cs ON cds.FK_COLLECTION_SLOT_ID=cs.PK_COLLECTION_SLOT_ID ".
					"JOIN nepbuy_shops s ON p.FK_SHOP_ID=s.PK_SHOP_ID ".
					"WHERE o.STATUS = 'delivered' AND s.FK_USER_ID=$user_id AND o.DELIVERED_DATE > (CURRENT_TIMESTAMP - INTERVAL '$interval' DAY) AND o.DELIVERED_DATE < CURRENT_TIMESTAMP";

		$orders = array();

		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			while($order = oci_fetch_assoc($stid)) {
				array_push($orders, $order);
			}
		}

		return $orders;
	}
?>

<div>
	<table>
		<thead>
			<th>Product name</th>
			<th>Product quantity</th>
			<th>Product price</th>
			<th>Ordered date</th>
			<th>Delivered date</th>
			<th>Status</th>
			<th>Sub total</th>
		</thead>
		<tbody>
			<?php
			$total = 0;
			foreach ($completed_orders as $order) {
				?>
				<tr>
					<td><?php echo $order["PRODUCT_NAME"];?></td>
					<td><?php echo $order["PRODUCT_QUANTITY"];?></td>
					<td><?php echo $order["PRODUCT_PRICE"];?></td>
					<td><?php echo $order["ORDERED_DATE"];?></td>
					<td><?php echo $order["DELIVERED_DATE"];?></td>
					<td><?php echo $order["STATUS"]; ?></td>
					<td><?php 
						$sub_total = $order["PRODUCT_QUANTITY"] * $order["PRODUCT_PRICE"]; 
						$total = $total + $sub_total; 
						echo $sub_total; 
					?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<th>Total</th>
				<th><?php echo $total; ?></th>
			</tr>
		</tfoot>
	</table>
</div>