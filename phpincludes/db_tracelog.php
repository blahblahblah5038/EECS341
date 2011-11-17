<?PHP
class db_tracelog
{

	/************************************************************************
	 *																		*
	 *		Public functions to add tuples to or return from in tracelog	*
	 *																		*
	 *		NOTE:	There are no deletion or edit methods					*
	 *																		*
	 ************************************************************************/

	//	Name:			tracelog()
	//	Input:			person ID, action string
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		adds record to tracelog with timestamp
	//	Function Calls:	timestamp(), run_query()
	//					
	//	NOTES:			CALLS timestamp(): NEED TO SPECIFY WHICH BUILT-IN COMMAND TO USE
	public function tracelog($pid, $action)
	{
		// Check if person exists
		if(	personExists($pid)	)
		{
			$timestamp	=	timestamp();
			$query		=	"INSERT INTO tracelog(pid, action, timestamp) VALUES ("
							.$pid		.", '"
							.$action	."', '"
							.$timestamp	."')";
						
			$result		=	run_query(	$GLOBALS['dbd'],	$query	)	or die("Error in query");
		}
		else
		{
			echo "Record was not added to the tracelog. The person specified by $pid does not exist.";
		}
	}
	
	//	Name:			viewTracelog()
	//	Input:			start date, end date
	//	Output:			handle for result tuples, echo errors
	//	Errors:			query error, no tuples returned
	//	Actions:		returns tuples from tracelog added between specified dates
	//	Function Calls:	run_query(), mysqli_num_rows(), mysqli_free_result()
	//					
	//	NOTES:			returns FALSE if no tuples are found
	public function viewTracelog($start_date, $end_date)
	{
		$query	=	"SELECT * FROM tracelog WHERE timestamp BETWEEN '"
					.$start_date	."' AND '"
					.$end_date		."'";
					
		$result	=	run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		
		// If no tuples are found, print error message, free memory, and return FALSE
		if( mysqli_num_rows($result) == 0 )
		{
			echo "No tracelog events were found during the specified dates.";
			mysqli_free_result($result);
			return FALSE;
		}
		
		// Else return tuples
		return	$result;		
	}

	//	Name:			viewTracelogByUser()
	//	Input:			person ID, start date, end date
	//	Output:			handle for result tuples, echo errors
	//	Errors:			query error, person does not exist, no tuples returned
	//	Actions:		returns tuples from tracelog added between specified dates by user PID
	//	Function Calls:	personExists(), run_query(), mysqli_num_rows(), mysqli_free_result()
	//					
	//	NOTES:			returns FALSE if no tuples are found or person does not exist
	public function viewTraceLogByUser($pid, $start_date, $end_date)
	{
		// Check if person exists
		if( personExists($pid) )
		{
			$query	=	"SELECT * FROM tracelog WHERE "
						."pid = "				.$pid		." AND "
						."timestamp BETWEEN '"	.$start_date
						."' AND '"				.$end_date."'";
			
			$result	=	run_query(	$GLOBALS['dbd'],	$query	)	or die("Error in query");
			
			// If no tuples are found, print error message, free memory, and return false
			if( mysqli_num_rows($result) == 0 )
			{
				echo "No tracelog events were entered by user $pid between $start_date and $end_date.";
				mysqli_free_result($result);
				return	FALSE;
			}
			
			// Else return tuples
			return	$result;
		}
		else
		{
			echo "Could not complete search. The person indicated by PID $pid does not exist.";
			return	FALSE;
		}
	}
	
	//	Name:			viewTracelogByAction()
	//	Input:			action, start date, end date
	//	Output:			handle for result tuples, echo errors
	//	Errors:			query error, no tuples returned
	//	Actions:		returns tuples from tracelog added between specified dates with specific action tag
	//	Function Calls:	run_query(), mysqli_num_rows(), mysqli_free_result()
	//					
	//	NOTES:				
	public function viewTracelogByAction($action, $start_date, $end_date)
	{
		// Query database for all tracelog events between specified dates
		$query	=	"SELECT * FROM tracelog WHERE timestamp BETWEEN '"
					.$start_date."' AND '".$end_date."' AND "
					."action LIKE '$action%";
					
		$result	=	run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		
		// Check if query returned any results, if not print error message, clear memory, return FALSE
		if(	mysqli_num_rows($result) == 0 )
		{
			echo "No tracelog events found between $start_date and $end_date.";
			mysqli_free_result($result);
			return	FALSE;
		}
		
		return $result;
	}

} // end of class
?>