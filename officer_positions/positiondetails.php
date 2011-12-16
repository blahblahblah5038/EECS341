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
         include("../phpincludes/db_equipment.php");
    
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
             $members = db_access::getPosition($_POST['pos_id']);
             $row = mysqli_fetch_row($members);
         }    
         else     
         {
             $detailsset = false;
             echo "<h2>Add New Position</h2>";
             $row = array("","","");
         }


         echo "<form action='position_success.php' method='POST'>";
         echo "<table>";
         echo "<TD>Title:<TD><input type='text'      name='title'              value='".$row[0]."' /><TR>";
         echo "<TD>Description:<TD><input type='text' 	name='description'		value='".$row[1]."' /><TR>";
         echo "</table>";
         
         if($detailsset)
         {
             echo "<input type='submit' value='edituser' /></br>";
         }
         else
         {
             echo "<input type='submit' value='adduser' /></br>";
         }
         
         echo "</form>";
         echo "<form action='delete_position.php'>";
         echo "<input type=hidden value='".$row[0]."' name=pos_id />";
         echo "<input type='submit' value='deluser' /></br>";
         echo "</form>";
    }  
  
?>
