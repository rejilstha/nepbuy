<?php
	require __DIR__ . '/../connection.php';
	require __DIR__ . '/../trader_access.php';
	require __DIR__ . '/../includes/header.php';

	$user_id = $_SESSION["user_session"];

	$products = get_products($user_id, $CONNECTION);

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

<!-- hero page -->
<section id="hero-page1">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">
				<h2 class="title">Stock reports</h2>
			</div>

		</div>

	</div>            
</section>	

<div>
	<table class="table table-striped">
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

<?php require __DIR__ . '/../includes/footer.php'; ?>