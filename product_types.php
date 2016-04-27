<?php
	include("connection.php");

	// Submitted from the add product.
	if(isset($_POST["create-product-type-submit"])) {
		add_product_type(
			$_POST["product-type-name"], $_POST["fk-parent-id"], $CONNECTION);
	}

	function add_product_type($product_type_name, $parent_id, $connection) {

		$parent_id = ($parent_id == '' ? "NULL" : $parent_id);

		$sqlString = "INSERT INTO nepbuy_product_types(NAME,FK_PARENT_ID) VALUES('".
					$product_type_name."',".$parent_id.")";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function get_product_type($product_type_id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_product_types WHERE PK_PRODUCT_TYPE_ID=".$product_type_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		return oci_fetch_assoc($stid);
	}


	$sqlString = "SELECT * FROM nepbuy_product_types ORDER BY PK_PRODUCT_TYPE_ID";
	$stid = oci_parse($CONNECTION, $sqlString);
	if(oci_execute($stid) > 0) {
		?>
		<table>
			<tr>
				<th>Name</th>
				<th>Parent</th>
			</tr>
			<?php
			while($row = oci_fetch_assoc($stid)) {
				if($row["FK_PARENT_ID"] == '') {
				?>
					<tr>
						<td><?php echo $row['NAME']; ?></td>
					</tr>
				<?php 
				} else {
					$product_type = get_product_type($row["FK_PARENT_ID"], $CONNECTION);
				?>

					<tr>
						<td><?php echo $row['NAME']; ?></td>
						<td><?php echo $product_type['NAME']; ?></td>
					</tr>
				<?php
				}
			?>
				
			<?php
			}
			?>
		</table>
	<?php
	}
?>