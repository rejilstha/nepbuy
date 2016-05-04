<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
require __DIR__ ."/../admin_access.php";
require __DIR__ . '/../../includes/constants.php';

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
		if($product_image["name"] == '') {
			$sqlString = "UPDATE nepbuy_products SET ".
			"NAME='$product_name',DESCRIPTION='$description',PRICE=$price,QUANTITY_PER_ITEM=$qty_per_item,STOCK_AVAILABLE=$stock_available,MIN_ORDER=$min_order,MAX_ORDER=$max_order,ALLERGY_INFO='$allergy_info',FK_SHOP_ID=$fk_shop_id,FK_PRODUCT_TYPE_ID=$product_type ".
			"WHERE PK_PRODUCT_ID = $id";
			$stid = oci_parse($connection, $sqlString);
			oci_execute($stid);
		}
		else{
			$product_location = $upload_location . basename($product_image["name"]);
			move_uploaded_file($product_image["tmp_name"], $product_location);

			$sqlString = "UPDATE nepbuy_products SET ".
			"NAME='$product_name',DESCRIPTION='$description',PRICE=$price,QUANTITY_PER_ITEM=$qty_per_item,STOCK_AVAILABLE=$stock_available,MIN_ORDER=$min_order,MAX_ORDER=$max_order,ALLERGY_INFO='$allergy_info',FK_SHOP_ID=$fk_shop_id,FK_PRODUCT_TYPE_ID=$product_type,PHOTO_LOCATION='$product_location' ".
			"WHERE PK_PRODUCT_ID = $id";
			$stid = oci_parse($connection, $sqlString);
			oci_execute($stid);
		}
	}

	function get_product($id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_products WHERE PK_PRODUCT_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return oci_fetch_assoc($stid);
		}
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
					<img src="<?php echo $product['PHOTO_LOCATION']; ?>" height="200px">
				</div>

				<div class="row-fluid">
					<form method="post" enctype="multipart/form-data">
						<input name="id" type="hidden" value="<?php echo $product['PK_PRODUCT_ID']; ?>" />
						<div class="row-fluid">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="product-name">Product</label>
									<input class="form-control" name="product-name" type="text" value="<?php echo $product['NAME']; ?>" placeholder="Product name" required>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="description">Description</label>
									<input class="form-control" name="description" type="text" value="<?php echo $product['DESCRIPTION']; ?>" placeholder="Description">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="price">Price</label>
									<input class="form-control" name="price" type="number" value="<?php echo $product['PRICE']; ?>" placeholder="Price" required>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="qty-per-item">Quantity per item</label>
									<input class="form-control" name="qty-per-item" type="number" value="<?php echo $product['QUANTITY_PER_ITEM']; ?>" placeholder="Quantity per item" required>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="stock-available">How many?</label>
									<input class="form-control" name="stock-available" type="number" value="<?php echo $product['STOCK_AVAILABLE']; ?>" placeholder="Stock available" required>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="min-order">Min order</label>
									<input class="form-control" name="min-order" type="number" value="<?php echo $product['MIN_ORDER']; ?>" placeholder="Min order">
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="max-order">Max order</label>
									<input class="form-control" name="max-order" type="number" value="<?php echo $product['MAX_ORDER']; ?>" placeholder="Max order">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="allergy-info">Allergy info</label>
									<input class="form-control" name="allergy-info" type="text" value="<?php echo $product['ALLERGY_INFO']; ?>" placeholder="Allergy info">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="fk-shop-id">Shop</label>
									<select class="form-control" name="fk-shop-id" required>
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
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="product-image">Product image</label>
									<input class="form-control" type="file" name="product-image" accept=".png,.jpg,.jpeg,.bmp,.gif">
								</div>
							</div>
						</div>
						<input class="add-btn" type="submit" name="edit-product-submit" value="Edit product">
					</form>

					<form method="post">
						<input name="id" type="hidden" value="<?php echo $product["PK_PRODUCT_ID"]; ?>">
						<input class="btn btn-danger" name="delete-product" type="submit" value="Delete">
						<a class="btn btn-default" href="index.php">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>