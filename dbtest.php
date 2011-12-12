<?php
	include("phpincludes/header.php");
	include("phpincludes/login.php");
	include("phpincludes/db_access.php");
	include("phpincludes/db_equipment.php");
?>

<?php
	echo "SELECT * FROM members<br />";
	$testlist = db_connect::run_query("SELECT * FROM members");
	echo "got a test list<br />";
	$row = mysqli_fetch_row($testlist);
	echo "has a row<br />";
	while ($row)
	{
		$row = mysqli_fetch_row($testlist);
		echo "got another row<br />";
	}
	echo "end of rows<br />";
}
*/?>

<?php include("phpincludes/footer.php"); ?>
