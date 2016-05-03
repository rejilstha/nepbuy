<?php
	// List of all the products

	include("../connection.php");
	include("../includes/header.php");

	// Submitted from the add product.
	if(isset($_POST["create-product-submit"])) {
		add_product(
			$_POST["product-name"], $_POST["description"], $_POST["price"], 
			$_POST["qty-per-item"], $_POST["stock-available"], $_POST["min-order"], 
			$_POST["max-order"], $_POST["allergy-info"], $_POST["fk-shop-id"], 
			$_POST["product-type"], $CONNECTION);
	}

	function add_product(
		$product_name, $description, $price, $qty_per_item, $stock_available,
		$min_order, $max_order, $allergy_info, $fk_shop_id, $product_type, $connection
		) {

		$min_order = ($min_order == '' ? "NULL" : $min_order);
		$max_order = ($max_order == '' ? "NULL" : $max_order);
		$photo = 'NULL';

		$sqlString = "INSERT INTO nepbuy_products(NAME,DESCRIPTION,PRICE,QUANTITY_PER_ITEM,".
					"STOCK_AVAILABLE,MIN_ORDER,MAX_ORDER,ALLERGY_INFO,FK_SHOP_ID,".
					"FK_PRODUCT_TYPE_ID,PHOTO) VALUES('".
					$product_name."','".$description."',".$price.",".$qty_per_item.",".
					$stock_available.",".$min_order.",".$max_order.",'".
					$allergy_info."',".$fk_shop_id.",".$product_type.",".$photo.")";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}


	$sqlString = "SELECT * FROM nepbuy_products ORDER BY PK_PRODUCT_ID";
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
				<th>Price</th>
				<th>Quantity/item</th>
			</tr>
			<?php
			while($row = oci_fetch_assoc($stid)) {
			?>
				<tr>
					<td><?php echo $row['NAME']; ?></td>
					<td><?php echo $row['PRICE']; ?></td>
					<td><?php echo $row['QUANTITY_PER_ITEM']; ?></td>
				</tr>
			<?php
			}
			?>
		</table>
	<?php
	}
	include("../includes/footer.php");
?>