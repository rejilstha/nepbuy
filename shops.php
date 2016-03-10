<?php
	//list of products of a shop
    $shop;
    $products;
	$trader;
	
	//display shop information
?>
	<div>
		Shop Name:
		<?php
			echo $shop["name"];
		?>
		Shop Location:
		<?php
			echo $shop["location"];
		?>
		Trader:
		<?php
			echo $trader["name"];
		?>
	</div>
	
<?php
	foreach ($product as $products) {
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
	</div>	
	<?php
	}
?>