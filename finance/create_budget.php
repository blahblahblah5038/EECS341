<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Create Budget</title>
<h1 align="center">Create A New Budget</h1>
<script language="javascript" src="../calendar/calendar.js"></script>
<?PHP 
include("../phpincludes/db_finance.php");
include_once("../phpincludes/db_connect.php");
//include_once("../header.php");
	?>
</head>

<body>
<?PHP

// make connection to database
//	$dbc	=	mysqli_connect("localhost", "root", "Svetskar97") or die("Could not connect");
//	mysqli_select_db( $dbc, "archeryclub") or die("Could not select database");
	
	$financeObj1			=	new db_finance();
	$SCPC_form_fields		=	array();
	$USG_form_fields		=	array();
	
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
                require_once("../calendar/classes/tc_calendar.php");
                $myCalendar	=	new	tc_calendar("startDate", TRUE);
                $myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
                $myCalendar	->	setDate(date('j'), date('n'), date('Y'));
                $myCalendar	->	writeScript();
            ?>
            </td><td>Start Date</td></tr>
            <tr><td>
            <?PHP
                require_once("../calendar/classes/tc_calendar.php");
                $myCalendar	=	new	tc_calendar("endDate", TRUE);
                $myCalendar	->	setIcon("../calendar/images/iconCalendar.gif");
                $myCalendar	->	setDate(date('j'), date('n'), date('Y') + 1);
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
        	<td>&nbsp;</td>
            <td></td>
        </tr>
        <tr>
        	<td></td>
            <td><input type="submit" value="SUBMIT" name="SubmitBudgetItems">
            	<input type="hidden" value="<?PHP echo $_REQUEST['startDate']	?>" name="startDate">
                <input type="hidden" value="<?PHP echo $_REQUEST['endDate']		?>" name="endDate"></form></td>
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
		$formLength		=	array();
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
				$financeObj1->lineItemFields($_POST['ChosenSCPC'][$i], $namesSCPC, $defValueSCPC, $formLength[$i]);
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
		echo	"<input type=\"hidden\" value=\"".$_REQUEST['startDate']."\" name=\"startDate\">"
                ."<input type=\"hidden\" value=\"".$_REQUEST['endDate']."\" name=\"endDate\"></form>";
	
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
		$length			=	array();
		$i				=	0;
		$j				=	0;

		// Retrieve SCPC form data
		while(! empty($_REQUEST["SCPC-$i-$j"])	)
		{
			while(	(! empty($_REQUEST["SCPC-$i-$j"]))	|| ($j < 4)	)
			{
				$SCPC_form_fields[$i][$j]	=	$_REQUEST["SCPC-$i-$j"];
				$j++;
			}
			$length[0][$i]	=	$j;		// length[0] is SCPC; length[1] is USG;	[0][1] is from SCPC form 1
			$j = 0;
			$i++;
		}

		$i	=	0;
		$j	=	0;
		
		// Retrieve USG form data
		while(! empty($_REQUEST["USG-$i-$j"])	)
		{
			while(	(! empty($_REQUEST["USG-$i-$j"]))	|| ($j < 4)	)
			{
				$USG_form_fields[$i][$j]	=	$_REQUEST["USG-$i-$j"];
				$j++;
			}
			$length[1][$i]	=	$j;		// length[0] is SCPC; length[1] is USG;	[0][1] is form SCPC form 1
			$j = 0;
			$i++;
		}

		// Create table
		$summaryTable		 =	"<form method=\"post\" action=".$_SERVER['PHP_SELF']."><table align=\"center\" bordercolor=\"#000000\" border=\"4\" cellspacing=\"3\" cellpadding=\"4\">";
		$summaryTable		.=	"<tr><td>Item Name</td><td>Amount Requested</td><td>Amount Allocated</td><td>Balance</td></tr>";
		if( count($length[0]) > 0 )
			$summaryTable		.=	"<tr><th colspan=\"4\">Sport Club Items</th></tr>";
		for( $i = 0; $i < count($length[0]); $i++)
		{
			$summaryTable	.=	"<tr><td>".$SCPC_form_fields[$i][0]."</td><td align=\"center\">".$SCPC_form_fields[$i][1]."</td><td align=\"center\">".$SCPC_form_fields[$i][2]."</td><td align=\"center\">".$SCPC_form_fields[$i][3]."</td></tr>";
		}
		if( count($length[1]) > 0 )
			$summaryTable		.=	"<tr><th colspan=\"4\">USG Items</th></tr>";	
		for( $i = 0; $i < count($length[1]); $i++)
		{
			$summaryTable	.=	"<tr><td>".$USG_form_fields[$i][0]."</td><td align=\"center\">".$USG_form_fields[$i][1]."</td><td align=\"center\">".$USG_form_fields[$i][2]."</td><td align=\"center\">".$USG_form_fields[$i][3]."</td></tr>";
		}
		
	
		// Print Page and provide option for submission
		echo	"<div><h3 align=\"center\">Please review the summary below.</h3><p align=\"center\">If there are any errors, please go back and fix them before clicking Submit</p></div>";
		echo	"<div>$summaryTable</table></div>";
		for( $y = 0; $y < count($length[0]); $y++)
		{
			for( $z = 0; $z < $length[0][$y]; $z++)
				{
					echo	"<input type=\"hidden\" name=\"hidden-SCPC-".$y."-".$z."\" 	value=\"".$SCPC_form_fields[$y][$z]."\">";
				}
		}
		for( $y = 0; $y < count($length[1]); $y++)
		{
			for( $z = 0; $z < $length[1][$y]; $z++)
				{
					echo	"<input type=\"hidden\" name=\"hidden-USG-".$y."-".$z."\" 	value=\"".$USG_form_fields[$y][$z]."\">";
				}
		}		
		echo	"<div align=\"center\"><br /><input type=\"SUBMIT\" name=\"CreateFinalBudget\" value=\"SUBMIT\"></div>";
		echo	"<input type=\"hidden\" value=\"".$_REQUEST['startDate']."\" name=\"startDate\">"
                ."<input type=\"hidden\" value=\"".$_REQUEST['endDate']."\" name=\"endDate\"></form>";
		
	 }
	 
	 /*************************************************************************
	 *
	 *		After submitting CompletedForms, show summary page
	 *		
	 *
	 *************************************************************************/
	 
	 if( (isset($_REQUEST['CreateFinalBudget']))	)
	 {
		echo	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
				."<input type=\"button\" onClick=\"location.href='../finance_main.php'\" name=\"ReturnToFinanceMain\" value=\"Return to Main Finance Page\"></form><br />";
		
		// Retrieve results from forms
		$SCPC_fields	=	array(); 
		$USG_fields		=	array();
		$length			=	array();
		$i				=	0;
		$j				=	0;

		// Retrieve SCPC form data
		while(! empty($_REQUEST["hidden-SCPC-$i-$j"])	)
		{
			while(	(! empty($_REQUEST["hidden-SCPC-$i-$j"]))	|| ($j < 4)	)
			{
				$SCPC_form_fields[$i][$j]	=	$_REQUEST["hidden-SCPC-$i-$j"];
				$j++;
			}
			$length[0][$i]	=	$j;		// length[0] is SCPC; length[1] is USG;	[0][1] is from SCPC form 1
			$j = 0;
			$i++;
		}

		$i	=	0;
		$j	=	0;
		
		// Retrieve USG form data
		while(! empty($_REQUEST["hidden-USG-$i-$j"])	)
		{
			while(	(! empty($_REQUEST["hidden-USG-$i-$j"]))	|| ($j < 4)	)
			{
				$USG_form_fields[$i][$j]	=	$_REQUEST["hidden-USG-$i-$j"];
				$j++;
			}
			$length[1][$i]	=	$j;		// length[0] is SCPC; length[1] is USG;	[0][1] is form SCPC form 1
			$j = 0;
			$i++;
		}
		
		$start_date1		=	$_REQUEST['startDate'];
		$end_date1			=	$_REQUEST['endDate'];
		$total_requested	=	0;
		$total_allocated 	= 	0;
		$balance			=	0;
		$description		=	"";		
		
		//	Calculate values for the budget's creation
		for($i = 0; $i < count($length[0]); $i++)
		{
			$total_requested	+=	$SCPC_form_fields[$i][1];
			$total_allocated	+=	$SCPC_form_fields[$i][2];
			$balance			+=	$SCPC_form_fields[$i][3];
		}

		if( $total_requested != 0 )
			$description	.=	"SPORT CLUB, ";
		else
			$description	.=	"USG, ";
		
		for($i = 0; $i < count($length[1]); $i++)
		{
			$total_requested	+=	$USG_form_fields[$i][1];
			$total_allocated	+=	$USG_form_fields[$i][2];
			$balance			+=	$USG_form_fields[$i][3];
		}

		$description		.=	"CREATED, ".date('Y-m-d');

		//	Insert new budget tuple into BUDGET table
		$financeObj1->addBudget($start_date1, $end_date1, $total_requested, $total_allocated, $description);
		
		//	Insert new budget item tuples into BUDGET_ITEM table
		for($i = 0; $i < count($length[0]); $i++)
		{
			$descriptionItem	=	"";
			
			// Concatenate all extra form info in description
			for($j = 4; $j < $length[0][$i]; $j++)
			{
				if( $j != 4 )
					$descriptionItem	.=	", ";
				$descriptionItem	.=	$SCPC_form_fields[$i][$j];
			}
			$financeObj1->addBudgetItem($SCPC_form_fields[$i][0], $start_date1, $SCPC_form_fields[$i][1], $SCPC_form_fields[$i][2], $SCPC_form_fields[$i][3], $descriptionItem);
		}
			
		for($i = 0; $i < count($length[1]); $i++)
		{
			$descriptionItem	=	"";
			
			// Concatenate all extra form info in description
			for($j = 4; $j < $length[1][$i]; $j++)
			{
				if( $j != 4 )
					$descriptionItem	.=	", ";
				$descriptionItem	.=	$USG_form_fields[$i][$j];
			}
			$financeObj1->addBudgetItem($USG_form_fields[$i][0], $start_date1, $USG_form_fields[$i][1], $USG_form_fields[$i][2], $USG_form_fields[$i][3], $descriptionItem);
		}

		//	Validate the budget to make sure it is correct
		$financeObj1->validateBudget($start_date1);
		
		
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