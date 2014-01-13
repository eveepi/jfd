<?php
	checkRights(200,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'klassen_verwalten';
    echo "<h1>Klassen Verwalten</h1>";

    if($page==md5('verwalten')) {	
		$_GET['b'] = isset($_GET['b']) ? $_GET['b'] : '';
		$_GET['status'] = isset($_GET['status']) ? $_GET['status'] : 1;
		echo "<a class=\"button\" href=\"index.php?content=klassen_verwalten&p=".md5('neu')."\">Neue Klasse anlegen</a><br /><br />";
		
		echo "<a href=\"index.php?content=klassen_verwalten&b=".$_GET['b']."&status=1\"";
		if ($_GET['status'] == 1) { echo "class=\"selected\" "; }
		echo ">&nbsp;aktiviert&nbsp;</a>&nbsp;";		
	
		echo "<a href=\"index.php?content=klassen_verwalten&b=".$_GET['b']."&status=0\"";
		if ($_GET['status'] == 0) { echo "class=\"selected\" "; }
		echo ">&nbsp;deaktiviert&nbsp;</a>&nbsp;<br/><br/>";	
			
		echo '<a href="index.php?content=klassen_verwalten&status='.$_GET['status'].'">Alle</a>&nbsp; ';

    	for($i=65;$i <= 90;$i++){
				echo "<a href=\"index.php?content=klassen_verwalten&status=".$_GET['status']."&b=". chr($i) ."\"";
				if (chr($i) == $_GET['b']) { echo "class=\"selected\" "; }
				echo ">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}

		echo "<br /><br />";

		$sql = "SELECT * FROM `$tbl_klassen[tbl]` 
            WHERE `$tbl_klassen[status]` = $_GET[status] 
            AND `$tbl_klassen[bezeichnung]` LIKE '".$_GET['b']."%' 
            ORDER BY `$tbl_klassen[bezeichnung]`;";
        
    $result = mysql_query($sql);
    
    if($row = mysql_fetch_assoc($result)) {
    echo "<table cellpadding='5' cellspacing='0'>
    <tr>
      <th>Interne Bezeichnung</th>
      <th>Bezeichnung</th>
      <th> </th>
    </tr>";
				
		$i = 0;
		do{
			$i = ($i - 1) * ($i - 1);
			echo "<tr class=\"row".$i."\">";
			echo "  <td>".$row[$tbl_klassen['bezeichnung_intern']]."</td>";
			echo "  <td>".$row[$tbl_klassen['bezeichnung']]."</td>";
			echo "  <td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&k_id=".$row[$tbl_klassen['id']]."\">
			<img src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
			</a> </td>";
			echo "</tr>";
        }while($row = mysql_fetch_assoc($result));
         
        echo "</table>";
        } else {
			echo "Es sind keine Eintr&auml;ge vorhanden.";
        }

} elseif($page==md5('bearbeiten')) { 
        $sql = "SELECT * FROM `$tbl_klassen[tbl]` 
				WHERE `$tbl_klassen[id]` = '".$_GET['k_id']."';
                ";
        $result=mysql_query($sql);		
        $row = mysql_fetch_assoc($result);
?>
	<h2>Klasse bearbeiten</h2>
		<fieldset>
			<form action="index.php?content=<?php echo $self;?>&p=<?php echo md5('eintragen');?>" method="post">	
				<label for="k_bezeichnung_intern" >Bezeichnung-Intern:</label>
				<input maxlength="50" type="text" name="k_bezeichnung_intern" id="k_bezeichnung_intern" value="<?php echo $row[$tbl_klassen['bezeichnung_intern']];?>"/>
				<br /> 

				<label for="k_bezeichnung" >Bezeichnung:</label>
				<input maxlength="50" type="text" name="k_bezeichnung" id="k_bezeichnung" value="<?php echo $row[$tbl_klassen['bezeichnung']];?>"/>
				<br /> 

				<input maxlength="5" type="hidden" name="k_id" id="k_id" value="<?php echo $row[$tbl_klassen['id']];?>"/>
				
				<label for="k_status" >Status:</label>
                <select name="k_status" id="k_status">
                    <option value="0" <?php  if($row[$tbl_klassen['status']]==0) echo "selected=\"selected\""; ?> >Inaktiv</option>
                    <option value="1" <?php  if($row[$tbl_klassen['status']]==1) echo "selected=\"selected\""; ?>>Aktiv</option>
                </select>
				<br />
				
				<input type="submit" value="&Auml;ndern" /> 
			</form>
		</fieldset>	
<?php	

} elseif($page==md5('eintragen')) {
			
		$sql = "UPDATE `$tbl_klassen[tbl]` 
				SET 
				`$tbl_klassen[bezeichnung_intern]` = '$_POST[k_bezeichnung_intern]', 
				`$tbl_klassen[bezeichnung]` = '$_POST[k_bezeichnung]', 
				`$tbl_klassen[status]` = '$_POST[k_status]'
			   	WHERE $tbl_klassen[id] = '$_POST[k_id]'";

		$result = mysql_query($sql);
		$sql_class = new Sql;
		$sql_class->insertLog($tbl_klassen['tbl'],$_POST['k_id'],$sql);

		echo "<h2>Klasse ge&auml;ndert</h2>";
		
		echo "<a href=\"index.php?content=klassen_verwalten\">Weiter</a>";
		
} elseif($page==md5('insert')) {
		
		$sql = "INSERT INTO `$tbl_klassen[tbl]` (
				`$tbl_klassen[id]` ,
				`$tbl_klassen[bezeichnung_intern]` ,
				`$tbl_klassen[bezeichnung]` ,
				`$tbl_klassen[status]`
				)
				VALUES (
				NULL , 
				'$_POST[k_bezeichnung_intern]', 
				'$_POST[k_bezeichnung]', 
				1
				);";

		$result = mysql_query($sql);
		$sql_class = new Sql;
		$sql_class->insertLog($tbl_klassen['tbl'],$sql_class->getLastId(),$sql);
		
		echo "<h2>Klasse eingetragen</h2>";
		
		echo "<a href=\"index.php?content=klassen_verwalten\">Weiter</a>";
				
} elseif($page==md5('neu')) {
?>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#klasse").validate(
				{
					rules: {
						k_bezeichnung : "required",
						k_bezeichnung_intern : "required"
					},
					messages: {
						k_bezeichnung: "Bitte geben Sie eine Bezeichnung für die Klassen an.",
						k_bezeichnung_intern: "Bitte geben Sie eine interne Bezeichnung für die Klassen an."
					}
				} 
			);
		});
	</script>
	<h2>Klasse erstellen</h2>
		<fieldset>
			<form action="index.php?content=<?php echo $self; ?>&p=<?php echo md5('insert');?>" method="post" id="klasse">	
				<label for="k_bezeichnung_intern" >Bezeichnung-Intern:</label>
				<input maxlength="50" type="text" name="k_bezeichnung_intern" id="k_bezeichnung_intern" value=""/>
				<br /> 

				<label for="k_bezeichnung" >Bezeichnung:</label>
				<input maxlength="50" type="text" name="k_bezeichnung" id="k_bezeichnung" value=""/>
				<br /> 
				<input type="submit" value="Einf&uuml;gen" /> 
			</form>
		</fieldset>	
<?php
} 
?>