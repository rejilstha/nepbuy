<?php
	require __DIR__ .'/../connection.php';
	include('../includes/header.php');

	//detail information of product
	if(isset($_GET["id"])){
		$product_id = $_GET["id"];
		$product = getProduct($product_id, $CONNECTION);
		if($product == NULL) {
			echo "No product";
			return;
		}

		$shop = getShop($product["FK_SHOP_ID"], $CONNECTION);
		$product_type = getProductType($product["FK_PRODUCT_TYPE_ID"], $CONNECTION);
		$trader = getTrader($shop["FK_USER_ID"], $CONNECTION);
	}
	else {
		echo "No products"; //404
		return;
	}

	if(isset($_POST["pk_product_id"])){
		if($_POST["qty"] > 0 )
			add_to_cart($_SESSION["user_session"], $_POST["pk_product_id"], $_POST["qty"], $product["MAX_ORDER"], $product["STOCK_AVAILABLE"], $CONNECTION);
	}
	
	function add_to_cart($user_session_id, $product_id, $qty, $max_quantity, $stock_available, $connection)	{
		//add to cart

		// Check if same product exists
		$check_sql_string = "SELECT COUNT(*) as COUNT FROM nepbuy_carts WHERE USER_SESSION='".$user_session_id."' AND FK_PRODUCT_ID=".$product_id;
		$chk_st_id = oci_parse($connection, $check_sql_string);
		oci_execute($chk_st_id);
		$count = oci_fetch_assoc($chk_st_id);

		// Check if any rows exist
		if($count['COUNT'] == 0) {
			$sqlString = "INSERT INTO nepbuy_carts(USER_SESSION,FK_PRODUCT_ID,PRODUCT_QUANTITY) VALUES('".$user_session_id."',".$product_id.','.$qty.')';
			$stid = oci_parse($connection, $sqlString);
			$result = oci_execute($stid);
			if($result)
				echo "Added to cart";
			else
				echo "Failed to add to cart";
		}
		else {

			// Check if the product quantity doesn't exceed the MAX_ORDER OR STOCK_AVAILABLE limit.
			$check_max_sql_string = "SELECT PRODUCT_QUANTITY FROM nepbuy_carts WHERE USER_SESSION='".$user_session_id."' AND FK_PRODUCT_ID=".$product_id;
			$ch_max_st_id = oci_parse($connection, $check_max_sql_string);
			oci_execute($ch_max_st_id);
			$prod_quantity = oci_fetch_assoc($ch_max_st_id);

			echo $stock_available;
			echo $prod_quantity['PRODUCT_QUANTITY'] + $qty;			

			if($prod_quantity['PRODUCT_QUANTITY'] + $qty > $stock_available) {
				$qty = $stock_available - $prod_quantity['PRODUCT_QUANTITY'];
			}
			
			if($max_quantity != NULL && intval($prod_quantity['PRODUCT_QUANTITY']) + intval($qty) > intval($max_quantity))
				$qty = intval($max_quantity) - intval($prod_quantity['PRODUCT_QUANTITY']);

			$sqlString = "UPDATE nepbuy_carts SET PRODUCT_QUANTITY = PRODUCT_QUANTITY + ".$qty." WHERE USER_SESSION='".$user_session_id."' AND FK_PRODUCT_ID=".$product_id;
			$stid = oci_parse($connection, $sqlString);
			$result = oci_execute($stid);
			if($result)
				echo "Updated to cart";
			else
				echo "Failed to update the cart";	
		}
	}

	function getTrader($trader_id, $connection) {
		$sqlString = 'SELECT * FROM nepbuy_users where PK_USER_ID='.$trader_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$trader = oci_fetch_assoc($stid);
		return $trader;
	}

	function getProduct($product_id, $connection) {
		$sqlString = 'SELECT * FROM nepbuy_products where PK_PRODUCT_ID='.$product_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$product = oci_fetch_assoc($stid);
		return $product;
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

<section>
	<div class="row">
		<div class="container">
			<div>
				<img src="<?php echo $product["PHOTO_LOCATION"]; ?>">
			</div>
			<div>
				Product Name:
				<?php
					echo $product["NAME"];
				?>
			</div>
			<div>
				Product Description:
				<?php
					echo $product["DESCRIPTION"];
				?>
			</div>
			<div>
				Product Stock Available:
				<?php
					echo $product["STOCK_AVAILABLE"];
				?>
			</div>
			<div>
				Product Min Order:
				<?php
					if ($product["MIN_ORDER"]!=NULL)
						echo $product["MIN_ORDER"];
					else 
						echo "No min order.";
				?>
			</div>
			<div>
				Product Max Order:
				<?php
					if ($product["MAX_ORDER"]!=NULL)
						echo $product["MAX_ORDER"];
					else 
						echo "No max order.";
				?>
			</div>
			<div>
				Product Allergy Information:
				<?php
					if($product["ALLERGY_INFO"] != NULL)
						echo $product["ALLERGY_INFO"];
					else
						echo "No allergy info."
				?>
			</div>
			<div>
				Product from Shop:
				<?php
					echo $shop["NAME"];
				?>
			</div>
			<div>
				Product Type:
				<?php
					echo $product_type["NAME"];
				?>
			</div>
			<div>
				Product Price:
				<?php
					echo $product["PRICE"];
				?>
			</div>
			<div>
				Product Trader:
				<?php
					echo $trader["NAME"];
				?>
			</div>
			<div>
				<?php
					if ($product["STOCK_AVAILABLE"] > 0)
					{
						?>
						<form method ="post">
							<input name="pk_product_id" type="hidden" value="<?php echo $product["PK_PRODUCT_ID"]; ?>"/>
							<input name= "qty" type="number" value="1" min="<?php echo $product["MIN_ORDER"];?>"
								max="<?php 
									if (isset($product['MAX_ORDER']) && $product['MAX_ORDER'] > $product['STOCK_AVAILABLE']) 
										echo $product['STOCK_AVAILABLE'];
									elseif (!isset($product['MAX_ORDER'])) {
										echo $product['STOCK_AVAILABLE'];
									} else {
										echo $product['MAX_ORDER']; 
									}
									?>"/>
							<input type="submit" value="Add to Cart"/>
						</form>
						<?php
					}
					else {
						echo "No stock available. Choose other product.";
					}	
				?>
			</div>
				//update quantity if exist
		</div>
	</div>
</section>

<?php include('../includes/footer.php'); ?>