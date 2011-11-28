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
<?php if (!db_access::isMember(phpCAS::getUser())) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else if (!db_access::isEquipmentManager( db_access::getPidFromCaseId(phpCAS::getUser()) ) ) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { //is equipment manager
	// view all equipment, with links to:
	// allow editing of all equipment
	// allow checking in/out of all equipment
	echo "<h2>Inventory Administration</h2>";
}
?>
<?php include("phpincludes/footer.php"); ?>
