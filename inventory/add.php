<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");
	include("../phpincludes/db_members.php");
?>

<script language="JavaScript" src="formview.js"></script>

<h2>Inventory: Add Item</h2>

<?php
$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid) && !db_access::isEquipmentManager($userpid)) {
	echo "<div class='error'>Sorry, you are not authorized to view this page.</div>";
} else {
	if (isset($_POST['submit'])) { // form submitted
		// get data from form
		$type = $_POST['type'];
		$serialno = $_POST['serialnum'];
		$brand = $_POST['brand'];
		$owner = $_POST['owner'];
		
		//TODO: validate
		switch ($type) {
			case "S":
				$length = $_POST['length'];
				db_equipment::addStabilizer($type, $serialno, $brand, $owner, $length);
				break;
			case "L":
				$interface = $_POST['interface'];
				$distmarks = $_POST['distmarks'];
				$drawstr = $_POST['drawstrength'];
				db_equipment::addLimb($type, $serialno, $brand, $owner, $interface, $distmarks, $drawstr);
				break;
			case "A":
				$model = $_POST['model'];
				$carr = $_POST['completearrow'];
				$bareshaft = $_POST['bareshaft'];
				$fixable = $_POST['fixable'];
				$notes = $_POST['notes'];
				db_equipment::addArrow($type, $serialno, $brand, $owner, $model, $carr, $bareshaft, $fixable, $notes);
				break;
			case "R":
				$interface = $_POST['interface'];
				$height = $_POST['height'];
				$distmarks = $_POST['distmarks'];
				$handedness = $_POST['handedness'];
				$bft = $_POST['buttonformat'];
				$button = $_POST['button'];
				$arrowrest = $_POST['arrowrest'];
				db_equipment::addRiser($type, $serialno, $brand, $owner, $interface, $height, $distmarks, $handedness, $bft, $button, $arrowrest);
				break;
			default:
				db_equipment::addEquipment($type, $serialno, $brand, $owner);
				break;
		}
		echo "<p>Equipment successfully added.<br /><a href='/add.php'>Add another item</a><br /><a href='/inventory/'>Back to inventory</a></p>";
	} else { // display form
		echo <<<HERE
		<form action="add.php" method="POST">
		<table>
		<tr><td>Type</td><td>
			<select name="type" onChange="swapDisplay(this.options[this.selectedIndex].value)">
				<option value="" selected="selected">---</option>
				<option value="S">Stabilizer</option>
				<option value="L">Limb</option>
				<option value="A">Arrow</option>
				<option value="R">Riser</option>
			</select>
		</td></tr>
		<tr><td>Serial No.</td><td><input type="text" name="serialnum" /></td></tr>
		<tr><td>Brand</td><td><input type="text" name="brand" /></td></tr>
		<tr><td>Owner</td><td>
			<select name="owner">
HERE;
		// print list of members for options
		$memberlist = getMembers(NULL);
		$row = mysqli_fetch_row($memberlist);
		while ($row)	// display netid, submit pid
		{
			$row = mysqli_fetch_row($memberlist);
			echo "<option value='".$row[1]."'>".$row[2]."</option>";
		}
		
		echo <<<HERE
			</select>
		</td></tr>
		</table>
		<div id="stabilizerform" style="display:none">
			<h4>Stabilizer</h4>
			<table>
				<tr><td>Length</td><td><input type="text" name="length" /></td></tr>
			</table>
		</div>
		<div id="limbform" style="display:none">
			<h4>Limb</h4>
			<table>
				<tr><td>Interface</td><td><input type="text" name="interface" /></td></tr>
				<tr><td>Distinguishing Marks</td><td><input type="text" name="distmarks" /></td></tr>
				<tr><td>Draw Length</td><td><input type="text" name="drawstrength" /></td></tr>
			</table>
		</div>
		<div id="arrowform" style="display:none">
			<h4>Arrow</h4>
			<table>
				<tr><td>Model</td><td><input type="text" name="Model" /></td></tr>
				<tr><td>Complete Arrow</td><td><input type="text" name="completearrow" /></td></tr>
				<tr><td>Bare Shaft</td><td><input type="text" name="bareshaft" /></td></tr>
				<tr><td>Fixable</td><td><input type="text" name="fixable" /></td></tr>
				<tr><td>Notes</td><td><input type="text" name="notes" /></td></tr>
			</table>
		</div>
		<div id="riserform" style="display:none">
			<h4>Riser</h4>
			<table>
				<tr><td>Interface</td><td><input type="text" name="interface" /></td></tr>
				<tr><td>Height</td><td><input type="text" name="height" /></td></tr>
				<tr><td>Distinguishing Marks</td><td><input type="text" name="distmarks" /></td></tr>
				<tr><td>Handedness</td><td>
					<input type="radio" name="handedness" value="RIGHT" />Right<br />
					<input type="radio" name="handedness" value="LEFT" />Left
				</td></tr>
				<tr><td>Button Format</td><td>
					<input type="radio" name="buttonformat" value="1" />1<br />
					<input type="radio" name="buttonformat" value="2" />2
				</td></tr>
				<tr><td>Button</td><td><input type="text" name="button" /></td></tr>
				<tr><td>Arrow Rest</td><td><input type="text" name="arrowrest" /></td></tr>
			</table>
		</div>
		<input type="submit" name="submit" value="Add" />
		</form>
HERE;
	}
}
?>
<?php include("../phpincludes/footer.php"); ?>
