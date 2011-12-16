<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_officers.php");

    /*
    Gives a way of tracking who is an officer.    
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
        
 
        $officers = getOfficers(NULL);

        $admin = db_access::isAdmin($pid);

        echo "<form action='officerdetails.php' method='POST'> <input type='submit' value='Add New Officer' name='newuser'></form>";
        echo "<table><tr><th>Case ID</th><th>Student ID</th><th> </th></tr>";
        while($row = mysqli_fetch_row($officers))
        {
            echo "<tr><td>".$row[2]."</td><td>".$row[3]."</td><td>";
                 echo "<form action='officerdetails.php' method='POST'>";
                 echo "<input type='hidden' name='pid' value='".$row[0]."' />";
                 echo "<input type='submit' value='Details' name='details'></form>";
            echo "</td>";
        }
        echo "</table>";
}
?>
<?php include("phpincludes/footer.php"); ?>
