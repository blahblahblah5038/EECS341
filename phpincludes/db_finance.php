<?php
include_once('db_access.php');
include_once('db_connect.php');
class db_finance
{
	
	
	/************************************************************************
	 *																		*
	 *		Public functions to add or update tuples in tables				*
	 *																		*
	 *		NOTE:	There are no deletion methods for the Finance section	*
	 *																		*
	 ************************************************************************/
		
	//	Name:			validateBudget()
	//	Input:			budget start date
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, no items returned, wrong totals
	//	Actions:		checks if totals from budget_items = totals from budget (req., alloc., bal.)
	//	Function Calls:	db_connect::run_query(), mysqli_fetch_row(), mysqli_free_result(), mysqli_num_rows()
	//	
	//	NOTES:
	public function validateBudget($date)
	{
		
		//	Run queries to find tuples with date = $date
		$item_query		=	"SELECT requested, allocated, balance FROM budget_item WHERE budget_date = '"		.$date.	"'";
		$budget_query	=	"SELECT total_requested, total_allocated, balance FROM budget WHERE start_date = '"	.$date.	"'";
		
		$item_result	=	db_connect::run_query($item_query) or die('Error in query');
		$budget_result	=	db_connect::run_query($budget_query) or die('Error in query');
		
		// There are are results...
		if ( (mysqli_num_rows($item_result) > 0) && (mysqli_num_rows($budget_result) == 1 ) )
		{
			$request_total		=	0;
			$allocated_total	=	0;
			$balance_total		=	0;
			$display_error		=	false;
			
			// Sum all requested, allocated, and balance amounts from each returned budget_item
			while($item_row	=	mysqli_fetch_row($item_result))
			{
				$request_total		+=	$item_row[0];
				$allocated_total	+=	$item_row[1];
				$balance_total		+=	$item_row[2];
			}
			
			$budget_row	=	mysqli_fetch_row($budget_result);
			$error_msg	=	"You have a problem. \n";
			
			// Compare summed totals to budget total
			// If there something does not match, print a HELPFUL error message
			if ( $request_total != $budget_row[0] )
			{	
				$display_error	=	true;
				$error_msg	.=	"The requested totals do not match."
								." Budget_total: ". $budget_row[0] ."\t Budget_item totals: ". $request_total ."\n";
			}
			
			if ( $allocated_total != $budget_row[1] )
			{
				$display_error	=	true;
				$error_msg	.=	"The allocated totals do not match."
								." Budget_total: ". $budget_row[1] ."\t Budget_item totals: ". $allocated_total ."\n";
			}
			
			if ( $balance_total !=	$budget_row[2] )
			{
				$display_error	=	true;
				$error_msg	.=	"The balance totals do not match."
								." Budget_total: ". $budget_row[2] ."\t Budget_item totals: ". $balance_total ."\n";
			}
			
			if( $display_error )
				echo $error_msg;		
		}
		else
		{
			echo "No budget or budget_items were returned. Your date must be wrong.";
		}
		echo	"validated";
		// Free up space
		mysqli_free_result($item_result);
		mysqli_free_result($budget_result);
	}
	
	//	Name:			addBudget()
	//	Input:			start date, end date, total request, total allocation, and description
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, start date already in use
	//	Actions:		checks if exists and creates tuple if not found
	//	Function Calls:	db_finance::budgetExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			
	public function addBudget($start_date, $end_date, $total_requested, $total_allocated, $description)
	{
		// check if budget exists, if no create tuple
		if (! db_finance::budgetExists($start_date) )
		{
			$query	=	"INSERT INTO budget (start_date, end_date, total_requested, total_allocated, balance, description) VALUES ("
						."'"	.$start_date
						."', '"	.$end_date
						."', "	.$total_requested
						.", "	.$total_allocated
						.", "	.$total_allocated	// starting balance at creation = total allocation
						.", '"	.$description."')";
						
			db_connect::run_query( $query ) or die("Error in query");
			echo "Created budget<br />";
		}
		else
		{
			echo "Budget was not created. The start date $start_date has already been used.";
		}
	}
	
	//	Name:			addBudgetItem()
	//	Input:			name, budget start date, requested, allocated, description
	//	Output:			nothing returned,	echo errors
	//	Errors:			query error, budget does not exist, budget item already exists
	//	Actions:		checks if exists and creates tuple if not found
	//	Function Calls:	db_finance::budgetExists(), db_finance::budgetItemExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			budgetItemExists can return false if the budget does not exist, so
	//					both testing methods must be run
	public function addBudgetItem($name, $budget_date, $requested, $allocated, $balance, $description)
	{
		// check if budget exists
		if( db_finance::budgetExists($budget_date) )
		{
			// check if budget_item exists, if no create the specified tuple
			if (! db_finance::budgetItemExists($name, $budget_date) )
			{
				$query	=	"INSERT INTO budget_item (name, budget_date, requested, allocated, balance, description) VALUES ("
							."'"	.$name
							."', '"	.$budget_date
							."', "	.$requested
							.", "	.$allocated
							.", "	.$balance
							.", '"	.$description."')";
							
				db_connect::run_query(	$query	)	or die("Error in query");
			}
			else
				echo "No changes were made to budget_item table. The budget item $name for $budget_date already exists.";
		}
		else
			echo "No changes were made to the budget_item table. The budget specified by $budget_date does not exist.";
	}
	
	//	Name:			addDues()
	//	Input:			person ID, budget date, transaction date, amount, description
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		creates new transaction for member and adds dues tuple
	//	Function Calls:	db_access::personExists(), db_finance::addTransaction(), db_finance::findTransaction(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			
	public function addDues($pid, $budget_date, $trans_date, $amount, $description)
	{
		// Check if person exists
		if( db_access::personExists($pid) )
		{
			// Create transaction
			db_finance::addTransaction("Membership Dues", $budget_date, $trans_date, $amount, $description, null);
			
			// Get transaction ID
			$trans_id	=	db_finance::findTransaction("Membership Dues", $budget_date, $trans_date, $amount, $description, null);
			
			// Create dues tuple
			$query	=	"INSERT INTO dues (pid, trans_id, amount, description) VALUES ("
								.$pid
						.", "	.$trans_id
						.", "	.$amount
						.", '"	.$description."')";
						
			$results	=	db_connect::run_query(	$query	)	or die("Error in query");
			mysqli_free_result($results);
		}
		else
		{
			echo "No dues were recorded. The person with pid $pid does not exist.";
		}
	}
	
	//	Name:			addTransaction()
	//	Input:			line item name, budget start date, transaction date, amount, description, receipt
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, budget does not exist, budget item does not exist
	//	Actions:		
	//	Function Calls:	db_finance::budgetItemExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			insertion into transaction table causes a TRIGGER to activate
	//					this TRIGGER will update the balances of the budget_item and budget tables
	//
	//					TRIGGER HAS NOT YET BEEN WRITTEN OR IMPLEMENTED
	//
	//					THERE IS NO WAY TO EDIT OR DELETE A TRANSACTION (by design) EXCEPT THROUGH TERMINAL
	public function addTransaction($item_name, $budget_date, $trans_date, $amount, $description, $receipt)
	{
		// Check to make sure budget and budget_items exist, then create transaction
		if ( db_finance::budgetItemExists($item_name, $budget_date) )
		{
			$query	=	"INSERT INTO transaction (item_name, budget_date, trans_date, amount, description, receipt) VALUES ("
						."'"	.$item_name
						."', '"	.$budget_date
						."', '"	.$trans_date
						."', "	.$amount
						.", '"	.$description
						."', "	.$receipt.")";
						
			$results	= db_connect::run_query( $query	)	or die("Error in query");
			mysqli_free_result($results);
		}
		else
		{
			echo "Transaction was not recorded. Either the budget ( $budget_date ) or the budget item ( $item_name ) does not exist.";
		}
	}
	
	//	Name:			editBudget()
	//	Input:			start date, end date, total requested, total allocated, balance, description
	//	Output:			nothing returned,	echo errors
	//	Errors:			query error, budget does not exist
	//	Actions:		overrides budget (specified by start date) with new info
	//	Function Calls:	db_finance::budgetExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			UPDATING THE BUDGET IS NOT RECOMMENDED AFTER BUDGET_ITEMS HAVE BEEN CREATED
	//					CARE MUST BE TAKEN TO ENSURE THE BALANCE IS CORRECT
	public function editBudget($start_date, $end_date, $total_requested, $total_allocated, $balance, $description)
	{
		// Check if budget exists
		if(	db_finance::budgetExists($start_date) )
		{
			$query	=	"UPDATE budget SET "	
						." end_date = '"			.$end_date."',"
						." total_requested = "		.$total_requested.","
						." total_alloacted = "		.$total_allocated.","
						." balance = "				.$balance.","
						." description = '"			.$description."'"
						." WHERE start_date = '"	.$start_date."'";
						
			$result	=	db_connect::run_query( $query	)	or die("Error in query");
			mysqli_free_result($result);
		}
		else
		{
			echo "No changes were made. The budget with start date $start_date does not exist.";
		}
	}
	
	//	Name:			editBudgetItem()
	//	Input:			name, budget start date, requested, allocated, balance, description
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, budget does not exist, budget_item does not exist
	//	Actions:		overrides budget_item (specified by start date and name) with new info
	//					DOES NOT UPDATE BUDGET OR TRANSACTIONS
	//	Function Calls:	db_finance::budgetExists(), db_finance::budgetItemExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			UPDATING THE BUDGET_ITEM IS NOT RECOMMENDED AFTER TRANSACTIONS HAVE BEEN CREATED
	//					CARE MUST BE TAKEN TO ENSURE THE BALANCE IS CORRECT
	public function editBudgetItem($name, $budget_date, $requested, $allocated, $balance, $description)
	{
		// Check if budget exists
		if ( db_finance::budgetExists($budget_date) )
		{
			// Check if budget_item exists
			if ( db_finance::budgetItemExits($name, $budget_date) )
			{
				// Update specified tuple
				$query	=	"UPDATE budget_item SET "
							." requested = "		.$requested
							.", alloacted = "		.$allocated
							.", balance = "			.$balance
							.", description = '"	.$description."'"
							." WHERE name = '"		.$name."'"
							." AND budget_date = '"	.$budget_date."'";
							
				$result	=	db_connect::run_query(	$query	)	or die("Error in query");
				mysqli_free_result($result);
			
			}
			else
			{
				echo "No changes were made to the budget_item table. The budget_item tuple specified by $name and $budget_date does not exist.";
			}
		}
		else
		{
			echo "No changes were made to the budget_item table. The budget specified by $budget_date does not exist.";
		}
	}
	
	//	Name:			editDues()
	//	Input:			person ID, old transaction ID, new transaction date, new amount, new description
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, person does not exist, transaction does not exist
	//	Actions:		creates 2 new transactions
	//						1 to counter old trans (-1 * old amount)
	//						1 to add new trans for person
	//					finds dues item with old trans_id and overrides info
	//	Function Calls:	db_access::personExists(), db_finance::addTransaction(), db_finance::findTransaction(),
	//					db_connect::run_query(), mysqli_num_rows(), mysqli_fetch_row(), mysqli_free_result()
	//
	//	NOTES:			Does not check for previous dues entity, assumes it exists because of old_trans_id
	public function editDues($pid, $old_trans_id, $trans_date, $new_amount, $new_description)
	{
		// Check that member exists
		if ( db_access::personExists($pid) )
		{
			// Find old transaction amount 
			$query1		=	"SELECT * FROM transaction WHERE trans_id = ".$old_trans_id;
			$result1	=	db_connect::run_query(	$query1	)	or die("Error in query");
			
			
			if( mysqli_num_rows($result1) == 0 )
			{
				// Create new transaction with amount = -1 * old_amount
				$trans_row	=	mysqli_fetch_row($result1);
				db_finance::addTransaction(	$trans_row[1], $trans_row[2], $trans_date, (-1 * $trans_row[4]),
								"Updating dues entity. Negating old transaction to create a new one.", null );
				
				// Create new transaction with new_amount
				db_finance::addTransaction(	"Membership Dues", $trans_row[2], $trans_date, $new_amount, $new_description, null );
				
				// Find new transaction id
				$new_trans_id	=	db_finance::findTransaction( "Membership Dues", $trans_row[2], $trans_date, $new_amount, $new_description, null );
				
				// Update old dues 
				$query2		=	"UPDATE dues SET "
								."trans_id = "			.$new_trans_id
								.", amount = "			.$new_amount
								.",	description = '"	.$new_description
								."'	WHERE pid = "		.$pid
								." AND trans_id = "		.$old_trans_id;
								
				$result2	=	db_connect::run_query(	$query2	)	or die("Error in query");
				mysqli_free_result($result2);
				
			}
			else
			{
				echo "No changes were made to dues or transactions. The trans id $old_trans_id did not return any results.";
			}
			
			mysqli_free_result($result1);
		}
		else
		{
			echo "No changes were made to dues or transactions. The person given by pid $pid does not exist.";
		}
	}
	
	//	Name:			budgetExists()
	//	Input:			start date
	//	Output:			true/false,	echo errors
	//	Errors:			query error
	//	Actions:		
	//	Function Calls:	db_connect::run_query(), mysqli_num_rows(), mysqli_free_result()
	//	
	//	NOTES:			ASSUMES DATE IS ENTERED CORRECTLY
	private function budgetExists($date)
	{
		$query1		=	"SELECT * FROM budget WHERE start_date = '".$date."'";
		$results1	=	db_connect::run_query( $query1	) or die("Error in query");
		$value		=	mysqli_num_rows( $results1);
		mysqli_free_result( $results1);
		return ( $value > 0 );
	}
	
	//	Name:			budgetItemExists()
	//	Input:			name, budget start date
	//	Output:			true/false,	echo errors
	//	Errors:			query error
	//	Actions:		checks if budget and budgetItem exist
	//	Function Calls:	db_finance::budgetExists(), db_connect::run_query(), mysqli_num_rows(), mysqli_free_result()
	//	
	//	NOTES:			can return false if budget doesn't exist
	public function budgetItemExists($name, $start_date)
	{
		// check if budget exists
		if ( db_finance::budgetExists($start_date) )
		{
			$query	=	"SELECT * FROM budget_item WHERE "
						."name = '".$name."' AND "
						."budget_date = '".$start_date."'";
						
			$results	=	db_connect::run_query( $query	) or die("Error in query");
			$value		=	mysqli_num_rows($results);
			mysqli_free_result($results);
			return ($value > 0);
		}
		else
		{
			return false;
		}
	}
	
	//	Name:			duesExists()
	//	Input:			person ID, transaction ID
	//	Output:			true/false, echo errors
	//	Errors:			query error
	//	Actions:		checks if dues tuple exists
	//	Function Calls:	db_connect::run_query(), mysqli_num_rows(), mysqli_free_result()
	//					
	//	NOTES:			
	public function duesExists($pid, $trans_id)
	{
		$query	=	"SELECT * FROM dues WHERE "
					."pid = "		.$pid ." AND "
					."trans_id = "	.$trans_id;
		
		$result	=	db_connect::run_query(	$query	)	or die("Error in query");
		$value	=	mysqli_num_rows($result);
		mysqli_free_result($result);
		return	($value > 0);
	}
	
	//	Name:			findTransaction()
	//	Input:			line item name, budget date, transaction date, amount, description, receipt number
	//	Output:			returns transaction id, echo errors
	//	Errors:			query error, no results from search, more than 1 result from search
	//	Actions:		searches transaction table for specific transaction and returns trans_id value
	//	Function Calls:	db_connect::run_query(), mysqli_num_rows(), mysqli_fetch_row(), mysqli_free_result()
	//	
	//	NOTES:			enter null for unknown fields
	public function findTransaction($item_name, $budget_date, $trans_date, $amount, $description, $receipt)
	{
		$query	=	"SELECT trans_id FROM transaction WHERE ";
		
		// check for and exclude null values
		if ($item_name != null	)
			$query	.=	"item_name = '"		.$item_name."' AND ";
		if ($budget_date != null	)
			$query	.=	"budget_date = '"	.$budget_date."' AND ";
		if ($trans_date != null	)
			$query	.=	"trans_date = '"	.$trans_date."' AND ";
		if ($amount	!=	null	)
			$query	.=	"amount = "			.$amount." AND ";
		if ($description	!=	null	)
			$query	.=	"description = '"	.$description."' AND ";
		if ($receipt	!=	null	)
			$query	.=	"receipt = "		.$receipt." AND ";
			
		$query	.=	"TRUE";
		
		$results	=	db_connect::run_query( $query	)	or die("Error in query");
		$num_rows	=	mysqli_num_rows($results);
		
		if ($num_rows == 0)
		{
			echo "Your query returned no results, so the trans_id returned will be -1.";
			mysqli_free_result($results);
			return -1;
		}
		else if ($num_rows > 1)
		{
			echo "Your query returned more than 1 result, so the trans_id returned was the first record.";
			
		}
		
		// Return the first trans_id from the first (or only tuple result) and free memory
		$trans_row	=	mysqli_fetch_row($results);
		$value		=	$trans_row[0];
		mysqli_free_result($results);
		return $value;
	}
	
	//	Name:			echoForm1()
	//	Input:			arry of field names,
	//					array of field default values
	//					reference name for submit button
	//	Output:			return statement to echo
	//	Errors:			none
	//	Actions:		creates a post form with a text field for each field name in $names
	//					reference name is $fieldName-$i for 0 <= $i < num fields in $names
	//	Function Calls:	none
	//	
	//	NOTES:			default values are optional but array defValues is not
	public function echoForm1($names, $defValues, $fieldName, $submitName)
	{
		// Create basic form with table
		$formStatement	=	//"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
								/*.*/"<table>";
		
		// Populate table with input text boxes from list (and default values)
		for( $i = 0; $i < count($names); $i++ )
		{
			$formStatement	.=		"<tr>"
										."<td>".$names[$i]."</td>"
										."<td><input type=\"text\" name=\"".$fieldName."-".$i."\"";
			if($defValues[$i] != NULL)
				$formStatement	.=		" value=\"".$defValues[$i]."\"";
				
			$formStatement	.=			"></td>"
									."</tr>";
		}
		
		// Create submit button and end table
		$formStatement	.=			"<tr>"
										."<td>"/*<input type=\"submit\" name=\"".$submitName."\" value=\"Submit\">*/."</td>"
									."</tr>"
								."</table>"
							."</form>";
							
		return $formStatement;
	}

	//	Name:			printBudgetItems()
	//	Input:			budget start date, bool if to make separate table
	//	Output:			returns printable table, echo errors
	//	Errors:			query error
	//	Actions:		makes string to print the name, requested, allocated, and balance values
	//	Function Calls:	mysqli_query(), mysqli_fetch_row(), mysqli_free_result()
	//	
	//	NOTES:
	public function printBudgetItems($startDate, $separateTables, $selection, $name)
	{
		$query1	=	"SELECT * FROM budget_item WHERE budget_date = '".$startDate."'";
	//	$result1	=	mysqli_query($databaseConn, $query1) or die("Query Error");
		$result1	=	db_connect::run_query( $query1	)	or die("Error in query");
		//	If there are no results, print error and button to create a new budget item
		if( mysqli_num_rows($result1) == 0 )
		{
			echo "No budget_items were found for this budget.<br />You can add budget items to this budget. Press the button below.<br />";
			$output	=	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
							."<input type=\"submit\" name=\"createBudgetItem1\" value=\"Create Budget Items\"></form>";
			return	$output;
		}
		
		$outputTable;
		if($separateTables)
			$outputTable	.=	"<table>";
		$outputTable	.=		"<tr>"
									."<td>Name</td>"
									."<td>Requested</td>"
									."<td>Allocated</td>"
									."<td>Balance</td>";
		if($selection == "checkbox")							
			$outputTable	.=	"<td>Select to Alter</td>";
		$outputTable	.=		"</tr>";
		$i	=	0;	
		while(	$rows = mysqli_fetch_row($result1))
		{
			$outputTable	.=	"<tr>"
									."<td>$rows[0]</td>"
									."<td align=\"center\">$rows[2]</td>"
									."<td align=\"center\">$rows[3]</td>"
									."<td align=\"center\">$rows[4]</td>";
			if($selection == "checkbox")
				$outputTable	.=	"<td align=\"center\"><input type=\"$selection\" name=\"$name-$i\" value=\"$rows[0]\">";
			$outputTable	.=	"</tr>";
			$i++;
		}
		if($separateTables)
			$outputTable	.=	"</table>";
	//	echo	$outputTable;
		mysqli_free_result($result1);
		return ($outputTable);
	}
	
	//	Name:			lineItemFields()
	//	Input:			line item from selected budget,
	//					array of field names,
	//					array of field default values
	//	Output:			return nothing, echo errors
	//	Errors:			line item does not have a form yet
	//	Actions:		compares input line Item to listed line items
	//					overrides name/defValue arrays with new labels
	//	Function Calls:	none
	//	
	//	NOTES:			ADDED LINE_ITEM, Total fields to SCPC Form Fields accomodate printing summary
	//					Changed order of USG form fields to ITEM, Total, (everything else)
/*****************************************************************************************************************************************************/
	//			changes to make		
	//					CHANGE: changed Total to Total Request
	//					ADDED:	Allocated and Balance to positions 2 and 3
	public function lineItemFields($lineItem, &$names, &$defValue, &$length)
	{
		switch( $lineItem )
		{	/******************************************************
			 *
			 * 				SCPC Form Fields
			 *
			 *****************************************************/
			case "Affiliation Dues":
			case "SC_E_dues":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Name", "Cost");
				$defValue	=	array(0 => "Affiliation Dues", "100", "0", "0", "Default Name", "100");
				$length		=	count($names);
				break;
			case "Donations":
			case "SC_I_donate":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Anticipated Donations");
				$defValue	=	array(0 => "Donations", "1", "0", "0", "Default Name");
				$length		=	count($names);
				break;
			case "Entry Fees":
			case "SC_E_fees":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Where", "When", "Number of Teams", "Cost per Team", "Event Title");
				$defValue	=	array(0 => "Entry Fees", "1", "0", "0", "Default Location", "Default Date", "Default Number", "Default Cost per Team", "Default Title");
				$length		=	count($names);
				break;
			case "Equipment":
			case "SC_E_equip":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Quantity", "Item", "Unit Price");
				$defValue	=	array(0 => "Equipment", "1", "0", "0", "Default Quantity", "Default Item", "Default Unit Price");
				$length		=	count($names);
				break;
			case "Expense of Money Making Projects":
			case "SC_E_mmp":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Date", "Event");
				$defValue	=	array(0 => "Expense of Money Making Projects", "1", "0", "0", "Default Date", "Default Event");
				$length		=	count($names);
				break;
			case "Facilities":
			case "SC_E_facil":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Location", "Date", "Cost");
				$defValue	=	array(0 => "Facilities", "1", "0", "0", "Default Location", "Default Date", "Default Cost");
				$length		=	count($names);
				break;
			case "Fundraising":
			case "SC_I_fundraise":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Date", "Event", "Income");
				$defValue	=	array(0 => "Fundraising", "1", "0", "0", "Default Date", "Default Event", "Default Income");
				$length		=	count($names);
				break;
			case "Membership Dues":
			case "SC_I_dues":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Number of Members(expected)", "Dues");
				$defValue	=	array(0 => "Membership Dues", "1", "0", "0", "Default Number", "Default Amount");
				$length		=	count($names);
				break;
			case "Miscellaneous":
			case "SC_E_misc":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Item", "Cost");
				$defValue	=	array(0 => "Miscellaneous", "1", "0", "0", "Default Item", "Default Cost");
				$length		=	count($names);
				break;
			case "Office Expenses":
			case "SC_E_office":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Item", "Cost");
				$defValue	=	array(0 => "Office Expenses", "1", "0", "0", "Default Item", "Default Cost");
				$length		=	count($names);
				break;
			case "Officials":
			case "SC_E_officials":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Date", "Number of Officials", "Cost per Official");
				$defValue	=	array(0 => "Officials", "1", "0", "0", "Default Date", "Default Number", "Default Cost");
				$length		=	count($names);
				break;
			case "Other Income":
			case "SC_I_other":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Item", "Amount");
				$defValue	=	array(0 => "Other Income", "1", "0", "0", "Default Item", "Default Amount");
				$length		=	count($names);
				break;
			case "Room/Lodging":
			case "SC_E_room":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Where", "When", "Number of Days", "Number of People", "Number of Nights");
				$defValue	=	array(0 => "Room/Lodging", "1", "0", "0", "Default Location", "Default Date", "Default Number", "Default Number", "Default Number");
				$length		=	count($names);
				break;
			case "SCPC Grant":
			case "SC_I_grant":
				$names		=	array(0 =>	"LINE_ITEM", "Total", "Allocated", "Balance", "Amount");
				$defValue	=	array(0 => 	"SCPC Grant", "1", "0", "0", "Default Amount");
				$length		=	count($names);
				break;
			case "Transportation":
			case "SC_E_trans":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Where", "When", "Number of People", "Number of Vehicles", "Mileage");
				$defValue	=	array(0 => "Transportation", "1", "0", "0", "Default Location", "Default Date", "Default Number", "Default Number", "Default Mileage");
				$length		=	count($names);
				break;
			case "Workshops, Clinics, etc.":
			case "SC_E_clinic":
				$names		=	array(0 => "LINE_ITEM", "Total", "Allocated", "Balance", "Date", "Name", "Cost");
				$defValue	=	array(0 => "Workshops, Clinics, etc.", "1", "0", "0", "Default Date", "Default Name", "Default Cost");
				$length		=	count($names);
				break;
			
			
			/******************************************************
			 *
			 * 				USF Form Fields
			 *
			 *****************************************************/			
			case "Automatic":
			case "USG_auto":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance");
				$defValue	=	array(0 =>	"Automatic", "50.00", "0", "0");
				$length		=	count($names);
				break;
			case "Beginner Safety Equipment":
			case "USG_bsafe":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"Beginner Safety Equipment", "50.00", "0", "0", "Default Description", "Equipment", "1", "50.00");
				$length		=	count($names);
				break;
			case "USG Equipment":
			case "USG_equip":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"USG Equipment", "Default Total Cost", "0", "0", "Default Description", "Equipment", "1", "Default Unit Cost");
				$length		=	count($names);
				break;
			case "Instructor Fees":
			case "USG_fees":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"Instructor Fees", "1320.00", "0", "0", "Professional Instruction for Advanced Archers", "Contracted Services", "2", "660.00");
				$length		=	count($names);
				break;
			case "Renovation Materials":
			case "USG_renov":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"Renovation Materials", "Default Total Cost", "0", "0", "Default Description", "Equipment", "1", "Default Unit Cost");
				$length		=	count($names);
				break;
			case "USCA Dues":
			case "USG_USCA":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"USCA Dues", "90.00", "0", "0", "Club Recognition by USCA", "Contracted Services", "1", "90.00");
				$length		=	count($names);
				break;
			case "Targets for Caskey":
			case "USG_Caskey_tar":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"Caskey Targets", "Default Total Cost", "0", "0", "New Targets: 40cm for Compound and recurve", "Equipment", "Default Quantity", "Default Unit Cost");
				$length		=	count($names);
				break;
			case "Officials for Caskey":
			case "USG_Caskey_off":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"Caskey Officials", "100.00", "0", "0", "Hire officials to judge Caskey Tournament", "Contracted Services", "2", "50.00");
				$length		=	count($names);
				break;
			case "Chairs for Caskey":
			case "USG_Caskey_chair":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"Caskey Chair Rental", "95.00", "0", "0", "60 chairs + moving fee", "Equipment", "1", "95.00");
				$length		=	count($names);
				break;
			case "Targets for States":
			case "USG_State_tar":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"States Targets", "Default Total Cost", "0", "0", "New Targets: 40cm and 60cm for Compound and recurve", "Equipment", "Default Quantity", "Default Unit Cost");
				$length		=	count($names);
				break;
			case "Officials for States":
			case "USG_State_off":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"States Officials", "200.00", "0", "0", "Officials to judge the tournament for two days (\$50 per day per official)", "2", "100.00");
				$length		=	count($names);
				break;
			case "Chairs for States":
			case "USG_State_chair":
				$names		=	array(0 =>	"Item", "Total", "Allocated", "Balance", "Description", "Item Category", "Quantity", "Unit Price");
				$defValue	=	array(0 =>	"States Chair Rental", "110.00", "0", "0", "80 chairs + moving fee", "Equipment", "1", "110.00");
				$length		=	count($names);
				break;
			
			
			/******************************************************
			 *
			 * 				Default Form Response
			 *
			 *****************************************************/			
			 default:
				echo	"The selected line item ($lineItem) does not have a form yet.";
				break;
		}
	}
	
	//	Name:			updateBudgetItem()
	//	Input:			item name, budget date, new request, new allocate, new balance
	//	Output:			return nothing, echo errors
	//	Errors:			query error
	//	Actions:		updates the budget and budget_item tables with new values
	//	Function Calls:	db_connect::run_query(), mysqli_num_rows(), mysqli_fetch_row(), mysqli_free_result
	//					db_finance::editBudget(), db_finance::editBudgetItem()
	//	
	//	NOTES:
	public function updateBudgetItem($name, $budget_date, $requested, $allocated, $balance)
	{		
			$name			=	"Affiliation Dues";
			$budget_date	=	"2011-08-01";
			$end_date;
			$requested		=	0;
			$allocated		=	0;
			$balance		=	0;
			$description	=	"";
			$oldValues		=	array();
			$query1			=	"SELECT * FROM budget_item WHERE name = '".$name."' AND budget_date = '".$budget_date."'";
			$query2			=	"SELECT * FROM budget WHERE start_date = '".$budget_date."'";
			$result1		=	db_connect::run_query($query1);
			$result2		=	db_connect::run_query($query2);
			if( ( mysqli_num_rows($result1) != 1 ) && ( mysqli_num_rows($result2) != 1 ) )
				echo	"Error";
			else
			{
				$row1 = mysqli_fetch_row($result1);
				for($i = 0; $i < 6; $i++)
					$oldValues[$i]	=	$row1[$i];
					
				$row2		=	mysqli_fetch_row($result2);
				$end_date	=	$row2[1];
				
				$description	.=	"EDIT, ".date('Y-m-d').", ".$oldValues[5];
				db_finance::editBudgetItem($name, $budget_date, $requested, $allocated, $balance, $description);
				db_finance::editBudget($budget_date, $end_date, $row2[2] - $oldValues[2] + $requested, $row2[3] - $oldValues[3] + $allocated, $row2[4] - $oldValues[4] + $balance, "EDIT, ".date('Y-m-d').", $name, ".$description);
				echo	"<br />Edit budget item: $name; budget: $budget_date<br />";
			}
			mysqli_free_result($result1);
			mysqli_free_result($result2);
	}
	
/*	
	//	Name:			printBudgetsFromDate()
	//	Input:			date within applicable range of budget
	//	Output:			return nothing, echo errors
	//	Errors:			query error, no budgets in range
	//	Actions:		prints the name, requested, allocated, and balance values
	//	Function Calls:	mysqli_query(), mysqli_fetch_row(), mysqli_free_result()
	//	
	//	NOTES:
	public function printBudgetsFromDate($date)
	{
		$startDates		=	array();
		$outputTables	=	array();
		$i				=	0;
		
		// query database for budgets
		$query	=	"SELECT start_date FROM budget WHERE '"
					.$date."' BETWEEN start_date AND end_date";
		
		$results	=	db_connect::run_query( $query	)	or die("Error in query");
		
		// Return Error
		if( mysqli_num_rows($results) == 0 )
		{
			return	("The date used ($date) does not correspond to any budget in the system.");	
		}
		
		// store budget start dates in array
		while( $row = mysqli_fetch_row($results))
		{
			$outputTables[$i]	=	printBudgetItems($row[$i]);
			$i++;
		}
		
		// call printBudgetItems() for each start date
		// and store tables in array
		
		// free result and return array of tables
		mysqli_free_result($results);
		return $outputTables;
	}
	
	*/
	
	
	
	
	
	
	
} // end of class


?>
