<?php
require __DIR__ . '/../../connection.php';
require __DIR__ . '/../admin_access.php';
require __DIR__ ."/../includes/header.php";


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
					<div class="form-group">
						<label for="status">Status</label>
						<select class="form-control" name="status" required>
							<option value="Verified">Verified</option>
							<option value="Pending">Pending</option>
						</select>
					</div>
					<div class="submit-btn">
						<input class="add-btn" type="submit" name="create-shop-submit" value="Save shop"/>
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