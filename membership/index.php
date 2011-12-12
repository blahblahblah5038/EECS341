<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");

	/* from the specs...
	
        */
?>
<h2>Inventory</h2>
<?php if (!db_access::isMember(phpCAS::getUser())) {
	echo <<<HERE
	<div class='error'>Sorry, you are not authorized to view this page.</div>
HERE;
} else { // is a member
	echo <h2>"Current Club Membership"</h2>
        //show members
        $members = getMembers(NULL);
        $row = mysqli_fetch_row($equiplist);

        echo "<table><tr><th>Name</th><th>Address</th><th>e-mail address</th><th>Phone</th><th> </th></tr>";
        while($row)
        {
            echo "<tr><td>".$row[1]." ".$row[2]."</td><td>".$row[3]." ".$row[4].", ".$row[5]." ".$row[6]."</td><td>".$row[7]."</td><td>".$row[8]."</td><td>.""BUTTON GOES HERE"."</td></tr>";
        }
        echo "</table>";
}
?>
<?php include("phpincludes/footer.php"); ?>
