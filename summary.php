<?php
	include("connection.php");

	// Must be replaced with Client.
	$user_id = $_SESSION["user_session"];

	if(isset($_POST["collection-day"])) {

		$products = get_cart_products($user_id, $CONNECTION);
		$total = 0.0;

		?>
		<table border="1">
			<th>Product Name</th>
			<th>Price</th>
			<th>Quantity</th>
			<th>Sub Total</th>
			<?php

			foreach ($products as $cart_product) {
					$product = getProduct($cart_product["FK_PRODUCT_ID"], $CONNECTION);
					$total += floatval($product["PRICE"])*intval($cart_product["PRODUCT_QUANTITY"]);
				?>
				<tr>
					<td><?php echo $product["NAME"]; ?></td>
					<td><?php echo $product["PRICE"]; ?></td>
					<td><?php echo $cart_product["PRODUCT_QUANTITY"]; ?></td>
					<td><?php echo floatval($product["PRICE"])*intval($cart_product["PRODUCT_QUANTITY"]); ?></td>
				</tr>
			<?php
			}
			?>
			<tr>
				<th>Total</th>
				<td></td>
				<td></td>
				<td><?php echo $total; ?></td>
			</tr>
		</table>

		<form method="POST" action="payment.php">
			<input name="collection-day" type="hidden" value="<?php echo $_POST['collection-day']; ?>" />
			<input name="collection-slot" type="hidden" value="<?php echo $_POST['collection-slot']; ?>" />
			<input name="submit" type="submit"  value="Proceed to payment" />
		</form>
	<?php
	}
	else {
		header("Location: cart.php");
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

	function getProduct($product_id, $connection) {
		$sqlString = 'SELECT * FROM nepbuy_products where PK_PRODUCT_ID='.$product_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$product = oci_fetch_assoc($stid);
		return $product;
	}
