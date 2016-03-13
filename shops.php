<?php
include("connection.php");

	//detail information of product
	if(isset($_GET["id"])){
		$shop_id = $_GET["id"];
		$shop = getShop($shop_id, $CONNECTION);
		$products = getProducts($shop_id, $CONNECTION);
		$user = getUser($shop["FK_USER_ID"], $CONNECTION);
		
	}

	function getUser($user_id, $connection) {
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
		$sqlString = 'SELECT * FROM nepbuy_shops where PK_SHOP_ID='.$shop_id;
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
<div>
	<div>
		Shop Name:
		<?php
			echo $shop["NAME"];
		?>
	</div>
	<div>
		Shop Location:
		<?php
			echo $shop["LOCATION"];
		?>
	</div>
	<div>
		Trader:
		<?php
			echo $user["NAME"];
		?>
	</div>
</div>	
<?php
	foreach ($products as $product) {
		$product_type = getProductType($product["FK_PRODUCT_TYPE_ID"], $CONNECTION);
?>	
	<div>
		<?php
			echo $product["PHOTO"];
		?>
		Product Name:
		<?php
			echo $product["NAME"];
		?>
		Product Price:
		<?php
			echo $product["PRICE"];
		?>
		Product Type:
		<?php
			echo $product_type["NAME"];
		?>
	</div>	
	<?php
	}
?>