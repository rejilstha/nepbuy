<?php
require __DIR__ . "/../connection.php";
if(!(require __DIR__ . '/../trader_access.php')) {
	return;
}
require __DIR__ . "/../includes/header.php";

$trader = $_SESSION["user_session"];

$product_types = get_product_types($CONNECTION);
$shops = get_shops($trader, $CONNECTION);

function get_shops($trader, $connection) {
	$sqlString = "SELECT * FROM nepbuy_shops WHERE FK_USER_ID=$trader";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$shops = array();
	while($shop = oci_fetch_assoc($stid)) {
		array_push($shops, $shop);
	}
	return $shops;	
}
?>
<section id="hero-page1">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">
				<h2 class="title">Product Info</h2>
			</div>

		</div>
		
	</div>            
</section>
<!-- product special / latest -->
<section id="special-offer">		
	<div class="row">
		<div class="container">	
			<form method="post" action="products.php">
				<div class="form-group">
					<label for="product-name">Product</label>
					<input class="form-control" name="product-name" type="text" value="" placeholder="Product name" required>
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<input class="form-control" name="description" type="text" value="" placeholder="Description">
				</div>
				<div class="form-group">
					<label for="price">Price</label>				
					<input class="form-control" name="price" type="number" value="" placeholder="Price" required>
				</div>
				<div class="form-group">
					<label for="qty-per-item">Quantity per item</label>
					<input class="form-control" name="qty-per-item" type="number" value="" placeholder="Quantity per item" required>
				</div>
				<div class="form-group">
					<label for="stock-available">How many?</label>
					<input class="form-control" name="stock-available" type="number" value="" placeholder="Stock available" required>
				</div>
				<div class="form-group">
					<label for="min-order">Min order</label>
					<input class="form-control" name="min-order" type="number" value="" placeholder="Min order">
				</div>
				<div class="form-group">
					<label for="max-order">Max order</label>
					<input class="form-control" name="max-order" type="number" value="" placeholder="Max order">
				</div>
				<div class="form-group">
					<label for="allergy-info">Allergy info</label>
					<input class="form-control" name="allergy-info" type="text" value="" placeholder="Allergy info">
				</div>
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
				<input class="cartadd" type="submit" name="create-product-submit" value="Create product">
			</form>
		</div>
	</div>
</section>

<?php
require __DIR__ . "/../includes/footer.php";
?>