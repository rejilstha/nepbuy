<?php
	include("connection.php");

	// Must be replaced with Client.
	$user_id = $_SESSION["user_session"];

	if(isset($_POST["collection-day"])) {

		$day_slot = getCollectionDaySlot($_POST["collection-day"], $_POST["collection-slot"], $CONNECTION);

		$products = get_cart_products($user_id, $CONNECTION);
		foreach ($products as $product) {
			updateStock($product);
			saveProduct($user_id, $product, $day_slot, $CONNECTION);
			removeFromCart($product, $CONNECTION);
		}

		echo "Payment Successful!!";
	}
	else {
		header("Location: show_cart.php");
	}

	function getCollectionDaySlot($collection_day, $collection_slot, $connection) {
		$sqlString = 'SELECT * FROM nepbuy_collection_days_slots where FK_COLLECTION_DAY_ID ='.$collection_day.' AND FK_COLLECTION_SLOT_ID='.$collection_slot;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$day_slot = oci_fetch_assoc($stid);
		return $day_slot;
	}

	function get_cart_products($user_id, $connection) {
		$sqlString = 'SELECT * FROM nepbuy_carts where FK_USER_ID='.$user_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$cart_products = array();
		while($cart_product = oci_fetch_assoc($stid)) {
			array_push($cart_products, $cart_product);
		}
		return $cart_products;
	}

	function saveProduct($user_id, $product, $day_slot, $connection) {
		$sqlString = 'INSERT INTO nepbuy_orders(FK_USER_ID, FK_COLLECTION_DAY_SLOT_ID, FK_PRODUCT_ID, PRODUCT_QUANTITY) VALUES('.$user_id.','.$day_slot["PK_COLLECTION_DAY_SLOT_ID"].','.$product["FK_PRODUCT_ID"].','.$product["PRODUCT_QUANTITY"].')';
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function removeFromCart($product, $connection) {
		$sqlString = 'DELETE FROM nepbuy_carts WHERE PK_CART_ID='.$product["PK_CART_ID"];
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function updateStock($product) {
		$sqlString = 'UPDATE nepbuy_products SET STOCK_AVAILABLE=STOCK_AVAILABLE - '.$product["PRODUCT_QUANTITY"].' WHERE PK_PRODUCT_ID='$product["FK_PRODUCT_ID"];
		
		
		$sqlString = 'DELETE FROM nepbuy_carts WHERE PK_CART_ID='.$product["PK_CART_ID"];
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}
?>