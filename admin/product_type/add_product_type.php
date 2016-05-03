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
	<form method="post" action="product_types.php">
		<input name="product-type-name" type="text" value="" placeholder="Product type name" required>
		<select name="fk-parent-id">
			<option value="">None</option>
			<?php
			foreach ($product_types as $product_type) {
			?>
				<option value="<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $product_type["NAME"]; ?></option>
			<?php 
			} 
			?>
		</select>
		<input type="submit" name="create-product-type-submit" value="Create product type">
	</form>
</div>

<?php
	include("includes/footer.php");
?>