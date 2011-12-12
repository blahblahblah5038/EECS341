<?php
// Functions regarding members
// db_connect::run_query from include("db_connect.php");

// Contacts

function getContact($pid)
{
       $querystr = "SELECT * FROM contact ";
       if($pid!==NULL)
       {
           $querystr = $querystr."WHERE pid=".$pid;
       }
       return db_connect::run_query($querystr);
}

function addContact($first_name, $last_name, $address, $city, $state, $zip, $email, $phone)
{
	$querystr = "INSERT INTO contact (first_name, last_name, address, city, state, zip, email, phone) VALUES (" .
		$first_name . ", " .
		$last_name . ", " .
		$address . ", " .
		$city . ", " .
		$state . ", " .
		$zip . ", " .
		$email . ", " .
		$phone . ")";
	db_connect::run_query($querystr);
}

function editContact($pid, $first_name, $last_name, $address, $city, $state, $zip, $email, $phone)
{
	$querystr = "UPDATE contact SET " .
		"first_name=" . $first_name .
		", last_name=" . $last_name .
		", address=" . $address .
		", city=" . $city .
		", state=" . $state .
		", zip=" . $zip.
		", email=" . $email .
		", phone=" . $phone .
		" WHERE pid=" . $pid;
	db_connect::run_query($querystr);
}

function deleteContact($pid)
{
	$querystr = "DELETE FROM contact WHERE pid=" . $pid;
	db_connect::run_query($querystr);
}

// Members
function addMember($pid, $netid, $studentid, $bow_preference, $handedness, $membership_expiration, $usca_id, $usca_expiration, $emergency_name, $emergency_phone, $insurance_company, $policy_number)
{
	$querystr = "INSERT INTO member (pid, netid, studentid, bow_preference, handedness, membership_expiration, usca_id, usca_expiration, emergency_name, emergency_phone, insurance_company, policy_number) VALUES ( " .
		$pid . ", " .
		$netid . ", " .
		$studentid. ", " .
		$bow_preference . ", " .
		$handedness . ", " .
		$membership_expiration . ", " .
		$usca_id . ", " .
		$usca_expiration . ", " .
		$emergency_name . ", " .
		$emergency_phone . ", " .
		$insurance_company . ", " .
		$policy_number . ")";
	db_connect::run_query($querystr);
}

function editMember($mid, $netid, $studentid, $bow_preference, $handedness, $membership_expiration, $usca_id, $usca_expiration, $emergency_name, $emergency_phone, $insurance_company, $policy_number)
{
	$querystr = "UPDATE member SET "
		" netid=" . $netid . 
		" studentid=" . $studentid . 
		" bow_preference=" . $bow_preference . 
		" handedness=" . $handedness . 
		" membership_expiration=" . $membership_expiration . 
		" usca_id=" . $usca_id . 
		" usca_expiration=" . $usca_expiration . 
		" emergency_name=" . $emergency_name . 
		" emergency_phone=" . $emergency_phone . 
		" insurance_company=" . $insurance_company . 
		" policy_number=" . $policy_number .
		" WHERE mid=" . $mid;
	db_connect::run_query($querystr);
}

function deleteMember($mid)
{
	$querystr = "DELETE FROM member WHERE mid=" . $mid;
	db_connect::run_query($querystr);
}

// Certifications
function addCertification($pid, $type, $expiration, $notes)
{
	$querystr = "INSERT INTO certification (pid, type, expiration, notes) VALUES (" .
		$pid . ", " .
		$type . ", " .
		$expiration . ", " .
		$notes . ")";
	db_connect::run_query($querystr);
}

function editCertification($cid, $pid, $type, $expiration, $notes)
{
	$querystr = "UPDATE certification SET " .
		" pid=" . $pid .
		" type=" . $type .
		" expiration=" . expiration .
		" notes=" . notes .
		" WHERE cid=" . $cid;
	db_connect::run_query($querystr);
}

function deleteCertification($cid)
{
	$querystr = "DELETE FROM certification WHERE cid=" . $cid;
	db_connect::run_query($querystr);
}

// Scores

function addScore($pid, $total, $location, $score_date, $division, $scorecard, $tens, $nines)
{
	$querystr = "INSERT INTO score (pid, total, location, score_date, division, scorecard, tens, nines) VALUES (" .
		$pid . ", " .
		$total . ", " .
		$location . ", " .
		$score_date . ", " .
		$division . ", " .
		$scorecard . ", " .
		$tens . ", " .
		$nines . ")";
	db_connect::run_query($querystr);
}

function editScore($sid, $pid, $total, $location, $score_date, $division, $scorecard, $tens, $nines)
{
	$querystr = "UPDATE score SET " .
		" pid=" . $pid .
		" total=" . $total .
		" location=" . $location .
		" score_date=" . $score_date .
		" division=" . $division .
		" scorecard=" . $scorecard .
		" tens=" . $tens .
		" nines=" . $nines .
		" WHERE sid=" . $sid;
	db_connect::run_query($querystr);
}

function deleteScore($sid)
{
	$querystr = "DELETE FROM score WHERE sid=" . $sid;
	db_connect::run_query($querystr);
}
?>
