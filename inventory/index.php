<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");

	/* from the specs...
	The club has numerous pieces of equipment that need tracked,
	which fall into the general categories of parts and arrows.
	Parts include the sub-categories of Limbs, Risers, Arrows, and
	Stabilizers, each of which has a separate set of additional
	attributes to record.  The equipment manager will be able to
	add and modify equipment.  Users can check equipment in and
	out for themselves; the equipment manager can check in and out
	for all users.
	*/
?>
<h2>Inventory</h2>

<?php
$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid)) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { // is a member
	$isEqMan = FALSE;
	if (db_access::isEquipmentManager($userpid))
		$isEqMan = TRUE;
	
	// Display member equipment info
	$userequip = getEquipmentByOwner($userpid);
	$row = mysqli_fetch_row($userequip);
	echo "<h3>My Equipment</h3>";
	echo "<table><tr><th>Type</th><th>Serial No.</th><th>Brand</th><th>Owner</th><th>Status</th></tr>";
	while ( $row = mysqli_fetch_row($userequip))
	{
		echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td>";
		if (isCheckedOut($row[0]))
			echo "<td><span class='error'>Checked out</td></tr>";
		else
			echo "<td><form action='checkout.php' method='POST'><input type='hidden' value='".$row[0]."' name='eid'><input type='submit' name='checkout' value='Check Out' /></form></td></tr>";
	}
	echo "</table>";
	
	$borrowed = getEquipmentByBorrower($userpid);
	$row = mysqli_fetch_row($borrowed);
	echo "<h3>My Borrowed Equipment</h3>";
	echo "<table><tr><th>Type</th><th>Serial No.</th><th>Brand</th><th>Owner</th></tr>";
	while ($row)
	{
		// users do not check in their own equipment - only equipment managers check things in
		$row = mysqli_fetch_row($borrowed);
		echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
	}
	echo "</table>";
	
	// Show inventory
	$equiplist = getEquipment();
	$row = mysqli_fetch_row($equiplist);
	echo "<h3>Inventory</h3>";
	echo "<table><tr><th>Type</th><th>Serial No.</th><th>Brand</th><th>Owner</th><th></th></tr>";
	while ($row)
	{
		$row = mysqli_fetch_row($equiplist);
		// All users can check equipment out, only equipment managers can check in
		echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>";
		echo '<form action="details.php" method="POST"><input type="hidden" value="'.$row[0].'" name="eid"><input type="hidden" value="'.$row[1].'" name="type" /><input type="submit" value="Details" /></form>';
		
		if ($isEqMan) {
			echo "<form action='edit.php' method='POST'><input type='hidden' value='".$row[0]."' name='eid'><input type='submit' name='edit' value='Edit' /></form>";
			echo "<form action='delete.php' method='POST'><input type='hidden' value='".$row[0]."' name='eid'><input type='submit' name='delete' value='Delete' /></form>";
		}
		
		if (isCheckedOut($row[0])) {
			echo "<span class='error'>Checked Out</span>";
			if ($isEqMan)
				echo "<form action='checkin.php' method='POST'><input type='hidden' value='".$row[0]."' name='eid'><input type='submit' name='checkin' value='Check In' /></form>";
		}
		else
			echo "<form action='checkout.php' method='POST'><input type='hidden' value='".$row[0]."' name='eid'><input type='submit' name='checkout' value='Check Out' /></form>";
		
		echo "</td></tr>";
	}
	echo "</table>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
