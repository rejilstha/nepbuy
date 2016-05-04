<?php
	require __DIR__ . "/../connection.php";
	require __DIR__ . "/../includes/constants.php";
	require __DIR__ . "/../includes/header.php";
	//include("includes/header.php");

	if(isset($_POST["create-shop-submit"])) {
		add_shop(
			$_POST["name"], $_POST["location"], $_POST["trader"], $MAX_SHOPS_ALLOWED, $CONNECTION);
	}

	function add_shop(
		$shop_name, $location, $trader, $max_shops_allowed, $connection) {

		$sqlString = "SELECT COUNT(*) as COUNT FROM nepbuy_shops";
		$stid = oci_parse($connection, $sqlString);
		if(oci_execute($stid) > 0) {
			$count = oci_fetch_assoc($stid)["COUNT"];
			if($count == $max_shops_allowed) {
				echo "Max shop limit reached.";
				return;
			}
		}

		$sqlString = "INSERT INTO nepbuy_shops(NAME,LOCATION,FK_USER_ID) VALUES('".
					$shop_name."','".$location."',".$trader.")";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);
	}

	$traders = get_traders($CONNECTION);

	function get_traders($connection) {
		$trader_id = get_trader_id($connection);

		$sqlString = "SELECT u.PK_USER_ID,u.NAME FROM nepbuy_user_roles ur ".
					"JOIN nepbuy_users u ON u.PK_USER_ID=ur.FK_USER_ID ".
					"WHERE FK_ROLE_ID=".$trader_id;
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$traders = array();
		while($trader = oci_fetch_assoc($stid)) {
			array_push($traders, $trader);
		}
		return $traders;
	}

	function get_trader_id($connection) {
		$sqlString = "SELECT PK_ROLE_ID FROM nepbuy_roles WHERE NAME='Trader'";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		return oci_fetch_assoc($stid)["PK_ROLE_ID"];
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
		<form method="post" action="index.php">
			<div class="form-group">
				<label for="name">Shop</label>
				<input class="form-control" name="name" type="text" value="" placeholder="Name of the shop" required>
			</div>
			<div class="form-group">
				<label for="location">Location</label>
				<input class="form-control" name="location" type="text" value="" placeholder="Location">
			</div>
			<div class="form-group">
				<label for="trader">Trader</label>
				<select class="form-control" name="trader" required>
					<?php
						foreach ($traders as $trader) {
							?>
							<option value="<?php echo $trader["PK_USER_ID"]; ?>"><?php echo $trader["NAME"]; ?></option>
						 <?php 
						} 
						?>
				</select>
			</div>
			<input class="btn btn-info" type="submit" name="create-shop-submit" value="Create shop">
		</form>
	</div>
</div>

<?php
	include(__DIR__."/../includes/footer.php");
?>