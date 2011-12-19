<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_members.php");

	/* from the specs...
       handles officers    
    */
?>
<h2>Current Officers</h2>
<?php if (!db_access::isMember(db_access::getPidFromCaseId(phpCAS::getUser()))) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { 
        $pid = db_access::getPidFromCaseId(phpCas::getUser());
       
        //show officers
        
 
        $members = getCurrentOfficers();

        $admin = db_access::isAdmin($pid);

        echo "<table><tr><th>Name</th><th>Position </th><th>Start of Term</th><th>End of Term</th><th> </th></tr>";
        while($row = mysqli_fetch_row($members))
        {
            echo "<tr><td>".$row[8]." ".$row[9]."</td><td>".$row[5]." </td><td>".$row[2]."</td><td>".$row[3]."</td><td>";
                 echo "<form action='memberdetails.php' method='POST'>";
                 echo "<input type='hidden' name='pid' value='".$row[1]."' />";
                 echo "<input type='submit' value='Details' name='details'></form>";
            echo "</td>";
        }
        echo "</table>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
