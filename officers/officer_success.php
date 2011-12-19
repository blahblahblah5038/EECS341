<?php
///////////////////////////////////////////////////////////////////////////////
////
////  This page performs the actual changing of the officer_history table.
////  Redirects to index.php on success.
////
///////////////////////////////////////////////////////////////////////////////

         include("../phpincludes/header.php");
         include("../phpincludes/login.php");
         include("../phpincludes/db_access.php");
         include("../phpincludes/db_members.php");

    $pid = db_access::getPidFromCaseId(phpCAS::getUser());    
    if(!(db_access::isAdmin($pid)))
    {
         echo "Error, you are not a club officer.  Go away.";
    }
    else
    {
         if(isset($_POST['edituser']))
         {

             echo print_r($_POST)."\n";
             editOfficer($_POST['pid'], $_POST['pos_id'],$_POST['start_date'],$_POST['end_date']);
         echo '<script language="javascript"><!--
             window.location.href = "index.php"
              //--></script>';
       
         }
         else if( isset($_POST['adduser']))
         {
             echo $_POST['pid'];
                 addOfficer($_POST['pid'], $_POST['pos_id'],$_POST['start_date'],$_POST['end_date']);
              echo '<script language="javascript"><!--
              window.location.href = "index.php"
              //--></script>';
         }
         else
         {
             echo "ERROR: invalid state!\n";
         }
    }  
  
?>
