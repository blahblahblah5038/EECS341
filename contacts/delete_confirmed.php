<?php
///////////////////////////////////////////////////////////////////////////////
////
////    This page is the one we go to after the user clicks a confirmation 
////    that the other user in question is to be deleted.  This page simply
////    performs the deletion and prints a message saying whether it worked 
////    or not.
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
         db_members::deleteContact($_POST['pid']);
    } 
    else
    {
         echo "ERROR: POST is not set right\n";
    } 
  
?>
