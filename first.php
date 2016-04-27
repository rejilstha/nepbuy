<?php
	require __DIR__  . '/PayPal-PHP-SDK/autoload.php';

	$apiContext = new \PayPal\Rest\ApiContext(
    	new \PayPal\Auth\OAuthTokenCredential(
				'ATvcFJ2EG690lBjqRqgVzjYwgTWV_rzo9S7aDQN5DjLe0fiClf-rOMVN4JYL9I2FCWpyu4bhSOWiKLdT',
				'EKCLjs0-ngXy-YeBrjhXZyt5N_G1t8f7n_2kbyB-0KmI7SjcnwnSExPzOWnpHuHY2xrIrqebtv0g_cs_')
		);

	$creditCard = new \PayPal\Api\CreditCard();
	$creditCard->setType("visa")
		->setNumber("4417119669820331")
	    ->setExpireMonth("11")
	    ->setExpireYear("2019")
	    ->setCvv2("012")
	    ->setFirstName("Joe")
	    ->setLastName("Shopper");

	try {
		$creditCard->create($apiContext);
		echo $creditCard;
	}
	catch (\PayPal\Exception\PayPalConnectionException $ex) {
	    // This will print the detailed information on the exception. 
	    //REALLY HELPFUL FOR DEBUGGING
	    echo $ex->getData();
	}
?>