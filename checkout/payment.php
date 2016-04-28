<?php
	require __DIR__  . '/../PayPal-PHP-SDK/autoload.php';
	require __DIR__."/../connection.php";
	require __DIR__."/../includes/constants.php";

	$user_id = $_SESSION["user_session"];
	if(!isset($user_id)) {
		header("Location: /nepbuy/login.php?status=1");
	}
	elseif (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"])) {
	  // User session is a guid and user isn't registered
		header("Location: /nepbuy/login.php?status=1");
	}

	$apiContext = new \PayPal\Rest\ApiContext(
    	new \PayPal\Auth\OAuthTokenCredential(
				'ATvcFJ2EG690lBjqRqgVzjYwgTWV_rzo9S7aDQN5DjLe0fiClf-rOMVN4JYL9I2FCWpyu4bhSOWiKLdT',
				'EKCLjs0-ngXy-YeBrjhXZyt5N_G1t8f7n_2kbyB-0KmI7SjcnwnSExPzOWnpHuHY2xrIrqebtv0g_cs_')
		);

	if(isset($_POST["collection-day"])) {

		$day_slot = getCollectionDaySlot(
			$_POST["collection-day"], $_POST["collection-slot"], $CONNECTION);

		if(max_deliveries_reached(
			$day_slot["PK_COLLECTION_DAY_SLOT_ID"], $MAX_DELIVERY_PER_SLOT, $CONNECTION
			)) {
			echo "Maximum deliveries for this slot has been reached. Please choose another delivery slot.";
			// header("Location: delivery.php");
			return;
		}

		$_SESSION["day_slot"] = $day_slot;

		$payer = new \PayPal\Api\Payer();
		$payer->setPaymentMethod("paypal");

		foreach ($variable as $key => $value) {
			# code...
		}
		$item1 = new \PayPal\Api\Item();
		$item1->setName('Ground Coffee 40 oz')
		    ->setCurrency('USD')
		    ->setQuantity(1)
		    ->setSku("123123") // Similar to `item_number` in Classic API
		    ->setPrice(17.5);
		$item2 = new \PayPal\Api\Item();
		$item2->setName('Granola bars')
		    ->setCurrency('USD')
		    ->setQuantity(5)
		    ->setSku("321321") // Similar to `item_number` in Classic API
		    ->setPrice(2);

		$itemList = new \PayPal\Api\ItemList();

		$products = get_cart_products($user_id, $CONNECTION);
		$total = 0;
		foreach ($products as $product) {
			$item = new \PayPal\Api\Item();
			$prod = get_product($product["FK_PRODUCT_ID"], $CONNECTION);
			$item->setName($prod['NAME'])
				->setCurrency('USD')
				->setQuantity($product['PRODUCT_QUANTITY'])
				->setPrice($prod['PRICE'])
				->setSku($product['FK_PRODUCT_ID']);
			$itemList->addItem($item);
			$total = $total + ($prod['PRICE'] * $product['PRODUCT_QUANTITY']);
		}

	    $amount = new \PayPal\Api\Amount();
		$amount->setCurrency("USD")
		    ->setTotal($total);

		$transaction = new \PayPal\Api\Transaction();
		$transaction->setAmount($amount)
		    ->setItemList($itemList)
		    ->setDescription("Payment description")
		    ->setInvoiceNumber(uniqid());

		$baseUrl = 'http://localhost/nepbuy';
		$redirectUrls = new \PayPal\Api\RedirectUrls();
		$redirectUrls->setReturnUrl("$baseUrl/checkout/payment.php?success=true")
		    ->setCancelUrl("$baseUrl/checkout/payment.php?success=false");

		$payment = new \PayPal\Api\Payment();
		$payment->setIntent("sale")
		    ->setPayer($payer)
		    ->setRedirectUrls($redirectUrls)
		    ->setTransactions(array($transaction));

		try {
			$payment->create($apiContext);
		}
		catch (\PayPal\Exception\PayPalConnectionException $ex) {
		    // This will print the detailed information on the exception. 
		    //REALLY HELPFUL FOR DEBUGGING
		    echo $ex->getData();
		}

		$approvalUrl = $payment->getApprovalLink();

		header("Location: ".$approvalUrl);
	}
	elseif (isset($_GET['success']) && $_GET['success'] == 'true') {
	 	$paymentId = $_GET['paymentId'];
    	$payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
	    $execution = new \PayPal\Api\PaymentExecution();
    	$execution->setPayerId($_GET['PayerID']);

    	try {
    		 $result = $payment->execute($execution, $apiContext);
    		 try {
            	$payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
    		}
    		catch (Exception $ex) {
    		}
    	} 
    	catch (Exception $ex) {
    	}

    	// Confirm order
    	$day_slot = $_SESSION["day_slot"];

    	$products = get_cart_products($user_id, $CONNECTION);
		foreach ($products as $product) {
			updateStock($product, $CONNECTION);
			saveProduct($user_id, $product, $day_slot, $CONNECTION);
			removeFromCart($product, $CONNECTION);
		}

		echo "Payment Successful!!";
	 }

	function get_product($id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_products WHERE PK_PRODUCT_ID=$id";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return oci_fetch_assoc($stid);
		}
	}

	function max_deliveries_reached($day_slot_id, $max_delivery, $connection) {
		$sqlString = "SELECT COUNT(*) AS COUNT FROM nepbuy_orders WHERE ".
					"FK_COLLECTION_DAY_SLOT_ID=$day_slot_id AND STATUS='pending'";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return (oci_fetch_assoc($stid)["COUNT"] == $max_delivery);
		}
	}

	function getCollectionDaySlot($collection_day, $collection_slot, $connection) {
		$sqlString = "SELECT * FROM nepbuy_collection_days_slots where FK_COLLECTION_DAY_ID =$collection_day AND FK_COLLECTION_SLOT_ID=$collection_slot";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$day_slot = oci_fetch_assoc($stid);
		return $day_slot;
	}

	function get_cart_products($user_id, $connection) {
		$sqlString = "SELECT * FROM nepbuy_carts where USER_SESSION=$user_id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$cart_products = array();
		while($cart_product = oci_fetch_assoc($stid)) {
			array_push($cart_products, $cart_product);
		}
		return $cart_products;
	}

	function saveProduct($user_id, $product, $day_slot, $connection) {
		$day_slot_id = $day_slot['PK_COLLECTION_DAY_SLOT_ID'];
		$product_id = $product['FK_PRODUCT_ID'];
		$quantity = $product['PRODUCT_QUANTITY'];
		$sqlString = "INSERT INTO nepbuy_orders(FK_USER_ID, FK_COLLECTION_DAY_SLOT_ID, FK_PRODUCT_ID, PRODUCT_QUANTITY,STATUS,ORDERED_DATE,DELIVERED_DATE) ".
			"VALUES($user_id,$day_slot_id,$product_id,$quantity,'pending',CURRENT_TIMESTAMP,NULL)";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function removeFromCart($product, $connection) {
		$cart_id = $product["PK_CART_ID"];
		$sqlString = "DELETE FROM nepbuy_carts WHERE PK_CART_ID=$cart_id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function updateStock($product, $connection) {
		$quantity = $product['PRODUCT_QUANTITY'];
		$product_id = $product['FK_PRODUCT_ID'];
		$sqlString = "UPDATE nepbuy_products SET STOCK_AVAILABLE=STOCK_AVAILABLE - $quantity WHERE PK_PRODUCT_ID=$product_id";
		
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}
?>