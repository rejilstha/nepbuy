<?php
require_once __DIR__ ."/connection.php";
include("includes/header.php");

$results = array();

if(isset($_GET["q"])) {
	$category = (isset($_GET["category"]) ? $_GET["category"] : "all-categories");
	$products = get_search_results($_GET["q"], $category, $CONNECTION);
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

	// function get_product_types($connection) {
	// 	$sqlString = "SELECT * FROM nepbuy_product_types WHERE FK_PARENT_ID IS NULL";
	// 	$stid = oci_parse($connection, $sqlString);
	// 	oci_execute($stid);

	// 	$product_types = array();
	// 	while($product_type = oci_fetch_assoc($stid)) {
	// 		array_push($product_types, $product_type);
	// 	}
	// 	return $product_types;
	// }
?>

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

<div class="row">
	<div class="container">
		<form method="get">
			<div class="form-group">
				<div class="col-sm-8">
					<input class="form-control" type="text" value="" name="q" placeholder="Search..." />
				</div>
				<div class="col-sm-2">
					<select class="form-control" name="category">
						<option value="all-categories">All categories</option>
						<?php
						foreach ($product_types as $product_type) {
							?>
							<option value="<?php echo $product_type['PK_PRODUCT_TYPE_ID']; ?>"><?php echo $product_type["NAME"]; ?></option>
							<?php				
						}
						?>
					</select>
				</div>
				<div class="col-sm-2">
					<input class="btn btn-info" type="submit" value="Search">
				</div>
			</div>
		</form>

		<div id="search-results">
			<?php
			foreach ($products as $product) {
				?>
				<div class="col-sm-3 item">
					<span>
						<img src="<?php echo $product["PHOTO_LOCATION"]; ?>" height="185px">
						<p>
							<span style="display: block;"><?php echo $product["NAME"]; ?></span>
							<strong>Price $<?php echo $product["PRICE"]; ?></strong> 
							<a href="#"><i class="fa fa-shopping-cart"></i></a> 
							<a href="/nepbuy/product/details.php?id=<?php echo $product["PK_PRODUCT_ID"]; ?>" class="view-btn">View Details</a>
						</p>
					</span>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<?php include("includes/footer.php"); ?>