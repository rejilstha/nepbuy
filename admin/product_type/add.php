<?php
require __DIR__ ."/../../connection.php";
require __DIR__ . '/../admin_access.php';
require __DIR__ ."/../includes/header.php";

$product_types = get_product_types($CONNECTION);
$shops = get_shops($CONNECTION);

function get_product_types($connection) {
	$sqlString = "SELECT * FROM nepbuy_product_types";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$product_types = array();
	while($product_type = oci_fetch_assoc($stid)) {
		array_push($product_types, $product_type);
	}
	return $product_types;
}

function get_shops($connection) {
	$sqlString = "SELECT * FROM nepbuy_shops";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$shops = array();
	while($shop = oci_fetch_assoc($stid)) {
		array_push($shops, $shop);
	}
	return $shops;	
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
				<li><a href="#">Product types</a></li>
			</ul>

			<div class="row-fluid">
				<div>
					<form method="post" action="index.php">
						<div class="form-group">
							<label for="product-type-name">Product type</label>
							<input class="form-control" name="product-type-name" type="text" value="" placeholder="Name" required>
						</div>
						<div class="form-group">
							<label for="fk-parent-id">Parent</label>
							<select name="fk-parent-id" class="form-control">
								<option value="">None</option>
								<?php
								foreach ($product_types as $product_type) {
									?>
									<option value="<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $product_type["NAME"]; ?></option>
									<?php 
								} 
								?>
							</select>
						</div>
						<div class="submit-btn">
							<input type="submit" class="add-btn" name="create-product-type-submit"value="Save product type" />
							<a class="btn btn-default" href="index.php">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require __DIR__ ."/../includes/footer.php";
?>