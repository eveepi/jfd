<?php
	checkRights(50,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'aerzte_verwalten';
    echo "<h1>&Auml;rzte Verwalten</h1>";
	
	?>
	<script>
		function deleteDentist(id){
			if (confirm("Wollen Sie diesen Arzt wirklich löschen?")){
				window.location = '<?php echo "index.php?content=".$self."&p=".md5('delete')."&a_id="; ?>' + id;
			}
			return 0;
		}
	</script>
	
	<?php
	
    if($page==md5('verwalten')) {	
				$_GET['b'] = isset($_GET['b']) ? $_GET['b'] : '';
		echo '<a href="index.php?content=aerzte_verwalten">Alle</a>&nbsp; ';

    	for($i=65;$i <= 90;$i++){
				echo "<a href=\"index.php?content=aerzte_verwalten&b=". chr($i) ."\"";
				if (chr($i) == $_GET['b']) { echo "class=\"selected\" "; }
				echo ">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}

		echo "<br /><br />";
		echo '<a class="button" href="index.php?content=aerzte_verwalten&p='.md5("anlegen").'">Neuen Arzt anlegen</a>';
		echo "<br /><br />";
	
		if(isset($_GET['b']) AND strlen($_GET['b'])==1){
			$sql = "SELECT * FROM `$tbl_aerzte[tbl]` WHERE `$tbl_aerzte[name]` LIKE '".$_GET['b']."%' AND $tbl_aerzte[id] != 0 AND `$tbl_aerzte[schul_id]` = $_SESSION[schul_id] ORDER BY `$tbl_aerzte[name]`;";
			}
			else{
			$sql = "SELECT * FROM `$tbl_aerzte[tbl]` WHERE `$tbl_aerzte[id]` != 0 AND `$tbl_aerzte[schul_id]` = $_SESSION[schul_id] ORDER BY `$tbl_aerzte[name]`;";
			}
        $result = mysql_query($sql);
        
        if($row = mysql_fetch_assoc($result)) {
        echo "<table  cellpadding='5' cellspacing='0'>
				<tr>
					<th>Name</th>
					<th>Telefonnummer</th>
					<th>Strasse</th>
					<th> </th>
					<th> </th>
				</tr>";
			
		$i = 0;
		do{
			$i = ($i - 1) * ($i - 1);
			echo "<tr class=\"row".$i."\">";
			echo "  <td>".$row[$tbl_aerzte['name']]."</td>";
			echo "  <td>".$row[$tbl_aerzte['telefon']]."</td>";
			echo "  <td>".$row[$tbl_aerzte['strasse']]."</td>";
			echo "  <td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&a_id=".$row[$tbl_aerzte['id']]."\">
			<img src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
			</a> </td>";
			echo "  <td><a style='cursor:pointer;' onclick='deleteDentist(\"".$row[$tbl_aerzte['id']]."\")'>
			<img src=\"".$icon_path."cross_48.png\" height=\"28\" width=\"28\" alt=\"l&ouml;schen\">
			</a> </td>";
			echo "</tr>";
		} while($row = mysql_fetch_assoc($result));
        echo "</table>";
        } else {
			echo "Es sind keine Eintr&auml;ge vorhanden.";
        }

} elseif($page==md5('bearbeiten')) { 
        $sql = "SELECT * FROM `$tbl_aerzte[tbl]` 
				WHERE `$tbl_aerzte[id]` = '".$_GET['a_id']."' ;
                ";
        $result=mysql_query($sql);		
        $row = mysql_fetch_assoc($result);
?>
	<h2>Arzt bearbeiten</h2>
		<fieldset>
			<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>" method="post">	
				<label for="ae_name" >Name:</label>
				<input maxlength="50" type="text" name="ae_name" id="ae_name" value="<?php echo $row[$tbl_aerzte['name']];?>"/>
				<br /> 
				
				<label for="ae_telefon" >Telefon:</label>
				<input maxlength="50" type="text" name="ae_telefon" id="ae_telefon" value="<?php echo $row[$tbl_aerzte['telefon']];?>"/>
				<br /> 
				
				<label for="ae_strasse" >Strasse / Nr.:</label>
				<input maxlength="50" type="text" name="ae_strasse" id="ae_strasse" value="<?php echo $row[$tbl_aerzte['strasse']];?>"/>
				<br /> 
				
				<?php echo getPlzForm("s_plz","ae_plz", $row[$tbl_aerzte['plz']]); ?><br />
				
				<input maxlength="5" type="hidden" name="ae_id" id="ae_id" size="5" value="<?php echo $row[$tbl_aerzte['id']];?>"/>
				
				<input type="submit" value="&Auml;ndern" /> 
			</form>
		</fieldset>	
<?php	
} elseif($page==md5('delete')) { 
        $sql = "Delete FROM `$tbl_aerzte[tbl]` 
				WHERE `$tbl_aerzte[id]` = '".$_GET['a_id']."'
				AND `$tbl_aerzte[schul_id]` = $_SESSION[schul_id]
				LIMIT 1;";
        $result=mysql_query($sql);		
        
        $sql_class = new Sql;
		$sql_class->insertLog($tbl_aerzte['tbl'],$_GET['a_id'],$sql);

		echo "Arzt gel&ouml;scht.";

} elseif($page==md5('eintragen')) {				
		$sql = "UPDATE `$tbl_aerzte[tbl]` 
				SET 
				`$tbl_aerzte[name]` = '$_POST[ae_name]', 
				`$tbl_aerzte[strasse]` = '$_POST[ae_strasse]',
				`$tbl_aerzte[plz]` = '$_POST[ae_plz]',
				`$tbl_aerzte[telefon]` = '$_POST[ae_telefon]'
			   	WHERE 
				$tbl_aerzte[id] = '$_POST[ae_id]';";

		$result = mysql_query($sql);
		$sql_class = new Sql;
		$sql_class->insertLog($tbl_aerzte['tbl'],$_POST['ae_id'],$sql);
		
		echo "<h2>Arzt ge&auml;ndert</h2>";
}elseif($page==md5("anlegen")){
	echo '	<div id="arztForm">
			<input type="hidden" id="schul_id" value="'.$_SESSION['schul_id'].'" />
			<label>Name:</label><input type="text" value="" id="a_name" name="a_name"/><br/>
			<label>Telefon:</label><input type="text" value="" id="a_telefon" name="a_telefon"/><br/>
			<label>Strasse:</label><input type="text" value="" id="a_strasse" name="a_strasse"/><br/>
			<label for="plz">PLZ / Ort:</label><input type="text" onchange="insert_ort(\'plz\',\'a_ort\',0)" value="" size="5" id="plz" name="plz" maxlength="5"/>
			<span id="a_ort">
				<select class="a_ort" name="a_ort" id="a_ort">
					<option value="">Bitte geben Sie eine Plz ein!</option>
				</select>
			</span>
			<br/><br/>
			<label>&nbsp;</label><input type="button" class="button" onclick="submit_dentist();" id="addDentistButton" value="Arzt Anlegen" name="a_save"/>
		</div>
	';
	?>
	<script>
	function submit_dentist(){
		saveDen();
		$("#arztForm").toggle();
		$(".arztSubmit").toggle();
	}
	</script>
	<div class="arztSubmit" style="display:none;">
		<span>Arzt wurde gespeichert.</span>
	</div>
	<?php
}
?>
