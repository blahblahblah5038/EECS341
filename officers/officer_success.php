<?php
///////////////////////////////////////////////////////////////////////////////
////
//// This page shows a more detailed version of the member information.  That 
//// is to say that the main contacts page may not show all of the fields, and
//// that this one is the most detailed view possible.
////
//// Furthermore, we will be offering edit and deletion features for officers
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
 //         echo '<script language="javascript"><!--
   //           window.location.href = "index.php"
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
