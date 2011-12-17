<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Budget Summary</title>
<h1 align="center">Budget Summary</h1>
<script language="javascript" src="../calendar/calendar.js"></script>
<?PHP include("../phpincludes/financeMethods.php");	?>
</head>

<body>

<?PHP

// make connection to database
	$dbc	=	mysqli_connect("localhost", "root", "Svetskar97") or die("Could not connect");
	mysqli_select_db( $dbc, "archeryclub") or die("Could not select database");
	/************************************************************************
	 *																		*
	 *			ADDITIONS:													*
	 *					-Transaction history along bottom					*
	 *																		*
	 *																		*
	 *																		*
	 ************************************************************************/

?>
	<?PHP
	//		Code for DatePicker
	
	if(! isset($_REQUEST['submitDate']))
	{
	?>
    <table cellpadding="5" cellspacing="5">
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
            <tr><td>
            <input type="submit" name="submitDate" value="Submit">
        </form>
        </td></tr>
    </table>
    <?PHP
	}
	
	if(	isset($_REQUEST['submitDate']))
	{
		$value	=	$_POST['startDate'];
		$query	=	"SELECT B.* FROM budget A, budget_item B WHERE '"
					.$value ."' BETWEEN A.start_date AND A.end_date"
					." AND A.start_date = B.budget_date";
/*********************************************************************/		
		$result	=	mysqli_query($dbc, $query);
/*********************************************************************/		
		// Header for table
		$output	=	"<div><table align=\"center\" bordercolor=\"#000000\" border=\"4\" cellspacing=\"3\" cellpadding=\"4\">";
		$output	.=	"<tr>"
						."<th>Item Name</th>"
						."<th>Requested</th>"
						."<th>Allocated</th>"
						."<th>Balance</th>"
						."<th>Item Description</th>"
					."</tr>";

		$oldDate;
		$newDate;
		
		if( mysqli_num_rows($result) == 0)
		{		
			echo "nothing";
		}
		else
		{
			while(	$row = mysqli_fetch_row($result)	)
			{
				$newDate	=	$row[1];
				
				// If a new budget comes up create a new header
				if( $oldDate != $newDate )
				{
					$output	.=	"<tr><th colspan=\"5\">Budget Start Date: &nbsp; $newDate</th></tr>";
				}
				$output	.=	"<tr>";
				for( $i = 0; $i < 6; $i++)
				{
					if( $i != 1 )
					{
						if( $i > 1 )
							$output	.=	"<td align=\"center\">".$row[$i]."</td>";
						else
							$output	.=	"<td>".$row[$i]."</td>";
					}
				}
				$output	.=	"</tr>";
				$oldDate	=	$newDate;
			}
			$output	.=	"</table></div>";
			mysqli_free_result($result);
			echo	$output;
		}
	?>
		
	<?PHP
	}
	?>
</body>
</html>