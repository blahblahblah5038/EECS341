<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");
?>
<h2>Inventory: Check In</h2>

<?php
$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid) && !db_access::isEquipmentManager($userpid))
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
if (!isset($_POST['eid']))
{
	echo "<div class='error'>No EID specified</div>";
} else {
	$eid = $_POST['eid'];
	if (db_equipment::isCheckedOut($row[0])) {
		if (isset($_POST['sub_checkin']) && isset($_POST['date']) && $_POST['date'] != '')
		{	// form was submitted, with info
			//TODO: validate date
			$date = $_POST['date'];
			$query = db_equipment::checkin($eid, $date);
			echo "<p>Equipment has been checked in.<br /><a href='/inventory'>Back to inventory</a></p>";
		} else { // print form
			// TODO: print info like who is checking it in
			// enter date of checkin time (auto to current)
			echo "<form action='checkin.php' method='POST'>";
			echo "Check-In Date (YYYY-MM-DD):<input type='text' value'".date("Y-m-d")."' name='date' />";
			echo "<input type='hidden' value='".$eid."' name='eid' />";
			echo "<input type='submit' name='sub_checkin' value='Check In' />";
			echo "</form>";
		}
	} else { // item wasn't checked out
		echo "<p>This item is already checked in.<br /><a href='/inventory'>Back to inventory</a></p>";
	}
}
?>
<?php include("../phpincludes/footer.php"); ?>
