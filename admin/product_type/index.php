<?php
	include("../connection.php");
	include("../includes/header.php");

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
		<section id="hero-page1">
			<div class="row">
			 <div class="container">
				<div class="col-sm-12">

				<div class="col-sm-4">
					<img src="/nepbuy/images/img/veg.png" width="100%" >
				</div>
				<div class="col-sm-8">
					<h2 class="title">NepBuy Introduction</h2>
					<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
				</div>	
				</div>

				
			</div>
				
			</div>            
		</section>	
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
	include("../includes/footer.php");
?>