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
         include_once("../phpincludes/db_officers.php");
    $pid = db_access::getPidFromCaseId(phpCAS::getUser());   
    if(!db_access::isAdmin($pid))
    {
         echo "Error, you are not a club officer.  Go away.";
    }
    else
    {
         $row = "";
         if(isset($_POST['details']))
         {
             $detailsset = true;
             echo "<h2>Detailed Position Info</h2>";
             $members = getPositions($_POST['pos_id']);
             $row = mysqli_fetch_row($members);
         }    
         else     
         {
             $detailsset = false;
             echo "<h2>Add New Position</h2>";
             $row = array("","","");
         }


         echo "<form action='position_success.php' method='POST'>";
         echo "<table class='noborder'>";
         echo "<input type=hidden value='".$row[0]."' name=pos_id />";
         echo "<TR><TD>Title:</TD><TD><input type='text' name='title' value='".$row[1]."' /></TR>";
         echo "<TR><TD>Description:</TD><TD><textarea rows='5' cols='50' name='description' >".$row[2]."</textarea></TR>";
         echo "</table><br />";
         
         if($detailsset)
         {
             echo "<input type='submit' name='edituser' value='Save' /></br>";
         }
         else
         {
             echo "<input type='submit' name='adduser' value='Save1' /></br>";
         }
         
         echo "</form>";
    }  
  
?>
