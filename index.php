<?php
include("connection.php");

if(isset($_POST["admin-logout-submit"])) {
		//Logout
	$_SESSION["user_session"] = trim(com_create_guid(), '{}');
}

$products = get_products($CONNECTION);

function get_products($connection) {
	$sqlString = "SELECT p.* FROM nepbuy_products p ".
	"WHERE ROWNUM <=4 ORDER BY PK_PRODUCT_ID DESC";

	$products = array();

	$stid = oci_parse($connection, $sqlString);
	if(oci_execute($stid) > 0) {
		while($product = oci_fetch_assoc($stid)) {
			array_push($products, $product);
		}
	}

	return $products;
}

include('includes/header.php');
?>

<!-- slider -->
<section>
	
	<!-- Start WOWSlider.com BODY section --> <!-- add to the <body> of your page -->
	<div id="wowslider-container1">
		<div class="ws_images"><ul>
			<li><img src="data1/images/2.jpg" alt="2" title="2" id="wows1_0"/></li>
			<li><a href="http://wowslider.com/vi"><img src="data1/images/4.jpg" alt="bootstrap carousel" title="4" id="wows1_1"/></a></li>
			<li><img src="data1/images/5.jpg" alt="5" title="5" id="wows1_2"/></li>
		</ul></div>
		<div class="ws_bullets"><div>
			<a href="#" title="2"><span><img src="data1/tooltips/2.jpg" alt="2"/>1</span></a>
			<a href="#" title="4"><span><img src="data1/tooltips/4.jpg" alt="4"/>2</span></a>
			<a href="#" title="5"><span><img src="data1/tooltips/5.jpg" alt="5"/>3</span></a>
		</div></div><div class="ws_script" style="position:absolute;left:-99%"><a href="http://wowslider.com/vi">cssslider</a> by WOWSlider.com v8.7</div>
		<div class="ws_shadow"></div>
	</div>	
	<script type="text/javascript" src="engine1/wowslider.js"></script>
	<script type="text/javascript" src="engine1/script.js"></script>
	<!-- End WOWSlider.com BODY section -->


</section>		
<!-- SliderEnd  -->

<!-- hero page -->
<section id="hero-page0">
	<div class="row">
		<div class="container">
			<div class="col-sm-12">
				<h2 class="title">Welcome</h2>
				<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
			</div>

		</div>

	</div>            
</section>		
<!-- hero page  -->

<!-- product special / latest -->
<section id="special-offer">		
	<div class="row">
		<div class="container">
			<h2 class="special-txt">Special Product</h2>
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
</section>		
<!-- product special end -->

<!-- hero page -->
<section id="hero-page">
	<div class="row">
		<div class="container">
			<div class="col-sm-8">
				<h2 class="title">NepBuy Introduction</h2>
				<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
			</div>
			<div class="col-sm-4">
				<img src="images/img/hero.png" >
			</div>
		</div>

	</div>            
</section>		
<!-- hero page  -->


<?php include('includes/footer.php'); ?>

