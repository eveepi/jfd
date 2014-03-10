<?php
include('../../variablen.php');
include('../../config.php');

	$_POST['selected'] = (isset($_POST['selected']))? $_POST['selected'] : 0;

	echo "<select id='".$_POST['ort']."' name='".$_POST['ort']."' class=\"a_ort\" >";
	
	if ($_POST['ort'] != ""){
		$sql = "SELECT * FROM ".$tbl_orte['tbl']." 
				WHERE ".$tbl_orte['plz']." = ".$_POST['plz'].";";
		
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)){
			echo "<option value='".$row[$tbl_orte['id']]."'";
			if ($_POST['selected'] == $row[$tbl_orte['id']] ) { echo "selected='selected'"; }
			echo">".$row[$tbl_orte['ort']]."</option>";
		}
	}else {
		echo "<option value=''>Bitte geben Sie eine Plz ein!</option>";
	}	
	echo "</select>";
?>