<?php
///////////////////////////////////////////////////////////////////////////////
////
//// This page shows a more detailed version of the officer information.  That 
//// is to say that the main officers page may not show all of the fields, and
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
         echo "<form action='officer_success.php' method='POST'>";
         echo "<table class='noborder'>";
         $row = "";
         if(isset($_POST['details']))
         {
             $detailsset = true;
             echo "<h2>Detailed Officer Info</h2>";
             $members = getOfficerDetails($_POST['pid'],$_POST['pos_id']);
             while($row = mysqli_fetch_row($members))
             { 
                  echo "<input type='hidden' name='edituser' value='edituser' />";
                  echo "<input type='hidden' name='pid' value='".$row[0]."' />";
                  echo "<input type='hidden' name='pos_id' value='".$row[1]."' />";
                  echo "<TR><TD>Title:</TD><TD>".$row[2];
                  echo "</TD></TR><TR><TD>Start Date:</TD><TD><input type='text' readonly='readonly' name='start_date' value='".$row[3]."' />";
                  echo "</TD></TR><TR><TD>End Date:</TD><TD><input type='text' name='end_date' value='".$row[4]."' />";
                  echo "</TD></TR><TR><TD colspan='2'><input type='submit' name='Save' value='Save' /></TD></TR>";
             }
         }    
         else     
         {
             $detailsset = false;
             echo "<h2>Add New Officer</h2>";
             echo "<input type='hidden' name='pid' value='".$_POST['pid']."' />";
             echo "<input type='hidden' name='adduser' value='adduser' />"; 
             echo "<TR><TD>Title:<TD><select name='pos_id'>";
             $titles = getPositions(NULL);
             while($row=mysqli_fetch_row($titles))
             {
                 echo "<option value='".$row[0]."'>".$row[1]."</option>";
             }
             echo "</select>";          
    
             echo "<TR><TD>Start Date:<TD><input type='text' name='start_date' value='".$row[3]."' />";
             echo "<TR><TD>End Date:<TD><input type='text' name='end_date' value='".$row[4]."' />";
             echo "<TR><TD><input type='submit' name='Save' value='Save' />";

         }
         echo "</form>";
    }  
  
?>
