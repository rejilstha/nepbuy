<?php
	require __DIR__ . '/../connection.php';
	if(!(require __DIR__ . '/../trader_access.php')) {
		return;
	}
	require __DIR__ . '/../includes/constants.php';

	if(!isset($_GET["id"]))
	{
		echo "No product"; //404
		return;
	}

	$trader = $_SESSION["user_session"];

	// If submitted from edit form
	if(isset($_POST["edit-product-submit"])) {
		$product_image = $_FILES["product-image"];
		edit_product(
			$_POST["id"],$_POST["product-name"], $_POST["description"], $_POST["price"], 
			$_POST["qty-per-item"], $_POST["stock-available"], $_POST["min-order"], 
			$_POST["max-order"], $_POST["allergy-info"], 
			$_POST["product-type"], $product_image, $PRODUCT_FILE_UPLOAD_LOCATION, $CONNECTION);
	}

	// If submitted from delete form
	if(isset($_POST["delete-product"])) {
		delete_product($_POST["id"], $CONNECTION);
		header("Location: products.php");
	}

	$product = get_product($_GET["id"], $trader, $CONNECTION);
	if($product == NULL)
	{
		echo "No product";
		return;
	}

	$product_types = get_product_types($CONNECTION);

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

	function edit_product(
		$id,
		$product_name, $description, $price, $qty_per_item, $stock_available,
		$min_order, $max_order, $allergy_info, $product_type, $product_image, 
		$upload_location, $connection
		) {

		$min_order = ($min_order == '' ? "NULL" : $min_order);
		$max_order = ($max_order == '' ? "NULL" : $max_order);
		if($product_image["name"] == '') {
			$sqlString = "UPDATE nepbuy_products SET ".
					"NAME='$product_name',DESCRIPTION='$description',PRICE=$price,QUANTITY_PER_ITEM=$qty_per_item,STOCK_AVAILABLE=$stock_available,MIN_ORDER=$min_order,MAX_ORDER=$max_order,ALLERGY_INFO='$allergy_info',FK_PRODUCT_TYPE_ID=$product_type ".
					"WHERE PK_PRODUCT_ID = $id";
			$stid = oci_parse($connection, $sqlString);
			oci_execute($stid);
		}
		else{
			$location = "../uploads/products/". basename($image["name"]);
			$product_location = $upload_location . basename($product_image["name"]);
			move_uploaded_file($product_image["tmp_name"], $location);

			$sqlString = "UPDATE nepbuy_products SET ".
						"NAME='$product_name',DESCRIPTION='$description',PRICE=$price,QUANTITY_PER_ITEM=$qty_per_item,STOCK_AVAILABLE=$stock_available,MIN_ORDER=$min_order,MAX_ORDER=$max_order,ALLERGY_INFO='$allergy_info',FK_PRODUCT_TYPE_ID=$product_type,PHOTO_LOCATION='$product_location' ".
						"WHERE PK_PRODUCT_ID = $id";
			$stid = oci_parse($connection, $sqlString);
			oci_execute($stid);
		}
	}

	function get_product($id, $trader, $connection) {
		$sqlString = "SELECT * FROM nepbuy_products p ".
					"JOIN nepbuy_shops s ON p.FK_SHOP_ID=s.PK_SHOP_ID ".
					"WHERE s.FK_USER_ID=$trader AND p.PK_PRODUCT_ID = $id";
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
	<img src="<?php echo $product['PHOTO_LOCATION']; ?>" height="200px">
	<input type="file" name="product-image" accept=".png,.jpg,.jpeg,.bmp,.gif">
	<input type="submit" name="edit-product-submit" value="Edit product">
</form>

<form method="post">
	<input name="id" type="hidden" value="<?php echo $product["PK_PRODUCT_ID"]; ?>">
	<input name="delete-product" type="submit" value="Delete">
</form>