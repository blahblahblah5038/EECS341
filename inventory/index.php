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
	if (db_access::isEquipmentManager($userpid)) {
		echo "<a href='admin.php'>Administration</a>";
	}
	
	// Display member equipment info
	$userequip = getEquipmentByOwner($userpid);
	$row = mysqli_fetch_row($userequip); //FYI, I think this may be incorrect, see contacts/index.php -wes
	echo "<h3>My Equipment</h3>";
	echo "<table><tr><th>Type</th><th>Serial No.</th><th>Brand</th><th>Owner</th></tr>";
	while ( $row = mysqli_fetch_row($userequip))
	{
		//TODO: links
		echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
	}
	echo "</table>";
	
	$borrowed = getEquipmentByBorrower($userpid);
	$row = mysqli_fetch_row($borrowed);
	echo "<h3>My Borrowed Equipment</h3>";
	echo "<table><tr><th>Type</th><th>Serial No.</th><th>Brand</th><th>Owner</th></tr>";
	while ($row)
	{
		//TODO: links
		$row = mysqli_fetch_row($borrowed);
		echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
	}
	echo "</table>";
	
	// Show inventory
	$equiplist = getEquipment();
	$row = mysqli_fetch_row($equiplist);
	echo "<table><tr><th>Type</th><th>Serial No.</th><th>Brand</th><th>Owner</th></tr>";
	while ($row)
	{
		$row = mysqli_fetch_row($equiplist);
		//TODO: links for checking in/out
		echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td></tr>";
	}
	echo "</table>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
