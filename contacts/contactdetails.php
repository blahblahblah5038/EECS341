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
         echo "Person ID: 	<input type='text' 	name='PID'		value='".$row[0]."' /><br />";
         echo "First Name: 	<input type='text' 	name='First Name'	value='".$row[1]."' /><br />";
         echo "Last Name: 	<input type='text' 	name='Last Name'	value='".$row[2]."' /><br />";
         echo "Address: 	<input type='text' 	name='Address'		value='".$row[3]."' /><br />";
         echo "City: 		<input type='text' 	name='City'		value='".$row[4]."' /><br />";
         echo "State: 		<input type='text' 	name='State'		value='".$row[5]."' /><br />";
         echo "Zip code: 	<input type='text' 	name='Zip Code'		value='".$row[6]."' /><br />";
         echo "Email: 		<input type='text' 	name='e-mail'		value='".$row[7]."' /><br />";
         echo "Phone Number: 	<input type='text' 	name='Phone'   		value='".$row[8]."' /><br />";
         echo "<input type='submit' value='Save' /></br>";
         echo "</form>";
         echo "<form action='delete_contact.php'>";
         echo "<input type=hidden value='".$row[1]."' name=pid />";
         echo "<input type='submit' value='deluser' /></br>";
         echo "</form>";
    }  
  
?>
