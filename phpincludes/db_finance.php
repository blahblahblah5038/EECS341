<?php
include_once('phpincludes/db_access.php');
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
		if ( (mysqli_num_rows($item_result) > 0) && (mysqli_num_rows($budget_result) > 0 ) )
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
		
		// Free up space
		mysqli_free_result($item_result);
		mysqli_free_result($budget_result);
	}
	
	
	//	Name:			addBudget()
	//	Input:			start date, end date, total request, total allocation, and description
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, start date already in use
	//	Actions:		checks if exists and creates tuple if not found
	//	Function Calls:	budgetExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			
	public function addBudget($start_date, $end_date, $total_requested, $total_allocated, $description)
	{
		// check if budget exists, if no create tuple
		if (! budgetExists($start_date) )
		{
			$query	=	"INSERT INTO budget (start_date, end_date, total_requested, total_allocated, balance, description) VALUES ("
						."'"	.$start_date
						."', '"	.$end_date
						."', "	.$total_requested
						.", "	.$total_allocated
						.", "	.$total_allocated	// starting balance at creation = total allocation
						.", '"	.$description."')";
						
			$results	=	db_connect::run_query( $query ) or die("Error in query");
			mysqli_free_result($results);
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
	//	Function Calls:	budgetExists(), budgetItemExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			budgetItemExists can return false if the budget does not exist, so
	//					both testing methods must be run
	public function addBudgetItem($name, $budget_date, $requested, $allocated, $description)
	{
		// check if budget exists
		if( budgetExists($budget_date) )
		{
		
			// check if budget_item exists, if no create the specified tuple
			if (! budgetItemExists($name, $budget_date) )
			{
				$query	=	"INSERT INTO budget_item (name, budget_date, requested, allocated, balance, description) VALUES ("
							."'"	.$name
							."', '"	.$budget_date
							."', "	.$requested
							.", "	.$allocated
							.", "	.$allocated			// starting balance = allocated amount
							.", '"	.$description."')";
							
				$results	=	db_connect::run_query( $query	)	or die("Error in query");
				mysqli_free_result($results);
			}
			else
			{
				echo "No changes were made to budget_item table. The budget item $name for $budget_date already exists.";
			}
		}
		else
		{
			echo "No changes were made to the budget_item table. The budget specified by $budget_date does not exist.";
		}
	}
	
	//	Name:			addDues()
	//	Input:			person ID, budget date, transaction date, amount, description
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		creates new transaction for member and adds dues tuple
	//	Function Calls:	pesonExists(), addTransaction(), findTransaction(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			
	public function addDues($pid, $budget_date, $trans_date, $amount, $description)
	{
		// Check if person exists
		if( db_access::personExists($pid) )
		{
			// Create transaction
			addTransaction("Membership Dues", $budget_date, $trans_date, $amount, $description, null);
			
			// Get transaction ID
			$trans_id	=	findTransaction("Membership Dues", $budget_date, $trans_date, $amount, $description, null);
			
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
	//	Function Calls:	budgetItemExists(), db_connect::run_query(), mysqli_free_result()
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
		if ( budgetItemExists($item_name, $budget_date) )
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
	//	Function Calls:	budgetExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			UPDATING THE BUDGET IS NOT RECOMMENDED AFTER BUDGET_ITEMS HAVE BEEN CREATED
	//					CARE MUST BE TAKEN TO ENSURE THE BALANCE IS CORRECT
	public function editBudget($start_date, $end_date, $total_requested, $total_allocated, $balance, $description)
	{
		// Check if budget exists
		if(	budgetExists($start_date) )
		{
			$query	=	"UPDATE budget SET "	
						." end_date = '"			.$end_date."'"
						." total_requested = "		.$total_requested
						." total_alloacted = "		.$total_allocated
						." balance = "				.$balance
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
	//	Function Calls:	budgetExists(), budgetItemExists(), db_connect::run_query(), mysqli_free_result()
	//	
	//	NOTES:			UPDATING THE BUDGET_ITEM IS NOT RECOMMENDED AFTER TRANSACTIONS HAVE BEEN CREATED
	//					CARE MUST BE TAKEN TO ENSURE THE BALANCE IS CORRECT
	public function editBudgetItem($name, $budget_date, $requested, $allocated, $balance, $description)
	{
		// Check if budget exists
		if ( budgetExists($budget_date) )
		{
			// Check if budget_item exists
			if ( budgetItemExits($name, $budget_date) )
			{
				// Update specified tuple
				$query	=	"UPDATE budget_item SET "
							.", requested = "		.$requested
							.", alloacted = "		.$allocated
							.", balance = "			.$balance
							.", description = '"	.$description."'"
							." WHERE name = "		.$name
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
	//	Function Calls:	db_access::personExists(), addTransaction(), findTransaction(), db_connect::run_query(), mysqli_num_rows(), mysqli_fetch_row(), mysqli_free_result()
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
				addTransaction(	$trans_row[1], $trans_row[2], $trans_date, (-1 * $trans_row[4]),
								"Updating dues entity. Negating old transaction to create a new one.", null );
				
				// Create new transaction with new_amount
				addTransaction(	"Membership Dues", $trans_row[2], $trans_date, $new_amount, $new_description, null );
				
				// Find new transaction id
				$new_trans_id	=	findTransaction( "Membership Dues", $trans_row[2], $trans_date, $new_amount, $new_description, null );
				
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
		$query		=	"SELECT * FROM budget WHERE start_date = '$date'";
		$results	=	db_connect::run_query(	$query	) or die("Error in query");
		$value		=	mysqli_num_rows($results);
		mysqli_free_result($results);
		return ($value > 0);
	}
	
	//	Name:			budgetItemExists()
	//	Input:			name, budget start date
	//	Output:			true/false,	echo errors
	//	Errors:			query error
	//	Actions:		checks if budget and budgetItem exist
	//	Function Calls:	budgetExists(), db_connect::run_query(), mysqli_num_rows(), mysqli_free_result()
	//	
	//	NOTES:			can return false if budget doesn't exist
	public function budgetItemExists($name, $start_date)
	{
		// check if budget exists
		if ( budgetExists($start_date) )
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
	

} // end of class


?>
