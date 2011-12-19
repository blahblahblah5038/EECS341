<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");
?>
<h2>Inventory: View Details</h2>

<?php
function print_info($row, $type)
{
	// type can be: blank, S, L, A, R (for stabilizer, limb, arrow, and riser)
	$headers = array("EID", "Type", "Serial No.", "Brand", "Owner");
	
	// this could probably be done more efficiently
	$s_headers = array("Length");
	$l_headers = array("Interface", "Distinguishing Marks", "Draw Strength");
	$a_headers = array("Model", "Complete Arrow", "Bare Shaft", "Fixable", "Notes");
	$r_headers = array("Interface", "Height", "Distinguishing Marks", "Handedness", "Button Format", "Button", "Arrow Rest");
	
	switch($type)
	{
		case 'S': $headers = array_merge($headers, $s_headers); break;
		case 'L': $headers = array_merge($headers, $l_headers); break;
		case 'A': $headers = array_merge($headers, $a_headers); break;
		case 'R': $headers = array_merge($headers, $r_headers); break;
		default: break;
	}
	
	echo "<table>";
	for ($i = 0; $i < count($row); $i++)
		echo "<tr><td>".$headers[$i]."</td><td>".$row[$i]."</td></tr>";
	
	echo "</table>";
}

$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid)) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { // is a member
	// Get type of equipment
	$eid = $_POST["eid"]; //TODO: sanitize
	$query = db_equipment::getEquipment($eid);
	$row = mysqli_fetch_row($query);
	$type = $row[1];
	
	// Display detailed info about the item
	$row = db_equipment::viewEquipment($eid, $type);
	
	// Check if equipment manager
	$isEqMan = FALSE;
	if (db_access::isEquipmentManager($userpid)) {
		$isEqMan = TRUE;
		
		// print buttons to edit / delete this item
		echo "<form action='edit.php' method='POST'>" .
			"<input type='hidden' value='".$row[0]."' name='eid'>" .
			"<input type='hidden' value='".$row[1]."' name='type' />" .
			"<input type='submit' name='edit' value='Edit' /></form> ";
		echo "<form action='delete.php' method='POST'>" .
			"<input type='hidden' value='".$row[0]."' name='eid'>" .
			"<input type='submit' name='delete' value='Delete' /></form>";
	}
	
	print_info($row, $type);
	
	// Link to check out
	if (db_equipment::isCheckedOut($row[0])) {
		echo "<span class='error'>Checked Out</span>";
		
		if ($isEqMan)
			echo "<form action='checkin.php' method='POST'>" .
				"<input type='hidden' value='".$row[0]."' name='eid'>" .
				"<input type='submit' name='checkin' value='Check In' /></form>";
	}
	else
		echo "<form action='checkout.php' method='POST'>" .
			"<input type='hidden' value='".$row[0]."' name='eid'>" .
			"<input type='submit' name='checkout' value='Check Out' /></form>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
