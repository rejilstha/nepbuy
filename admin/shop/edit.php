<?php
require __DIR__ . '/../../connection.php';
require __DIR__ ."/../includes/header.php";
require __DIR__ ."/../admin_access.php";

if(!isset($_GET["id"]))
{
	echo "No shop"; //404
	return;
}

	// If submitted from edit form
if(isset($_POST["edit-shop-submit"])) {
	edit_shop($_POST["id"],$_POST["name"], $_POST["location"], $_POST["trader"], $_POST["status"], $CONNECTION);
}

	// If submitted from delete form
if(isset($_POST["delete-shop-submit"])) {
	delete_shop($_POST["id"], $CONNECTION);
	header("Location: shops.php");
}

$shop = get_shop($_GET["id"], $CONNECTION);
if($shop == NULL)
{
	echo "No shop";
	return;
}

$traders = get_traders($CONNECTION);

function delete_shop($id, $connection) {
	$sqlString = "DELETE FROM nepbuy_shops WHERE PK_SHOP_ID=$id";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);
}

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

function edit_shop($id, $name, $location, $trader, $status, $connection) {

	$sqlString = "UPDATE nepbuy_shops SET ".
	"NAME='$name',LOCATION='$location',FK_USER_ID=$trader,STATUS='$status' ".
	"WHERE PK_SHOP_ID = $id";
	$stid = oci_parse($connection, $sqlString);
	oci_execute($stid);
}

function get_shop($id, $connection) {
	$sqlString = "SELECT * FROM nepbuy_shops WHERE PK_SHOP_ID = $id";
	$stid = oci_parse($connection, $sqlString);
	if(oci_execute($stid) > 0) {
		return oci_fetch_assoc($stid);
	}
}
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
				<form method="post">
					<input name="id" type="hidden" value="<?php echo $shop['PK_SHOP_ID']; ?>">
					<div class="form-group">
						<label for="name">Shop</label>
						<input class="form-control" name="name" type="text" value="<?php echo $shop['NAME']; ?>" placeholder="Name of the shop" required>
					</div>
					<div class="form-group">
						<label for="location">Shop</label>
						<input class="form-control" name="location" type="text" value="<?php echo $shop['LOCATION']; ?>" placeholder="Location">
					</div>
					<div class="form-group">
						<label for="trader">Trader</label>
						<select class="form-control" name="trader" required>
							<?php
							foreach ($traders as $trader) {
								if($trader["PK_USER_ID"] == $shop["FK_USER_ID"]) {
									?>
									<option selected value="<?php echo $trader["PK_USER_ID"]; ?>"><?php echo $trader["NAME"]; ?></option>
									<?php 
								} else {
									?>
									<option value="<?php echo $trader["PK_USER_ID"]; ?>"><?php echo $trader["NAME"]; ?></option>
									<?php
								}
							} 
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="status">Status</label>
						<select class="form-control" name="status" required>
							<?php
							if($shop["STATUS"] == "Verified") {
								?>
								<option selected value="Verified">Verified</option>
								<option value="Pending">Pending</option>
								<?php
							} else {
								?>
								<option value="Verified">Verified</option>
								<option selected value="Pending">Pending</option>		
								<?php
							}
							?>
						</select>
					</div>
					<div class="submit-btn">
						<input class="add-btn" type="submit" name="edit-shop-submit" value="Save shop">

					</div>
				</form>

				<form method="post">
					<input name="id" type="hidden" value="<?php echo $shop['PK_SHOP_ID']; ?>">
					<div class="submit-btn">
						<input class="btn btn-danger" type="submit" name="delete-shop-submit" value="Delete">
						<a class="btn btn-default" href="index.php">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
require __DIR__ ."/../includes/footer.php";
?>