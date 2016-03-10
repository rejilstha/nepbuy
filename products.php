<?php
	//detail information of product
    $product;
	$shop;
	$product_type;
	$user;
	if(isset($_POST)){
		add_to_cart($user["pk_user_id"], $product["product_id"], $_POST["qty"]);
	}
	
	function add_to_cart($user, $product, $qty)	{
		//add to cart
	}
?>

<div>
	<?php
			echo $product["photo"];
		?>
		Product Name:
		<?php
			echo $product["name"];
		?>
		Product Description:
		<?php
			echo $product["description"];
		?>
		Product Stock Available:
		<?php
			echo $product["stock_available"];
		?>
		Product Min Order:
		<?php
			if ($product["min_order"!=NULL])
				echo $product["min_order"];
			else 
				echo "No min order.";
		?>
		Product Max Order:
		<?php
			if ($product["max_order"]!=NULL)
				echo $product["max_order"];
			else 
				echo "No max order.";
		?>
		Product Allergy Information:
		<?php
			echo $product["allergy_info"];
		?>
		Product from Shop:
		<?php
			echo $shop["name"];
		?>
		Product Type:
		<?php
			echo $product_type["name"];
		?>
		Product Price:
		<?php
			echo $product["price"];
		?>
		<?php
			if ($product["stock_available"])
			{
				?>
				<form action="post">
					<input name="pk_product_id" type="hidden" value="<?php echo $product["pk_product_id"]; ?>"/>
					<input name= "qty" type="number" value="0" min="<?php echo $product["min_order"];?>" max="<?php echo $product["max_order"];?>"/>
					<input type="submit" value="Add to Cart"/>
				</form>
				<?php
			}
			else {
				echo "No stock available. Choose other product.";
			}	
		?>
		//update quantity if exist
</div>