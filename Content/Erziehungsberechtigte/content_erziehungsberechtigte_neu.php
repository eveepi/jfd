<?php
	checkRights(40,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('anlegen');
	$self = 'erziehungsberechtigte_neu';
    
    if (isset($_SESSION['s_id']) OR isset($_GET['sid']))
    {
		
		echo "<h1>Erziehungsberechtigte anlegen</h1>";
	
		$_SESSION['e1_nachname'] 	= isset($_SESSION['e1_nachname']) 	? $_SESSION['e1_nachname'] : '';
		$_SESSION['e1_vorname']  	= isset($_SESSION['e1_vorname'])  	? $_SESSION['e1_vorname'] : '';
		$_SESSION['e1_fon_privat']  = isset($_SESSION['e1_fon_privat']) ? $_SESSION['e1_fon_privat'] : '';
		$_SESSION['e1_fon_dienst']  = isset($_SESSION['e1_fon_dienst']) ? $_SESSION['e1_fon_dienst'] : '';
		
		$_SESSION['e2_nachname'] 	= isset($_SESSION['e2_nachname']) 	? $_SESSION['e2_nachname'] : '';
		$_SESSION['e2_vorname']  	= isset($_SESSION['e2_vorname'])  	? $_SESSION['e2_vorname'] : '';
		$_SESSION['e2_fon_dienst']  = isset($_SESSION['e2_fon_dienst']) ? $_SESSION['e2_fon_dienst'] : '';
		

		if($page==md5('anlegen')) {
		
		if (isset($_GET['sid']))
			$_SESSION['s_id'] = $_GET['sid'];
		
		
		?>
			<h2>Neuer Erziehungsberechtigter</h2>
			<fieldset>
			
				<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>" method="post">
				
					<fieldset title="1. Erziehungsberechtigter"><legend> 1. Erziehungsberechtigter </legend>
						<label for="e1_nachname" >Nachname:</label>
						<input  maxlength="20" type="text" name="e1_nachname" id="e1_nachname" value="<?php echo $_SESSION['e1_nachname'];?>"/><br /> 
						
						<label for="e1_vorname" >Vorname:</label>
						<input maxlength="20" type="text" name="e1_vorname" id="e1_vorname" value="<?php echo $_SESSION['e1_vorname'];?>"/><br /> 
						
						<label for="e1_fon_privat" >Telefon privat:</label>
						<input maxlength="15" type="text" name="e1_fon_privat" id="e1_fon_privat" value="<?php echo $_SESSION['e1_fon_privat'];?>"/><br />
						
						<label for="e1_fon_dienst" >Telefon dienstlich</label>
						<input maxlength="15" type="text" name="e1_fon_dienst" id="e1_fon_dienst" value="<?php echo $_SESSION['e1_fon_dienst'];?>"/><br />
					</fieldset><br />
					
					<fieldset title="2. Erziehungsberechtigter"><legend> 2. Erziehungsberechtigter </legend>
						<label for="e2_nachname" >Nachname:</label>
						<input maxlength="20" type="text" name="e2_nachname" id="e2_nachname" value="<?php echo $_SESSION['e2_nachname'];?>"/><br /> 
						
						<label for="e2_vorname" >Vorname:</label>
						<input maxlength="20" type="text" name="e2_vorname" id="e_vorname" value="<?php echo $_SESSION['e2_vorname'];?>"/><br /> 
						
						<label for="e2_fon_dienst" >Telefon dienstlich</label>
						<input maxlength="15" type="text" name="e2_fon_dienst" id="e2_fon_dienst" value="<?php echo $_SESSION['e2_fon_dienst'];?>"/><br />
					</fieldset>
					
					<input type="submit" value="Absenden" /> 
				</form>
			</fieldset>
		
		<?php
		} elseif($page==md5('eintragen')) {
		
		$_SESSION['e1_nachname'] 	= $_POST['e1_nachname'];
		$_SESSION['e1_vorname'] 	= $_POST['e1_vorname'];
		$_SESSION['e1_fon_privat'] 	= $_POST['e1_fon_privat'];
		$_SESSION['e1_fon_dienst'] 	= $_POST['e1_fon_dienst'];
		$_SESSION['e2_nachname'] 	= $_POST['e2_nachname'];
		$_SESSION['e2_vorname'] 	= $_POST['e2_vorname'];
		$_SESSION['e2_fon_dienst'] 	= $_POST['e2_fon_dienst'];
		
		if(($_SESSION['e1_nachname']=='' OR $_SESSION['e1_vorname']=='' OR ($_SESSION['e1_fon_privat']=='' AND $_SESSION['e1_fon_dienst']=='')) OR 
			($_SESSION['e2_nachname']!='' AND  $_SESSION['e2_vorname']=='') OR ($_SESSION['e2_nachname']=='' AND $_SESSION['e2_vorname']!='')){
				echo "<p>Es müssen <b>Vorname, Nachname</b> und eine private <b>Telefonnummer</b> des ersten Erziehungsberechtigten angegeben werden. <br />
					  Falls ein zweiter Erziehungsberechtigter angegeben wird, benötigt dieser <b>Vor- und Nachnamen</b>.</p>";
				?>
				
					<h2>Neuer Erziehungsberechtigter</h2>
					<fieldset>
					
						<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>" method="post">
						
							<fieldset title="1. Erziehungsberechtigter"><legend> 1. Erziehungsberechtigter </legend>
								<label for="e1_nachname" >Nachname:</label>
								<input <?php if ($_SESSION['e1_nachname']=='') echo "class=\"wrong_insert\" ";?>maxlength="20" type="text" name="e1_nachname" id="e1_nachname" value="<?php echo $_SESSION['e1_nachname'];?>"/><br /> 
								
								<label for="e1_vorname" >Vorname:</label>
								<input <?php if ($_SESSION['e1_vorname']=='') echo "class=\"wrong_insert\" ";?>maxlength="20" type="text" name="e1_vorname" id="e1_vorname" value="<?php echo $_SESSION['e1_vorname'];?>"/><br /> 
								
								<label for="e1_fon_privat" >Telefon privat:</label>
								<input <?php if ($_SESSION['e1_fon_privat']=='') echo "class=\"wrong_insert\" ";?>maxlength="15" type="text" name="e1_fon_privat" id="e1_fon_privat" value="<?php echo $_SESSION['e1_fon_privat'];?>"/><br />
								
								<label for="e1_fon_dienst" >Telefon dienstlich</label>
								<input maxlength="15" type="text" name="e1_fon_dienst" id="e1_fon_dienst" value="<?php echo $_SESSION['e1_fon_dienst'];?>"/><br />
							</fieldset><br />
							
							<fieldset title="2. Erziehungsberechtigter"><legend> 2. Erziehungsberechtigter </legend>
								<label for="e2_nachname" >Nachname:</label>
								<input <?php if ($_SESSION['e2_nachname']=='' AND $_SESSION['e2_vorname']!='') echo "class=\"wrong_insert\" ";?>maxlength="20" type="text" name="e2_nachname" id="e2_nachname" value="<?php echo $_SESSION['e2_nachname'];?>"/><br /> 
								
								<label for="e2_vorname" >Vorname:</label>
								<input <?php if ($_SESSION['e2_vorname']=='' AND $_SESSION['e2_nachname']!='') echo "class=\"wrong_insert\" ";?>maxlength="20" type="text" name="e2_vorname" id="e_vorname" value="<?php echo $_SESSION['e2_vorname'];?>"/><br /> 
								
								<label for="e2_fon_dienst" >Telefon dienstlich</label>
								<input maxlength="15" type="text" name="e2_fon_dienst" id="e2_fon_dienst" value="<?php echo $_SESSION['e2_fon_dienst'];?>"/><br />
							</fieldset>
							
							<input type="submit" value="Absenden" /> 
						</form>
					</fieldset>

				<?php	      
			}else {
	
	
				$sql="
					INSERT INTO `$tbl_erziehungsberechtigte[tbl]` 
					( `$tbl_erziehungsberechtigte[id]` , `$tbl_erziehungsberechtigte[vorname]` , `$tbl_erziehungsberechtigte[name]` , `$tbl_erziehungsberechtigte[fon_privat]` , `$tbl_erziehungsberechtigte[fon_dienst]`, `$tbl_erziehungsberechtigte[vorname2]` , `$tbl_erziehungsberechtigte[name2]` , `$tbl_erziehungsberechtigte[fon_dienst2]` , `$tbl_erziehungsberechtigte[schul_id]` )
					VALUES (NULL , '".$_SESSION['e1_vorname']."', '".$_SESSION['e1_nachname']."', '".$_SESSION['e1_fon_privat']."', '".$_SESSION['e1_fon_dienst']."' , '".$_SESSION['e2_vorname']."', '".$_SESSION['e2_nachname']."', '".$_SESSION['e2_fon_dienst']."', '".$_SESSION['schul_id']."'
					);";
				$result = mysql_query($sql);
				
				$sql1 = "SELECT last_insert_id() as id";
				$result1 = mysql_query($sql1);
				$row = mysql_fetch_assoc($result1);

				$_SESSION['e_id'] = $row['id'];
				
				$sql1="
					INSERT INTO `$tbl_erziehungsberechtigteZuordnung[tbl]` ( `$tbl_erziehungsberechtigteZuordnung[s_id]` , `$tbl_erziehungsberechtigteZuordnung[e_id]` )
					VALUES (
					$_SESSION[s_id], 
					$_SESSION[e_id]
					);";
				$result1 = mysql_query($sql1);		
				
				
				if (!$result) {
					echo "<h2>Fehler</h2>";
					echo "<p>Der Erziehungsberechtigte konnte nicht angelegt werden, <br />bitte kontaktieren Sie den Systemadministrator und leiten sie den folgenden Text an ihn weiter:<br />
					".$sql."</p>";
				}else {
				echo "<h2>Eingetragen</h2>";
				echo "<p>Erziehungsberechtigte eingetragen.</p>";
				
				
				$sql = "SELECT $tbl_erziehungsberechtigte[id] FROM `$tbl_erziehungsberechtigte[tbl]`  
						WHERE $tbl_erziehungsberechtigte[vorname] = '".$_SESSION['e1_vorname']."' 
						AND $tbl_erziehungsberechtigte[name] = '".$_SESSION['e1_nachname']."' 
						AND $tbl_erziehungsberechtigte[fon_privat] = '".$_SESSION['e1_fon_privat']."' 
						AND $tbl_erziehungsberechtigte[fon_dienst] = '".$_SESSION['e1_fon_dienst']."' ;";
				$result=mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				
				$sql_class = new Sql;
				$sql_class->insertLog("erziehungsberechtigte",$sql_class->getLastId(),$sql);	
				
				}
				$_SESSION['e1_nachname'] = '';
				$_SESSION['e1_vorname'] = '';
				$_SESSION['e1_fon_privat'] = '';
				$_SESSION['e1_fon_dienst'] = '';
				$_SESSION['e2_nachname'] = '';
				$_SESSION['e2_vorname'] = '';
				$_SESSION['e2_fon_dienst'] = '';

						
		
			}	
	}
}
else
{
	echo "<h1>Keine Sch&uuml;ler-ID &uuml;bergeben!</h1>";
			?>
		<h2>Sch&uuml;ler ausw&auml;hlen</h2>
		<a class="button" href="index.php?content=schueler_neu">Neuen Sch&uuml;ler anlegen</a>
        <br /><br />
		<a href="index.php?content=erziehungsberechtigte_neu">Alle</a>&nbsp; 
<?php
    	for($i=65;$i <= 89;$i++){
				echo "<a href=\"index.php?content=erziehungsberechtigte_neu&b=". chr($i) ."\">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}
		echo "<br /><br />";
?>
        <table>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Geburtsdatum</th>
                <th></th>
            </tr>
		<?php
		if(isset($_GET['b']) AND strlen($_GET['b'])==1){
			$sql = "SELECT `$tbl_schueler[id]`, `$tbl_schueler[vorname]`, `$tbl_schueler[name]`, `$tbl_schueler[geburtsdatum]`  FROM `$tbl_schueler[tbl]` 
					WHERE `$tbl_schueler[schul_id]` = '$_SESSION[schul_id]' AND `$tbl_schueler[name]` LIKE '".$_GET['b']."%'ORDER BY `$tbl_schueler[name]`;";
			}
			else{
			$sql = "SELECT `$tbl_schueler[id]`, `$tbl_schueler[vorname]`, `$tbl_schueler[name]`, `$tbl_schueler[geburtsdatum]`  FROM `$tbl_schueler[tbl]` WHERE `$tbl_schueler[schul_id]` = '$_SESSION[schul_id]' ORDER BY `$tbl_schueler[name]`;";
			}
        $result = mysql_query($sql);
        while($row = mysql_fetch_assoc($result)) {
                echo "<tr>";
                echo "  <td>".$row[$tbl_schueler['id']]."</td>";
                echo "  <td>".$row[$tbl_schueler['vorname']]."</td>";
                echo "  <td>".$row[$tbl_schueler['name']]."</td>";
                echo "  <td>".$row[$tbl_schueler['geburtsdatum']]."</td>";
                echo "  <td><a href=\"index.php?content=erziehungsberechtigte_neu&sid=".$row[$tbl_schueler['id']]." \">Sch&uuml;ler ausw&auml;hlen</a> </td> ";
                echo "</tr>";
        }
	?>
       </table>
	<?php
}
?>