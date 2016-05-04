<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
require __DIR__ ."/../admin_access.php";


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
				<li><a href="index.php">Products</a></li>
			</ul>

			<div class="row-fluid">
				<form method="post" action="index.php" enctype="multipart/form-data">
					<div class="row-fluid">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="product-name">Product</label>
								<input class="form-control" name="product-name" type="text" value="" placeholder="Product name" required>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="description">Description</label>
								<input class="form-control" name="description" type="text" value="" placeholder="Description">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="price">Price</label>
								<input class="form-control" name="price" type="number" value="" placeholder="Price" required>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="qty-per-item">Quantity per item</label>
								<input class="form-control" name="qty-per-item" type="number" value="" placeholder="Quantity per item" required>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="stock-available">How many?</label>
								<input class="form-control" name="stock-available" type="number" value="" placeholder="Stock available" required>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="min-order">Min order</label>
								<input class="form-control" name="min-order" type="number" value="" placeholder="Min order">
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="max-order">Max order</label>
								<input class="form-control" name="max-order" type="number" value="" placeholder="Max order">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="allergy-info">Allergy info</label>
								<input class="form-control" name="allergy-info" type="text" value="" placeholder="Allergy info">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="fk-shop-id">Shop</label>
								<select class="form-control" name="fk-shop-id" required>
									<?php
									foreach ($shops as $shop) {
										?>
										<option value="<?php echo $shop["PK_SHOP_ID"]; ?>"><?php echo $shop["NAME"]; ?></option>
										<?php 
									} 
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="col-sm-4">
							<div class="form-group">
								<label for="product-type">Product type</label>
								<select class="form-control" name="product-type" required>
									<?php
									foreach ($product_types as $product_type) {
										?>
										<option value="<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $product_type["NAME"]; ?></option>
										<?php 
									} 
									?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label for="product-image">Product image</label>
								<input class="form-control" type="file" name="product-image" accept=".png,.jpg,.jpeg,.bmp,.gif">
							</div>
						</div>
					</div>
					<div class="submit-btn">
						<input class="add-btn" type="submit" name="create-product-submit" value="Create product">
						<a class="btn btn-default" href="index.php">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
require __DIR__ ."/../includes/footer.php";
?>