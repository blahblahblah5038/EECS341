<?PHP
include_once ('db_connect.php');
class db_access
{
	
	/************************************************************************
	 *																		*
	 *		Public functions to check status of user						*
	 *																		*
	 *		NOTE:	uses SQL_TIMESTAMP instead of a date (UNTESTED)			*
	 *																		*
	 ************************************************************************/
	
   	//	Name:			personExists()
	//	Input:			person ID
	//	Output:			true/false, echo errors
	//	Actions:		checks if person exists 
	//	Function Calls:	personExists(), SQL_TIMESTAMP(), wasOfficer()
	//
	//	NOTES:			uses SQL_TIMESTAMP instead of date
	public function personExists($pid)
	{
	    // Run query
		$query	=	"SELECT * FROM contact WHERE pid = '$pid'";
		$result	=	db_connect::run_query($query) or die("Error in query");
		
		// Set output to TRUE if any tuples are returned, free memory, and return output
		$output	=	FALSE;
		if( mysqli_num_rows($result) > 0 )
			$output	=	TRUE;
			
		mysqli_free_result($result);
		return	$output;

    }
 
	//	Name:			isAdmin()
	//	Input:			person ID
	//	Output:			true/false, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		checks if person is a current officer
	//	Function Calls:	personExists(), SQL_TIMESTAMP(), wasOfficer()
	//
	//	NOTES:			uses SQL_TIMESTAMP instead of date
	public function isAdmin($pid)
	{
		// Check if person exists
		if(! personExists($pid) )
		{
			echo "The person specified by PID $pid does not exist.";
			return	FALSE;
		}
		
		//	Call wasOfficer with PID, and current date to see if is a current officer
		return	(	wasOfficer($pid, null, SQL_TIMESTAMP)	);		
	}


	//	Name:			isBudgetAdmin()
	//	Input:			person ID
	//	Output:			true/false, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		checks if person is the current treasurer
	//	Function Calls:	personExists(), SQL_TIMESTAMP(), getPositionID(), wasOfficer()					
	//
	//	NOTES:			uses SQL_TIMESTAMP instead of date
	public function isBudgetAdmin()
	{
		// Check if person exists
		if(! personExists($pid) )
		{
			echo "The person specified by PID $pid does not exist.";
			return	FALSE;
		}
		
		//	Call getPositionID to return pos_id
		//	Call wasOfficer with PID, pos_id, and current date to see if is current treasurer
		return	(	wasOfficer($pid, getPositionID("Treasurer"), SQL_TIMESTAMP)	);	
	}
	
	//	Name:			isEquipmentManager()
	//	Input:			person ID
	//	Output:			true/false, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		checks if person is the current equipment manager
	//	Function Calls:	personExists(), SQL_TIMESTAMP(), getPositionID(), wasOfficer()				
	//
	//	NOTES:			uses SQL_TIMESTAMP instead of date
	public function isEquipmentManager($pid)
	{
		// Check if person exists
		if(! personExists($pid) )
		{
			echo "The person specified by PID $pid does not exist.";
			return	FALSE;
		}
		
		//	Call getPositionID to return pos_id
		//	Call wasOfficer with PID, pos_id, and current date to see if is current equipment manager
		return	(	wasOfficer($pid, getPositionID("Equipment Manager"), SQL_TIMESTAMP)	);	
	}
	
	//	Name:			isMember()
	//	Input:			person ID
	//	Output:			true/false, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		checks if person is a current member
	//	Function Calls:	personExists(), run_query(), mysqli_num_rows(), mysqli_free_result()			
	//
	//	NOTES:			
	public function isMember($pid)
	{
		// Check if person exists
		if(!db_access::personExists($pid) )
		{
			echo "The person specified by PID $pid does not exist.";
			return	FALSE;
		}
		
		// Run query
		$query	=	"SELECT * FROM member WHERE pid = '$pid'";
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		
		// Set output to TRUE if any tuples are returned, free memory, and return output
		$output	=	FALSE;
		if( mysqli_num_rows($result) > 0 )
			$output	=	TRUE;
			
		mysqli_free_result($result);
		return	$output;
	}
	
	/************************************************************************
	 *																		*
	 *							Private functions							*
	 *																		*
	 *		NOTE:															*
	 *																		*
	 ************************************************************************/
	
	//	Name:			getPositionID()
	//	Input:			Title
	//	Output:			position ID, echo errors
	//	Errors:			query error, title does not exist
	//	Actions:		finds position ID number given a title
	//	Function Calls:	run_query(), mysqli_num_rows(), mysqli_free_result()			
	//
	//	NOTES:			Returns -1 if position does not exist
	private function getPositionID($title)
	{
		$query	=	"SELECT pos_id FROM officer_positions WHERE title = '$title'";
		$result	=	run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");

		// If no results are found, return -1
		$output	=	-1;
		
		// If one or more results are found, return the first one
		if( mysqli_num_rows($result) > 0 )
		{
			$row	=	mysqli_fetch_row($result);
			$output	=	$row[0];
		}
		
		// Free memory and return value
		mysqli_free_result($result);
		return	$output;
	}
	
	//	Name:			wasOfficer()
	//	Input:			person ID, position ID, date
	//	Output:			true/false, echo errors
	//	Errors:			query error
	//	Actions:		Depends on input
	//						null,	null,	null:		always FALSE
	//						Number,	null,	null:		TRUE = Person has held an office
	//						null,	Number,	null:		TRUE = Position was occupied at some time
	//						null,	null,	Date:		TRUE = There were occupied positions on the specified date
	//						Number,	Number,	null:		TRUE = Person has held Position at some time
	//						Number,	null,	Date:		TRUE = Person held an office on the specified date
	//						null,	Number,	Date:		TRUE = Position was occupied on the specified date
	//						Number,	Number,	Date:		TRUE = Person held the Position on the specified date
	//	Function Calls:	run_query(), mysqli_num_rows(), mysqli_free_result()				
	//
	//	NOTES:			assumes all erros on input are checked prior to calling function
	//					not all terms need values, null values are ignored
	private function wasOfficer($pid, $pos_id, $date)
	{
		// If all 3 terms are null, print an error and return FALSE
		if( ($pid == null) && ($pos_id == null) && ($date == null) )
		{
			echo "Your input is flawed. Take your failure elsewhere as it will not be tolerated here.";
			return FALSE;
		}
		
		//	Specify query to include part of / all of input terms
		$query	=	"SELECT * FROM officer_history WHERE ";
		
		if(	$pid != null )
			$query	.=	"pid = '"	.$pid	."' AND ";
		
		if(	$pos_id != null)
			$query	.=	"pos_id = '"	.$pos_id	."' AND ";
		
		if( $date != null)
			$query	.=	$date." BETWEEN start_date AND end_date AND";
		
		$query	.= "TRUE";
			
		// Run query, free memory, and return TRUE if any results are found		
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		$output	=	FALSE;
		if( mysqli_num_rows($result) > 0 )
			$output	=	TRUE;
		
		mysqli_free_result($result);
		return	$output;
	}
	
	// Return PID of a person, given a Case Network ID (like abc123)
	public function getPidFromCaseId($caseid)
	{
		// use like: $pid = db_access::getPidFromCaseId(phpCAS::getUser())
		$querystr = "SELECT pid FROM member WHERE netid='".$caseid."'";
		$result = db_connect::run_query($GLOBALS['dbc'], $query) or die("Error finding PID");
		if (mysqli_num_rows($result) == 0)
			return '';
		$row = mysqli_fetch_row($result);
		return $row[0];
	}
	
}	// end of class

?>
