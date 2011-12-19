<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_members.php");
    include_once("../phpincludes/db_officers.php");
	/* from the specs...
       handles officers    
    */
?>
<h2>Officer History</h2>
<?php if (!db_access::isMember(db_access::getPidFromCaseId(phpCAS::getUser()))) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { 
       
        //show officers
        
 
        $members = getOfficerHistory();

        echo "<table><tr><th>Name</th><th>Title</th><th>Start Date</th><th>End Date</th></tr>";
        while($row = mysqli_fetch_row($members))
        {
            echo "<tr><td>".$row[8]." ".$row[9]."</td><td>".$row[5]."</td><td>".$row[2]."</td><td>".$row[3]."</td>";
        }
        echo "</table>";
}
?>
<?php include("../phpincludes/footer.php"); ?>
