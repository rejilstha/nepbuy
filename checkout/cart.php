<?php
require __DIR__ ."/../connection.php";

require __DIR__ . "/../cart_access.php";
include('../includes/header.php');

$user_id = $_SESSION["user_session"];

if(isset($_POST["submit"])){
	if($_POST["submit"] == "update") {
		update_qty($user_id, $_POST["pk_product_id"], $_POST["qty"], $CONNECTION);
	}
	else if($_POST["submit"] == "remove") {
		remove_product($user_id, $_POST["pk_product_id"], $CONNECTION);
	}
}

$cart_products = get_cart_products($user_id, $CONNECTION);

function update_qty($user_id, $product_id, $qty, $connection) {
	if($qty < 0)
		return;

	$sqlString = 'UPDATE nepbuy_carts SET PRODUCT_QUANTITY = '.$qty." WHERE USER_SESSION='".$user_id."' AND FK_PRODUCT_ID=".$product_id;
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

<!-- hero page -->
<section id="hero-page1">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">
				<div class="col-sm-3">
					<img src="/nepbuy/images/1.jpg" class="img-circle" alt="Cinque Terre" width="200px" height="200px"> 
				</div>
				<div class="col-sm-9">
					<h2 class="title">Rejil Shrestha</h2>
					<p class="text">They bring mad skills, tons of passion, and expertise in a delicious array of cuisines.</p>
					<br>
					<a class="edit-profile" href="/nepbuy/account/profile.php"><i class="fa fa-edit"></i>Edit Profile</a>
				</div>
			</div>

		</div>

	</div>            
</section>		
<!-- hero page  -->

<!-- product special / latest -->
<section id="">		
	<div class="row">
		<div class="container">

			<div class="col-sm-12">
				<h3>Shopping Cart</h3>
				<table class="cart">
					<div class="cart-item">
						<h2><i class="fa fa-shopping-cart"></i><?php echo " ".count($cart_products)." items"; ?></h2>
					</div>

					<thead>
						<tr>
							<th>ItemName</th>
							<th>Category</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$total = 0;
						foreach ($cart_products as $cart_product) {
							$product = get_product($cart_product["FK_PRODUCT_ID"], $CONNECTION);
							?>	
							<tr>
								<form method="post">
									<td><?php echo $product["NAME"]; ?></td>
									<td>Butcher</td>
									<input name="pk_product_id" type="hidden" value="<?php echo $product["PK_PRODUCT_ID"]; ?>"/>
									<td><input class="inputfield" name= "qty" type="number" value="<?php echo $cart_product["PRODUCT_QUANTITY"];?>" min="<?php echo $product["MIN_ORDER"];?>" 
										max="<?php 
										if (isset($product['MAX_ORDER']) && $product['MAX_ORDER'] > $product['STOCK_AVAILABLE']) 
											echo $product['STOCK_AVAILABLE'];
										elseif (!isset($product['MAX_ORDER'])) {
											echo $product['STOCK_AVAILABLE'];
										} else {
											echo $product['MAX_ORDER']; 
										}
										?>"/></td>
										<td><?php echo $product["PRICE"]; ?></td>
										<td>
											<?php 
											echo floatval($product["PRICE"])*intval($cart_product["PRODUCT_QUANTITY"]);
											$total+=floatval($product["PRICE"])*intval($cart_product["PRODUCT_QUANTITY"]); 
											?>
										</td>
									<!-- <td>
										<a href="" class="cart-btn">
											<i class="fa fa-refresh"></i>
										</a>
										<input name="submit" type="submit" value="update"/>
										<a href="" class="cart-btn">
											<i class="fa fa-close"></i>
										</a>
									</td> -->
									<td>
										<input class="btn btn-info" name="submit" type="submit" value="update"/>
										<input class="btn btn-danger" name="submit" type="submit" value="remove"/>
									</td>
								</form>

							</tr>

							<?php
						}
						?>

						<tr class="cart-total">
							<th colspan="4">Grand Total</th>
							<th><?php echo $total; ?></th>

						</tr>

					</tbody>
				</table>

				<a href="/nepbuy/index.php" class="btn btn-default">Continue Shopping</a>
				<?php 
					if(count($cart_products) > 0) {
					?>
						<a href="delivery.php" class="cart-btn-main">Checkout</a>
					<?php
					}
				?>

			</div>


		</div>

	</div>        
</section>		
<!-- product special end -->

<!-- hero page -->
<section id="hero-page">
	<div class="row">
		<div class="container">
			<div class="col-sm-8">
				<h2 class="title">Delivery Info</h2>
				<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
			</div>
			<div class="col-sm-4">
				<img src="/nepbuy/images/img/hero1.png" >
			</div>
		</div>

	</div>            
</section>		
<!-- hero page  -->
<?php
	require __DIR__ . '/../includes/footer.php';
?>