<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Finance Main Page</title>
<h1 align="center">Finance Main Page</h1>
</head>

<body>

	<table>
    	<form method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
		<tr>
        	<td><input type="button" onClick="location.href='finance/create_budget.php'" name="CreateBudget" value="Create New Budget"></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td><input type="button" onClick="location.href='finance/revise_budget.php'" name="ReviseBudget" value="Revise Budget"></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td><input type="button" onClick="location.href='finance/add_view_transactions.php'" name="Transactions" value="Add/View Transaction"></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td><input type="button" onClick="location.href='finance/budget_summary.php'" name="BudgetSummary" value="Budget Summary"></td>
        </tr>
        </form>
	</table>
    

</body>
</html>