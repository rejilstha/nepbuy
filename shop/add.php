<?php
	require __DIR__ . "/../connection.php";
	if(!(require __DIR__ . "/../trader_access.php")) {
		return;
	}
	require __DIR__ . "/../includes/constants.php";
	require __DIR__ . "/../includes/header.php";

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
			<input class="btn btn-info" type="submit" name="create-shop-submit" value="Create shop">
		</form>
	</div>
</div>

<?php
	include(__DIR__."/../includes/footer.php");
?>