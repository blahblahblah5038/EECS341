<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");
	include("../phpincludes/db_members.php");
?>
<h2>Inventory: Check Out</h2>

<?php
$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid))
	echo "<div class='error'>Sorry, you are not authorized to view this page.</div>";
if (!isset($_POST['eid']))
{
	echo "<div class='error'>No EID specified<br /><a href='/inventory'>Back to inventory</a></div>";
} else {
	// Check if equipment manager
	$isEqMan = FALSE;
	if (db_access::isEquipmentManager($userpid))
		$isEqMan = TRUE;
	
	$eid = $_POST['eid'];
	if (!db_equipment::isCheckedOut($eid))
	{
		if (isset($_POST['sub_checkout']) && isset($_POST['borrower']) && isset($_POST['date']) && $_POST['date'] != '')
		{	// form was submitted, with info
			//TODO: validate
			$borrower = $_POST['borrower'];
			$condition = $_POST['condition'];
			$notes = $_POST['notes'];
			$date = $_POST['date'];
			$query = db_equipment::loan($eid, $borrower, $condition, $notes, $date);
			echo "<p>Equipment has been checked out.<br /><a href='/inventory'>Back to inventory</a></p>";
		} else { // print form
			echo "<form action='checkout.php' method='POST'>";
			echo "<input type='hidden' value='".$eid."' name='eid' />";
			echo "<table class='noborder'>";
			if ($isEqMan) {
				// For equipment manager: drop-down list of members
				$memberlist = getMembers(NULL);
				$row = mysqli_fetch_row($memberlist);
				echo "<tr><td>Borrower</td><td><select name='borrower'>";
				while ($row)	// display netid, submit pid
				{
					echo "<option value='".$row[1]."'>".$row[2]."</option>";
					$row = mysqli_fetch_row($memberlist);
				}
			} else {
				// For other members: only check out for themselves
				echo "<input type='hidden' value='".$userpid."' name='borrower'/>";
			}
			echo "</td></tr><tr><td>Condition</td><td><input type='text' name='condition' /></td></tr>";
			echo "<tr><td>Notes</td><td><input type='text' name='notes' /></td></tr>";
			// this doesn't work
			echo "<tr><td>Check-Out Date (YYYY-MM-DD)</td><td><input type='text' value'".date("Y-m-d")."' name='date' /></td></tr>";
			echo "<tr><td><input type='submit' name='sub_checkout' value='Check Out' /></td></tr>";
			echo "</table></form>";
		}
	} else { // item wasn't checked out
		echo "<p>This item is already checked out.<br /><a href='/inventory'>Back to inventory</a></p>";
	}
}
?>
<?php include("../phpincludes/footer.php"); ?>
