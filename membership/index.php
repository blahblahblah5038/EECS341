<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_members.php");

	/* from the specs...
       In order for the club to keep track of contacts, there will be a contacts table of all of the people that are in the club records including the person’s name, a unique PID (Person IDentifier), and some basic contact information (address, e-mail, phone).  This portion of the database may contain both members and non-members. It is simply a way of associating other records with a person, and also a way for club members to look up contact info. This PID field is a primary key that points back to a particular person and will often be used as a foreign key elsewhere in the database.
        */
?>
<h2>Members</h2>
<?php if (!db_access::isMember(db_access::getPidFromCaseId(phpCAS::getUser()))) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { 
        $pid = db_access::getPidFromCaseId(phpCas::getUser());
       
        //show members
        
 
        $members = getMembers(NULL);

        $admin = db_access::isAdmin($pid);

        echo "<table><tr><th>Name</th><th>Case ID</th><th>Student ID</th><th> </th><th></th></tr>";
        while($row = mysqli_fetch_row($members))
        {
            echo "<tr><td>".$row[14]." ".$row[15]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>";
                 echo "<form action='memberdetails.php' method='POST'>";
                 echo "<input type='hidden' name='pid' value='".$row[1]."' />";
                 echo "<input type='submit' value='Details' name='details'></form>";
            echo "</td>";
            echo "<td><form action='../officers/officerdetails.php' method='post' />";
                 echo "<input type='hidden' name='pid' value='".$row[1]."' />";
                 echo "<input type='hidden' name='adduser' value='adduser' />";
                 echo "<input type='submit' name='submit' value='Add as Officer' /></form></td>";
        }
        echo "</table>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
