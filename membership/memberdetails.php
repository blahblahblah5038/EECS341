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
         $row = "";
         if(isset($_POST['details']))
         {
             $detailsset = true;
             echo "<h2>Detailed Contact Info</h2>";
             $members = getMembers($_POST['pid']);
             $row = mysqli_fetch_row($members);
         }    
         else     
         {
             $detailsset = false;
             echo "<h2>Add New Contact</h2>";
             $row = array("","","","","","","","","","","","","","");
         }

         echo print_r($row);
         echo "<form action='member_success.php' method='POST'>";
         echo "<table>";
         echo "<TD>MID:<TD>".$row[0]."<TR>";
         echo "<input type='hidden' name='mid' value=".$row[0]." />";
         echo "<TD>PID:<TD>".($row[1]?$row[1]:$_POST['pid'])."<TR>";
         if($detailsset)
             echo "<input type='hidden' name='pid' value=".$row[1]." />";
         else 
             echo "<input type='hidden' name='pid' value=".$_POST['pid']." />";
         echo "<TD>Network ID:<TD><input type='text'      name='netid'              value='".$row[2]."' /><TR>";
         echo "<TD>Student ID:<TD><input type='text'      name='studid'              value='".$row[3]."' /><TR>";
         echo "<TD>Bow Type Preference:<TD><input type='text'      name='bowpref' value='".$row[4]."' /><TR>";
         echo "<TD>Handedness:<TD><input type='text'      name='hand'              value='".$row[5]."' /><TR>";
         echo "<TD>Club Membership Expiration:<TD><input type='text'      name='clubexpir' value='".$row[6]."' /><TR>";
         echo "<TD>USCA Membership ID:<TD><input type='text'      name='memid' value='".$row[7]."' /><TR>";
         echo "<TD>USCA Membership Expiration<TD><input type='text'      name='uscaexpir' value='".$row[8]."' /><TR>";
         echo "<TD>Emergency Contact Name:<TD><input type='text'      name='emername'              value='".$row[9]."' /><TR>";
         echo "<TD>Emergency Contact Phone:<TD><input type='text'      name='emerphone'              value='".$row[10]."' /><TR>";
         echo "<TD>Health Insurance Company:<TD><input type='text'      name='insurer'              value='".$row[11]."' /><TR>";
         echo "<TD>Policy Number:<TD><input type='text'      name='policy'              value='".$row[12]."' /><TR>";
         echo "</table>";
         
         if($detailsset)
         {
             echo "<input type='submit' value='Save' name='edituser' />";
         }
         else
         {
             echo "<input type='submit' value='Save' name='adduser' />";
         }
         
         echo "</form>";
         echo "<form action='delete_member.php' method='post'>";
         if($detailsset) 
               echo "<input type=hidden value='".$row[1]."' name='pid' />";
         else
               echo "<input type=hidden value='".$_POST['pid']."' name='pid' />";
         echo "<input type='submit' value='Delete' name='deluser' /></br>";
         echo "</form>";
    }  
  
?>
