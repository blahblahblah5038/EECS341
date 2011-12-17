<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");
?>
<h2>Inventory: Delete Item</h2>

<?php
$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid) && !db_access::isEquipmentManager($userpid)) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else {
	if (isset($_POST['eid']))
	{
		$eid = $_POST['eid'];
		if (isset($_POST['del_conf'])) {
			// user confirmed delete
			db_equipment::deleteEquipment($eid);
			echo "<p>Equipment successfully deleted.<br /><a href='/inventory'>Back to inventory</a></p>";
		} else {
			// display info and confirmation
			$info = db_equipment::getEquipment($eid);
			$row = mysqli_fetch_row($info);
			echo "<p>Equipment Record:<br />";
			echo "Type: ".$row[1];
			echo "<br />Serial No.: ".$row[2];
			echo "<br />Brand: ".$row[3];
			//echo "<br />Owner: ".$row[4]; //TODO: make this a name not a pid
			echo "</p><h3>Really delete?</h3>";
			echo '<form action="delete.php" method="POST"><input type="hidden" name="eid" value="'.$eid.'" />';
			echo '<input type="submit" name="del_conf" value="Yes, delete" /></form>';
			echo '<form action="/inventory" method="POST"><input type="submit" value="No, cancel" /></form>';
		}
	} else { // no eid
		echo "<p class='error'>Error: No EID specified<br /><a href='/inventory'>Back to inventory</a></p>";
	}
}
?>
<?php include("../phpincludes/footer.php"); ?>
