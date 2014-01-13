<?php
	checkRights(200,$user);

	// init der sortier Variablen
    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'bearbeiter_verwalten';
	$b = isset($_GET['b']) ? $_GET['b'] : "";
	$orderBy = (isset($_GET['orderBy'])) ? $_GET['orderBy'] : "m_username";
	$sortBy = (isset($_GET['sortBy'])) ? $_GET['sortBy'] : "ASC";
	if ($sortBy == "ASC" ){
		$sortBy = "DESC";
	} else {
		$sortBy = "ASC";

	}
	
    echo "<h1>Mitarbeiter Verwalten</h1>";
    if($page==md5('verwalten')) {
    	

		
?>
		<a class="button" href="index.php?content=bearbeiter_neu">Mitarbeiter erstellen</a><br />
		<br /><br />
		<a href="<?php echo "index.php?content=".$self."&b=&orderBy=".$orderBy."&sortBy=".$sortBy; ?>">Alle</a>&nbsp; 
<?php 
    	for($i=65;$i <= 90;$i++){
				echo "<a href=\"index.php?content=bearbeiter_verwalten&b=". chr($i) ."\"";
				if (chr($i) == $b) { echo "class=\"selected\" "; }
				echo ">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}
		echo "<br /><br />";

		
		    if((isset($_GET['b']) AND strlen($_GET['b'])== 1) || $orderBy != ""){
			$sql = "SELECT *  FROM `$tbl_mitarbeiter[tbl]`
					WHERE `$tbl_mitarbeiter[username]` LIKE '".$b."%'
					AND `$tbl_mitarbeiter[status]` <= 200
					ORDER BY `$orderBy` $sortBy";
			}
			else{
				$sql = "SELECT *  FROM `$tbl_mitarbeiter[tbl]` 
						WHERE `$tbl_mitarbeiter[status]` <= 200
						ORDER BY $tbl_mitarbeiter[username];";
			}

			$result = mysql_query($sql);
			
			if ($row = mysql_fetch_assoc($result)){
				?>
				<table cellpadding='5' cellspacing='0'>
					<tr>
						<th><a href="<?php echo "index.php?content=".$self."&b=".$b."&orderBy=m_username"."&sortBy=".$sortBy; ?>">Username</a></th>
						<th><a href="<?php echo "index.php?content=".$self."&b=".$b."&orderBy=m_name"."&sortBy=".$sortBy; ?>">Vorname</a></th>
						<th><a href="<?php echo "index.php?content=".$self."&b=".$b."&orderBy=m_vorname"."&sortBy=".$sortBy; ?>">Nachname</a></th>
						<th><a href="<?php echo "index.php?content=".$self."&b=".$b."&orderBy=m_status"."&sortBy=".$sortBy; ?>">Status</a></th>
						<th>&nbsp;</th>
					</tr>			
				
				<?php
				
				$status['0']="deaktiviert";
				$status['50']="Schule";
				$status['200']="JFD";
				
				$i = 0;
				do{
					$i = ($i - 1) * ($i - 1);
					echo "<tr class=\"row".$i."\">";		
					echo "<td>".$row[$tbl_mitarbeiter['username']]."</td>";
					echo "<td>".$row[$tbl_mitarbeiter['name']]."</td>";
					echo "<td>".$row[$tbl_mitarbeiter['vorname']]."</td>";
					echo "<td>".$status[$row[$tbl_mitarbeiter['status']]]."</td>";
					echo "<td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&b_username=".$row[$tbl_mitarbeiter['username']]."\">
					<img src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\" title=\"bearbeiten\">
					</a> </td>";
					echo "<td><a href=\"index.php?content=".$self."&p=".md5('resetpw')."&b_username=".$row[$tbl_mitarbeiter['username']]."\">
					<img src=\"".$icon_path."pw.gif\" height=\"28\" width=\"28\" alt=\"Passwort zur&uuml;cksetzen\" title=\"Passwort zur&uuml;cksetzen\">
					</a></td></tr>";
				}while($row = mysql_fetch_assoc($result));
			?>
				</table>
			<?php
			}else {
				echo "Es sind keine Eintr&auml;ge vorhanden.";
			}
			
} elseif($page==md5('bearbeiten')) { 
	checkRights(200,$user);
	$sql = "SELECT *
			FROM `$tbl_mitarbeiter[tbl]`
			WHERE $tbl_mitarbeiter[username] LIKE '".$_GET['b_username']."'
			";
	$result = mysql_query($sql);		
	$row = mysql_fetch_assoc($result);

?>
	<h2>Mitarbeiter &auml;ndern</h2>
		<fieldset>
			<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>" method="post">
				<label for="b_usernamen" >Username:</label>
				<?php echo "<b>".$row[$tbl_mitarbeiter['username']]."</b>";?>
				<br /> 
				
				<label for="b_vorname" >Vorname:</label>
				<input maxlength="20" type="text" name="b_vorname" id="b_vorname" value="<?php echo $row[$tbl_mitarbeiter['vorname']];?>"/>
				<br /> 
				
				<label for="b_nachname" >Nachname:</label>
				<input maxlength="30" type="text" name="b_nachname" id="b_nachname" value="<?php echo $row[$tbl_mitarbeiter['name']];?>"/>
				<br /> 	

				<label for="b_email" >E-Mail:</label>
				<input maxlength="30" type="text" name="b_email" id="b_email" value="<?php echo $row[$tbl_mitarbeiter['email']];?>"/>
				<br /> 	
				
				<label for="b_schul_id" >Schule:</label>
                <select name="b_schul_id" id="b_schul_id">
				
					<?php 
					$sql2 = "SELECT *
							FROM `$tbl_schulen[tbl]`";
					$result2 = mysql_query($sql2);		
					while( $row2 = mysql_fetch_assoc($result2)){
						echo "<option value='".$row2[$tbl_schulen['id']]."'";
						if ( $row2[$tbl_schulen['id']] == $row[$tbl_mitarbeiter['schul_id']]) { echo "selected='selected '"; }
						echo ">".$row2[$tbl_schulen['name']]."</option>";
					}
					
					?>

                </select>
				<br />				
				
				<label for="b_status" >Status:</label>
                <select name="b_status" id="b_status">
                    <option value="0" <?php  if($row[$tbl_mitarbeiter['status']]==0) echo "selected=\"selected\""; ?> >Inaktiv</option>
                    <option value="50" <?php  if($row[$tbl_mitarbeiter['status']]==50) echo "selected=\"selected\""; ?>>Schule</option>
                    <option value="200" <?php  if($row[$tbl_mitarbeiter['status']]==200) echo "selected=\"selected\""; ?>>JFD</option>
                </select>
				<br />
				
				<input type="hidden" name="b_username" id="b_username" value="<?php echo $row[$tbl_mitarbeiter['username']];?>"/>
				
				<input type="submit" value="&Auml;ndern" /> 
			</form>
		</fieldset>
<?php
} elseif($page==md5('eintragen')) {
	checkRights(200,$user);

		
		
		$sql=" UPDATE  
			(  , , ,  )
			VALUES (, ,  '', )
			WHERE `$tbl_mitarbeiter[username]` = '$_POST[b_username]';";
			
		$sql="UPDATE `$tbl_mitarbeiter[tbl]` 
				SET `$tbl_mitarbeiter[name]`='$_POST[b_nachname]',
					`$tbl_mitarbeiter[vorname]`='$_POST[b_vorname]',
					`$tbl_mitarbeiter[email]`='$_POST[b_email]',
					`$tbl_mitarbeiter[status]`='$_POST[b_status]',
					`$tbl_mitarbeiter[schul_id]`='$_POST[b_schul_id]' 
				WHERE `$tbl_mitarbeiter[username]`='$_POST[b_username]' ;";	
		mysql_query($sql);
		
		$sql_class = new Sql;
		$sql_class->insertLog("mitarbeiter",$_POST['b_username'],$sql);
	
		echo "<h2>Mitarbeiter ge&auml;ndert</h2>";
						
} elseif($page==md5('resetpw')) {
	checkRights(200,$user);
		echo "<h2>Passwort zur&uuml;cksetzen</h2>";
		echo "Wollen sie das Passwort von <b>".$_GET['b_username']."</b> wirklich zur&uuml;cksetzen?<br />";
		echo "<a class=\"button\" href=\"index.php?content=".$self."&p=".md5('reset')."&b_username=".$_GET['b_username']."\">Ja </a>&nbsp;&nbsp;&nbsp;";
		echo "<a class=\"button\" href=\"index.php?content=".$self."\">Nein</a>";
		
}elseif($page==md5('reset')) {
	checkRights(200,$user);
		$user ->resetPW($_GET['b_username']);
		echo "Passwort zur&uuml;ckgesetzt<br/>";	
		echo "<a class=\"button\" href=\"index.php?content=".$self."\">Weiter</a>";	
		}
?>