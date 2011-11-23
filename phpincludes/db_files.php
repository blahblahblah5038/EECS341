<?PHP
include_once('phpincludes/db_access.php');
class db_files
{

	/************************************************************************
	 *																		*
	 *		Public functions to add, update, or deleter files				*
	 *																		*
	 *		NOTE:															*
	 *																		*
	 ************************************************************************/


	//	Name:			addFile()
	//	Input:			person ID, filename, description, data
	//	Output:			file ID, echo errors
	//	Errors:			query error, person does not exist
	//	Actions:		adds file to file_uploads table
	//	Function Calls:	db_access::personExists(), db_connect::run_query(), mysqli_free_result(), findFID(), addFileOwner()
	//					
	//	NOTES:			Returns -1 if no file is uploaded
	public function addFile($pid, $file_name, $description, $data)
	{
		// Check that person exists
		// If pid is invalid, print error message and return -1
		if(! db_access::personExists($pid) )
		{
			echo "No files were uploaded. The person specified by pid $pid does not exist.";
			return -1;
		}
		
		// Insert tuple into file_uploads table then free memory
		$query	=	"INSERT INTO file_uploads (filename, description, data) VALUES ("
					.", '"	.$file_name
					."', '"	.$description
					."', "	.$data.")";
					
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or	die("Error in query");
		mysqli_free_result($result);
		
		// Find FID value, add owner, and return FID
		$fileID	=	findFID($pid, $file_name, $description);
		addFileOwner($fileID, $pid);
		return ( $fileID );
	}
	
	//	Name:			findFID()
	//	Input:			person ID, filename, description
	//	Output:			file ID, echo errors
	//	Errors:			query error, person does not exist, no FID found, multiple FID found
	//	Actions:		returns file ID
	//	Function Calls:	db_access::personExists(), db_connect::run_query(), mysqli_num_rows(), mysqli_fetch_row() mysqli_free_result()
	//					
	//	NOTES:			Returns -1 if no file is uploaded
	//					Description is optional
	public function findFID($pid, $file_name, $description)
	{
		if(! db_access::personExists($pid) )
			return -1;
		
		// Pose query, description is optional
		$query	=	"SELECT fid FROM file_uploads WHERE pid = $pid AND filename = '$file_name'";
		
		if($description != null)
			$query	.=	" AND description = '$description'";
				
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		
		// If no results are return, print error message, free memory, and return -1
		if( mysqli_num_rows($result) == 0 )
		{
			echo "No FID value was found for the given PID, file_name, and description.";
			mysqli_free_result($result);
			return -1;
		}
	
		// Fetch the first row of the result, free memory, and return FID	
		else if( mysqli_num_rows($result) > 1 )
			echo "Input values for PID, file_name, and description returned multiple files. Only the first will be returned.";
	
		$row	=	mysqli_fetch_row($result);
		$value	=	$row[0];
		mysqli_free_result($result);
		return $value;
	}
	
	//	Name:			findFileOwners()
	//	Input:			file ID
	//	Output:			handle for file owners (rows of PIDs)
	//	Errors:			query error
	//	Actions:		returns table of file owners
	//	Function Calls:	db_connect::run_query()
	//					
	//	NOTES:			no error checking
	public function findFileOwners($fid)
	{
		$query	=	"SELECT pid FROM file_permissions WHERE fid = $fid";
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		return	$result;
	}


	//	Name:			deleteFile()
	//	Input:			file ID, deleter PID
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, deleter or file does not exist
	//	Actions:		adds file FID to trashbin for deletion
	//	Function Calls:	db_connect::run_query()
	//					
	//	NOTES:			
	public function deleteFile($fid, $pid)
	{
		// Check if both person and file exists, if not print error, and return
		if(!	(( db_access::personExists($pid) )	&&	( fileExists($fid) ) ))
		{
			echo "The file was not moved or deleted. Either the person ($pid) or file ($fid) does not exist.";
			return;
		}
		
		// Run query
		$query	=	"INSERT INTO trashbin (fid, pid, date_added) VALUES ("
					.$fid.", ".$pid.", CURRENT_TIMESTAMP)";
		
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
	}
	
	//	Name:			addFileOwner()
	//	Input:			file ID, new owner PID
	//	Output:			nothing returned, echo errors
	//	Errors:			query error, new owner or file does not exist
	//	Actions:		adds owner for a file
	//	Function Calls:	db_access::personExists(), fileExists(), db_connect::run_query(), mysqli_free_result()
	//					
	//	NOTES:			
	public function addFileOwner($fid, $pid)
	{
		// Check if both person and file exists, if not print error, and return
		if(!	(( db_access::personExists($pid) )	&&	( fileExists($fid) ) ))
		{
			echo "No file owner was added. Either the person ($pid) or file ($fid) does not exist.";
			return;
		}
		
		// Run query, free memory
		$query	=	"INSERT INTO file_permissions (fid, pid) VALUES ( $fid, $pid )";
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		mysqli_free_result($result);
	}

	//	Name:			fileExists()
	//	Input:			file ID
	//	Output:			TRUE/FALSE, echo errors
	//	Errors:			query error
	//	Actions:		checks for file
	//	Function Calls:	db_connect::run_query(), mysqli_num_rows mysqli_free_result()
	//					
	//	NOTES:			
	public function fileExists($fid)
	{
		// Run query
		$query	=	"SELECT * FROM file_uploads WHERE fid = $fid";
		$result	=	db_connect::run_query(	$GLOBALS['dbc'],	$query	)	or die("Error in query");
		$output	=	FALSE;
		
		// if any tuples are returned, return TRUE;
		if(	mysqli_num_rows($result) > 0 )
			$output	=	TRUE;
			
		// Free memory and return result
		mysqli_free_result($result);
		return	$output;
	}

} // end of class
?>
