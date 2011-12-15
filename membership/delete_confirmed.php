<?php
///////////////////////////////////////////////////////////////////////////////
////
//// This page shows a more detailed version of the contact information.  That 
//// is to say that the main contacts page may not show all of the fields, and
//// that this one is the most detailed view possible.
////
//// Furthermore, we will be offering edit and deletion features for officers
////
///////////////////////////////////////////////////////////////////////////////

         include("../phpincludes/header.php");
         include("../phpincludes/login.php");
         include("../phpincludes/db_access.php");
         include("../phpincludes/db_equipment.php");

    if(!if(db_access::isAdmin($pid)))
    {
         echo "Error, you are not a club officer.  Go away.";
    }
    else if (isset($_POST['pid']))
    {
         db_members::deleteMemberWithPid($_POST['pid']);
    } 
    else
    {
         echo "ERROR: POST is not set right\n";
    } 
  
?>
