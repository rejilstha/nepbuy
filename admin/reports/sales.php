<?php
require __DIR__ . '/../../connection.php';
require __DIR__ . '/../admin_access.php';
require __DIR__ . '/../includes/header.php';

$interval = 30;
$completed_orders = get_completed_orders($interval, $CONNECTION);

function get_completed_orders($interval, $connection) {
	$sqlString = "SELECT o.PK_ORDER_ID,o.PRODUCT_QUANTITY, TO_CHAR(o.ORDERED_DATE, 'MM/DD/YYYY') AS ORDERED_DATE, TO_CHAR(o.DELIVERED_DATE, 'MM/DD/YYYY') AS DELIVERED_DATE, o.STATUS".
	",p.NAME AS PRODUCT_NAME, p.PRICE AS PRODUCT_PRICE, cd.NAME AS COLLECTION_DAY, cs.START_TIME, cs.END_TIME FROM nepbuy_orders o ".
	"JOIN nepbuy_products p ON o.FK_PRODUCT_ID=p.PK_PRODUCT_ID ".
	"JOIN nepbuy_collection_days_slots cds ON o.FK_COLLECTION_DAY_SLOT_ID=cds.PK_COLLECTION_DAY_SLOT_ID ".
	"JOIN nepbuy_collection_days cd ON cds.FK_COLLECTION_DAY_ID=cd.PK_COLLECTION_DAY_ID ".
	"JOIN nepbuy_collection_slots cs ON cds.FK_COLLECTION_SLOT_ID=cs.PK_COLLECTION_SLOT_ID ".
	"JOIN nepbuy_shops s ON p.FK_SHOP_ID=s.PK_SHOP_ID ".
	"WHERE o.STATUS = 'delivered' AND o.DELIVERED_DATE > (CURRENT_TIMESTAMP - INTERVAL '$interval' DAY) AND o.DELIVERED_DATE < CURRENT_TIMESTAMP";

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
				<li><a href="#">Sales</a></li>
			</ul>

			<div class="row-fluid">
				<table class="table table-striped">
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
			</div>
		</div>
	</div>


	<?php require __DIR__ . '/../includes/footer.php'; ?>