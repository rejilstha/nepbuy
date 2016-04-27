<?php
	require __DIR__  . '/PayPal-PHP-SDK/autoload.php';
	require('connection.php');

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
?>