<?php
	include("connection.php");

	// SHould be replaced by client.
	$user_id = $_SESSION["user_session"];
	$cart_products = get_cart_products($user_id, $CONNECTION);

	if(isset($_POST["submit"])){
		if($_POST["submit"] == "update") {
			update_qty($user_id, $_POST["pk_product_id"], $_POST["qty"], $CONNECTION);
		}
		else if($_POST["submit"] == "remove") {
			remove_product($user_id, $_POST["pk_product_id"], $CONNECTION);
		}

	}

	function update_qty($user_id, $product_id, $qty, $connection) {
		$sqlString = "UPDATE nepbuy_carts SET PRODUCT_QUANTITY = '.$qty.' WHERE USER_SESSION='".$user_id."' AND FK_PRODUCT_ID=".$product_id;
		$stid = oci_parse($connection, $sqlString);
		$result = oci_execute($stid);
		if($result)
			echo "Updated to cart";
		else
			echo "Failed to update the cart";	
	}

	function remove_product($user_id, $product_id, $connection) {
		$sqlString = "DELETE FROM nepbuy_carts WHERE USER_SESSION='".$user_id."' AND FK_PRODUCT_ID=".$product_id;
		$stid = oci_parse($connection, $sqlString);
		$result = oci_execute($stid);
		if($result)
			echo "Removed from cart";
		else
			echo "Failed to remove from the cart";	
	}

	function get_cart_products($user_id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_carts where USER_SESSION='".$user_id."'";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$cart_products = array();
		while($cart_product = oci_fetch_assoc($stid)) {
			array_push($cart_products, $cart_product);
		}
		return $cart_products;
	}

	function get_product($product_id, $connection) {
		$sqlString = 'SELECT * FROM nepbuy_products where PK_PRODUCT_ID='.$product_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$product = oci_fetch_assoc($stid);
		return $product;
	}
?>
<?php
	$total = 0;
	foreach ($cart_products as $cart_product) {
		$product = get_product($cart_product["FK_PRODUCT_ID"], $CONNECTION);
?>	
	<div>
		<div>
			<?php
				echo $product["PHOTO_LOCATION"];
			?>
		</div>
		<div>
			Product Name:
			<?php
				echo $product["NAME"];
			?>
		</div>
		<div>
			Product Price:
			<?php
				echo $product["PRICE"];
			?>
		</div>
		<div>
			Quantity:
			<?php
				echo $cart_product["PRODUCT_QUANTITY"];
			?>
		</div>
		<div>
			Sub-Total:
			<?php
				echo floatval($product["PRICE"])*intval($cart_product["PRODUCT_QUANTITY"]);
				$total+=floatval($product["PRICE"])*intval($cart_product["PRODUCT_QUANTITY"]);
			?>
		</div>
		<div>
			<form method="post">
				<input name="pk_product_id" type="hidden" value="<?php echo $product["PK_PRODUCT_ID"]; ?>"/>
				<input name= "qty" type="number" value="<?php echo $cart_product["PRODUCT_QUANTITY"];?>" min="<?php echo $product["MIN_ORDER"];?>" max="<?php echo $product["MAX_ORDER"];?>"/>
				<input name="submit" type="submit" value="update"/>
			</form>
		</div>
		<div>
			<form method="post">
				<input name="pk_product_id" type="hidden" value="<?php echo $product["PK_PRODUCT_ID"]; ?>"/>
				<input name="submit" type="submit" value="remove"/>
			</form>
		</div>
	</div>	
	<?php
	}
?>
<div>
	Total:
	<?php
			echo $total;
	?>
	<form method="post" action="choose_delivery.php">
		<input name="submit" type="submit" value="checkout"/>
	</form>
</div>
