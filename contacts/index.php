<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_members.php");

	/* from the specs...
       In order for the club to keep track of contacts, there will be a contacts table of all of the people that are in the club records including the person’s name, a unique PID (Person IDentifier), and some basic contact information (address, e-mail, phone).  This portion of the database may contain both members and non-members. It is simply a way of associating other records with a person, and also a way for club members to look up contact info. This PID field is a primary key that points back to a particular person and will often be used as a foreign key elsewhere in the database.
        */
?>
<h2>Contact Info</h2>
<?php if (!db_access::isMember(db_access::getPidFromCaseId(phpCAS::getUser()))) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { 
        $pid = db_access::getPidFromCaseId(phpCas::getUser());
       
        //show contacts
        $members = getContacts(NULL);

        $admin = db_access::isAdmin($pid);

        echo "<form action='contactdetails.php' method='POST'> <input type='submit' value='Add New Contact' name='newuser'></form>";

        echo "<table><tr><th>Name</th><th>Address</th><th>e-mail address</th><th>Phone</th><th> </th><th> </th></tr>";

        while($row=mysqli_fetch_row($members))
        {
            echo "<TR><td>".$row[1]." ".$row[2]."</td><td>".$row[3]." ".$row[4].", ".$row[5]." ".$row[6]."</td><td>".$row[7]."</td><td>".$row[8]."</td><td>";
            if($row[10]==NULL)
            {
                 echo "<form action='/membership/memberdetails.php' method='post'>";
                 echo "<input type='hidden' name='pid' value=".$row[0]." />";
                 echo "<input type='submit' name='bleh' value='Add As Member' />";
                 echo "</form></td><td>";
            }else echo "</td><td>";
                 echo "<form action='contactdetails.php' method='POST'>";
                 echo "<input type='hidden' name='pid' value='".$row[0]."' />";
                 echo "<input type='submit' value='Details' name='details'></form>";
            echo "</td>";
        }
        echo "</table>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
