<?PHP
	//	Finance Functions
class formMethods
{
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
	//	NOTES:
	public function lineItemFields($lineItem, &$names, &$defValue)
	{
		switch( $lineItem )
		{	/******************************************************
			 *
			 * 				SCPC Form Fields
			 *
			 *****************************************************/
			case "Affiliation Dues":
			case "SC_E_dues":
				$names		=	array(0 => "Name", "Cost");
				$defValue	=	array(0 => "Default Name", "\$100");
				break;
			case "Donations":
			case "SC_I_donate":
				$names		=	array(0 => "Anticipated Donations");
				$defValue	=	array(0 => "Default Name");
				break;
			case "Entry Fees":
			case "SC_E_fees":
				$names		=	array(0 => "Where", "When", "Number of Teams", "Cost per Team", "Event Title", "Total Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Number", "Default Cost per Team", "Default Title", "Default Total Cost");
				break;
			case "Equipment":
			case "SC_E_equip":
				$names		=	array(0 => "Quantity", "Item", "Unit Price", "Total Cost");
				$defValue	=	array(0 => "Default Quantity", "Default Item", "Default Unit Price", "Default Total Cost");
				break;
			case "Expense of Money Making Projects":
			case "SC_E_mmp":
				$names		=	array(0 => "Date", "Event", "Total Cost");
				$defValue	=	array(0 => "Default Date", "Default Event", "Default Total Cost");
				break;
			case "Facilities":
			case "SC_E_facil":
				$names		=	array(0 => "Location", "Date", "Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Cost");
				break;
			case "Fundraising":
			case "SC_I_fundraise":
				$names		=	array(0 => "Date", "Event", "Income");
				$defValue	=	array(0 => "Default Date", "Default Event", "Default Income");
				break;
			case "Membership Dues":
			case "SC_I_dues":
				$names		=	array(0 => "Number of Members(expected)", "Dues", "Total");
				$defValue	=	array(0 => "Default Number", "Default Amount", "Default Total");
				break;
			case "Miscellaneous":
			case "SC_E_misc":
				$names		=	array(0 => "Item", "Cost");
				$defValue	=	array(0 => "Default Item", "Default Cost");
				break;
			case "Office Expenses":
			case "SC_E_office":
				$names		=	array(0 => "Item", "Cost");
				$defValue	=	array(0 => "Default Item", "Default Cost");
				break;
			case "Officials":
			case "SC_E_officials":
				$names		=	array(0 => "Date", "Number of Officials", "Cost per Official", "Total Cost");
				$defValue	=	array(0 => "Default Date", "Default Number", "Default Cost", "Default Cost");
				break;
			case "Other Income":
			case "SC_I_other":
				$names		=	array(0 => "Item", "Amount");
				$defValue	=	array(0 => "Default Item", "Default Amount");
				break;
			case "Room/Lodging":
			case "SC_E_room":
				$names		=	array(0 => "Where", "When", "Number of Days", "Number of People", "Number of Nights", "Total Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Number", "Default Number", "Default Number", "Default Cost");
				break;
			case "SCPC Grant":
			case "SC_I_grant":
				$names		=	array(0 =>	"Amount");
				$defValue	=	array(0 => 	"Default Amount");
				break;
			case "Transportation":
			case "SC_E_trans":
				$names		=	array(0 => "Where", "When", "Number of People", "Number of Vehicles", "Mileage", "Total Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Number", "Default Number", "Default Mileage", "Default Cost");
				break;
			case "Workshops, Clinics, etc.":
			case "SC_E_clinic":
				$names		=	array(0 => "Date", "Name", "Cost");
				$defValue	=	array(0 => "Default Date", "Default Name", "Default Cost");
				break;
			
			
			/******************************************************
			 *
			 * 				USF Form Fields
			 *
			 *****************************************************/			case "Automatic":
			case "USG_auto":
				$names		=	array(0 =>	"Amount");
				$defValue	=	array(0 =>	"50");
				break;
			case "Beginner Safety Equipment":
			case "USG_bsafe":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Beginner Safety Equipment", "Default Description", "Equipment", "1", "50.00", "50.00");
				break;
			case "USG Equipment":
			case "USG_equip":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Default Item", "Default Description", "Equipment", "1", "Default Unit Cost", "Default Total Cost");
				break;
			case "Instructor Fees":
			case "USG_fees":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Instructor Fees", "Professional Instruction for Advanced Archers", "Contracted Services", "2", "660.00", "1320.00");
				break;
			case "Renovation Materials":
			case "USG_renov":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Renovation Materials", "Default Description", "1", "Default Unit Cost", "Default Total Cost");
				break;
			case "USCA Dues":
			case "USG_USCA":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"USCA Dues", "Club Recognition by USCA", "Contracted Services", "1", "90.00", "90.00");
				break;
			case "Targets for Caskey":
			case "USG_Caskey_tar":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Targets", "New Targets: 40cm for Compound and recurve", "Equipment", "Default Quantity", "Default Unit Cost", "Default Total Cost");
				break;
			case "Officials for Caskey":
			case "USG_Caskey_off":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Officials", "Hire officials to judge Caskey Tournament", "Contracted Services", "2", "50.00", "100.00");
				break;
			case "Chairs for Caskey":
			case "USG_Caskey_chair":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Chair Rental", "60 chairs + moving fee", "Equipment", "1", "95.00", "95.00");
				break;
			case "Targets for States":
			case "USG_State_tar":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Targets", "New Targets: 40cm and 60cm for Compound and recurve", "Equipment", "Default Quantity", "Default Unit Cost", "Default Total Cost");
				break;
			case "Officials for States":
			case "USG_State_off":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Officials", "Officials to judge the tournament for two days (\$50 per day per official)", "2", "100.00", "200.00");
				break;
			case "Chairs for States":
			case "USG_State_chair":
				$names		=	array(0 =>	"Item", "Description", "Item Category", "Quantity", "Unit Price", "Item Request");
				$defValue	=	array(0 =>	"Chair Rental", "80 chairs + moving fee", "Equipment", "1", "110.00", "110.00");
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
	
	//	Name:			printBudgetItems()
	//	Input:			budget start date
	//	Output:			return nothing, echo errors
	//	Errors:			query error
	//	Actions:		prints the name, requested, allocated, and balance values
	//	Function Calls:	mysqli_query(), mysqli_fetch_row(), mysqli_free_result()
	//	
	//	NOTES:
	public function printBudgetItems($databaseConn, $startDate)
	{
		$query1	=	"SELECT * FROM budget_item WHERE budget_date = '2011-01-01'";//".$startDate."'";
		$result1	=	mysqli_query($databaseConn, $query1) or die("Query Error");
		
		//	If there are no results, print error and button to create a new budget item
		if( mysqli_num_rows($result1) == 0 )
		{
			echo "No budget_items were found for this budget.<br />You can add budget items to this budget. Press the button below.<br />";
			$output	=	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
							."<input type=\"submit\" name=\"createBudgetItem1\" value=\"Create Budget Items\"></form>";
			echo	$output;
			return;
		}
		
		$outputTable	=	"<table>"
								."<tr>"
									."<td>Name</td>"
									."<td>Requested</td>"
									."<td>Allocated</td>"
									."<td>Balance</td>"
								."</tr>";
								
		while(	$rows = mysqli_fetch_row($result1))
		{
			$outputTable	.=	"<tr>"
									."<td>$rows[0]</td>"
									."<td>$rows[2]</td>"
									."<td>$rows[3]</td>"
									."<td>$rows[4]</td>"
								."</tr>";
		}
		$outputTable	.=	"</table>";
		echo	$outputTable;
		mysqli_free_result($result1);
	}
	
		
	//	Name:			echoForm1()
	//	Input:			arry of field names,
	//					array of field default values
	//					reference name for submit button
	//	Output:			return statement to echo
	//	Errors:			none
	//	Actions:		creates a post form with a text field for each field name in $names
	//					reference name is $fieldName_$i for 0 <= $i < num fields in $names
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
										."<td><input type=\"text\" name=\"".$fieldName."_".$i."\"";
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
	
	//	Name:			()
	//	Input:			submitName, defaultDate
	//	Output:			return statement to echo
	//	Errors:			none
	//	Actions:		
	//	Function Calls:	none
	//	
	//	NOTES:	
		
	
}

?>