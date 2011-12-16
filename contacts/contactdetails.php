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

    if(!isset($_POST['details'])
    
    if(!if(db_access::isAdmin($pid)))
    {
         echo "Error, you are not a club officer.  Go away.";
    }
    else
    {
         $row = {};
         if(isset($_POST['details'])
         {
             $detailsset = true;
             echo "<h2>Detailed Contact Info</h2>";
             $members = db_access::getContact(NULL);
             $row = mysqli_fetch_row($members);
         }    
         else     
         {
             $detailsset = false;
             echo "<h2>Add New Contact</h2>";
             $row = {"","","","","","","","",""};
         }
       
         echo "<form action='contact_success.php'>"
         echo "<table>";
         echo "<TD>Person ID:<TD><input type='text' 	name='pid'		value='".$row[0]."' /><TR>";
         echo "<TD>First Name:<TD><input type='text' 	name='fname'	value='".$row[1]."' /><TR>";
         echo "<TD>Last Name:<TD><input type='text' 	name='lname'	value='".$row[2]."' /><TR>";
         echo "<TD>Address:<TD><input type='text' 	name='address'		value='".$row[3]."' /><TR>";
         echo "<TD>City:<TD><input type='text' 	name='city'		value='".$row[4]."' /><TR>";
         echo "<TD>State:<TD><input type='text' 	name='state'		value='".$row[5]."' /><TR>";
         echo "<TD>Zip code:<TD><input type='text' 	name='zip'		value='".$row[6]."' /><TR>";
         echo "<TD>Email:<TD><input type='text' 	name='email'		value='".$row[7]."' /><TR>";
         echo "<TD>Phone Number:<TD><input type='text' 	name='phone'   		value='".$row[8]."' /><TR>";
         echo "<input type='submit' value='Save' /><TR>";
         echo "</form>";
         echo "<form action='delete_contact.php'>";
         echo "<input type=hidden value='".$row[1]."' name=pid />";
         echo "<input type='submit' value='deluser' /><TR>";
         echo "</form>";
    }  
  
?>
