<?php
	checkRights(50,$user);
	$page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'termin';
    echo "<h1>Termine</h1>";

if($page==md5('verwalten')) {
	checkRights(200,$user);
		echo "<br /><a class=\"button\" href=\"index.php?content=termin&p=".md5('neuertermin')." \">Neuer Termin</a> <br /><br />";
		$sql="
			SELECT *
			FROM `$tbl_termine[tbl]` WHERE `$tbl_termine[id]` != 1
			;";
	$result = mysql_query($sql);
	
		     ?>
		     <table>
				<tr>
					<th>Datum</th>
					<th>Termin</th>
					<th></th>
				</tr>
			</table>
	<?php
	$i = 0;
	while($row = mysql_fetch_assoc($result))
	{
		$i = ($i - 1) * ($i - 1);
		echo "<tr class=\"row".$i."\">";
		echo "<td>".convertDate($row[$tbl_termine['date']])."</td>";
		echo "<td>".$row[$tbl_termine['betreff']]."</td>";
		echo "<td><a href=\"index.php?content=termin&p=".md5('bearbeiten')."&t_id=".$row[$tbl_termine['id']]."\" >
			<img src=\"icons/pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
			</td></a>";
		echo "<td><a href=\"index.php?content=termin&p=".md5('loeschen')."&t_id=".$row[$tbl_termine['id']]."\" >
			<img src=\"icons/cross_48.png\" height=\"28\" width=\"28\" alt=\"l&ouml;schen\">
			</a></td>";
	}
	echo "</table>";

}elseif($page==md5('showone')) {	
	echo "<h2>Termindetails</h2>";
	$sql = "SELECT *
			FROM `$tbl_termine[tbl]`
			WHERE $tbl_termine[id] LIKE '$_GET[t_id]'
			";
	$result = mysql_query($sql);
	$row = mysql_fetch_assoc($result);
	echo "<b>Datum:</b> ".$row[$tbl_termine['date']]."<br />";
	echo "<b>Betreff: </b>".$row[$tbl_termine['betreff']]."<br /><br />";
	echo nl2br($row[$tbl_termine['nachricht']]);
	
} elseif($page==md5('bearbeiten')) {
	$sql = "SELECT *
			FROM `$tbl_termine[tbl]`
			WHERE $tbl_termine[id] LIKE '$_GET[t_id]'
			";
	$result = mysql_query($sql);		
	$row = mysql_fetch_assoc($result);
?>

	<h2>Termin bearbeiten</h2>
		<fieldset>
			<form action="index.php?content=termin&p=<?php echo md5('aendern');?>" method="post">
				<label for="t_id">TerminID:</label>
				<?php echo "<b>".$row[$tbl_termine['id']]."</b>";?>
				<br /> 
				
				<label for="t_tag" >Datum:</label>
				<?php
					echo getDateBox("date", $row[$tbl_termine['date']]);
				?>
				<br /> 
				
				<label for="t_termin" >Betreff:</label>
				<input type="text" name="t_termin" id="t_termin" value="<?php echo $row[$tbl_termine['betreff']];?>"/>
				<br />
				
				<label for="t_nachricht" >Nachricht:</label>
				<textarea name="t_nachricht" id="t_nachricht" cols="30" rows="10" ><?php echo $row[$tbl_termine['nachricht']];?></textarea>
				<br />
				
				<input type="hidden" name="t_id" id="t_id" value="<?php echo $row[$tbl_termine['id']];?>"/>
				
				<input type="submit" value="&Auml;ndern" /> 
			</form>
		</fieldset>	
		
<?php	} elseif($page==md5('aendern')) {
		checkRights(200,$user);
		echo "<h2>Termin ge&auml;ndert</h2>";
		$sql = "UPDATE $tbl_termine[tbl] SET $tbl_termine[date] = '$_POST[date_year]-$_POST[date_month]-$_POST[date_day]',
				$tbl_termine[betreff] = '$_POST[t_termin]', $tbl_termine[nachricht] = '$_POST[t_nachricht]'  
				WHERE $tbl_termine[id] = '$_POST[t_id]';";
		$result = mysql_query($sql);
		
		$sql_class = new Sql;
		$sql_class->insertLog("termine",$_POST['t_id'],$sql);
		
		echo "<br /><a class=\"button\" href=\"index.php?content=termin&p=".md5('neuertermin')." \">Neuer Termin</a> <br /><br />";
		$sql="
			SELECT *
			FROM `$tbl_termine[tbl]` WHERE `$tbl_termine[id]` != 1
			;";
	$result = mysql_query($sql);
	
		     ?>
		     <table>
				<tr>
					<th>Datum</th>
					<th>Termin</th>
					<th></th>
				</tr>
			</table>
	<?php
	
	while($row = mysql_fetch_assoc($result))
	{
		echo "<form action=\"index.php?content=termin&p=".md5('bearbeiten')."&t_id=".$row['t_id']."\" method=\"POST\">";
		echo "<tr><td>".convertDate($row[$tbl_termine['date']])."</td>";
		echo "<td>".$row[$tbl_termine['betreff']]."</td>";
		echo "<td><input type='submit' value='&Auml;ndern'></td>";
		echo "</form>";
		echo "<form action=\"index.php?content=termin&p=".md5('loeschen')."&t_id=".$row[$tbl_termine['id']]."\" method=\"POST\">";
		echo "<td><input type='submit' value='L&ouml;schen'></td>";
		echo "</form>";
	}
	
	echo "</table>";
 

}elseif($page==md5('neuertermin')) {
checkRights(200,$user);	
?>	
<h2>Neuer Termin</h2>
	<fieldset>
		<form action="index.php?content=termin&p=<?php echo md5('eintragen');?>" method="post">
			
			<label for="t_tag" >Datum:</label>
				<?php
					echo getDateBox("date");
				?>
			<br /> 
			
			<label for="t_termin" >Betreff:</label>
			<input type="text" name="t_termin" id="t_termin" "/>
			<br />
			
			<label for="t_nachricht" >Nachricht:</label>
			<textarea name="t_nachricht" id="t_nachricht" cols="30" rows="10" /></textarea>
			<br />
			
			<input type="submit" value="Eintragen" /> 
		</form>
	</fieldset>		
<?php

}elseif($page==md5('eintragen')) {
checkRights(200,$user);	

    echo "<h2>Termin eingetragen</h2>";
    echo "<p>Der Termin wurde erfolgreich eingetragen</p>";

	$sql = "INSERT INTO `$tbl_termine[tbl]` ( `$tbl_termine[id]` , `$tbl_termine[date]` , `$tbl_termine[betreff]`, `$tbl_termine[nachricht]` )
			VALUES (
			'', '$_POST[date_year]-$_POST[date_month]-$_POST[date_day]', '$_POST[t_termin]','$_POST[t_nachricht]'
			);";
	$result = mysql_query($sql);
	
	$sql_class = new Sql;
	$sql_class->insertLog("termine",$sql_class->getLastId(),$sql);
	
}elseif($page==md5('loeschen')) {
checkRights(200,$user);
	echo "<h2>Termin gel&ouml;scht</h2>";	
	$sql = "DELETE FROM $tbl_termine[tbl] WHERE `$tbl_termine[id]` = '$_GET[t_id]' LIMIT 1 ;";
	$result = mysql_query($sql);
	
	$sql_class = new Sql;
	$sql_class->insertLog("termine",$_GET['t_id'],$sql);
}
?>