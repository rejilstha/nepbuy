<?php
	require __DIR__ . '/../connection.php';
	if(!(require __DIR__ . '/../trader_access.php')) {
		return;
	}
	require __DIR__ . '/../includes/header.php';

	$trader = $_SESSION["user_session"];

	if(!isset($_GET["id"]))
	{
		echo "No shop"; //404
		return;
	}

	// If submitted from edit form
	if(isset($_POST["edit-shop-submit"])) {
		edit_shop($_POST["id"],$_POST["name"], $_POST["location"], $CONNECTION);
	}

	$shop = get_shop($_GET["id"], $trader, $CONNECTION);
	if($shop == NULL)
	{
		echo "No shop";
		return;
	}

	function edit_shop($id, $name, $location, $connection) {

		$sqlString = "UPDATE nepbuy_shops SET ".
					"NAME='$name',LOCATION='$location' ".
					"WHERE PK_SHOP_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	function get_shop($id, $trader, $connection) {
		$sqlString = "SELECT * FROM nepbuy_shops WHERE FK_USER_ID=$trader AND PK_SHOP_ID = $id";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			return oci_fetch_assoc($stid);
		}
	}
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
	<div class="container-fluid">
		<div class="row-fluid">
	<form method="post">
		<input name="id" type="hidden" value="<?php echo $shop['PK_SHOP_ID']; ?>">
		<div>
		<input required class="inputfield" name="name" type="text" value="<?php echo $shop['NAME']; ?>" placeholder="Name of the shop" required>
		</div>
		<div>
		<input class="inputfield" name="location" type="text" value="<?php echo $shop['LOCATION']; ?>" placeholder="Location">
		</div>
		<input class="submit-btn" type="submit" name="edit-shop-submit" value="Edit shop">
	</form>
	</div>
	</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>