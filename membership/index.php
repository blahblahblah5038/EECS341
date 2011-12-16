<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");

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
        
 
        $members = db_access::getMember(NULL);
        $row = mysqli_fetch_row($members);

        $admin = db_access::isAdmin($pid);

        echo "<table><tr><th>Case ID</th><th>Student ID</th><th> </th>";
        while($row)
        {
            echo "<td>".$row[2]."</td><td>".$row[3]."</td><td>";
                 echo "<form action='memberdetails.php' method='POST'>";
                 echo "<input type='hidden' name='pid' value='".$row[0]."' />";
                 echo "<input type='submit' value='Details' name='details'></form>";
            echo "</td>";
        }
        echo "</table>";
}
?>
<?php include("phpincludes/footer.php"); ?>
