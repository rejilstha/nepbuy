<?php
	require __DIR__ .'/../connection.php';
	include('../includes/header.php');

	//detail information of product type
	if(isset($_GET["id"])){
		$product_type_id = $_GET["id"];
		$product_type= getProductType($product_type_id, $CONNECTION);
		if($product_type == NULL) {
			echo "No product type";
			return;
		}
		$products = getProducts($product_type_id, $CONNECTION);
	}
	else {
		echo "No product types"; //404
		return;
	}

	function getProductType($product_type_id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_product_types WHERE PK_PRODUCT_TYPE_ID=$product_type_id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
		return oci_fetch_assoc($stid);
	}

	function getProducts($product_type_id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_products where FK_PRODUCT_TYPE_ID=$product_type_id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$products = array();

		while($product = oci_fetch_assoc($stid)){
			array_push($products, $product);
		}
		return $products;
	}
?>

<!-- hero page -->
<section id="hero-page1">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">
				<h2 class="title"><?php echo $product_type["NAME"] ?></h2>
				<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines.</p>
			</div>

		</div>
		
	</div>            
</section>		
<!-- hero page  -->

<!-- product special / latest -->
<section id="special-offer">		
	<div class="row">
		<div class="container">
			<?php
			foreach ($products as $product) {
				?>	
				<div class="col-sm-3 item">
					<span>
						<img src="<?php echo $product["PHOTO_LOCATION"]; ?>" height="185px">
						<p>
							<span style="display: block;"><?php echo $product["NAME"]; ?></span>
							<strong>Price $<?php echo $product["PRICE"]; ?></strong> 
							<a href="#"><i class="fa fa-shopping-cart"></i></a> 
							<a href="/nepbuy/product/details.php?id=<?php echo $product["PK_PRODUCT_ID"]; ?>" class="view-btn">View Details</a>
						</p>
					</span>
				</div>
			<?php
				}
			?>
			</div>
		</div>
	</section>
<?php
include("../includes/footer.php");
?>