<?php
	include("phpincludes/header.php");
	include("phpincludes/login.php");
	include("phpincludes/db_access.php");
	include("phpincludes/db_equipment.php");
	
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
<?php if (!isMember(phpCAS::getUser())) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else {
	// is a member
	if (isEquipmentManager(phpCAS::getUser())) {
		// Allow adding equipment to the inventory
		// Allow checking in/out for all users
		echo "<div class='admin'>";
		
		echo "</div>"
	}
	// Show member info
	// i.e., which equipments are checked out to you
	// (call getEquipByOwner, getEquipByBorrower)
	$userequip = getEquipmentByOwner($pid);
	$row = mysqli_fetch_row($userequip);
	echo "<h3>My Equipment</h3>";
	while ($row)
	{
		//TODO: print links
		$row = mysqli_fetch_row($userequip);
	}
	
	$borrowed = getEquipmentByBorrower($pid);
	$row = mysqli_fetch_row($borrowed);
	echo "<h3>My Borrowed Equipment</h3>";
	while ($row)
	{
		//TODO: print links
		$row = mysqli_fetch_row($borrowed);
	}
	
	// Show inventory
	// probably shouldn't show owner if not admin?
	$equiplist = getEquipment();
	$row = mysqli_fetch_row($equiplist);
	print "<table><tr><th>Type</th><th>Serial No.</th><th>Brand</th><th>Owner</th></tr>";
	while ($row)
	{
		$row = mysqli_fetch_row($equiplist);
		//TODO: links for checking in/out
		print "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[1]."</td><td>".$row[1]."</td></tr>";
	}
	print "</table>";
}
?>

<h2>Inventory</h2>

<?php include("phpincludes/footer.php"); ?>
