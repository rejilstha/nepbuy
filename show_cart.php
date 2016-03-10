<?php
	//collect cart data from database
?>
<?php
	$total = 0;
	foreach ($cart_product as $cart) {
		$product;
		$user;
?>	
	<div>
		<?php
			echo $product["photo"];
		?>
		Product Name:
		<?php
			echo $product["name"];
		?>
		Product Price:
		<?php
			echo $product["price"];
		?>
		Quantity:
		<?php
			echo $cart_product["qty"];
		?>
		Sub-Total:
		<?php
			echo $product["price"]*$cart_product["qty"];
			$total+=$product["price"]*$cart_product["qty"];
		?>
		<form>
			<input name= "qty" type="number" value="<?php echo $cart["qty"];?>" min="<?php echo $product["min_order"];?>" max="<?php echo $product["max_order"];?>"/>
			<input type="submit" value="update"/>
		</form>
		<form>
			<input type="submit" value="remove"/>
		</form>
	</div>	
	<?php
	}
?>
<div>
	Total:
	<?php
			echo $total;
	?>
	<form action="post">
		<input type="submit" value="checkout"/>
	</form>
</div>
