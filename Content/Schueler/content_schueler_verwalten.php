<?php
	checkRights(50,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'schueler_verwalten';
    echo "<h1>Sch&uuml;ler Verwalten</h1>";
	
	if(!isset($_GET['back']))
		$_GET['back'] = "";
			
    if($page==md5('verwalten')) {	
    $_GET['b'] = isset($_GET['b']) ? $_GET['b'] : '';
?>
        <a class="button" href="index.php?content=schueler_neu">Neuen Sch&uuml;ler anlegen</a>
        <br /><br />
		<a href="index.php?content=schueler_verwalten">Alle</a>&nbsp; 
<?php
    	for($i=65;$i <= 90;$i++){
				echo "<a href=\"index.php?content=schueler_verwalten&b=". chr($i) ."\" ";
				if (chr($i) == $_GET['b']) { echo "class=\"selected\" "; }
				echo ">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}
		echo "<br /><br />";


		$sql = "SELECT `$tbl_schueler[id]`, `$tbl_schueler[vorname]`, `$tbl_schueler[name]`  FROM `$tbl_schueler[tbl]` WHERE `$tbl_schueler[schul_id]` = $_SESSION[schul_id] AND `$tbl_schueler[name]` LIKE '".$_GET['b']."%'ORDER BY `$tbl_schueler[name]`;";

        $result = mysql_query($sql);
        
       if($row = mysql_fetch_assoc($result)){
		   echo "
			<table>
				<tr>
					<th>ID</th>
					<th>Vorname</th>
					<th>Nachname</th>
					<th> </th>
				</tr>";
            
			$i = 1;
			echo "<tr class=\"row".$i."\">";
			echo "  <td>".$row[$tbl_schueler['id']]."</td>";
			echo "  <td>".$row[$tbl_schueler['vorname']]."</td>";
			echo "  <td>".$row[$tbl_schueler['name']]."</td>";
			echo "  <td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_schueler['id']]."\">
			<img src=\"icons/pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
			</a> </td>";
			echo "</tr>";
	        
        while($row = mysql_fetch_assoc($result)) {			
                $i = ($i - 1) * ($i - 1);
				echo "<tr class=\"row".$i."\">";
				echo "  <td>".$row[$tbl_schueler['id']]."</td>";
                echo "  <td>".$row[$tbl_schueler['vorname']]."</td>";
                echo "  <td>".$row[$tbl_schueler['name']]."</td>";
                echo "  <td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_schueler['id']]."\">
                <img src=\"icons/pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
                </a> </td>";
                echo "</tr>";

        }
        echo "</table>";
        }else {
			echo "Es sind keine Eintr&auml;ge vorhanden.";
        }

} elseif($page==md5('bearbeiten')) { 
        $sql = "SELECT *
                FROM `$tbl_schueler[tbl]`
                WHERE `$tbl_schueler[id]` LIKE '".$_GET['s_id']."'
                ";
        $result=mysql_query($sql);		
        $row = mysql_fetch_assoc($result);
		       
        $sql = "SELECT * FROM $tbl_aerzte[tbl]";
		$result2 = mysql_query($sql);
?>
	<h2>Sch&uuml;ler bearbeiten</h2>
		<fieldset>
			<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>&back=<?=$_GET['back']?>" method="post">	
				<label for="s_vorname" >Vorname:</label>
				<input maxlength="50" type="text" name="s_vorname" id="s_vorname" value="<?php echo $row[$tbl_schueler['vorname']];?>"/>
				<br /> 
				
				<label for="s_nachname" >Nachname:</label>
				<input maxlength="50" type="text" name="s_nachname" id="s_nachname" value="<?php echo $row[$tbl_schueler['name']];?>"/>
				<br /> 
				
				<label for="s_str" >Stra&szlig;e / Nr.:</label>
				<input maxlength="50" type="text" name="s_str" id="s_str" value="<?php echo $row[$tbl_schueler['strasse']];?>"/>
				<br /> 
				
				<?php echo getPlzForm("s_plz","s_ort", $row[$tbl_schueler['plz']]); ?><br />
				
				<?php echo getKlassenForm("s_klasse",$row[$tbl_schueler['klassen_id']]); ?><br />
				
				<label for="s_geburtsdatum" >Geburtsdatum:</label>
				<?php
					echo getDateBox("s",$row['s_geburtsdatum']);
				?>

				<br />
				<label for="schul_id" >&nbsp;</label>
				<input type="hidden" name="s_id" id="s_id" value="<?php echo $row[$tbl_schueler['id']];?>"/>
				
				<input type="submit" value="&Auml;ndern" /> 
				<?php
					if(isset($_GET['back']) && $_GET['back'] != ""){	
						echo "<a href=\"/index.php?content=betreuungsauftraege_verwalten&p=7b77ba4ee8c2d1c9a348fee869eea6be&baid=$_GET[back]\" class=\"button\">Zur&uuml;ck</a>";
					}
				?>
			</form>
		</fieldset>	
<?php	

} elseif($page==md5('eintragen')) {
		echo "<h2>Sch&uuml;ler ge&auml;ndert</h2>";
		
		$sql = "UPDATE `$tbl_schueler[tbl]` 
				SET 
				`$tbl_schueler[vorname]` = '$_POST[s_vorname]', 
				`$tbl_schueler[name]` = '$_POST[s_nachname]', 
				`$tbl_schueler[strasse]` = '$_POST[s_str]',
				`$tbl_schueler[plz]` = '$_POST[s_ort]',
				`$tbl_schueler[geburtsdatum]` = '$_POST[s_year]-$_POST[s_month]-$_POST[s_day]',
				`$tbl_schueler[klassen_id]`= $_POST[s_klasse]
			   	WHERE 
				$tbl_schueler[id] = '$_POST[s_id]';";

		$result = mysql_query($sql);
		
		$sql_class = new Sql;		
		$sql_class->insertLog("schueler",$_POST['s_id'],$sql);

		if(isset($_GET['back'])&& $_GET['back'] != ""){	
			echo "<a href=\"/index.php?content=betreuungsauftraege_verwalten&p=7b77ba4ee8c2d1c9a348fee869eea6be&baid=$_GET[back]\" class=\"button\">Zur&uuml;ck</a>";
			exit(0);
		}
?>
	<table>
		<tr>
			<th>ID</th>
			<th>Vorname</th>
			<th>Nachname</th>
			<th> </th>
		</tr>
<?php
	$sql = "SELECT `$tbl_schueler[id]`, `$tbl_schueler[vorname]`, `$tbl_schueler[name]`  FROM `$tbl_schueler[tbl]` ORDER BY `$tbl_schueler[name]`;";
	$result = mysql_query($sql);
    while($row = mysql_fetch_assoc($result)) {
		echo "<tr>";
        echo "  <td>".$row[$tbl_schueler['id']]."</td>";
        echo "  <td>".$row[$tbl_schueler['vorname']]."</td>";
        echo "  <td>".$row[$tbl_schueler['name']]."</td>";
        echo "  <td><a href=\"index.php?content=schuelerdetails&sid=".$row[$tbl_schueler['id']]." \">Details</a> </td> ";
        echo "  <td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_schueler['id']]."\">Bearbeiten</a> </td>";
        echo "</tr>";

    }
   ?>
   </table>
   <?php
} 
?>