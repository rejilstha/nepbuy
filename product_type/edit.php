<?php
	require __DIR__ . '/../connection.php';

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

<form method="post">
	<input name="id" type="hidden" value="<?php echo $product_t['PK_PRODUCT_TYPE_ID']; ?>">
	<input name="product-type-name" type="text" value="<?php echo $product_t['NAME']; ?>" placeholder="Product type name" required>
	<select name="fk-parent-id">
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
	<input type="submit" name="edit-product-type-submit" value="Edit product type">
</form>

<form method="post">
	<input name="id" type="hidden" value="<?php echo $product_t['PK_PRODUCT_TYPE_ID']; ?>">
	<input type="submit" name="delete-product-type-submit" value="Delete">
</form>