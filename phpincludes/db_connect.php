<?php
include_once("hidden/db_login_info.php");
class db_connect
{
    // Functions to connect to database
        
    // Connect to database
    function conn($server, $user, $pwd)
    {
    	if(!isset($GLOBALS['dbc']))
    	{
    		$GLOBALS['dbc'] = @mysqli_connect($dbhost, $dbuser, $dbpass)
    			OR die ("<p>Could not connect to database</p>");
		
    		@mysqli_select_db($GLOBALS['dbc'], $dbname)
    			OR die ("<p>Could not select the database</p>");
    	}
    }

    // Run query and return result set
    function run_query($querystr)
    {
    	// todo: sanitize?
    	$query = @mysqli_query($GLOBALS['dbc'], $query)
    		OR die("<p>Error processing query</p>");
    	return $query;
    }
}
?>
