<?php

$connection = oci_connect ( "nepbuy", "nepbuy", "localhost:8080/nepbuy");
if(!$connection) {
	$m = oci_error();
	echo $m['message'];
	exit;
}
else {
	print "Connected to Oracle!";
}

oci_close($connection);
?>