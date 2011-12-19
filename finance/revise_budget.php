<?PHP //include_once("../phpincludes/header.php"); ?>
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
	/*************************************************************************
	 *	SECTION:	I
	 *		Code for DatePicker, select the start and end dates
	 *
	 *************************************************************************/	
	 
	if(	(! isset($_REQUEST['submitDate'])) && (! isset($_REQUEST['RevisionList']) )	&& (! isset( $_REQUEST['CompletedForms']) )	)
	{
	?>
    <table cellpadding="5" cellspacing="5">
    	<tr><td>Please select a date that falls in the range of the desired budget.</td></tr>
        <tr><td>&nbsp;</td></tr>
        <form method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>" >
        <tr><td>
            <?PHP
                require_once("../calendar/classes/tc_calendar.php");
                $myCalendar	=	new	tc_calendar("startDate", TRUE);
				$myCalendar->	setPath("../calendar/");
                $myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
                $myCalendar	->	setDate(date('j'), date('n'), date('Y'));
                $myCalendar	->	writeScript();
            ?>
            </td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Please select the type of budget to revise.</td></tr>
            <tr><td><input type="radio" name="budget" value="Sport Club">Sport Club &nbsp;
            		<input type="radio" name="budget" value="USG">USG</td></tr>
            <tr><td><br />
            <input type="submit" name="submitDate" value="Submit">
        </form>
        </td></tr>
    </table>
    <?PHP
	}
	
	/*************************************************************************
	 *	SECTION:	II
	 *		Display the budget items for all budgets meeting selection criteria
	 *		Create a form to select which items to modify
	 *
	 *************************************************************************/
	
	if( isset($_REQUEST['submitDate'])	)
	{
		// Variables
		$budget		=	$_REQUEST['budget'];
		$startDate	=	$_REQUEST['startDate'];
		$query;
		$result;
		$newTable	=	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" ><table align=\"center\" border=\"2\">";
		$i			=	0;	
		$j			=	0;	
		$start_date;
		$end_date;
		$time_diff;
		$dates		=	array();
		
		//	Check to make sure budget exists
		$query	=	"SELECT * FROM budget A, budget_item B WHERE '".$startDate
					."' BETWEEN A.start_date AND A.end_date AND B.budget_date = A.start_date";
		
		//	Apply budget constraints (USG or SCPC)	
		if( $budget == "USG"	)	
			$query	.=	" AND DATEDIFF(end_date, start_date) <= 200";
		else if (	$budget	==	"Sport Club"	)
			$query	.=	" AND DATEDIFF(end_date, start_date) > 200";
				
		$result	=	db_connect::run_query($query) or die ("Error in query");
		
		if( mysqli_num_rows($result) == 0	)
		{
			echo "No budget was found with the start date $startDate";	
		}
		else
		{
			$row		=	mysqli_fetch_row($result);

			/*************************************************************/
			/*	Do-while loop to print budget_item tables and store info */
			/*************************************************************/
			do
			{
				$newDate	=	$row[0];
				
				if( $newDate != $oldDate)
				{
					$newTable	.=	"<tr><th colspan=\"5\">Budget Start Date: $row[0]</th></tr>";
					$newTable	.=	db_finance::printBudgetItems($row[0], false, "checkbox", "Budget-$i");
					$i++;
					$dates[$j++]	=	$newDate;
				}
				$oldDate	=	$newDate;
			}
			while( $row = mysqli_fetch_row($result));
			
			echo	$newTable."</table>";
			echo "<p>Please check the boxes of the line items you wish to edit.<p><br />";
			echo	"<br /><br /><input type=\"submit\" name=\"RevisionList\" value=\"Submit\">"
					."<input type=\"hidden\" name=\"NumOfBudgets\" value=\"$i\" >";
			for($j = 0; $j < count($dates); $j++)
				echo	"<input type=\"hidden\" name=\"StartDates-$j\" value=\"$dates[$j]\" >";
			echo	"</form>";
		}
		mysqli_free_result($result);
	}
	
	/*************************************************************************
	 *	SECTION:	III
	 *		Create and display tables to enter updated info for budget_items
	 *
	 *************************************************************************/
	
	if( isset($_REQUEST['RevisionList']) )
	{
	//	echo		"Complete";
		$numBudgets	=	$_REQUEST["NumOfBudgets"];
	//	$numItems	=	$_REQUEST["NumOfItems"];
		$dates		=	array();
		$i			=	0;
		$j			=	0;
		
		for($j = 0; $j < $numBudgets; $j++)
		{
			$dates[$j]	=	$_REQUEST["StartDates-$j"];
	//		echo	"<br />dates[$j]: ".$dates[$j];
		}
		
		$i = 0;
		echo	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\"><table align=\"center\" cellpadding=\"25\" border=\"5\" bordercolordark=\"#000000\">";
		
		
		
		
		$itemName	=	array();
		$budgetDate	=	array();
		$numOfItems	=	20;
	
		/**************************************************/
		/*	store only checked budget_item data in arrays */
		/**************************************************/
		for($k = 0; $k < $numBudgets; $k++)
		{
			for($m = 0; $m < $numOfItems; $m++)
			{
				if( $_REQUEST["Budget-$k-$m"]	)
				{
					$itemName[$i]	=	$_REQUEST["Budget-$k-$m"];
					$budgetDate[$i]	=	$dates[$k];
					$i++;
				}
			}
		}
		
		
//		for( $k = 0; $k < count($itemName); $k++)
//			echo	"itemName[$k]: ".$itemName[$k]."<br />";
			
		$i	=	0;
		
		/*********************************************/
		/*	For each checked box, print forms
		/*********************************************/
		while( $itemName[$i] )
		{		
			// Reinitialize arrays to zero	
			$fieldNames		=	array();
			$defValues		=	array();
			
			// Print headers and forms
			echo	"<tr><th>Form $i (Date: $budgetDate[$i])</th></tr>";//: ".$_POST['ChosenSCPC'][$i]."</th>";			
			db_finance::lineItemFields($itemName[$i], $fieldNames, $defValues);
			if(! empty($fieldNames) )
				echo	"<tr><td>".db_finance::echoForm1($fieldNames, $defValues, "FormField-$i", "submit3")."</td></tr>";
			else
				echo	"<tr><td>No fields found.</td></tr>";	
			$i++;
		}
		echo	"<tr><td align=\"center\"><input type=\"submit\" name=\"CompletedForms\" value=\"Submit\"></td></tr></table>";
		for($j = 0; $j < count($dates); $j++)
			echo	"<input type=\"hidden\" name=\"StartDates-$j\" value=\"$budgetDates[$j]\" >";
		echo	"<input type=\"hidden\" name=\"NumOfBudgets\" value=\"$numBudgets\" >";
	
	}
	
	
	/*************************************************************************
	 *	SECTION:	IV
	 *		Display a summary page
	 *		Create a form with hidden fields to transmit to the final page
	 *
	 *************************************************************************/
	
	if( isset($_REQUEST['CompletedForms']))
	{
		//	Create Summary page
		
		
		//	Create Hidden fields
		/*
			Need the following for each budget item:
				-name
				-budget_date
				-requested amount
				-allocated amount
				-balance
		*/
		
		//	Variables
		$A		=	array();
		$B		=	array();
		$formFields1	=	array();
	//	$dates	=	0;
		$i		=	0;
		$j		=	0;
		$k		=	0;
		
		
		
		
		
			$numBudgets	=	$_REQUEST["NumOfBudgets"];
			$dates		=	array();
			$i			=	0;
			$j			=	0;
			$itemName	=	array();
			$budgetDate	=	array();
			$numOfItems	=	20;
			
			for($j = 0; $j < $numBudgets; $j++)
			{
				$dates[$j]	=	$_REQUEST["StartDates-$j"];
				echo	"<br />dates[$j]: ".$dates[$j];
			}
			
			for($i = 0; $i < 100; $i++)
			{
				for($j = 0; $j < 4; $j++)
				{
					
						echo	"FormField-$i-$j: ".$_REQUEST["FormField-$i-$j"]."<br />";
				}
			}
			
			//	For each budget item, retrieve old values for requested, allocated, and balance
			//	overwrite budget item with new values
			//	update budget by subtracting old values and adding new values
			
		//	db_finance::updateBudgetItem($name, $budget_date, $requested, $allocated, $balance);
			
			
	/*		
			
			
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
			 
//			 	SECTION:	V
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
<?PHP include_once("../phpincludes/footer.php"); ?>