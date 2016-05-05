<?php
require __DIR__ . '/../connection.php';
require __DIR__ . '/../trader_access.php';
require __DIR__ . '/../includes/header.php';

$user_id = $_SESSION["user_session"];

$pending_orders = get_pending_orders($user_id, $CONNECTION);

function get_pending_orders($user_id, $connection) {
	$sqlString = "SELECT o.*,p.NAME AS PRODUCT_NAME, p.PRICE AS PRODUCT_PRICE, cd.NAME AS COLLECTION_DAY, cs.START_TIME, cs.END_TIME FROM nepbuy_orders o ".
	"JOIN nepbuy_products p ON o.FK_PRODUCT_ID=p.PK_PRODUCT_ID ".
	"JOIN nepbuy_collection_days_slots cds ON o.FK_COLLECTION_DAY_SLOT_ID=cds.PK_COLLECTION_DAY_SLOT_ID ".
	"JOIN nepbuy_collection_days cd ON cds.FK_COLLECTION_DAY_ID=cd.PK_COLLECTION_DAY_ID ".
	"JOIN nepbuy_collection_slots cs ON cds.FK_COLLECTION_SLOT_ID=cs.PK_COLLECTION_SLOT_ID ".
	"JOIN nepbuy_shops s ON p.FK_SHOP_ID=s.PK_SHOP_ID ".
	"WHERE o.STATUS = 'pending' AND s.FK_USER_ID=$user_id";

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

<!-- hero page -->
<section id="hero-page1">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">
				<h2 class="title">Pending reports</h2>
			</div>

		</div>

	</div>            
</section>	

<div class="row">
	<div class="container">
		<table class="table table-striped">
			<thead>
				<th>Product name</th>
				<th>Product quantity</th>
				<th>Product price</th>
				<th>Ordered date</th>
				<th>Delivery day</th>
				<th>Status</th>
				<th>Sub total</th>
			</thead>
			<tbody>
				<?php
				$total = 0;
				foreach ($pending_orders as $order) {
					?>
					<tr>
						<td><?php echo $order["PRODUCT_NAME"];?></td>
						<td><?php echo $order["PRODUCT_QUANTITY"];?></td>
						<td><?php echo $order["PRODUCT_PRICE"];?></td>
						<td><?php echo $order["ORDERED_DATE"];?></td>
						<td><?php echo $order["COLLECTION_DAY"] ." (". $order["START_TIME"] ." - ". $order["END_TIME"];?>)</td>
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
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>