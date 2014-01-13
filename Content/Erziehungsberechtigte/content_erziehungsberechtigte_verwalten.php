<?php
	checkRights(50,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'erziehungsberechtigte_verwalten';
    echo "<h1>Erziehungsberechtigte Verwalten</h1>";
	
	if(!isset($_GET['back']))
		$_GET['back'] = "";
	
    if($page==md5('verwalten')) {
    $_GET['b'] = isset($_GET['b']) ? $_GET['b'] : '';	
    echo "<a class=\"button\" href=\"index.php?content=erziehungsberechtigte_neu\">Neue Erziehungsberechtigte anlegen</a><br /><br />";

	echo "	<a href=\"index.php?content=".$self."\">Alle</a>&nbsp; ";

    	for($i=65;$i <= 90;$i++){
				echo "<a href=\"index.php?content=".$self."&b=". chr($i) ."\"";
				if (chr($i) == $_GET['b']) { echo "class=\"selected\" "; }
				echo ">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}
		echo "<br /><br />";

        


		if(isset($_GET['b']) AND strlen($_GET['b'])==1){
			$sql = "SELECT `$tbl_erziehungsberechtigte[id]`, `$tbl_erziehungsberechtigte[name]`, `$tbl_erziehungsberechtigte[vorname]`
					FROM `$tbl_erziehungsberechtigte[tbl]`
					WHERE `$tbl_erziehungsberechtigte[name]` LIKE '".$_GET['b']."%'
					AND `$tbl_erziehungsberechtigte[schul_id]` = $_SESSION[schul_id]
					ORDER BY `$tbl_erziehungsberechtigte[name]`;";
			}
			else{
			$sql = "SELECT `$tbl_erziehungsberechtigte[id]`, `$tbl_erziehungsberechtigte[name]`, `$tbl_erziehungsberechtigte[vorname]`  
					FROM `$tbl_erziehungsberechtigte[tbl]` 
					WHERE `$tbl_erziehungsberechtigte[schul_id]` = $_SESSION[schul_id]
					ORDER BY `$tbl_erziehungsberechtigte[name]`;";
			}
        $result = mysql_query($sql);
        
        if ($row = mysql_fetch_assoc($result)){
			echo "
				<table>
					<tr>
						<th>ID</th>
						<th>Vorname</th>
						<th>Nachname</th>
						<th> </th>
						<th> </th>
					</tr>
					";
				$i = 1;
				echo "<tr class=\"row".$i."\">";	
                echo "  <td>".$row[$tbl_erziehungsberechtigte['id']]."</td>";
                echo "  <td>".$row[$tbl_erziehungsberechtigte['vorname']]."</td>";
                echo "  <td>".$row[$tbl_erziehungsberechtigte['name']]."</td>";
                echo "  <td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_erziehungsberechtigte['id']]."\">
                <img src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
                </a> </td>";
                echo "</tr>";       
                 
			while($row = mysql_fetch_assoc($result)) {
				$i = ($i - 1) * ($i - 1);
				echo "<tr class=\"row".$i."\">";
                echo "  <td>".$row[$tbl_erziehungsberechtigte['id']]."</td>";
                echo "  <td>".$row[$tbl_erziehungsberechtigte['vorname']]."</td>";
                echo "  <td>".$row[$tbl_erziehungsberechtigte['name']]."</td>";
                echo "  <td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_erziehungsberechtigte['id']]."\">
                <img src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
                </a> </td>";
                echo "</tr>";

			}
			echo "</table>";
        } else {
			echo "Es sind keine Eintr&auml;ge vorhanden.";
		}

} elseif($page==md5('bearbeiten')) { 
        $sql = "SELECT *
                FROM `$tbl_erziehungsberechtigte[tbl]`
                WHERE `$tbl_erziehungsberechtigte[id]` LIKE '".$_GET['s_id']."'
                AND `$tbl_erziehungsberechtigte[schul_id]` = $_SESSION[schul_id];";
        $result=mysql_query($sql);		
        $row = mysql_fetch_assoc($result);
        
       
?>
	<h2>Erziehungsberechtigte bearbeiten</h2>

		<fieldset>
			
				<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>&back=<?=$_GET['back']?>" method="post">
				
					<fieldset title="1. Erziehungsberechtigter"><legend> 1. Erziehungsberechtigter </legend>
						<label for="e1_nachname" >Nachname:</label>
						<input  maxlength="50" type="text" name="e1_nachname" id="e1_nachname" value="<?php echo $row[$tbl_erziehungsberechtigte['name']];?>"/><br /> 
						
						<label for="e1_vorname" >Vorname:</label>
						<input maxlength="50" type="text" name="e1_vorname" id="e1_vorname" value="<?php echo $row[$tbl_erziehungsberechtigte['vorname']];?>"/><br /> 
						
						<label for="e1_strasse" >Strasse/Nr:</label>
						<input maxlength="50" type="text" name="e1_strasse" id="e1_strasse" value="<?php echo $row[$tbl_erziehungsberechtigte['strasse']];?>"/><br /> 
						
						<?php echo getPlzForm("e1_plz","e1_ort", $row[$tbl_erziehungsberechtigte['plz']]); ?><br />
												
						<label for="e1_fon_privat" >Telefon privat:</label>
						<input maxlength="50" type="text" name="e1_fon_privat" id="e1_fon_privat" value="<?php echo $row[$tbl_erziehungsberechtigte['fon_privat']];?>"/><br />
						
						<label for="e1_fon_dienst" >Telefon dienstlich:</label>
						<input maxlength="50" type="text" name="e1_fon_dienst" id="e1_fon_dienst" value="<?php echo $row[$tbl_erziehungsberechtigte['fon_dienst']];?>"/><br />
					</fieldset><br />
					
					<fieldset title="2. Erziehungsberechtigter"><legend> 2. Erziehungsberechtigter </legend>
						<label for="e2_nachname" >Nachname:</label>
						<input maxlength="50" type="text" name="e2_nachname" id="e2_nachname" value="<?php echo $row[$tbl_erziehungsberechtigte['name2']];?>"/><br /> 
						
						<label for="e2_vorname" >Vorname:</label>
						<input maxlength="50" type="text" name="e2_vorname" id="e_vorname" value="<?php echo $row[$tbl_erziehungsberechtigte['vorname2']];?>"/><br /> 
						
						<label for="e2_fon_dienst" >Telefon dienstlich:</label>
						<input maxlength="50" type="text" name="e2_fon_dienst" id="e2_fon_dienst" value="<?php echo $row[$tbl_erziehungsberechtigte['fon_dienst2']];?>"/><br />
					</fieldset>
					
					<input type="hidden" name="e_id" id="e_id" value=" <?php echo $_GET['s_id'];?>"/>
					
					<input type="submit" value="Absenden" /> 
					<?php
						if(isset($_GET['back']) && $_GET['back'] != "")	
							echo "<a href=\"/index.php?content=betreuungsauftraege_verwalten&p=7b77ba4ee8c2d1c9a348fee869eea6be&baid=$_GET[back]\" class=\"button\">Zur&uuml;ck</a>";
					?>
				</form>
			</fieldset>
<?php	

} elseif($page==md5('eintragen')) {
		echo "<h2>Erziehungsberechtigte ge&auml;ndert</h2>";
		$sql = "UPDATE `$tbl_erziehungsberechtigte[tbl]` 
				SET `$tbl_erziehungsberechtigte[name]`='$_POST[e1_nachname]',
				`$tbl_erziehungsberechtigte[vorname]`='$_POST[e1_vorname]',
				`$tbl_erziehungsberechtigte[strasse]`='$_POST[e1_strasse]',
				`$tbl_erziehungsberechtigte[plz]`='$_POST[e1_ort]',
				`$tbl_erziehungsberechtigte[fon_privat]`='$_POST[e1_fon_privat]',
				`$tbl_erziehungsberechtigte[fon_dienst]`='$_POST[e1_fon_dienst]',
				`$tbl_erziehungsberechtigte[name2]`='$_POST[e2_nachname]',
				`$tbl_erziehungsberechtigte[vorname2]`='$_POST[e2_vorname]',
				`$tbl_erziehungsberechtigte[fon_dienst2]`='$_POST[e2_fon_dienst]' 
				WHERE `$tbl_erziehungsberechtigte[id]`='$_POST[e_id]';
				";			
		
		$result = mysql_query($sql);
		
		$sql_class = new Sql;
		$sql_class->insertLog("erziehungsberechtigte",$_POST['e_id'],$sql);
		
		if(isset($_GET['back']) && $_GET['back'] != "")	
		{
			echo "<a href=\"/index.php?content=betreuungsauftraege_verwalten&p=7b77ba4ee8c2d1c9a348fee869eea6be&baid=$_GET[back]\" class=\"button\">Zur&uuml;ck</a>";
		} else{
				echo "<a href=\"index.php?content=".$self."&p=".md5('verwalten')."\" class=\"button\">Weiter</a>";
		}
} 
?>