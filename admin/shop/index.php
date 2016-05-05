<?php
require __DIR__ . '/../../connection.php';
require __DIR__ . '/../admin_access.php';
require __DIR__ ."/../includes/header.php";

	// Submitted from the add shop.
if(isset($_POST["create-shop-submit"])) {
	add_shop(
		$_POST["name"], $_POST["location"], $_POST["trader"], $_POST["status"], $CONNECTION);
}

if(isset($_POST["confirm-shop-submit"])) {
	confirm_shop($_POST["shop-id"], $CONNECTION);
}

function add_shop(
	$shop_name, $location, $trader, $status, $connection) {

	$sqlString = "INSERT INTO nepbuy_shops(NAME,LOCATION,FK_USER_ID,STATUS) VALUES('$shop_name','$location',$trader,'$status')";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);
}

function confirm_shop($shop_id, $connection) {
	$sqlString = "UPDATE nepbuy_shops SET STATUS='Verified' WHERE PK_SHOP_ID=$shop_id";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);
}

function get_shop_trader($trader, $connection) {
	$sqlString = "SELECT * FROM nepbuy_users WHERE PK_USER_ID=".$trader;
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);

	return oci_fetch_assoc($stid);
}


$sqlString = "SELECT * FROM nepbuy_shops ORDER BY PK_SHOP_ID";
$stid = oci_parse($CONNECTION, $sqlString);
if(oci_execute($stid) > 0) {
	?>
	<div class="container-fluid-full">
		<div class="row-fluid">

			<?php require __DIR__.'/../includes/nav.php'; ?>

			<!-- start: Content -->
			<div id="content" class="span10">				
				<ul class="breadcrumb">
					<li>
						<i class="icon-home"></i>
						<a href="index.html">Home</a>
						<i class="icon-angle-right"></i>
					</li>
					<li><a href="index.php">Shops</a></li>
				</ul>

				<div class="row-fluid">
					<div class="span12" onTablet="span12" onDesktop="span12">
					<h2 class="headV">List Shops</h2>
					<a class="add-btn" href="add.php"><i class="icon-plus"></i> Add New</a>
					<table class="table table-striped">
						<tr>
							<th>Name</th>
							<th>Location</th>
							<th>Shop Owner</th>
							<th>Status</th>
							<th></th>
							<th></th>
						</tr>
						<?php
						while($row = oci_fetch_assoc($stid)) {
							$trader = get_shop_trader($row['FK_USER_ID'], $CONNECTION);
							?>
							<tr>
								<td><?php echo $row['NAME']; ?></td>
								<td><?php echo $row['LOCATION']; ?></td>
								<td><?php echo $trader['NAME']; ?></td>
								<td><?php echo $row['STATUS']; ?></td>
								<td><a class="btn btn-default" href="edit.php?id=<?php echo $row["PK_SHOP_ID"];?>"><i class="icon-edit"></i> Edit</a></td>
								<td>
									<?php 
									if($row['STATUS'] != 'Verified') {
										?>
										<form method="post">
											<input name="shop-id" type="hidden" value="<?php echo $row["PK_SHOP_ID"]; ?>" />
											<input name="confirm-shop-submit" class="btn btn-default" type="submit" value="Confirm"/>
										</form>
									<?php
									}
									?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php
}
require __DIR__ ."/../includes/footer.php";
?>