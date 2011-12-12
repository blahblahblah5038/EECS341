<?php
class db_connect
{
    // Functions to connect to database
        
    // Connect to database
    function conn()
    {
        require_once("../hidden/db_login_info.php");
    	if(!isset($GLOBALS['dbc']))
    	{
    		$GLOBALS['dbc'] = @mysqli_connect($dbhost, $dbuser, $dbpass)
    			OR die ("<p>1:Could not connect to database</p>");
		
    		@mysqli_select_db($GLOBALS['dbc'], $dbname)
    			OR die ("<p>2:Could not select the database</p>");
    	}
    }

    // Run query and return result set
    function run_query($querystr)
    {
    	// todo: sanitize?
    	$query = mysqli_query($GLOBALS['dbc'], $querystr)
    		OR die("<p>1 - Error processing query '".$querystr."':".mysqli_error($GLOBALS['dbc']));
    	return $query;
    }
}

db_connect::conn();
?>
