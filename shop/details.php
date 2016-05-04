<?php
include("../connection.php");
include("../includes/header.php");

	//detail information of shop
if(isset($_GET["id"])){
	$shop_id = $_GET["id"];
	$shop = getShop($shop_id, $CONNECTION);
	$products = getProducts($shop_id, $CONNECTION);
	$trader = getTrader($shop["FK_USER_ID"], $CONNECTION);

}

function getTrader($user_id, $connection) {
	$sqlString = 'SELECT * FROM nepbuy_users where PK_USER_ID='.$user_id;
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$user = oci_fetch_assoc($stid);
	return $user;
}

function getProducts($shop_id, $connection) {
	$sqlString = 'SELECT * FROM nepbuy_products where FK_SHOP_ID='.$shop_id;
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$products = array();

	while($product = oci_fetch_assoc($stid)){
		array_push($products, $product);
	}
	return $products;
}

function getShop($shop_id, $connection){
	$sqlString = "SELECT * FROM nepbuy_shops where PK_SHOP_ID=$shop_id AND STATUS='Verified'";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$shop = oci_fetch_assoc($stid);
	return $shop;
}

function getProductType($product_type_id, $connection){
	$sqlString = 'SELECT * FROM nepbuy_product_types where PK_PRODUCT_TYPE_ID='.$product_type_id;
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$product_type = oci_fetch_assoc($stid);
	return $product_type;
}
?>
<!-- hero page -->
<section id="hero-page1">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">
				<h2 class="title"><?php echo $shop["NAME"]. " - ".$shop["LOCATION"]; ?> <i class="fa  fa-angle-double-right"></i> <?php echo $trader["NAME"]; ?></h2>
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
				$product_type = getProductType($product["FK_PRODUCT_TYPE_ID"], $CONNECTION);
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