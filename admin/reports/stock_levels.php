<?php
require __DIR__ . '/../../connection.php';
require __DIR__ . '/../admin_access.php';
require __DIR__ . '/../includes/header.php';

$products = get_products($CONNECTION);

function get_products($connection) {
	$sqlString = "SELECT p.* FROM nepbuy_products p ".
	"JOIN nepbuy_shops s ON p.FK_SHOP_ID=s.PK_SHOP_ID ";

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
				<li><a href="#">Stock levels</a></li>
			</ul>

			<div class="row-fluid">
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
		</div>
	</div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>