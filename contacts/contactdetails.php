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
             $members = getContacts($_POST['pid']);
             $row = mysqli_fetch_row($members);
         }    
         else     
         {
             $detailsset = false;
             echo "<h2>Add New Contact</h2>";
             $row = array("","","","","","","","","");
         }
       
         echo "<form action='contact_success.php' method='post'>";
         echo "<table>";
         echo "<TD>Person ID:<TD>".$row[0]." <TR><input type=hidden value=".$row[0]." name=pid />";
         echo "<TD>First Name:<TD><input type='text' 	name='fname'	value='".$row[1]."' /><TR>";
         echo "<TD>Last Name:<TD><input type='text' 	name='lname'	value='".$row[2]."' /><TR>";
         echo "<TD>Address:<TD><input type='text' 	name='address'		value='".$row[3]."' /><TR>";
         echo "<TD>City:<TD><input type='text' 	name='city'		value='".$row[4]."' /><TR>";
         echo "<TD>State:<TD><input type='text' 	name='state'		value='".$row[5]."' /><TR>";
         echo "<TD>Zip code:<TD><input type='text' 	name='zip'		value='".$row[6]."' /><TR>";
         echo "<TD>Email:<TD><input type='text' 	name='email'		value='".$row[7]."' /><TR>";
         echo "<TD>Phone Number:<TD><input type='text' 	name='phone'   		value='".$row[8]."' /><TR>";
         if($detailsset)echo "<TD><input type='submit' value='Save' name='edituser' />";
         else echo "<TD><input type='submit' value='Save' name='adduser'     />";
         echo "</form>";
         echo "<form action='delete_contact.php' method='post'>";
         echo "<input type=hidden value='".$row[0]."' name='pid' />";
         if($detailsset)echo "</TD><TD><input type='submit' value='Delete' name='deluser' /></TD><TR>";
         echo "</form></table>";
    }  
  
?>
