<?php 
	checkRights(50,$user);
?>
<h1>Betreuungsauftr&auml;ge Suchen</h1>
<br />
<br />
<div>Verwenden Sie % Zeichen als Platzhalter.</div>
<br />
<?php 
	if(!isset($_POST['send'])){ ?>
			<form method="post" action="<?php // echo $_SERVER['PHP_SELF']; ?>">
				<label>Vorname</label><input type="text" value="" name="s_vorname" /><br />
				<label>Nachname</label><input type="text" value="" name="s_nachname" /><br />
				<?php if(checkRights(250, $user, 1)){?>
				<label>SchÃ¼ler-ID</label><input type="text" value="" name="s_id" /><br/>
				<?php } ?>
				<input type="submit" value="Suchen" name="send" />
			</form>
<?php 
	}else{ 
		echo '<a class="button" href="index.php?content=betreuungsauftraege_suche">Neue Suche</a><br/><br/>';
		$sql = new Sql;
		
		if($_POST['s_id'] != "" && checkRights(250, $user, 1)){
			$result = $sql->getBetreuungsAuftragBySchuelerId($_POST['s_id']);
		}else{
			$result = $sql->getUserDataByNameOrForename($_SESSION['schul_id'], $_POST['s_nachname'], $_POST['s_vorname']);
		}		
			
		if($data = mysql_fetch_array($result)){
			
			 echo "<table cellpadding='5' cellspacing='0'>
					<tr>
						<th>Name</th>
						<th>Vorname</th>
						<th>Betreuungsauftrag</th>
						<th>Essensauftrag</th>
						<th>Ferienbetreuung</th>
						<th></th>
						<th></th>
					</tr>";
			$i = 0;
			$page = 1;
			$pageCount = 0;
			$self = "betreuungsauftraege_verwalten";
			
			$changed_school = false;
			if($data['s_schul_id'] != $_SESSION['schul_id']){
				$_SESSION['schul_id'] = $data['s_schul_id'];
				$changed_school = true;
			}									
			do{
				
				$i = ($i - 1) * ($i - 1);
				$pageCount++;
				if($pageCount == LIMIT_NUM){
					$pageCount = 0;
					$page++;
				}
				// init der variablen
				$betr = ($data[$tbl_betreuungsauftraege['montag']] != "" ||  $data[$tbl_betreuungsauftraege['dienstag']] != "" || $data[$tbl_betreuungsauftraege['mitwoch']] != "" || $data[$tbl_betreuungsauftraege['donnerstag']] != "" || $data[$tbl_betreuungsauftraege['freitag']] != "")? 1 : 0;
				$essen = ($data[$tbl_betreuungsauftraege['essenstage']] > 0)? 1: 0;
				$ferien = ($data[$tbl_betreuungsauftraege['ferien']] > 0)? 1: 0;
				
				if ($betr){	$betr = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28\" alt=\"ja\" >";
				} else { $betr = "";}
				if ($essen){ $essen = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28 alt=\"ja\" > ";
				} else { $essen = "";}
				if ($ferien){ $ferien = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28\" alt=\"ja\" >";
				} else { $ferien = "";}
				
				echo "<tr class=\"row".$i." page".$page."\">";
				echo "	<td>".$data[$tbl_schueler['name']]."</td>";
				echo "	<td>".$data[$tbl_schueler['vorname']]."</td>";
				echo "	<td align=\"center\" class=\"betr\">$betr</td>";
				echo "	<td align=\"center\" class=\"essen\">$essen</td>";
				echo "	<td align=\"center\" class=\"ferien\">$ferien</td>"; 
				echo "	<td><a href=\"index.php?content=".$self."&p=".md5('pdf')."&sid=".$data[$tbl_schueler['id']]."\">
						<img  title=\"Drucken\" src=\"".$icon_path."printer_48.png\" height=\"28\" width=\"28\" alt=\"pdf\" class=\"icon\">
						</a></td>";
				echo "	<td align=\"center\"><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&baid=".$data[$tbl_betreuungsauftraege['id']]."\">
						<img title=\"Bearbeiten\" src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\" class=\"icon\">
						</a></td>";
				echo "</tr>";
			}while($data = mysql_fetch_assoc($result));
			echo "</table>";
			if($changed_school){
				echo '<span class="red">Schule wurde gewechselt!</span>';
			}
		}else{
			echo "<br/><br />Keine Auftr&auml;ge gefunden!";
		}
?>
			
<!-- Seitenwähler -->
<div align="left" id="pagePicker">
<img title="vorherige Seite" src=<?php echo "'".$icon_path."arrow_left_green_48.png'"; ?> onclick="prevPage();" style="display: table-cell; vertical-align: middle; cursor: pointer;" height="28" width="28" alt="vorherige Seite" class="icon" >
<select name="pagePicker" id="pagePicker" onchange="changePage();" style="display: table-cell; vertical-align: middle;">
<?php 
for($i=1; $i <= $page; $i++){
	echo "<option value='".$i."'>Seite ".$i."</option>";
}
?>
</select>
<img title="n&auml;chste Seite" src=<?php echo "'".$icon_path."arrow_right_green_48.png'"; ?> onclick="nextPage();" style="display: table-cell; vertical-align: middle; cursor: pointer;" height="28" width="28" alt="n&auml;chste Seite" class="icon">
</div>
	
<?php } ?>
<br />