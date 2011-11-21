<?php include("phpincludes/header.php"); ?>
<?php
	//phpinfo();
?>
<p>This is an example page, this is the main text WOO</p>

<?php include("phpincludes/footer.php"); ?>
<?php
	include("phpincludes/login.php");
	include("phpincludes/db_access.php");
	include("phpincludes/db_equipment.php");
	
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
		
		echo "</div>";

	}/*
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
*/?>

<h2>Inventory</h2>

<?php include("phpincludes/footer.php"); ?>
