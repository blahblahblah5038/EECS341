<?php
// Functions regarding officers
// db_connect::run_query from include("db_connect.php");

function getOfficerHistory()
{
   $querystr = "SELECT * FROM officer_history H, officer_positions P, contact C WHERE H.pos_id = P.pos_id AND H.pid = C.pid";
   return db_connect::run_query($querystr);
}

function getCurrentOfficers()
{
    $querystr = "SELECT * FROM officer_history H, officer_positions P, contact C WHERE H.pos_id = P.pos_id AND H.pid = C.pid AND CURDATE() BETWEEN start_date AND end_date";
    return db_connect::run_query($querystr);
}

function getOfficerDetails($pid)
{
    $querystr = "SELECT pid, officer_positions.pos_id, title, start_date, end_date from officer_history, officer_positions where officer_history.pid=".$pid." AND officer_history.pos_id=officer_positions.pos_id AND start_date<=CURDATE()<=end_date";
    return db_connect::run_query($querystr);
}

function addOfficer($pid, $pos_id, $start_date, $end_date)
{
	//TODO: (possibly) convert date formats?
	$querystr = "INSERT INTO officer_history (pid, pos_id, start_date, end_date) VALUES ( " .
		$pid . ", " .
		$pos_id . ", '" .
		$start_date . "', '" .
		$end_date . "')";
	db_connect::run_query($querystr);
}

function getPositions($pos_id)
{
    $querystr="SELECT pos_id, title, description FROM officer_positions ";
    if($pos_id!==NULL)
    {
        $querystr.=" WHERE pos_id=".$pos_id;
    }
    return db_connect::run_query($querystr);
}

function editOfficer($pid, $pos_id, $start_date, $end_date)
{
	$querystr = "UPDATE officer_history SET start_date='" . $start_date . "', end_date='" . $end_date .
		"' WHERE pid=" . $pid . " AND pos_id=" . $pos_id;
    echo $querystr."\n";
	db_connect::run_query($querystr);
}

function editPosition($pos_id, $title, $description)
{
	$querystr = "UPDATE officer_positions SET title='" . $title . "', description='" . $description .
		"' WHERE pos_id=" . $pos_id;
	db_connect::run_query($querystr);
}
function addPosition($title, $description)
{
	$querystr = "INSERT INTO officer_positions (title, description) VALUES ('" .
		$title . "', '" .
		$description . "')";
	db_connect::run_query($querystr);
}

function deleteOfficer($pid, $pos_id)
{
	$querystr = "DELETE FROM officer_history WHERE pid=" . $pid . " AND pos_id=" . $pos_id;
	db_connect::run_query($querystr);
}

function deletePosition($pos_id)
{
	$querystr = "DELETE FROM officer_positions WHERE pos_id=" . $pos_id;
	db_connect::run_query($querystr);
}
?>
