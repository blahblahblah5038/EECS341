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
         if(isset($_POST['details'])
         {
             echo "<h2>Detailed Contact Info</h2>";
             $members = db_access::getContact(NULL);
             $row = mysqli_fetch_row($members);
         }    
         else     
         {
             echo "<h2>Add New Contact</h2>";
         }

         echo "<form action='contact_success.php'>"
         echo "<table>"
         echo "<TD>Member ID:<TD><input type='text'      name='MID'              value='".$row[0]."' /><TR>";
         echo "<TD>Person ID:<TD><input type='text' 	name='PID'		value='".$row[1]."' /><TR>";
         echo "<TD>Network ID:<TD><input type='text'      name='Network ID'              value='".$row[2]."' /><TR>";
         echo "<TD>Student ID:<TD><input type='text'      name='Student ID'              value='".$row[3]."' /><TR>";
         echo "<TD>Bow Type Preference:<TD><input type='text'      name='Bow Preference' value='".$row[4]."' /><TR>";
         echo "<TD>Handedness:<TD><input type='text'      name='Handedness'              value='".$row[5]."' /><TR>";
         echo "<TD>Certification ID:<TD><input type='text'      name='certid'              value='".$row[6]."' /><TR>";
         echo "<TD>Club Membership Expiration:<TD><input type='text'      name='expir' value='".$row[7]."' /><TR>";
         echo "<TD>USCA Membership ID:<TD><input type='text'      name='expir' value='".$row[8]."' /><TR>";
         echo "<TD>USCA Membership Expiration<TD><input type='text'      name='expir' value='".$row[9]."' /><TR>";
         echo "</table>"

         echo "First Name: 	<input type='text' 	name='First Name'	value='".$row[1]."' /><br />";
         echo "Last Name: 	<input type='text' 	name='Last Name'	value='".$row[2]."' /><br />";
         echo "Address: 	<input type='text' 	name='Address'		value='".$row[3]."' /><br />";
         echo "City: 		<input type='text' 	name='City'		value='".$row[4]."' /><br />";
         echo "State: 		<input type='text' 	name='State'		value='".$row[5]."' /><br />";
         echo "Zip code: 	<input type='text' 	name='Zip Code'		value='".$row[6]."' /><br />";
         echo "Email: 		<input type='text' 	name='e-mail'		value='".$row[7]."' /><br />";
         echo "Phone Number: 	<input type='text' 	name='Phone'   		value='".$row[8]."' /><br />";
Member ID	(primary key)
Person ID	(foreign key)
Case Network ID
Case Student ID
Bow Type Preference
Handedness
Certification ID	(foreign key)
Club Membership Expiration (when they graduate/quit)
USCA Membership ID
USCA Membership Expiration
Emergency Contact Name
Emergency Contact Phone
Health Insurance Company
Policy Number
         echo "<input type='submit' value='Save' /></br>";
         echo "</form>";
         echo "<form action='delete_member.php'>"
         echo "<input type='submit' value='Delete User' /></br>";
         echo "</form>";
    }  
  
?>
