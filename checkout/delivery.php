<?php
require __DIR__ ."/../connection.php";
require __DIR__ . "/../user_access.php";
include('../includes/header.php');

$user_id = $_SESSION["user_session"];
$products = get_cart_products($user_id, $CONNECTION);
$total = 0.0;

$days = getDeliveryDays($CONNECTION);
$slots = getDeliverySlots($CONNECTION);

function getDeliveryDays($connection){

	$sqlString = "Select * from nepbuy_collection_days";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$days = array();
	while($day = oci_fetch_assoc($stid)) {
		array_push($days, $day);
	}
	return $days;
}

function getDeliverySlots($connection){
	$sqlString = "Select * from nepbuy_collection_slots";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	$slots = array();
	while($slot = oci_fetch_assoc($stid)) {
		array_push($slots, $slot);
	}
	return $slots;
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
?>

<!-- hero page -->
<section id="hero-page1">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">

				<div class="col-sm-4">
					<img src="/nepbuy/images/img/veg.png" width="100%" >
				</div>
				<div class="col-sm-8">
					<h2 class="title">NepBuy Introduction</h2>
					<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
				</div>	
			</div>


		</div>
		
	</div>            
</section>		
<!-- hero page  -->
<div class="row">
	<div class="container">
		<h3>Summary</h3>
		<table class="table table-striped">
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
				<th colspan="3">Total</th>
				<th><?php echo $total; ?></th>
			</tr>
		</table>

		<div>
			<h3>Choose delivery</h3>
			<form method="POST" action="payment.php">
				<div class="form-group">
					<label>Collection-day:</label>
					<select class="form-control" name="collection-day" required>
						<?php
						foreach ($days as $day) {
							?>
							<option value="<?php echo $day["PK_COLLECTION_DAY_ID"]; ?>"><?php echo $day["NAME"]; ?></option>
							<?php
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label>Collection-slot:</label>
					<select class="form-control" name="collection-slot" required>
						<?php
						foreach ($slots as $slot) {
							?>
							<option value="<?php echo $slot["PK_COLLECTION_SLOT_ID"]; ?>"><?php echo $slot["START_TIME"]; ?> - <?php echo $slot["END_TIME"]; ?></option>
							<?php
						}
						?>
					</select>
				</div>
				<input class="cartadd" name="submit" type="submit" value="Place order" />
			</form>
		</div>
	</div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>