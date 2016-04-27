<?php
	include("connection.php");

	$results = array();

	if(isset($_GET["q"])) {
		$category = (isset($_GET["category"]) ? $_GET["category"] : "all-categories");
		$results = get_search_results($_GET["q"], $category, $CONNECTION);
	}

	function get_search_results($query, $category, $connection) {
		$sqlString = "SELECT * FROM nepbuy_products WHERE UPPER(NAME) like UPPER('%".$query."%')";
		if($category != "all-categories")
			$sqlString = $sqlString." AND FK_PRODUCT_TYPE_ID=".$category;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$results = array();
		while ($result = oci_fetch_assoc($stid)) {
			array_push($results, $result);
		}

		return $results;
	}

	$product_types = get_product_types($CONNECTION);

	function get_product_types($connection) {
		$sqlString = "SELECT * FROM nepbuy_product_types WHERE FK_PARENT_ID IS NULL";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$product_types = array();
		while($product_type = oci_fetch_assoc($stid)) {
			array_push($product_types, $product_type);
		}
		return $product_types;
	}
?>

<form method="get">
	<input type="text" value="" name="q"/>
	<select name="category">
		<option value="all-categories">All categories</option>
		<?php
			foreach ($product_types as $product_type) {
				?>
				<option value="<?php echo $product_type['PK_PRODUCT_TYPE_ID']; ?>"><?php echo $product_type["NAME"]; ?></option>
			<?php				
			}
		?>
	</select>
	<input type="submit" value="Search">
</div>

<div id="search-results">
	<?php
	foreach ($results as $result) {
		?>
		<div>
			<a href="product.php?id=<?php echo $result['PK_PRODUCT_ID']; ?>"><?php echo $result["NAME"]; ?></a>
		</div>
	<?php
	}
	?>
</div>