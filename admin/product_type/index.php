<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
if(!(require __DIR__ . '/../admin_access.php')) {
	return;
}

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
					<li><a href="#">Product types</a></li>
				</ul>

				<div class="row-fluid">	
					<div class="span12" onTablet="span12" onDesktop="span12">
					<h2 class="headV">List Product types</h2>
					<a class="add-btn" href="add.php"><i class="icon-plus"></i> Add New</a>
					<table class="table table-striped">
						<tr>
							<th>Name</th>
							<th>Parent</th>
							<th></th>
						</tr>
						<?php
						while($row = oci_fetch_assoc($stid)) {
							if($row["FK_PARENT_ID"] == '') {
								?>
								<tr>
									<td><?php echo $row['NAME']; ?></td>
									<td></td>
									<td><a class="btn btn-default" href="edit.php?id=<?php echo $row["PK_PRODUCT_TYPE_ID"];?>"><i class="icon-edit"></i> Edit</a></td>
								</tr>
								<?php 
							} else {
								$product_type = get_product_type($row["FK_PARENT_ID"], $CONNECTION);
								?>

								<tr>
									<td><?php echo $row['NAME']; ?></td>
									<td><?php echo $product_type['NAME']; ?></td>
									<td><a class="btn btn-default" href="edit.php?id=<?php echo $row["PK_PRODUCT_TYPE_ID"];?>"><i class="icon-edit"></i> Edit</a></td>
								</tr>
								<?php
							}
							?>

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