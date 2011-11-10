<?php
// Functions regarding equipment
// run_query from include("db_connect.php");

function addEquipment($type, $serialnum, $brand, $owner)
{
	$querystr = "INSERT INTO equipment (type, serialnum, brand, owner) VALUES (" +
		$type + "," +
		$serialnum + "," +
		$brand + "," +
		$owner + ")";
	run_query($querystr);
	
	// return eid
	$querystr = "SELECT eid FROM equipment WHERE" +
		"type=" + $type +
		" AND serialnum=" + $serialnum +
		" AND brand=" + $brand +
		" AND owner=" + $owner;
	
	$query = run_query($querystr);
	$row = mysqli_fetch_row($query);
	$eid = $row[0];
	return $eid;
}

function addStabilizer($type, $serialnum, $brand, $owner, $length)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO stabilizer (eid, length) VALUES (" +
		$eid + "," +
		$length + ")";
	run_query($querystr);
}

function addLimb($type, $serialnum, $brand, $owner, $interface, $distinguishing_marks, $draw_strength)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO limb (eid, interface, distinguishing_marks, draw_strength) VALUES (" +
		$eid + "," +
		$interface + "," +
		$distinguishing_marks + "," +
		$draw_strength + ")";
	run_query($querystr);
}

function addArrow($type, $serialnum, $brand, $owner, $model, $complete_arrow, $bare_shaft, $fixable, $notes)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO arrow (eid, model, complete_arrow, bare_shaft, fixable, notes) VALUES (" +
		$eid + "," +
		$model + "," +
		$complete_arrow + "," +
		$bare_shaft + "," +
		$fixable + "," +
		$notes + ")";
	run_query($querystr);
}

function addRiser($type, $serialnum, $brand, $owner, $interface, $height, $distinguishing_marks, $handedness, $button_format, $button, $arrow_rest)
{
	$eid = addEquipment($type, $serialnum, $brand, $owner);
	$querystr = "INSERT INTO riser (eid, interface, height, distinguishing_marks, handedness, button_format, button, arrow_rest) VALUES (" +
		$eid + "," +
		$interface + "," +
		$height + "," +
		$distinguishing_marks + "," +
		$handedness + "," +
		$button_format + "," +
		$button + "," +
		$arrow_rest + ")";
	run_query($querystr);
}

//TODO: editEquipment, editStabilizer, editLimb, editArrow, editRiser

function deleteEquipment($eid)
{
	$querystr = "DELETE FROM equipment WHERE eid=" + $eid;
	run_query($querystr);
}

//TODO: timestamp ?
function loan($eid, $borrower, $condition, $notes)
{
	$querystr = "INSERT INTO equipment_loans (eid, pid, eq_condition, notes) VALUES (" +
		$eid + "," +
		$borrower + "," +
		$condition + "," +
		$notes + ")";
	run_query($querystr);
}
?>