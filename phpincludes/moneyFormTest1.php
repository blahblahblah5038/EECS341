<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Financial Form Test1</title>
</head>

<body>

<?PHP

	/************************************************************************
	 *																		*
	 *					NOTES AND GENERAL COMMENTS							*
	 *																		*
	 *		-Partial functionality achieved: displays forms, updates, etc.	*
	 *																		*
	 *		-Need to add a calendar function allow selection of start		*
	 *			and end dates for the budget and check if they are valid	*
	 *			an example of what I want is at the following site			*
	 *			under the header DatePicker style							*
	 *			http://www.triconsole.com/php/calendar_datepicker.php		*
	 *																		*
	 *		-Desired format:												*
	 *			-ask for budget start and end dates (+validation)			*
	 *			-remove remove dates from page, show drop down list for		*
	 *				budget items (pulled from the database)					*
	 *			-add one type of form at a time	 (without losing prev form	*
	 *			-add multiple copies of a single form(beneath existing form)*
	 *			-submission button at bottom of the page					*
	 *			-validate fields to make sure the key fields are completed	*
	 *			-show confirmation page before executing request			*
	 *				ASK FOR CONFIRMATION OR MAKE CHANGES					*
	 *			-after confirmed, make changes to the database				*
	 *																		*
	 ************************************************************************/









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
		{
			case "Affiliation Dues":
				$names		=	array(0 => "Name", "Cost");
				$defValue	=	array(0 => "Default Name", "\$100");
				break;
			case "Donations":
				$names		=	array(0 => "Anticipated Donations");
				$defValue	=	array(0 => "Default Name");
				break;
			case "Entry Fees":
				$names		=	array(0 => "Where", "When", "Number of Teams", "Cost per Team", "Event Title", "Total Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Number", "Default Cost per Team", "Default Title", "Default Total Cost");
				break;
			case "Equipment":
				$names		=	array(0 => "Quantity", "Item", "Unit Price", "Total Cost");
				$defValue	=	array(0 => "Default Quantity", "Default Item", "Default Unit Price", "Default Total Cost");
				break;
			case "Expense of Money Making Projects":
				$names		=	array(0 => "Date", "Event", "Total Cost");
				$defValue	=	array(0 => "Default Date", "Default Event", "Default Total Cost");
				break;
			case "Facilities":
				$names		=	array(0 => "Location", "Date", "Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Cost");
				break;
			case "Fundraising":
				$names		=	array(0 => "Date", "Event", "Income");
				$defValue	=	array(0 => "Default Date", "Default Event", "Default Income");
				break;
			case "Membership Dues":
				$names		=	array(0 => "Number of Members(expected)", "Dues", "Total");
				$defValue	=	array(0 => "Default Number", "Default Amount", "Default Total");
				break;
			case "Miscellaneous":
				$names		=	array(0 => "Item", "Cost");
				$defValue	=	array(0 => "Default Item", "Default Cost");
				break;
			case "Office Expenses":
				$names		=	array(0 => "Item", "Cost");
				$defValue	=	array(0 => "Default Item", "Default Cost");
				break;
			case "Officials":
				$names		=	array(0 => "Date", "Number of Officials", "Cost per Official", "Total Cost");
				$defValue	=	array(0 => "Default Date", "Default Number", "Default Cost", "Default Cost");
				break;
			case "Other Income":
				$names		=	array(0 => "Item", "Amount");
				$defValue	=	array(0 => "Default Item", "Default Amount");
				break;
			case "Room/Lodging":
				$names		=	array(0 => "Where", "When", "Number of Days", "Number of People", "Number of Nights", "Total Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Number", "Default Number", "Default Number", "Default Cost");
				break;
			case "Transportation":
				$names		=	array(0 => "Where", "When", "Number of People", "Number of Vehicles", "Mileage", "Total Cost");
				$defValue	=	array(0 => "Default Location", "Default Date", "Default Number", "Default Number", "Default Mileage", "Default Cost");
				break;
			case "Workshops, Clinics, etc.":
				$names		=	array(0 => "Date", "Name", "Cost");
				$defValue	=	array(0 => "Default Date", "Default Name", "Default Cost");
				break;
			default:
				echo	"The selected line item ($line_item) does not have a form yet.";
				break;
		}
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
		$formStatement	=	"<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">"
								."<table>";
		
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
										."<td><input type=\"submit\" name=\"".$submitName."\" value=\"Submit\"></td>"
									."</tr>"
								."</table>"
							."</form>";
							
		return $formStatement;
	}
}
?>
<?PHP
	// make connection to database
	$dbc	=	mysqli_connect("localhost", "root", "Svetskar97") or die("Could not connect");
	mysqli_select_db( $dbc, "archeryclub") or die("Could not select database");
	
	/************************************************************************
	 *																		*
	 *	ADD CODE HERE:	DatePicker Style to select dates					*
	 *		-calendar														*
	 *		-check value to make sure it is not already used in db			*
	 *		-submit date and show drop down for budget_items (next section)	*
	 *																		*
	 ************************************************************************/
?>



	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    	<select name="name1">
        	<?PHP
				$query	=	"SELECT name FROM budget_item WHERE budget_date = '2011-01-01'";
				$result	=	mysqli_query($dbc, $query);
				
				while(	$row = mysqli_fetch_row($result)	)
				{
					echo "<option value=\""	.$row[0]	."\">"	.$row[0]	."</option>";
				}
			?>
        </select>
        <input type="submit" name="submitBItem1" value="Select line item">
  	</form>
 
<?php
	
	//	If 
	if (isset($_POST['submitBItem1']))
	{
		$names		=	array();
		$defValue	=	array();
		$formObj1	=	new	formMethods();
		$formObj1->lineItemFields($_POST['name1'], $names, $defValue);
		if(! empty($names) )
			echo $formObj1->echoForm1($names, $defValue, "Fnames", "submit2");
		else
			echo "No fields found.";
	}
	
	?>
    
<?PHP
	
	if( isset($_POST['submit2']) )
	{
		$fieldNames	=	array();
		$i	=	0;
		while(! empty($_POST["Fnames_$i"]) )
		{
			$fieldNames[$i]	=	$_POST["Fnames_$i"];
			$i++;
		}
		
		for($i = 0; $i < count($fieldNames); $i++)
		{
			echo "Number ".($i + 1)." of ".count($fieldNames).": ".$fieldNames[$i]."<br />";
		}
	}
	
?>
</body>
</html>