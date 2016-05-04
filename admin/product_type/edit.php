<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
if(!(require __DIR__ . '/../admin_access.php')) {
	return;
}

if(!isset($_GET["id"]))
{
		echo "No product type"; //404
		return;
	}

	// If submitted from edit form
	if(isset($_POST["edit-product-type-submit"])) {
		edit_product_type($_POST["id"],$_POST["product-type-name"], $_POST["fk-parent-id"], $CONNECTION);
	}

	// If submitted from delete form
	if(isset($_POST["delete-product-type-submit"])) {
		delete_product($_POST["id"], $CONNECTION);
		header("Location: product_types.php");
	}

	$product_t = get_product_type($_GET["id"], $CONNECTION);
	if($product_t == NULL)
	{
		echo "No product type";
		return;
	}

	$product_types = get_product_types($_GET["id"], $CONNECTION);

	function delete_product($id, $connection) {
		$sqlString = "DELETE FROM nepbuy_product_types WHERE PK_PRODUCT_TYPE_ID=$id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function get_product_types($id, $connection) {
		// Get product types with id not equal to current product type and
		// with no parent (top-level product-types)
		$sqlString = "SELECT * FROM nepbuy_product_types WHERE FK_PARENT_ID IS NULL AND PK_PRODUCT_TYPE_ID != $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$product_types = array();
		while($product_type = oci_fetch_assoc($stid)) {
			array_push($product_types, $product_type);
		}
		return $product_types;
	}

	function edit_product_type($id, $product_type_name, $parent_id, $connection) {

		$parent_id = ($parent_id == '' ? "NULL" : $parent_id);

		$sqlString = "UPDATE nepbuy_product_types SET ".
		"NAME='$product_type_name',FK_PARENT_ID=$parent_id ".
		"WHERE PK_PRODUCT_TYPE_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function get_product_type($id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_product_types WHERE PK_PRODUCT_TYPE_ID = $id";
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
				<li><a href="index.php">Product types</a></li>
			</ul>

			<div class="row-fluid">
				<form method="post">
					<input name="id" type="hidden" value="<?php echo $product_t['PK_PRODUCT_TYPE_ID']; ?>">
					<div class="form-group">
						<label for="product-type-name">Product type</label>
						<input class="form-control" name="product-type-name" type="text" value="<?php echo $product_t['NAME']; ?>" placeholder="Product type name" required>
					</div>
					<div class="form-group">
						<label for="fk-parent-id">Parent</label>
						<select name="fk-parent-id" class="form-control">
							<option value="">None</option>
							<?php
							foreach ($product_types as $product_type) {
								if($product_t["FK_PARENT_ID"] != '' && 
									$product_type["PK_PRODUCT_TYPE_ID"] == $product_t["FK_PARENT_ID"]) {
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
						<input class="add-btn" type="submit" name="edit-product-type-submit" value="Save product type" />
					</form>
					<form method="post" style="margin-top: 10px">
							<input name="id" type="hidden" value="<?php echo $product_t['PK_PRODUCT_TYPE_ID']; ?>">
						<button class="btn btn-danger" type="submit" name="delete-product-type-submit">Delete</button>
						<a class="btn btn-default" href="index.php">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require __DIR__ ."/../includes/footer.php";
?>