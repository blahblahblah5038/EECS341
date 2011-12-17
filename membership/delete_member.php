<?php
///////////////////////////////////////////////////////////////////////////////
////
////    This page asks for confirmation before deleting a user. It redirects
////    to the index page if they don't really want to delete the user and to
////    The actual deletion page if they do.
////
///////////////////////////////////////////////////////////////////////////////

         include("../phpincludes/header.php");
         include("../phpincludes/login.php");
         include("../phpincludes/db_access.php");
         include("../phpincludes/db_equipment.php");
    $pid = db_access::getPidFromCaseId(phpCAS::getUser());
    if(!(db_access::isAdmin($pid)))
    {
         echo "Error, you are not a club officer.  Go away.";
    }
    else if (isset($_POST['pid']))
    {
         echo "<h2>Detailed Contact Info</h2>";
         echo "Are you sure you want to delete that user?";
         echo "<form action='delete_confirmed.php' method='POST'>";
         echo "<input type='hidden' name='pid' value='".$_POST['pid']."' />";
         echo "<input type='submit' value='Yes' name='details'></form>";
         echo "<form action='index.php' method='POST'>";
         echo "<input type='hidden' name='pid' value='".$_POST['pid']."' />";
         echo "<input type='submit' value='No' name='details'></form>";
    }
    else  echo "ERROR: POST not set right\n";
  
?>
