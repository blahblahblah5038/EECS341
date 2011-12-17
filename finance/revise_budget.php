<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Revise Budget</title>
<h1 align="center">Revising An Existing Budget</h1>
<script language="javascript" src="../calendar/calendar.js"></script>
<?PHP 
include("../phpincludes/db_finance.php");
include_once("../phpincludes/db_connect.php")	?>
</head>

<body>

<?PHP
// make connection to database
	$dbc	=	mysqli_connect("localhost", "root", "Svetskar97") or die("Could not connect");
	mysqli_select_db( $dbc, "archeryclub") or die("Could not select database");

	$financeObj2	=	new	db_finance();
	/************************************************************************
	 *																		*
	 *	To Do List:															*
	 *		-Fix the radio button input to prevent multiple selections		*
	 *		-Print budget summary											*
	 *		-Add check boxes to fields needing changes						*
	 *																		*
	 ************************************************************************/

?>

	<?PHP
	//		Code for DatePicker
	if(	(! isset($_REQUEST['submitDate'])) && (! isset($_REQUEST['RevisionList']) )	&& (! isset( $_REQUEST['CompletedForms']) )	)
	{
	?>
    <table cellpadding="5" cellspacing="5">
    	<tr><td>Please select a date that falls in the range of the desired budget.</td></tr>
        <tr><td>&nbsp;</td></tr>
        <form method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
        <tr><td>
            <?PHP
                require_once("../calendar/classes/tc_calendar.php");
                $myCalendar	=	new	tc_calendar("startDate", TRUE);
                $myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
                $myCalendar	->	setDate(11, 1, 2011);
                $myCalendar	->	writeScript();
            ?>
            </td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Please select the type of budget to revise.</td></tr>
            <tr><td><input type="radio" name="SportClub" value="Sport Club">Sport Club &nbsp;
            		<input type="radio" name="USG" value="USG">USG</td></tr>
            <tr><td><br />
            <input type="submit" name="submitDate" value="Submit">
        </form>
        </td></tr>
    </table>
    <?PHP
	}
	
	if( isset($_REQUEST['submitDate'])	)
	{
		// Variables
		$query;
		$result;
		$oldDate;
		$newDate;
		$newTable	=	"<table align=\"center\" border=\"2\">";
		$i			=	0;
		$budgetType;
		$start_date;
		$end_date;
		$time_diff;
		
		
		//	Check to make sure budget exists
		$query	=	"SELECT * FROM budget A, budget_item B WHERE '".$_REQUEST['startDate']
					."' BETWEEN A.start_date AND A.end_date AND B.budget_date = A.start_date";
					
		$result	=	db_connect::run_query($query) or die ("Error in query");
		
		if( mysqli_num_rows($result) == 0	)
		{
			echo "No budget was found with the start date ".$_REQUEST['startDate'];	
		}
		else
		{
			echo	"Num of rows: ".mysqli_num_rows($result)."<br />";
			$row	=	mysqli_fetch_row($result);
			$newDate	=	$row[0];	
			$end_date	=	$row[1];
			echo		"<br />$newDate<br />";
			echo		"<br />$end_date<br />";
			
			/****************************************/
			
			$query2		=	"SELECT DATEDIFF('".$end_date."', '".$newDate."') AS DiffDate";
			$result2	=	db_connect::run_query($query2);
			if( mysqli_num_rows($result2) == 0)
				echo "no rows returned";
			$row2		=	mysqli_fetch_row($result2);
			echo		"<br />Date Diff: ".$row2[0]."<br />";
			echo		"<br />Date Diff: ".$row2."<br />";
			
			
			
			
			
			
		/*	$start_date	=	date($row[0]);
			echo $start_date;
			$end_date	=	date($row[1]);
			echo $end_date;
			echo	date($end_date - $start_date);
			*/
			do
			{
				$newDate	=	$row[0];
				if( $newDate != $oldDate)
				{
					$newTable	.=	"<tr><th colspan=\"5\">Budget Start Date: $row[0]</th></tr>";
					$newTable	.=	db_finance::printBudgetItems($row[0], false, "checkbox", "Budget-$i");
					$i++;
		/*			$start_date	=	date($row[0]);
					$end_date	=	date($row[1]);					
					$start_date1	=	new	DateTime($start_date);
					$end_date1		=	new	DateTime($end_date);
					echo	"start $start_date1;	end $end_date1<br />";
	//				$interval		=	$start_date1->diff($end_date1);
//					echo	$interval->format('%R%a days');
*/
				}
				$oldDate	=	$newDate;
			}
			while( $row = mysqli_fetch_row($result));
			echo	$newTable."</table>";
		//	db_finance::printBudgetItems($row[0]);
			{
				// Print type of budget
				// Print budget start and end dates
				// print the budget table: add check boxes next to item
				echo "Please check the boxes of the line items you wish to edit.<br />";
				echo "Check the 'Add Line Item' box to add a new line item then hit submit.";
				
			//	db_finance::printBudgetItems('2011-01-27');
			//	$financeObj2->printBudgetItems('2011-01-26');
			}
			echo	"<br /><br /><form><input type=\"submit\" name=\"RevisionList\" value=\"Submit\"></form>";
	?>
<?PHP
		}
	}
	
	if( isset($_REQUEST['RevisionList']) )
	{
		echo		"Complete";
		
		/**************************************************************************************/
		$i = 0;
		echo	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\"><table align=\"center\" cellpadding=\"25\" border=\"5\" bordercolordark=\"#000000\">";
				
		$ChosenSCPC	=	array(0 => "Facilities", "Officials");
		$ChosenUSG	=	array(0 => "Automatic", "USCA Dues");
		while( ($ChosenSCPC[$i] ) || ($ChosenUSG[$i]))
		{		
			// Reinitialize arrays to zero	
			$namesSCPC		=	array();
			$defValueSCPC	=	array();
			$namesUSG		=	array();
			$defValueUSG	=	array();
			
			// Print headers
			echo	"<tr><th>SCPC Form $i</th>";//: ".$_POST['ChosenSCPC'][$i]."</th>";			
			echo	"<th>USG Form $i</th></tr>";//: ".$_POST['ChosenUSG'][$i]."</th></tr>";	
			
			// Print the Forms for SCPC items on left side
			if($ChosenSCPC[$i] )
			{
			//	echo "<tr><td>something</td></tr>";
				$financeObj2->lineItemFields($ChosenSCPC[$i], $namesSCPC, $defValueSCPC);
				if(! empty($namesSCPC) )
					echo	"<tr><td>".$financeObj2->echoForm1($namesSCPC, $defValueSCPC, "SCPC-$i", "submit3")."</td>";
				else
					echo	"<tr><td>No fields found.</td>";	
			}
			else
				echo	"<tr><td>No fields found.</td>";	
				
			// Print the Forms for USG items on right side
			if($ChosenUSG[$i])
			{
				$financeObj2->lineItemFields($ChosenUSG[$i], $namesUSG, $defValueUSG);
				if(! empty($namesUSG) )
					echo	"<td>".$financeObj2->echoForm1($namesUSG, $defValueUSG, "USG-$i", "submit3")."</td></tr>";
				else
					echo	"<td>No fields found.</td></tr>";	
			}
			else
					echo	"<td>No fields found.</td></tr>";
			
			$i++;
		}
		echo	"<tr><td align=\"center\" colspan = \"2\"><input type=\"submit\" name=\"CompletedForms\" value=\"Submit\"></td></tr></table>";		
		/**************************************************************************************************/
		/*
		 *		The above can be turned into a function and called here and in create_budget.php page 3
		 *
		 **************************************************************************************************/
	}
	
	if( isset($_REQUEST['CompletedForms']))
	{
			echo		"Complete";
			$i	=	0;
			$j	=	0;
			$SCPC_fields	=	array();
			$USG_fields		=	array();
			while(! empty($_POST["SCPC-$i-0"])	)
			{
				while(! empty($_POST["SCPC-$i-$j"])	)
				{
					$SCPC_fields[$j]	=	$_POST["SCPC-$i-$j"];
					$j++;
				}
				$i++;
				$j = 0;
			}
			$i = 0;
			$j = 0;
			while(! empty($_POST["USG-$i-0"])	)
			{
				while(! empty($_POST["USG-$i-$j"])	)
				{
					$USG_fields[$j]	=	$_POST["US-$Gi-$j"];
					$j++;
				}
				$i++;
				$j = 0;
			}
			
			// Call method to update tables
			//		$1 = array, $2 = position of names, $3 = position of attribute to update
			//		Returns nothing
			updateBudgetAndItems($SCPC_fields, 0, 1);
			updateBudgetAndItems($SCPC_fields, 0, 2);
			updateBudgetAndItems($SCPC_fields, 0, 3);
	//		$_POST["SCPC-$i-0"]
			/****************************************************************************************
			 *
			 *	Using entered values, update the budget and budget_items
			 *		-first check if new values were entered for requested
			 *			-check for each form against current tuples in budget_item table
			 *			-calculate the net change in total request
			 *			-if there is a change, the total_requested in budget needs to be updated too
			 *		-next check if new values were entered for allocated
			 *			-compare old to new for each form
			 *			-calculate the net change in total allocated
			 *			-if there is a change, the total_allocated in budget needs to be updated too
			 *		-calculate the new balance
			 *			-for each form, calculate the new balance as oldBalance + change_in_allocated
			 *			-also update the balance for the budget
			 *		-make changes sequentially rather than simultaneously
			 *			-create methods to perform the above operations
			 *		-validate new budget
			 *
			 ***************************************************************************************/
	}
	
	/*
	<input type="button">Button<br />
<input type="checkbox">Checkbox<br />
<input type="radio">Radio<br />
<input type="submit">Submit<br />
<input type="reset">Reset<br />
<input type="password">Password<br />
	*/
?>


</body>
</html>