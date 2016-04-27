<?php
	include("connection.php");
	//include("includes/header.php");


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

<div>
	<form method="post" action="products.php">
		<input name="product-name" type="text" value="" placeholder="Product name" required>
		<input name="description" type="text" value="" placeholder="Description">
		<input name="price" type="number" value="" placeholder="Price" required>
		<input name="qty-per-item" type="number" value="" placeholder="Quantity per item" required>
		<input name="stock-available" type="number" value="" placeholder="Stock available" required>
		<input name="min-order" type="number" value="" placeholder="Min order">
		<input name="max-order" type="number" value="" placeholder="Max order">
		<input name="allergy-info" type="text" value="" placeholder="Allergy info">
		<select name="fk-shop-id" required>
			<?php
			foreach ($shops as $shop) {
			?>
				<option value="<?php echo $shop["PK_SHOP_ID"]; ?>"><?php echo $shop["NAME"]; ?></option>
			<?php 
			} 
			?>
		</select>
		<select name="product-type" required>
			<?php
			foreach ($product_types as $product_type) {
			?>
				<option value="<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $product_type["NAME"]; ?></option>
			<?php 
			} 
			?>
		</select>
		<input type="submit" name="create-product-submit" value="Create product">
	</form>
</div>

<?php
	include("includes/footer.php");
?>