<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add or View Transactions</title>
<h1 align="center">Add or View Transactions</h1>
<?PHP 
include("../phpincludes/db_finance.php");
include_once("../phpincludes/db_connect.php");
include_once("../phpincludes/header.php");

?>
</head>

<body>

<?PHP
	/*********************************************************
	 *	Section: I
	 *		Choice between Adding and Viewing Transactions
	 *			-Adding: Date picker for budget date
	 *			-Viewing:
	 *				*2 Date pickers to select start and end dates
	 *
	 *********************************************************/
	if( (! isset($_REQUEST["addTransDate"])) && (! isset($_REQUEST["viewTransDate"])) )
	{
		$addTransSection	=	"<h3 align=\"center\">Add Transactions</h3><p>Select the desired budget</p>";
		$addTransSection	.=	"<table cellpadding=\"5\" cellspacing=\"5\">"
										."<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
										   ."<tr><td>";
		echo $addTransSection;
												require_once("../calendar/classes/tc_calendar.php");
												$myCalendar	=	new	tc_calendar("addDate", TRUE);
												$myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
												$myCalendar	->	setDate(date('j'), date('n'), date('Y'));
												$myCalendar	->	writeScript();
	
		$addTransSection	=				"</td><td>Transaction Date</td></tr>"
											."<tr><td>&nbsp;</td><td>";
		echo $addTransSection;
	
		$addTransSection	=				"<input type=\"submit\" name=\"addTransDate\" value=\"Submit\">"
											."</td></tr>";
		echo	$addTransSection."</form></table>";
		
		$viewTransSection	=	"<h3 align=\"center\">View Transactions</h3><p align=\"center\">Select the desired date range to view all transactions between start and end date</p>";
		$viewTransSection	.=	"<table cellpadding=\"5\" cellspacing=\"5\">"
										."<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
										   ."<tr><td>";
		echo $viewTransSection;
												require_once("../calendar/classes/tc_calendar.php");
												$myCalendar	=	new	tc_calendar("startDate", TRUE);
												$myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
												$myCalendar	->	setDate(date('j'), date('n'), date('Y'));
												$myCalendar	->	writeScript();
	
		$viewTransSection	=				"</td><td>Start Date</td></tr>"
											."<tr><td>";
		echo $viewTransSection;
												require_once("../calendar/classes/tc_calendar.php");
												$myCalendar	=	new	tc_calendar("endDate", TRUE);
												$myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
												$myCalendar	->	setDate(date('j'), date('n') - 6, date('Y'));
												$myCalendar	->	writeScript();
	
		$viewTransSection	=				"</td><td>End Date</td></tr><tr><td>&nbsp;</td><td>"
											."<input type=\"submit\" name=\"viewTransDate\" value=\"Submit\">"
											."</td></tr>";
		echo	$viewTransSection."</form></table>";
	}
	
	
	 /*********************************************************
	 *	Section: II
	 *		Adding Transaction	Page 2
	 *			-Date picker for transaction date
	 *			-Drop down for budget items
	 *			-Text Box for Transaction Amount
	 *			-Text Box for Transaction Description
	 *			-Upload file button (non-functional)
	 *			-Submit button
	 *
	 *********************************************************/
	 if( isset($_REQUEST['addTransDate'])	)
	{
		$budget_date	=	$_REQUEST['addDate'];
		
		echo	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">";
		echo	"<table cellpadding=\"5\" cellspacing=\"5\"><tr><td>";
		
		
		require_once("../calendar/classes/tc_calendar.php");
		$myCalendar	=	new	tc_calendar("transDate", TRUE);
		$myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
		$myCalendar	->	setDate(date('j'), date('n'), date('Y'));
		$myCalendar	->	writeScript();
		
		echo	"</td><td>Transaction Date</td></tr>";
		echo	"<tr><td colspan=\"2\"><select name=\"budgetItem\">";
		
		$query	=	"SELECT A.name FROM budget_item A, budget B WHERE '".$budget_date."' BETWEEN B.start_date and B.end_date AND B.start_date = A.budget_date";
		$result	=	db_connect::run_query($query);
		while	($row = mysqli_fetch_row($result) )
		{
			echo	"<option value=\""	.$row[0]	."\">"	.$row[0]	."</option>";
		}
		echo	"</select></td></tr>";
		
		
		echo	"<tr><td><input type=\"text\" name=\"Description\"></td><td>Description</td></tr>";
		echo	"<tr><td><input type=\"text\" name=\"Amount\"><td>Amount</td></tr>";
		echo	"</table>";
		echo	"<input type=\"hidden\" value=\"".$_REQUEST["addDate"]."\" name=\"budgetDate\">";
		echo	"</form>";
	}
	 /*********************************************************
	 *	Section: III
	 *		Adding Transaction	Page 3 (summary)
	 *			-list ten most recent transactions (including new)
	 *			-return to main button
	 *
	 *********************************************************/
	 
	 /*********************************************************
	 *	Section: IV
	 *		Viewing Transaction	Page 2 (summary)
	 *			-Table containing transactions between start and end
	 *			-Cancel button next to each row
	 *			-Return to main button
	 *
	 *********************************************************/
	 if( isset($_REQUEST['viewTransDate'])	)
	{
		echo	"viewTransactions <br />start: ".$_REQUEST["startDate"]."<br />end: ".$_REQUEST["endDate"];
	}


include_once("../phpinclude/footer.php");
?>
</body>
</html>