<?php
	require __DIR__ ."/../connection.php";

	if (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"])) {
	  // User session is a guid and user isn't registered
		header("Location: login.php?status=1");
	}

	$days = getDeliveryDays($CONNECTION);
	$slots = getDeliverySlots($CONNECTION);

	function getDeliveryDays($connection){

		$sqlString = "Select * from nepbuy_collection_days";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$days = array();
		while($day = oci_fetch_assoc($stid)) {
			array_push($days, $day);
		}
		return $days;
	}

	function getDeliverySlots($connection){
		$sqlString = "Select * from nepbuy_collection_slots";
		$stid = oci_parse($connection, $sqlString);
		oci_execute($stid);

		$slots = array();
		while($slot = oci_fetch_assoc($stid)) {
			array_push($slots, $slot);
		}
		return $slots;
	}
?>

<form method="POST" action="payment.php">
	<div>
		<label>Collection-day:</label>
		<select name="collection-day" required>
			<?php
				foreach ($days as $day) {
					?>
					<option value="<?php echo $day["PK_COLLECTION_DAY_ID"]; ?>"><?php echo $day["NAME"]; ?></option>
					<?php
				}
			?>
		</select>
	</div>
	<div>
		<label>Collection-slot:</label>
		<select name="collection-slot" required>
			<?php
				foreach ($slots as $slot) {
					?>
					<option value="<?php echo $slot["PK_COLLECTION_SLOT_ID"]; ?>"><?php echo $slot["START_TIME"]; ?> - <?php echo $slot["END_TIME"]; ?></option>
					<?php
				}
			?>
		</select>
	</div>
	<input name="submit" type="submit" value="Place order" />
</form>