<?php
	// List of all the products

require __DIR__ . '/../../connection.php';
require __DIR__ . '/../admin_access.php';
require __DIR__ ."/../includes/header.php";
require __DIR__ . '/../../includes/constants.php';

	// Submitted from the add product.
if(isset($_POST["create-product-submit"])) {
	$product_image = $_FILES["product-image"];
	add_product(
		$_POST["product-name"], $_POST["description"], $_POST["price"], 
		$_POST["qty-per-item"], $_POST["stock-available"], $_POST["min-order"], 
		$_POST["max-order"], $_POST["allergy-info"], $_POST["fk-shop-id"], 
		$_POST["product-type"], $product_image, $PRODUCT_FILE_UPLOAD_LOCATION, $CONNECTION);
}

function add_product(
	$product_name, $description, $price, $qty_per_item, $stock_available,
	$min_order, $max_order, $allergy_info, $fk_shop_id, $product_type, $product_image, 
		$upload_location, $connection
	) {

	$min_order = ($min_order == '' ? "NULL" : $min_order);
	$max_order = ($max_order == '' ? "NULL" : $max_order);

	if($product_image["name"] == '') {
		$sqlString = "INSERT INTO nepbuy_products(NAME,DESCRIPTION,PRICE,QUANTITY_PER_ITEM,".
		"STOCK_AVAILABLE,MIN_ORDER,MAX_ORDER,ALLERGY_INFO,FK_SHOP_ID,".
		"FK_PRODUCT_TYPE_ID,PHOTO_LOCATION) VALUES(".
		"'$product_name','$description',$price,$qty_per_item,".
		"$stock_available,$min_order,$max_order,".
		"'$allergy_info',$fk_shop_id,$product_type,NULL)";

		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	} else {
		$location = "../../uploads/products/". basename($product_image["name"]);

		$product_location = $upload_location . basename($product_image["name"]);
		move_uploaded_file($product_image["tmp_name"], $location);

		$sqlString = "INSERT INTO nepbuy_products(NAME,DESCRIPTION,PRICE,QUANTITY_PER_ITEM,".
		"STOCK_AVAILABLE,MIN_ORDER,MAX_ORDER,ALLERGY_INFO,FK_SHOP_ID,".
		"FK_PRODUCT_TYPE_ID,PHOTO_LOCATION) VALUES(".
		"'$product_name','$description',$price,$qty_per_item,".
		"$stock_available,$min_order,$max_order,".
		"'$allergy_info',$fk_shop_id,$product_type,'$product_location')";
		
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}
}


$sqlString = "SELECT * FROM nepbuy_products ORDER BY PK_PRODUCT_ID";
$stid = oci_parse($CONNECTION, $sqlString);
if(oci_execute($stid) > 0) {
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
					<li><a href="#">Products</a></li>
				</ul>

				<div class="row-fluid">
					<div class="span12" onTablet="span12" onDesktop="span12">
					<h2 class="headV">List Products</h2>
					<a class="add-btn" href="add.php"><i class="icon-plus"></i> Add New</a>
					<table class="table table-striped">
						<tr>
							<th>Name</th>
							<th>Price</th>
							<th>Description</th>
							<th>Quantity/item</th>
							<th>Stock available</th>
							<th>Min order</th>
							<th>Max order</th>
							<th>Allergy info</th>
							<th></th>
						</tr>
						<?php
						while($row = oci_fetch_assoc($stid)) {
							?>
							<tr>
								<td><?php echo $row['NAME']; ?></td>
								<td><?php echo $row['PRICE']; ?></td>
								<td><?php echo $row['DESCRIPTION']; ?></td>
								<td><?php echo $row['QUANTITY_PER_ITEM']; ?></td>
								<td><?php echo $row['STOCK_AVAILABLE']; ?></td>
								<td><?php echo $row['MIN_ORDER']; ?></td>
								<td><?php echo $row['MAX_ORDER']; ?></td>
								<td><?php echo $row['ALLERGY_INFO']; ?></td>
								<td><a class="btn btn-default" href="edit.php?id=<?php echo $row["PK_PRODUCT_ID"];?>"><i class="icon-edit"></i> Edit</a></td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php
}
require __DIR__ ."/../includes/footer.php";
?>