<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_members.php");
    include("../phpincludes/db_officers.php");
/*
    gives a way to add and remove officer positions
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
        
 
        $positions = getPositions(NULL);

        $admin = db_access::isAdmin($pid);

        echo "<form action='positiondetails.php' method='POST'> <input type='submit' value='Add New Position' name='newuser'></form>";
        echo "<table><tr><th>Title</th><th>Description</th><th> </th></tr>";
        while($row = mysqli_fetch_row($positions))
        {
            echo "<tr><td>".$row[1]."</td><td>".$row[2]."</td><td>";
                 echo "<form action='positiondetails.php' method='POST'>";
                 echo "<input type='hidden' name='pos_id' value='".$row[0]."' />";
                 echo "<input type='submit' value='Details' name='details'></form>";
            echo "</td>";
        }
        echo "</table>";
}
?>
<?php include("phpincludes/footer.php"); ?>
