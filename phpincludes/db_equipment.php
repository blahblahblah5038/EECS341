<?php
// Functions regarding equipment
// db_connect::run_query from include("db_connect.php");

function addEquipment($type, $serialnum, $brand, $owner)
{
	$querystr = "INSERT INTO inventory (type, serialnum, brand, owner) VALUES (" .
		$type . "," .
		$serialnum . "," .
		$brand . "," .
		$owner . ")";
	db_connect::run_query($querystr);
	
	// return eid
	$querystr = "SELECT eid FROM inventory WHERE" .
		"type=" . $type .
		" AND serialnum=" . $serialnum .
		" AND brand=" . $brand .
		" AND owner=" . $owner;
	
	$query = db_connect::run_query($querystr);
	$row = mysqli_fetch_row($query);
	$eid = $row[0];
	return $eid;
}

function addStabilizer($type, $serialnum, $brand, $owner, $length)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO stabilizer (eid, length) VALUES (" .
		$eid . "," .
		$length . ")";
	db_connect::run_query($querystr);
}

function addLimb($type, $serialnum, $brand, $owner, $interface, $distinguishing_marks, $draw_strength)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO limb (eid, interface, distinguishing_marks, draw_strength) VALUES (" .
		$eid . "," .
		$interface . "," .
		$distinguishing_marks . "," .
		$draw_strength . ")";
	db_connect::run_query($querystr);
}

function addArrow($type, $serialnum, $brand, $owner, $model, $complete_arrow, $bare_shaft, $fixable, $notes)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO arrow (eid, model, complete_arrow, bare_shaft, fixable, notes) VALUES (" .
		$eid . "," .
		$model . "," .
		$complete_arrow . "," .
		$bare_shaft . "," .
		$fixable . "," .
		$notes . ")";
	db_connect::run_query($querystr);
}

function addRiser($type, $serialnum, $brand, $owner, $interface, $height, $distinguishing_marks, $handedness, $button_format, $button, $arrow_rest)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO riser (eid, interface, height, distinguishing_marks, handedness, button_format, button, arrow_rest) VALUES (" .
		$eid . "," .
		$interface . "," .
		$height . "," .
		$distinguishing_marks . "," .
		$handedness . "," .
		$button_format . "," .
		$button . "," .
		$arrow_rest . ")";
	db_connect::run_query($querystr);
}

// Edit Functions
function editEquipment($eid, $type, $serialnum, $brand, $owner)
{
	$querystr = "UPDATE inventory SET " .
		" type=" . $type .
		" serialnum=" . $serialnum .
		" brand=" . $brand .
		" owner=" . $owner .
		" WHERE eid=" . $eid;
	db_connect::run_query($querystr);
}

function editStabilizer($eid, $type, $serialnum, $brand, $owner, $length)
{
	$eid = editEquipment($eid, $type, $serialnum, $brand, $owner);
	$querystr = "UPDATE stabilizer SET length=" . $length . " WHERE eid=" . $eid;
	db_connect::run_query($querystr);
}

function editLimb($eid, $type, $serialnum, $brand, $owner, $interface, $distinguishing_marks, $draw_strength)
{
	$eid = editEquipment($eid, $type, $serialnum, $brand, $owner);
	$querystr = "UPDATE limb SET " .
		" interface=" . $interface . 
		" distinguishing_marks=" .$distinguishing_marks . 
		" draw_strength=" .$draw_strength .
		" WHERE eid=" . $eid;
	db_connect::run_query($querystr);
}

function editArrow($eid, $type, $serialnum, $brand, $owner, $model, $complete_arrow, $bare_shaft, $fixable, $notes)
{
	$eid = editEquipment($eid, $type, $serialnum, $brand, $owner);
	$querystr = "UPDATE arrow SET " .
		" model=" . $model . 
		" complete_arrow=" .$complete_arrow . 
		" bare_shaft=" .$bare_shaft . 
		" fixable=" .$fixable . 
		" notes=" .$notes .
		" WHERE eid=" . $eid;
	db_connect::run_query($querystr);
}

function editRiser($eid, $type, $serialnum, $brand, $owner, $interface, $height, $distinguishing_marks, $handedness, $button_format, $button, $arrow_rest)
{
	$eid = editEquipment($eid, $type, $serialnum, $brand, $owner);
	$querystr = "UPDATE riser SET " .
		" interface=" . $interface . 
		" height=" . $height . 
		" distinguishing_marks=" . $distinguishing_marks . 
		" handedness=" . $handedness . 
		" button_format=" . $button_format . 
		" button=" . $button . 
		" arrow_rest=" . $arrow_rest .
		" WHERE eid=" . $eid;
	db_connect::run_query($querystr);
}

// Other functions
function deleteEquipment($eid)
{
	$querystr = "DELETE FROM equipment WHERE eid=" . $eid;
	db_connect::run_query($querystr);
}

function getEquipment()
{
	$querystr = "SELECT eid, type, serialnum, brand, owner FROM inventory";
	$query = db_connect::run_query($querystr);
	return $query;
}

function getEquipmentByOwner($pid)
{
	$querystr = "SELECT eid, type, serialnum, brand, owner FROM inventory WHERE owner=" . $pid;
	$query = db_connect::run_query($querystr);
	return $query;
}

function getEquipmentByBorrower($pid)
{
	// return equipment that is currently checked out by the borrower
	$querystr = "SELECT eid, type, serialnum, brand, owner FROM inventory " .
		"WHERE eid IN (" .
		"SELECT eid FROM equipment_loans " .
		"WHERE pid=" . $pid . " AND checkin IS NULL)";
	$query = db_connect::run_query($querystr);
	return $query;
}

function viewEquipment($eid, $type)
{
	// type can be: blank, S, L, A, R (for stabilizer, limb, arrow, and riser)
	// return equipment details for a specific item
	$querystr = "SELECT eid, type, serialnum, brand, owner FROM inventory WHERE eid=" . $eid;
	$query = db_connect::run_query($querystr);
	$equip = mysqli_fetch_row($query);
	
	if ($type != '')
	{
		$details = array();
		$querystr = "";
		switch ($type)
		{
			case 'S': // stabilizer
				$querystr = "SELECT length FROM stabilizer WHERE eid=" . $eid;
				break;
			case 'L': // limb
				$querystr = "SELECT interface, distinguishing_marks, draw_strength FROM limb WHERE eid=" . $eid;
				break;
			case 'A': // arrow
				$querystr = "SELECT model, complete_arrow, bare_shaft, fixable, notes FROM arrow WHERE eid=" . $eid;
				break;
			case 'R': // riser
				$querystr = "SELECT interface, height, distinguishing_marks, handedness, button_format, button, arrow_rest FROM riser WHERE eid=" . $eid;
				break;
		}
		$query = db_connect::run_query($querystr);
		$details = mysqli_fetch_row($query);
		$equip = array_merge($equip, $details);
	}
	
	return $equip;
}

function loan($eid, $borrower, $condition, $notes, $checkout)
{
	$querystr = "INSERT INTO equipment_loans (eid, pid, eq_condition, notes, checkout) VALUES (" .
		$eid . "," .
		$borrower . "," .
		$condition . "," .
		$notes . "," .
		$checkout . ")";
	db_connect::run_query($querystr);
}

function isCheckedOut($eid)
{
	$querystr = "SELECT eid FROM equipment_loans WHERE eid=" . $eid . " AND checkin IS NULL";
	$query = db_connect::run_query($querystr);
	switch (mysqli_num_rows($query))
	{
		case 0:
			return FALSE;
		default:
			return TRUE;
	}
}

function checkin($id, $checkin)
{
	$querystr = "UPDATE equipment_loans SET checkin=".$checkin." WHERE id=".$id;
	$query = db_connect::run_query($querystr);
}
?>