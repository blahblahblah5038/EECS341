<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");
?>
<h2>Inventory: View Log</h2>

<?php
$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid) || !db_access::isEquipmentManager($userpid)) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { // is equipment manager
	$isEqMan = FALSE;
	
	// Display log info for all items
	$log = db_equipment::viewAllLoans();
	
	echo "<h3>Checkout Log</h3>";
	echo "<table><tr><th>ID</th><th>User</th><th>Condition</th><th>Notes</th><th>Checkout</th><th>Checkin</th></tr>";
	while ($row = mysqli_fetch_row($log))
	{	
		// show link to equipment details
		echo "<tr><td><form action='details.php' method='POST'>";
		echo "<input type='hidden' name='eid' value='".$row[1]."' />";
		echo "<input type='submit' name='submit' value='".$row[1]."' />";
		echo "</form></td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td></tr>";
	}
	echo "</table>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
