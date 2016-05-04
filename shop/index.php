<?php
require __DIR__ ."/../connection.php";
if(!(require __DIR__ . '/../trader_access.php')) {
	return;
}
require __DIR__ ."/../includes/header.php";

$trader = $_SESSION["user_session"];

// Submitted from the add shop.
if(isset($_POST["create-shop-submit"])) {
	add_shop(
		$_POST["name"], $_POST["location"], $trader, $MAX_SHOPS_ALLOWED, $CONNECTION);
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

	$sqlString = "INSERT INTO nepbuy_shops(NAME,LOCATION,FK_USER_ID,STATUS) VALUES('
	$shop_name','$location',$trader,'Pending')";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);
}

$sqlString = "SELECT * FROM nepbuy_shops WHERE FK_USER_ID=$trader ORDER BY PK_SHOP_ID";
$stid = oci_parse($CONNECTION, $sqlString);
if(oci_execute($stid) > 0) {
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
			<table class="table table-striped">
				<tr>
					<th>Name</th>
					<th>Location</th>
					<th>Status</th>
					<th></th>
				</tr>
				<?php
				while($row = oci_fetch_assoc($stid)) {
					?>
					<tr>
						<td><?php echo $row['NAME']; ?></td>
						<td><?php echo $row['LOCATION']; ?></td>
						<td><?php echo $row['STATUS']; ?></td>
						<td><a class="btn btn-default" href="edit.php?id=<?php echo $row["PK_SHOP_ID"]; ?>">Edit</a></td>
					</tr>
					<?php
				}
				?>
			</table>
		</div>
	</div>
	<?php
}
require __DIR__ ."/../includes/footer.php";
?>