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
         echo "<h2>Detailed Contact Info</h2>";
         echo "Are you sure you want to delete that user?"
         echo "<form action='delete_confirmed.php' method='POST'>";
         echo "<input type='hidden' name='pid' value='".$_POST['pid']."' />";
         echo "<input type='submit' value='Yes' name='details'></form>";
         echo "<form action='index.php' method='POST'>";
         echo "<input type='hidden' name='pid' value='".$_POST['pid']."' />";
         echo "<input type='submit' value='Yes' name='details'></form>";
    }
    else  echo "ERROR: POST not set right\n";
  
?>
