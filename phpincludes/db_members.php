<?php
// Functions regarding members
// db_connect::run_query from include("db_connect.php");

// Contacts

function isContact($pid)
{
     if($pid==NULL) return false;
     else
     {
         if(mysqli_num_rows(getContacts($pid))==0) return false;
         else return true;
     }
}

function getContacts($pid)
{
       $querystr = "SELECT * FROM contact LEFT JOIN member ON contact.pid=member.pid ";
       if($pid!==NULL)
       {
           $querystr = $querystr."WHERE contact.pid=".$pid;
       }
       return db_connect::run_query($querystr);
}

function addContact($first_name, $last_name, $address, $city, $state, $zip, $email, $phone)
{
	$querystr = "INSERT INTO contact (first_name, last_name, address, city, state, zipcode, email, phone) VALUES ('" .
		($first_name ?  $first_name : NULL)  . "','" .
		($last_name ?  $last_name : NULL)  . "','" .
		($address ?  $address : NULL)  . "','" .
		($city ?  $city : NULL)  . "','" .
		($state ?  $state : NULL)  . "','" .
		($zip ?  $zip : NULL)  . "','" .
		($email ?  $email : NULL)  . "','" .
		($phone ?  $phone : NULL)  . "')";
	db_connect::run_query($querystr);
}

function editContact($pid, $first_name, $last_name, $address, $city, $state, $zip, $email, $phone)
{
	$querystr = "UPDATE contact SET " .
		"first_name='" . ($first_name ?  $first_name : NULL)  .
		"', last_name='" . ($last_name ?  $last_name : NULL)  .
		"', address='" . ($address ?  $address : NULL)  .
		"', city='" . ($city ?  $city : NULL)  .
		"', state='" . ($state ?  $state : NULL)  .
		"', zipcode='" . ($zip ?  $zip : NULL)  .
		"', email='" . ($email ?  $email : NULL)  .
		"', phone='" . ($phone ?  $phone : NULL)  .
		"' WHERE pid=" . ($pid ?  $pid : NULL)  ;
	db_connect::run_query($querystr);
}

function deleteContact($pid)
{
	$querystr = "DELETE FROM contact WHERE pid=" . $pid;
	db_connect::run_query($querystr);
}

// Members
function getMembers($pid)
{
       $querystr = "SELECT * FROM member LEFT JOIN contact ON member.pid = contact.pid ";
       if($pid!==NULL)
       {
           $querystr = $querystr."WHERE member.pid=".$pid;
       }
       return db_connect::run_query($querystr);
}


function addMember($pid, $netid, $studentid, $bow_preference, $handedness, $membership_expiration, $usca_id, $usca_expiration, $emergency_name, $emergency_phone, $insurance_company, $policy_number)
{
	$querystr = "INSERT INTO member (pid, netid, studentid, bow_preference, handedness, membership_expiration, usca_id, usca_expiration, emergency_name, emergency_phone, insurance_company, policy_number) VALUES ( '" .
		($pid ?  $pid : NULL)  . "', '" .
		($netid ?  $netid : NULL)  . "', '" .
		($studentid ?  $studentid : NULL) . "', '" .
		($bow_preference ?  $bow_preference : NULL)  . "', '" .
		($handedness ?  $handedness : NULL)  . "', '" .
		($membership_expiration ?  $membership_expiration : NULL)  . "', '" .
		($usca_id ?  $usca_id : NULL)  . "', '" .
		($usca_expiration ?  $usca_expiration : NULL)  . "', '" .
		($emergency_name ?  $emergency_name : NULL)  . "', '" .
		($emergency_phone ?  $emergency_phone : NULL)  . "', '" .
		($insurance_company ?  $insurance_company : NULL)  . "', '" .
		($policy_number ?  $policy_number : NULL)  . "')";
	db_connect::run_query($querystr);
}

function editMember($mid, $netid, $studentid, $bow_preference, $handedness, $membership_expiration, $usca_id, $usca_expiration, $emergency_name, $emergency_phone, $insurance_company, $policy_number)
{
	$querystr = "UPDATE member SET ".
		" netid='" . ($netid ?  $netid : NULL)  . 
		"', studentid='" . ($studentid ?  $studentid : NULL)  . 
		"', bow_preference='" . ($bow_preference ?  $bow_preference : NULL)  . 
		"', handedness='" . ($handedness ?  $handedness : NULL)  . 
		"', membership_expiration='" . ($membership_expiration ?  $membership_expiration : NULL)  . 
		"', usca_id='" . ($usca_id ?  $usca_id : NULL)  . 
		"', usca_expiration='" . ($usca_expiration ?  $usca_expiration : NULL)  . 
		"', emergency_name='" . ($emergency_name ?  $emergency_name : NULL)  . 
		"', emergency_phone='" . ($emergency_phone ?  $emergency_phone : NULL)  . 
		"', insurance_company='" . ($insurance_company ?  $insurance_company : NULL)  . 
		"', policy_number='" . ($policy_number ?  $policy_number : NULL)  .
		"' WHERE mid=" . $mid ;
	db_connect::run_query($querystr);
}

function deleteMemberWithPid($pid)
{
	$querystr = "DELETE FROM member WHERE pid=" . $pid;
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
