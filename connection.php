<?php
session_start();
if (!isset($_SESSION["user_session"])) {
	// Assign random session id to the anonymous customer.
	$_SESSION["user_session"]= trim(com_create_guid(), '{}');
	echo "here";
}

$CONNECTION = oci_connect ("nepbuy", "nepbuy", "localhost/XE") or die('Error');
if($CONNECTION == "Error")

?>


