<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Create Budget</title>
<h1 align="center">Create A New Budget</h1>
<script language="javascript" src="calendar.js"></script>
<?PHP 
include("db_finance.php");
include_once("../db_connect.php");
	?>
</head>

<body>
<?PHP

// make connection to database
//	$dbc	=	mysqli_connect("localhost", "root", "Svetskar97") or die("Could not connect");
//	mysqli_select_db( $dbc, "archeryclub") or die("Could not select database");
	
	$financeObj1	=	new db_finance();
	
	/************************************************************************
	 *																		*
	 *	ADD CODE HERE:	DatePicker Style to select dates					*
	 *		-Add listbox to move items left-right							*
	 *																		*
	 *																		*
	 *																		*
	 ************************************************************************/

?>

	<?PHP
	/*************************************************************************
	 *
	 *		Code for DatePicker, select the start and end dates
	 *
	 *************************************************************************/

	if( (! isset($_REQUEST['submitDate'])) && (! isset($_REQUEST['SubmitBudgetItems'])) && (! isset($_REQUEST['CompletedForms'])) && (! isset($_REQUEST['CreateFinalBudget'])) )
	{
	?>
    <div>
	<h2>Please select the start and end dates for the new budget</h2>
	</div>
	<table cellpadding="5" cellspacing="5">
        <form method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
           <tr><td> 
			<?PHP
                require_once("classes/tc_calendar.php");
                $myCalendar	=	new	tc_calendar("startDate", TRUE);
                $myCalendar	->	setIcon("images/iconCalendar.gif");
                $myCalendar	->	setDate(11, 1, 2011);
                $myCalendar	->	writeScript();
            ?>
            </td><td>Start Date</td></tr>
            <tr><td>
            <?PHP
                require_once("classes/tc_calendar.php");
                $myCalendar	=	new	tc_calendar("endDate", TRUE);
                $myCalendar	->	setIcon("images/iconCalendar.gif");
                $myCalendar	->	setDate(11, 1, 2011);
                $myCalendar	->	writeScript();
            ?>
            </td><td>End Date</td></tr>
            <tr><td>
            <input type="submit" name="submitDate" value="Submit">
            </td></tr>

        </form>
        
     </table>
    <?PHP
	}
	
	/*************************************************************************
	 *
	 *		After selecting start/end dates, check to make sure that the
	 *		budget does not already exist
	 *
	 *************************************************************************/
	
	if(	isset($_POST['submitDate']))
	{
		$query	=	"SELECT * FROM budget WHERE start_date = '".$_REQUEST['startDate']."'";
		$result	=	db_connect::run_query($query);
		
		if( mysqli_num_rows($result) > 0 )
		{
			echo	"A budget already exists with the specified start date. Please try again with a different start date.";
		}
		
		else
		{
	/*************************************************************************
	 *
	 *		Listbox format to select budget items for new SCPC or USG budget
	 *		Preferred method: create EITHER SCPC or USG budget not both simultaneous
	 *
	 *************************************************************************/
			
	?>
    <h3 align="center">Select all desired lines items for the new budget.<br /></h3>
    <p align="center">Make sure all items in the right box are highlighted before clicking SUBMIT</p>
    
    <table align="center">
    	<tr>
        	<th valign="middle">Standard Sport Club Budget</th>
            <td></td>
        </tr>
        <tr><form method="POST" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
        	<td>
        		<select id="SCPC" name="SCPC[]" size="15" multiple="multiple">
                	<optgroup label="Expenses">
               			<option value="SC_E_office">	Office Expenses</option>
          				<option value="SC_E_trans">		Transportation</option>
                   		<option value="SC_E_room">		Room/Lodging</option>
          				<option value="SC_E_fees">		Entry Fees</option>
      					<option value="SC_E_equip">		Equipment</option>
     					<option	value="SC_E_facil">		Facilities</option>
     					<option value="SC_E_dues">		Affiliation Dues</option>
     					<option value="SC_E_officials">	Officials</option>
     					<option value="SC_E_clinic">	Workshops, Clinics, etc.</option>
     					<option value="SC_E_misc">		Miscellaneous</option>
     					<option value="SC_E_mmp">		Expense of Money Making Projects</option>
                 	</optgroup>
                    <optgroup label="Income">
                    	<option value="SC_I_dues">		Membership Dues</option>
                        <option value="SC_I_donate">	Donations</option>
                        <option value="SC_I_fundraise">	Fundraising</option>
                        <option value="SC_I_other">		Other</option>
                        <option value="SC_I_grant">		SCPC Grant</option>
                    </optgroup>
				</select>
            </td>
            <td align="center">
            	<input type="button" value="ADD" onClick="listbox_moveacross('SCPC', 'ChosenSCPC')">
                <br /><br />
                <input type="button" value="REMOVE" onClick="listbox_moveacross('ChosenSCPC', 'SCPC')">
          	</td>
            
            <td>
            	<select id="ChosenSCPC" name="ChosenSCPC[]" size="15" multiple="multiple">
                	<optgroup label="All SCPC Budget Items">
                    </optgroup>
                </select>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<th valign="middle">Standard USG budget</th>
            <td></td>
        </tr>
        <tr>
        	<td>
        		<select id="USG" name="USG[]" size="15" multiple="multiple">
                	<optgroup label="Semester Budget">
               			<option value="USG_auto">	Automatic</option>
          				<option value="USG_bsafe">	Beginner Safety Equipment</option>
                   		<option value="USG_equip">	Equipment</option>
          				<option value="USG_fees">	Instructor Fees</option>
      					<option value="USG_renov">	Renovation Materials</option>
     					<option	value="USG_USCA">	USCA Dues</option>
                  	</optgroup>
                    <optgroup label="Caskey">
	     				<option value="USG_Caskey_tar">		Targets for Caskey</option>
    	 				<option value="USG_Caskey_off">		Officials for Caskey</option>
     					<option value="USG_Caskey_chair">	Chairs for Caskey</option>
     				</optgroup>
                    <optgroup label="States">
	            		<option value="USG_State_tar">		Targets for States</option>
    	 				<option value="USG_State_off">		Officials for States</option>
     					<option value="USG_State_chair">	Chairs for States</option>
                  	</optgroup>
				</select>
            </td>
            <td align="center">
            	<input type="button" value="ADD" onClick="listbox_moveacross('USG', 'ChosenUSG')">
                <br /><br />
                <input type="button" value="REMOVE" onClick="listbox_moveacross('ChosenUSG', 'USG')">
          	</td>
            
            <td>
            	<select id="ChosenUSG" name="ChosenUSG[]" size="15" multiple="multiple">
                	<optgroup label="All USG Budget Items">
                    </optgroup>
                </select>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td>Create new line item</td>
            <td></td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="submit" value="SUBMIT" name="SubmitBudgetItems"></form></td>
            <td></td>
        </tr>
    </table>
    
    <?PHP
		}
	}
	
	/*************************************************************************
	 *
	 *		After clicking submit, create table of forms for each line item
	 *		TODO:
	 *			-Change Headers to represent Line Item Names
	 *
	 *************************************************************************/
	
	// If submit from SC budget
	if( isset($_REQUEST['SubmitBudgetItems']))
	{
		$i = 0;
		echo	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\"><table align=\"center\" cellpadding=\"25\" border=\"5\" bordercolordark=\"#000000\">";
				
		while( ($_POST['ChosenSCPC'][$i] ) || ($_POST['ChosenUSG'][$i]))
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
			if($_POST['ChosenSCPC'][$i] )
			{
				$financeObj1->lineItemFields($_POST['ChosenSCPC'][$i], $namesSCPC, $defValueSCPC);
				if(! empty($namesSCPC) )
					echo	"<tr><td>".$financeObj1->echoForm1($namesSCPC, $defValueSCPC, "SCPC-$i", "submit3")."</td>";
				else
					echo	"<tr><td>No fields found.</td>";	
			}
			else
				echo	"<tr><td>No fields found.</td>";	
				
			// Print the Forms for USG items on right side
			if($_POST['ChosenUSG'][$i])
			{
				$financeObj1->lineItemFields($_POST['ChosenUSG'][$i], $namesUSG, $defValueUSG);
				if(! empty($namesUSG) )
					echo	"<td>".$financeObj1->echoForm1($namesUSG, $defValueUSG, "USG-$i", "submit3")."</td></tr>";
				else
					echo	"<td>No fields found.</td></tr>";	
			}
			else
					echo	"<td>No fields found.</td></tr>";
			
			$i++;
		}
		echo	"<tr><td align=\"center\" colspan = \"2\"><input type=\"submit\" name=\"CompletedForms\" value=\"Submit\"></td></tr></table>";
	
	}
	
	/*************************************************************************
	 *
	 *		After submitting CompletedForms, show summary page
	 *		
	 *
	 *************************************************************************/
	 if( isset($_REQUEST['CompletedForms']))
	 {
		// Retrieve results from forms
		$SCPC_fields	=	array(); 
		$USG_fields		=	array();
		$SCPC_length	=	0;
		$USG_length		=	0;
		$totIncome		=	0;
		$totExpenses	=	0;
		$i				=	0;
		$k				=	0;

		while(! empty($_POST["SCPC-$i-0"]) )
		{
			$SCPC_fields[$k]	=	$_POST["SCPC-$i-0"];
			$SCPC_fields[++$k]	=	$_POST["SCPC-$i-1"];
			$i++;
			$k++;
		}
		
		// Store length and reset counters
		$SCPC_length	=	$k;
		$i				=	0;
		$k				=	0;
		
		while(! empty($_POST["USG-$i-0"]) )
		{
			$USG_fields[$k]		=	$_POST["USG-$i-0"];
			$USG_fields[++$k]	=	$_POST["USG-$i-1"];
			$i++;
			$k++;
		}
		$USG_length		=	$k;
		
		// Create table
		$summaryTable		 =	"<form method=\"post\" action=".$_SERVER['PHP_SELF']."><div><table align=\"center\" bordercolor=\"#000000\" border=\"4\" cellspacing=\"3\" cellpadding=\"4\">";
		$summaryTable		.=	"<tr><td>Item Name</td><td>Amount Requested</td></tr>";
		$summaryTable		.=	"<tr><th colspan=\"2\">Sport Club Items</th></tr>";
		for( $i = 0; $i < $SCPC_length; $i++)
		{
			$summaryTable	.=	"<tr><td>".$SCPC_fields[$i]."</td><td>".$SCPC_fields[++$i]."</td></tr>";
		}
		$summaryTable		.=	"<tr><th colspan=\"2\">USG Items</th></tr>";
		for( $i = 0; $i < $USG_length; $i++)
		{
			$summaryTable	.=	"<tr><td>".$USG_fields[$i]."</td><td>".$USG_fields[++$i]."</td></tr>";
		}
		
		$summaryTable		.=	"<tr><th>Total Income</th><th>Total Expenses</th></tr>"
								."<tr><td>$totIncome</td><td>$totExpenses</td></tr>";
		
		// Print Page and provide option for submission
		echo	"<div><h3 align=\"center\">Please review the summary below.</h3><p align=\"center\">If there are any errors, please go back and fix them before clicking Submit</p></div>";
		echo	"<div>$summaryTable</table></div>";
		echo	"<div align=\"center\"><br /><input type=\"SUBMIT\" name=\"CreateFinalBudget\" value=\"SUBMIT\"></div>";
		
	 }
	 
	 /*************************************************************************
	 *
	 *		After submitting CompletedForms, show summary page
	 *		
	 *
	 *************************************************************************/
	 
	 if( (isset($_REQUEST['CreateFinalBudget']))	)
	 {
		echo	"This form can be used but still needs tweaking.<br /> None of the data has been entered into the database yet.<br /> I'm having trouble retrieving the data.";
		echo	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
				."<input type=\"button\" onClick=\"location.href='../finance_main.php'\" name=\"ReturnToFinanceMain\" value=\"Return to Main Finance Page\"></form>";
				
		/*************************************************************/
		$start_date1			=	"2011-02-27";
		$end_date1			=	"2011-12-31";
		$total_requested	=	1234.56;
		$total_allocated 	= 	0;
		$balance			=	0;
		$description		=	"SPORT CLUB, CREATED, 2011-12-11, 1234567";
		
		$name				=	"Item_name";
		$budget_date		=	$start_date1;
		$requested			=	$total_requested;
		$allocated			=	0;
		$balanceItem		=	$allocated;
		$descriptionItem	=	"concatenate all form fields";
		
		$financeObj1->addBudget($start_date1, $end_date1, $total_requested, $total_allocated, $description);
		//while(! empty($items) )
		{
			$financeObj1->addBudgetItem($name, $budget_date, $requested, $allocated, $descriptionItem);
		}
		$financeObj1->validateBudget($start_date1);
/*	//	db_connect::conn();
		$query	=	"INSERT INTO budget (start_date, end_date, total_requested, total_allocated, balance, description) VALUES ("
						."'"	.$start_date1
						."', '"	.$end_date1
						."', "	.$total_requested
						.", "	.$total_allocated
						.", "	.$total_allocated	// starting balance at creation = total allocation
						.", '"	.$description."')";
						
//		mysqli_query($dbc, $query ) or die(mysqli_error($dbc));
//		db_connect::run_query($query);
//		echo "True or False: <br />";
//	echo $financeObj1->budgetExists($start_date1);
	*/
		
	 }
	?>
    <script>
		function listbox_moveacross(sourceID, destID)
		{
    		var src = document.getElementById(sourceID);
    		var dest = document.getElementById(destID);
 
		    for(var count=0; count < src.options.length; count++)
			{
 
        		if(src.options[count].selected == true) 
				{
                	var option = src.options[count];
	 
					var newOption = document.createElement("option");
					newOption.value = option.value;
					newOption.text = option.text;
					newOption.selected = true;
					try
					{
                         dest.add(newOption, null); //Standard
                         src.remove(count, null);
                 	}catch(error)
					{
                         dest.add(newOption); // IE only
                         src.remove(count);
                 	}
                	count--;
		        }
		    }
		} // end of listbox_moveacross
	</script>
</body>
</html>