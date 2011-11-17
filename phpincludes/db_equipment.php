<?php
// Functions regarding equipment
// run_query from include("db_connect.php");

function addEquipment($type, $serialnum, $brand, $owner)
{
	$querystr = "INSERT INTO inventory (type, serialnum, brand, owner) VALUES (" .
		$type . "," .
		$serialnum . "," .
		$brand . "," .
		$owner . ")";
	run_query($querystr);
	
	// return eid
	$querystr = "SELECT eid FROM inventory WHERE" .
		"type=" . $type .
		" AND serialnum=" . $serialnum .
		" AND brand=" . $brand .
		" AND owner=" . $owner;
	
	$query = run_query($querystr);
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
	run_query($querystr);
}

function addLimb($type, $serialnum, $brand, $owner, $interface, $distinguishing_marks, $draw_strength)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO limb (eid, interface, distinguishing_marks, draw_strength) VALUES (" .
		$eid . "," .
		$interface . "," .
		$distinguishing_marks . "," .
		$draw_strength . ")";
	run_query($querystr);
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
	run_query($querystr);
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
	run_query($querystr);
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
	run_query($querystr);
}

function editStabilizer($eid, $type, $serialnum, $brand, $owner, $length)
{
	$eid = editEquipment($eid, $type, $serialnum, $brand, $owner);
	$querystr = "UPDATE stabilizer SET length=" . $length . " WHERE eid=" . $eid;
	run_query($querystr);
}

function editLimb($eid, $type, $serialnum, $brand, $owner, $interface, $distinguishing_marks, $draw_strength)
{
	$eid = editEquipment($eid, $type, $serialnum, $brand, $owner);
	$querystr = "UPDATE limb SET " .
		" interface=" . $interface . 
		" distinguishing_marks=" .$distinguishing_marks . 
		" draw_strength=" .$draw_strength .
		" WHERE eid=" . $eid;
	run_query($querystr);
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
	run_query($querystr);
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
	run_query($querystr);
}

// Other functions
function deleteEquipment($eid)
{
	$querystr = "DELETE FROM equipment WHERE eid=" . $eid;
	run_query($querystr);
}

//TODO: timestamp ?
function loan($eid, $borrower, $condition, $notes)
{
	$querystr = "INSERT INTO equipment_loans (eid, pid, eq_condition, notes) VALUES (" .
		$eid . "," .
		$borrower . "," .
		$condition . "," .
		$notes . ")";
	run_query($querystr);
}
?>