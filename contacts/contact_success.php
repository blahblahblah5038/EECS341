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
    if(!db_access::isAdmin($pid))
    {
         echo "Error, you are not a club officer.  Go away.";
    }
    else
    {
         if(isset($_POST['edituser']))
         {
             editContact($_POST['pid'], $_POST['fname'],$_POST['lname'],$_POST['address'],$_POST['city'],$_POST['state'],$_POST['zip'],$_POST['email'],$_POST['phone']);        
         }
         else if( isset($_POST['adduser']))
         {
             if(isContact($_POST['pid']))
             {
                addContact($_POST['fname'],$_POST['lname'],$_POST['address'],$_POST['city'],$_POST['state'],$_POST['zip'],$_POST['email'],$_POST['phone']);        
             }
             else echo "ERROR: Make sure that person is a contact first!";
         }
         else
         {
             echo "ERROR: invalid state!\n";
         }
    }  
  
?>
