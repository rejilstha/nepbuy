<?php
	require __DIR__ . '/../connection.php';
	require __DIR__ . '/../includes/constants.php';

	if(!isset($_GET["id"]))
	{
		echo "No product"; //404
		return;
	}

	// If submitted from edit form
	if(isset($_POST["edit-product-submit"])) {
		$product_image = $_FILES["product-image"];
		edit_product(
			$_POST["id"],$_POST["product-name"], $_POST["description"], $_POST["price"], 
			$_POST["qty-per-item"], $_POST["stock-available"], $_POST["min-order"], 
			$_POST["max-order"], $_POST["allergy-info"], $_POST["fk-shop-id"], 
			$_POST["product-type"], $product_image, $PRODUCT_FILE_UPLOAD_LOCATION, $CONNECTION);
	}

	// If submitted from delete form
	if(isset($_POST["delete-product"])) {
		delete_product($_POST["id"], $CONNECTION);
		header("Location: products.php");
	}

	$product = get_product($_GET["id"], $CONNECTION);
	if($product == NULL)
	{
		echo "No product";
		return;
	}

	$product_types = get_product_types($CONNECTION);
	$shops = get_shops($CONNECTION);

	function delete_product($id, $connection) {
		$sqlString = "DELETE FROM nepbuy_products WHERE PK_PRODUCT_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

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

	function edit_product(
		$id,
		$product_name, $description, $price, $qty_per_item, $stock_available,
		$min_order, $max_order, $allergy_info, $fk_shop_id, $product_type, $product_image, 
		$upload_location, $connection
		) {

		$min_order = ($min_order == '' ? "NULL" : $min_order);
		$max_order = ($max_order == '' ? "NULL" : $max_order);
		if($product_image["name"] == NULL)
			$product_location = '';
		else
			$product_location = $upload_location . basename($product_image["name"]);

		move_uploaded_file($product_image["tmp_name"], $product_location);

		$sqlString = "UPDATE nepbuy_products SET ".
					"NAME='$product_name',DESCRIPTION='$description',PRICE=$price,QUANTITY_PER_ITEM=$qty_per_item,STOCK_AVAILABLE=$stock_available,MIN_ORDER=$min_order,MAX_ORDER=$max_order,ALLERGY_INFO='$allergy_info',FK_SHOP_ID=$fk_shop_id,FK_PRODUCT_TYPE_ID=$product_type,PHOTO_LOCATION='$product_location' ".
					"WHERE PK_PRODUCT_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function get_product($id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_products WHERE PK_PRODUCT_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return oci_fetch_assoc($stid);
		}
	}
?>

<form method="post" enctype="multipart/form-data">
	<input name="id" type="hidden" value="<?php echo $product['PK_PRODUCT_ID']; ?>" />
	<input name="product-name" type="text" value="<?php echo $product['NAME']; ?>" placeholder="Product name" required>
	<input name="description" type="text" value="<?php echo $product['DESCRIPTION']; ?>" placeholder="Description">
	<input name="price" type="number" value="<?php echo $product['PRICE']; ?>" placeholder="Price" required>
	<input name="qty-per-item" type="number" value="<?php echo $product['QUANTITY_PER_ITEM']; ?>" placeholder="Quantity per item" required>
	<input name="stock-available" type="number" value="<?php echo $product['STOCK_AVAILABLE']; ?>" placeholder="Stock available" required>
	<input name="min-order" type="number" value="<?php echo $product['MIN_ORDER']; ?>" placeholder="Min order">
	<input name="max-order" type="number" value="<?php echo $product['MAX_ORDER']; ?>" placeholder="Max order">
	<input name="allergy-info" type="text" value="<?php echo $product['ALLERGY_INFO']; ?>" placeholder="Allergy info">
	<select name="fk-shop-id" required>
		<?php
		foreach ($shops as $shop) {
			if($shop["PK_SHOP_ID"] == $product["FK_SHOP_ID"]) {
			?>
				<option selected value="<?php echo $shop["PK_SHOP_ID"]; ?>"><?php echo $shop["NAME"]; ?></option>
			<?php
			} else {
			?>
				<option value="<?php echo $shop["PK_SHOP_ID"]; ?>"><?php echo $shop["NAME"]; ?></option>
			<?php
			}
		} 
		?>
	</select>
	<select name="product-type" required>
		<?php
		foreach ($product_types as $product_type) {
			if($product_type["PK_PRODUCT_TYPE_ID"] == $product["FK_PRODUCT_TYPE_ID"]) {
			?>
				<option selected value="<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $product_type["NAME"]; ?></option>
			<?php 
			} else {
			?>
				<option value="<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $product_type["NAME"]; ?></option>
			<?php
			}
		} 
		?>
	</select>
	<img src="<?php echo $product['PHOTO_LOCATION']; ?>">
	<input type="file" name="product-image" accept=".png,.jpg,.jpeg,.bmp,.gif">
	<input type="submit" name="edit-product-submit" value="Edit product">
</form>

<form method="post">
	<input name="id" type="hidden" value="<?php echo $product["PK_PRODUCT_ID"]; ?>">
	<input name="delete-product" type="submit" value="Delete">
</form>