<?php
	include("../phpincludes/header.php");
	include("../phpincludes/login.php");
	include("../phpincludes/db_access.php");
	include("../phpincludes/db_equipment.php");
	include("../phpincludes/db_members.php");
?>

<script language="JavaScript" src="formview.js"></script>

<h2>Inventory: Edit Item</h2>

<?php
$userpid = db_access::getPidFromCaseId(phpCAS::getUser());
if (!db_access::isMember($userpid) && !db_access::isEquipmentManager($userpid)) {
	echo "<div class='error'>Sorry, you are not authorized to view this page.</div>";
} else {
	if (isset($_POST['eid']) && isset($_POST['type'])) {
		$eid = $_POST['eid'];
		$type = $_POST['type'];
		
		if (isset($_POST['submit'])) { // form submitted
			// get data from form
			$serialno = $_POST['serialnum'];
			$brand = $_POST['brand'];
			$owner = $_POST['owner'];
			
			//TODO: validate
			switch ($type) {
				case "S":
					$length = $_POST['length'];
					db_equipment::editStabilizer($type, $serialno, $brand, $owner, $length);
					break;
				case "L":
					$interface = $_POST['interface'];
					$distmarks = $_POST['distmarks'];
					$drawstr = $_POST['drawstrength'];
					db_equipment::editLimb($type, $serialno, $brand, $owner, $interface, $distmarks, $drawstr);
					break;
				case "A":
					$model = $_POST['model'];
					$carr = $_POST['completearrow'];
					$bareshaft = $_POST['bareshaft'];
					$fixable = $_POST['fixable'];
					$notes = $_POST['notes'];
					db_equipment::editArrow($type, $serialno, $brand, $owner, $model, $carr, $bareshaft, $fixable, $notes);
					break;
				case "R":
					$interface = $_POST['interface'];
					$height = $_POST['height'];
					$distmarks = $_POST['distmarks'];
					$handedness = $_POST['handedness'];
					$bft = $_POST['buttonformat'];
					$button = $_POST['button'];
					$arrowrest = $_POST['arrowrest'];
					db_equipment::editRiser($type, $serialno, $brand, $owner, $interface, $height, $distmarks, $handedness, $bft, $button, $arrowrest);
					break;
				default:
					db_equipment::editEquipment($type, $serialno, $brand, $owner);
					break;
			}
		} else { // display form
			
			// get equipment info here
			$info = viewEquipment($eid, $type);
			// eid, type, serialno, brand, owner
			// then additional fields depending on type: see details.php for list of headers
		
			echo '<form action="add.php" method="POST"><table>';
			echo '<input type="hidden" name="eid" value="'.$eid.'" />';
			
			// don't allow changing the type of an item
			echo '<tr><td>Type</td><td><input type="hidden" value="'.$type.'" />'.$type.'</td></tr>';
			
			echo '<tr><td>Serial No.</td><td><input type="text" name="serialnum" value="'.$info[2].'" /></td></tr>';
			echo '<tr><td>Brand</td><td><input type="text" name="brand" value="'.$info[3].'" /></td></tr>';
			echo '<tr><td>Owner</td><td><select name="owner">';

			// print list of members for options
			$memberlist = getMembers(NULL);
			$row = mysqli_fetch_row($memberlist);
			while ($row)	// display netid, submit pid
			{
				$row = mysqli_fetch_row($memberlist);
				echo "<option value='".$row[1]."'";
				if ($row[1] == $info[4])
					echo ' selected="selected" ';
				echo ">".$row[2]."</option>";
			}
			
			echo '</select></td></tr></table>';
			
			switch ($type)
			{
				case "S":
					echo '<div id="stabilizerform"><table>';
					echo '<tr><td>Length</td><td><input type="text" name="length" value="'.$info[5].'" /></td></tr>';
					echo '</table></div>';
				break;
				
				case "L":
					echo '<div id="limbform"><table>';
					echo '<tr><td>Interface</td><td><input type="text" name="interface" value="'.$info[5].'" /></td></tr>';
					echo '<tr><td>Distinguishing Marks</td><td><input type="text" name="distmarks" value="'.$info[6].'" /></td></tr>';
					echo '<tr><td>Draw Length</td><td><input type="text" name="drawstrength" value="'.$info[7].'" /></td></tr>';
					echo '</table></div>';
				break;
				
				case "A":
					echo '<div id="arrowform"><table>';
					echo '<tr><td>Model</td><td><input type="text" name="Model" value="'.$info[5].'" /></td></tr>';
					echo '<tr><td>Complete Arrow</td><td><input type="text" name="completearrow" value="'.$info[6].'" /></td></tr>';
					echo '<tr><td>Bare Shaft</td><td><input type="text" name="bareshaft" value="'.$info[7].'" /></td></tr>';
					echo '<tr><td>Fixable</td><td><input type="text" name="fixable" value="'.$info[8].'" /></td></tr>';
					echo '<tr><td>Notes</td><td><input type="text" name="notes" value="'.$info[9].'" /></td></tr>';
					echo '</table></div>';
				break;
				
				case "R":
					echo '<div id="riserform"><table>';
					echo '<tr><td>Interface</td><td><input type="text" name="interface" value="'.$info[5].'" /></td></tr>';
					echo '<tr><td>Height</td><td><input type="text" name="height" value="'.$info[6].'" /></td></tr>';
					echo '<tr><td>Distinguishing Marks</td><td><input type="text" name="distmarks" value="'.$info[7].'" /></td></tr>';
					echo '<tr><td>Handedness</td><td>';
						echo '<input type="radio" name="handedness" value="RIGHT" ';
						if ($info[8] == "RIGHT")
							echo 'selected="selected"';
						echo '/>Right<br /><input type="radio" name="handedness" value="LEFT" ';
						if ($info[8] == "LEFT")
							echo 'selected="selected"';
					echo '/>Left</td></tr><tr><td>Button Format</td><td>';
						echo '<input type="radio" name="buttonformat" value="1" ';
						if ($info[9] == 1)
							echo 'selected="selected"';
						echo '/>1<br /><input type="radio" name="buttonformat" value="2" ';
						if ($info[9] == 2)
							echo 'selected="selected"';
					echo '/>2</td></tr><tr><td>Button</td><td><input type="text" name="button" value="'.$info[10].'"/></td></tr>';
					echo '<tr><td>Arrow Rest</td><td><input type="text" name="arrowrest" value="'.$info[11].'" /></td></tr>';
					echo '</table></div>';
				break;
			}
			
			echo '<input type="submit" name="submit" value="Edit" /></form>';
		}
	} else { // no eid or type given
		echo "<p class='error'>Error: Missing EID or type<br /><a href='/inventory'>Back to Inventory</a></p>";
	}
}
?>
<?php include("../phpincludes/footer.php"); ?>
