<?php
	require __DIR__  . '/PayPal-PHP-SDK/autoload.php';

	$apiContext = new \PayPal\Rest\ApiContext(
    	new \PayPal\Auth\OAuthTokenCredential(
				'ATvcFJ2EG690lBjqRqgVzjYwgTWV_rzo9S7aDQN5DjLe0fiClf-rOMVN4JYL9I2FCWpyu4bhSOWiKLdT',
				'EKCLjs0-ngXy-YeBrjhXZyt5N_G1t8f7n_2kbyB-0KmI7SjcnwnSExPzOWnpHuHY2xrIrqebtv0g_cs_')
		);

	if (isset($_GET['success']) && $_GET['success'] == 'true') {
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


    	return $payment;
	}

	$payer = new \PayPal\Api\Payer();
	$payer->setPaymentMethod("paypal");

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
	$itemList->setItems(array($item1, $item2));

	$details = new \PayPal\Api\Details();
	$details->setShipping(1.2)
	    ->setTax(1.3)
	    ->setSubtotal(27.50);

    $amount = new \PayPal\Api\Amount();
	$amount->setCurrency("USD")
	    ->setTotal(30)
	    ->setDetails($details);

	$transaction = new \PayPal\Api\Transaction();
	$transaction->setAmount($amount)
	    ->setItemList($itemList)
	    ->setDescription("Payment description")
	    ->setInvoiceNumber(uniqid());

	$baseUrl = 'http://127.0.0.1/nepbuy';
	$redirectUrls = new \PayPal\Api\RedirectUrls();
	$redirectUrls->setReturnUrl("$baseUrl/first.php?success=true")
	    ->setCancelUrl("$baseUrl/first.php?success=false");

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

	
?>