<script type="text/javascript">
	function display_betreuung(day){
		if ($('#' + day + '_betreuung:checked').val() != "1"){
			$('#' + day).css("display", "none");
		} else {
			$('#' + day).css("display", "inline");
		}
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#school_form").validate({
				rules: {
					username:{
						required	: true,
						minlength	: 5
					},
					schulname:{
						required	: true,
					},
					telefon:{
						required	: true,
					},
					strasse:{
						required	: true,
					},
					plz:{
						required	: true,
						minlength	: 5
					},
					ansprechpartner:{
						required	: true,
					},
					pwd1:{
						required	: true,
						minlength	: 6
					},
					pwd2:{
						required	: true,
						equalTo		: "#pwd1"
					},
				},
				messages:{
					username:{
						required	: "Bitte einen Usernamen angeben!",
						minlength	: "Der Username muss mindestens 5 Zeichen lang sein!"
					},
					schulname:{
						required	: "Bitte einen Schulnamen angeben!",
					},
					telefon:{
						required	: "Bitte eine Telefonnummer angeben!",
					},
					strasse:{
						required	: "Bitte eine Strasse angeben!",
					},
					plz:{
						required	: "Bitte eine Postleitzahl angeben!",
						minlength	: "Die Postleitzahl muss 5 Zeichen haben!"
					},
					ansprechpartner:{
						required	: "Bitte ein Ansprechpartner angeben!",
					},
					pwd1:{
						required	: "Bitte ein Passwort angeben!",
						minlength	: "Das Passwort muss mindestens 6 Zeichen haben!"
					},
					pwd2:{
						required	: "Bitte eine Postleitzahl angeben!",
						equalTo		: "Passw&ouml;rter stimmen nicht &uuml;berein!"
					},
				}
			}
		);
		display_betreuung("mon");
		display_betreuung("tue");
		display_betreuung("wen");
		display_betreuung("thu");
		display_betreuung("fri");
		
	});
</script>

<?php
	checkRights(200,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'schulen_verwalten';
    echo "<h1>Schulen verwalten</h1>";

    if($page==md5('verwalten')) {	
    $_GET['b'] = isset($_GET['b']) ? $_GET['b'] : '';

?>   
		<a class="button" href="index.php?content=schule_neu">Schule erstellen</a><br />
		<br /><br />
		<a href="index.php?content=schulen_verwalten">Alle</a>&nbsp; 
<?php 
    	for($i=65;$i <= 90;$i++){
				echo "<a href=\"index.php?content=schulen_verwalten&b=". chr($i) ."\"";
				if (chr($i) == $_GET['b']) { echo "class=\"selected\" "; }
				echo ">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}
		echo "<br /><br />";

		    if(isset($_GET['b']) AND strlen($_GET['b'])==1){
			$sql = "SELECT *  FROM `$tbl_schulen[tbl]`
					WHERE `$tbl_schulen[name]` LIKE '".$_GET['b']."%'
					AND $tbl_schulen[id] > 0
					ORDER BY `$tbl_schulen[name]`";
			}
			else{
			$sql = "SELECT *  FROM `$tbl_schulen[tbl]` 
					WHERE $tbl_schulen[id] > 0
					ORDER BY $tbl_schulen[name];";
			}
			$result = mysql_query($sql);
			
			if ($row = mysql_fetch_assoc($result)){
			
			echo "<table cellpadding='5' cellspacing='0'>
					<tr>
						<th>Schulname</th>
						<th>Ansprechpartner</th>
						<th></th>
					</tr>";

			$i = 1;
			echo "<tr class=\"row".$i."\">";
			echo "<td>".$row[$tbl_schulen['name']]."</td>";
			echo "<td>".$row[$tbl_schulen['ansprechpartner']]."</td>";
			echo "<td><a href=\"index.php?content=".$self."&p=".md5('details')."&s_id=".$row[$tbl_schulen['id']]."\">
			".getIcon('details')."
			</a> </td>";
			echo "<td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_schulen['id']]."\">
			".getIcon('bearbeiten')."
			</a> </td></tr>";			
			
			while($row = mysql_fetch_assoc($result)) {
                $i = ($i - 1) * ($i - 1);
				echo "<tr class=\"row".$i."\">";
                echo "<td>".$row[$tbl_schulen['name']]."</td>";
                echo "<td>".$row[$tbl_schulen['ansprechpartner']]."</td>";
                echo "<td><a href=\"index.php?content=".$self."&p=".md5('details')."&s_id=".$row[$tbl_schulen['id']]."\">
                ".getIcon('details')."
                </a> </td>";
                echo "<td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_schulen['id']]."\">
                ".getIcon('bearbeiten')."
                </a> </td></tr>";
            }
			echo "</table>";
			} else{
				echo "Es sind keine Eintr&auml;ge vorhanden.";			
			}
			
} elseif($page==md5('bearbeiten')) { 
	checkRights(200,$user);
	$sql = "SELECT *
			FROM `$tbl_schulen[tbl]`
			WHERE $tbl_schulen[id] LIKE '".$_GET['s_id']."'
			";
	$result = mysql_query($sql);		
	$row = mysql_fetch_assoc($result);
	
	$kuendigung = $row['kuendigung'];

	
?>
	<h2>Schule &auml;ndern</h2>
		
			<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>" method="post" name="school_form" id="school_form">
				<fieldset>
					<legend>Schuldaten</legend>
					<input type="hidden" name="s_id" id="s_id" value="<?php echo $row[$tbl_schulen['id']];?>"/>
					
					<label for="s_name" >Name:</label>
					<input maxlength="50" type="text" name="s_name" id="s_name" value="<?php echo $row[$tbl_schulen['name']];?>"/>
					<br /> 
					
					<label for="s_strasse" >Stra&szlig;e:</label>
					<input maxlength="50" type="text" name="strasse" id="strasse" value="<?php echo $row[$tbl_schulen['strasse']];?>"/>
					<br /> 
					
					<?php echo getPlzForm("plz","ort", $row[$tbl_schulen['plz']]); ?><br />
		
					<label for="s_telefon" >Telefon:</label>
					<input maxlength="50" type="text" name="s_telefon" id="s_telefon" value="<?php echo $row[$tbl_schulen['telefon']];?>"/>
					<br />
					
					<label for="s_ansprechpartner" >Ansprechpartner:</label>
					<input type="text" name="s_ansprechpartner" id="s_ansprechpartner" value="<?php echo $row[$tbl_schulen['ansprechpartner']];?>"/>
					<br />
					
					<label for="s_kuerzel" >Kürzel:</label>
					<input type="text" name="s_kuerzel" id="s_kuerzel" value="<?php echo $row[$tbl_schulen['kuerzel']];?>"/>
					<br />					
				</fieldset>
				<br />	
				
				<fieldset>
					<legend>Allgemeine Einstellungen</legend>
					<table>
						<tr>
							<th>Tag</th>
							<th>Betreuung bis</th>
							<th>Essen</th>
						</tr>
						<tr>
							<td>Montag</td>
							<td><input type="checkbox" name="mon_betreuung" id="mon_betreuung" <?php if($row['ba_mon']) echo 'checked="checked"'; ?> value="1" onchange="display_betreuung('mon');"/>&nbsp;&nbsp;&nbsp;
							<span id="mon"><?php echo getTimeBox("mon_hour", "mon_minute", $row[$tbl_schulen['time_mon']]); ?></span></td>
							<td><select name="mon_launch"><option value="1" <?php if($row[$tbl_schulen['launch_mon']]) echo "selected";  ?>>Ja</option><option value="0" <?php if(!$row[$tbl_schulen['launch_mon']]) echo "selected";  ?>>Nein</option></select></td>
						</tr>
						<tr>
							<td>Dienstag</td>
							<td><input type="checkbox" name="tue_betreuung" id="tue_betreuung" <?php if($row['ba_tue']) echo 'checked="checked"'; ?> value="1" onchange="display_betreuung('tue');"/>&nbsp;&nbsp;&nbsp;
							<span id="tue"><?php echo getTimeBox("tue_hour", "tue_minute", $row[$tbl_schulen['time_tue']]); ?></span></td>
							<td><select name="tue_launch"><option value="1" <?php if($row[$tbl_schulen['launch_tue']]) echo "selected";  ?>>Ja</option><option value="0" <?php if(!$row[$tbl_schulen['launch_tue']]) echo "selected";  ?>>Nein</option></select></td>
						</tr>
						<tr>
							<td>Mittwoch</td>
							<td><input type="checkbox" name="wen_betreuung" id="wen_betreuung" <?php if($row['ba_wen']) echo 'checked="checked"'; ?> value="1" onchange="display_betreuung('wen');"/>&nbsp;&nbsp;&nbsp;
							<span id="wen"><?php echo getTimeBox("wen_hour", "wen_minute", $row[$tbl_schulen['time_wen']]); ?></span></td>
							<td><select name="wen_launch"><option value="1" <?php if($row[$tbl_schulen['launch_wen']]) echo "selected";  ?>>Ja</option><option value="0" <?php if(!$row[$tbl_schulen['launch_wen']]) echo "selected";  ?>>Nein</option></select></td>
						</tr>
						<tr>
							<td>Donnerstag</td>
							<td><input type="checkbox" name="thu_betreuung" id="thu_betreuung" <?php if($row['ba_thu']) echo 'checked="checked"'; ?> value="1" onchange="display_betreuung('thu');"/>&nbsp;&nbsp;&nbsp;
							<span id="thu"><?php echo getTimeBox("thu_hour", "thu_minute", $row[$tbl_schulen['time_thu']]); ?></span></td>
							<td><select name="thu_launch"><option value="1" <?php if($row[$tbl_schulen['launch_thu']]) echo "selected";  ?>>Ja</option><option  value="0" <?php if(!$row[$tbl_schulen['launch_thu']]) echo "selected";  ?>>Nein</option></select></td>
						</tr>
						<tr>
							<td>Freitag</td>
							<td><input type="checkbox" name="fri_betreuung" id="fri_betreuung" <?php if($row['ba_fri']) echo 'checked="checked"'; ?> value="1" onchange="display_betreuung('fri');"/>&nbsp;&nbsp;&nbsp;
							<span id="fri"><?php echo getTimeBox("fri_hour", "fri_minute", $row[$tbl_schulen['time_fri']]); ?></span></td>
							<td><select name="fri_launch"><option value="1" <?php if($row[$tbl_schulen['launch_fri']]) echo "selected";  ?>>Ja</option><option  value="0" <?php if(!$row[$tbl_schulen['launch_fri']]) echo "selected";  ?>>Nein</option></select></td>
						</tr>
					</table>
				
					<br/>
					<label for="preisproessen" >Preis pro Essen:</label>
					<input maxlength="50" type="text" name="preisproessen" id="preisproessen"  value="<?php echo $row['essenspreis']; ?>"/>
					<br /> 
					
					<label for="traeger" >Tr&auml;ger:</label>
					<input type="text" name="traeger" id="traeger"  value="<?php echo $row['traeger']; ?>"/>
					<br />
					
				</fieldset>
								
				<fieldset>
					<legend>Verdiensttabelle</legend>
					<table id="verdienst_table">
						<tr>
							<th></th>
							<th>Bruttoeinkommen</th>
							<th>Beitrag</th>
							<th>Essen f&uuml;r 1 &euro;</th>
						</tr>
						<?php
							
							$sql = "SELECT *
									FROM `verdienst`
									WHERE schul_id=$_GET[s_id]
									ORDER BY v_verdienst ASC, `from` ASC";
							$result = mysql_query($sql);
							$check = mysql_fetch_array($result);
							
							if(count($check['v_id'])){
								echo '<input type="hidden" name="v_action" value="v_update" />';
								$i=0;
								$sql = "SELECT *
										FROM `verdienst`
										WHERE schul_id=$_GET[s_id]
										ORDER BY v_verdienst ASC, `from` ASC";
								$result = mysql_query($sql);
								while($row = mysql_fetch_array($result)){
									$i++;
									
									$bis="";
									$mehr="";
									$essen="";
									
									if(!$row['from']){
										$bis = 'selected="selected"';
									}
									if($row['from']){
										$mehr = 'selected="selected"';
									}
									if($row['sozi']){
										$essen = 'checked="checked"';
									}
									echo "
									<tr class='verdienst'>
										<td>
											<input type='hidden' name='v_id[]' value='$row[v_id]' />
											<select name='from[]'>
												<option $bis value='0'>Bis</option>
												<option $mehr value='1'>Mehr</option>
											</select>
										</td>
										<td><input type='text' value='$row[v_verdienst]' name='verdienst[]' /></td>
										<td><input type='text' value='$row[beitrag]' name='beitrag[]' /></td>
										<td><input type='checkbox' value='1' $essen name='essen[]' /></td>
									</tr>
									";
									
									$bis = "";
									$mehr = "";
									$essen = "";
								}
								echo "
									<tr class='showVerdienstTr' style='display: none;'>
										<td>
											<input type='hidden' name='v_id[]' value='' />
											<select name='from[]' id='from'>
												<option value='0'>Bis</option>
												<option value='1'>Mehr</option>
											</select>
										</td>
										<td><input type='text' value='' name='verdienst[]' id='verdienst' /></td>
										<td><input type='text' value='' name='beitrag[]' id='beitrag' /></td>
										<td><input type='checkbox' value='1' name='essen[]' id='essen' /></td>
									</tr>
									";
								echo '
										<tr>
											<td>
												<a class="add" href="javascript:void(0);" onclick="getVerdienstInput();" >Hinzuf&uuml;gen</a>
												<a class="add" style="display:none;" href="javascript:void(0);" onclick="saveVerdienstInput();">Speichern</a>
											<td>
										<tr>
										';
							}else{
									echo '<input type="hidden" name="v_action" value="v_insert" />';
						?>
									<tr>
										<td>
											<select name="from[]">
												<option value="0">Bis</option>
											</select>
										</td>
										<td><input type="text" value="" name="verdienst[]" /></td>
										<td><input type="text" value="" name="beitrag[]" /></td>
										<td><input type="checkbox" value="1" name="essen[]" /></td>
									</tr>
									<?php
									for($i=0;$i<5;$i++){
										echo '
												<tr>
													<td>
														<select name="from[]">
															<option value="0">bis</option>
															<option value="1">ab</option>
														</select>
													</td>
													<td><input type="text" value="" name="verdienst[]" /></td>
													<td><input type="text" value="" name="beitrag[]" /></td>
													<td><input type="checkbox" value="1"  name="essen[]" /></td>
												</tr>
											';
										}
							}
						?>
					</table>
				</fieldset>
				
				<?php
				$arbeitsplatz = false;
				$einkommen = false;
				$umzug = false;
				$haerte = false;
				
				if($kuendigung >= 8){
					$kuendigung -= 8;
					$haerte = true;
				}
				
				if($kuendigung >= 4){
					$kuendigung -= 4;
					$umzug= true;
				}
		
				if($kuendigung >= 2){
					$kuendigung -= 2;
					$einkommen = true;
				}
		
				if($kuendigung >= 1){
					$kuendigung -= 1;
					$arbeitsplatz = true;
				}
				?>
				
				
				<fieldset>
					<legend>K&uuml;ndigungsgr&uuml;nde</legend>
					<table>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="1" <?php if($arbeitsplatz){echo 'checked="checked"';}?>></td>
							<td><label>Verlust des Arbeitsplatzes</label></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="2" <?php if($einkommen){echo 'checked="checked"';}?>></td>
							<td><label>Reduzierung des Einkommens um 20%</label></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="4" <?php if($umzug){echo 'checked="checked"';}?>></td>
							<td><label>Umzug mit Schulwechsel</label></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="8" <?php if($haerte){echo 'checked="checked"';}?>></td>
							<td><label>unzumutbare Härte</label></td>
						</tr>					
					</table>
				</fieldset>
				
				<input type="submit" name="send" value="&Auml;ndern" /> 
			</form>

<?php
} elseif($page==md5('eintragen')) {
	checkRights(200,$user);
	
		$_POST['mon_betreuung'] = isset($_POST['mon_betreuung']) ? $_POST['mon_betreuung'] : '0';
		$_POST['tue_betreuung'] = isset($_POST['tue_betreuung']) ? $_POST['tue_betreuung'] : '0';
		$_POST['wen_betreuung'] = isset($_POST['wen_betreuung']) ? $_POST['wen_betreuung'] : '0';
		$_POST['thu_betreuung'] = isset($_POST['thu_betreuung']) ? $_POST['thu_betreuung'] : '0';
		$_POST['fri_betreuung'] = isset($_POST['fri_betreuung']) ? $_POST['fri_betreuung'] : '0';
	
		$sql = "UPDATE `$tbl_schulen[tbl]`
				SET 
				$tbl_schulen[name]='$_POST[s_name]',
				$tbl_schulen[strasse]='$_POST[strasse]',
				$tbl_schulen[plz]='$_POST[ort]',
				$tbl_schulen[telefon]='$_POST[s_telefon]',
				$tbl_schulen[ansprechpartner]='$_POST[s_ansprechpartner]',
				$tbl_schulen[kuerzel]='$_POST[s_kuerzel]',
				$tbl_schulen[time_mon]='$_POST[mon_hour]:$_POST[mon_minute]', 
				$tbl_schulen[time_tue]='$_POST[tue_hour]:$_POST[tue_minute]', 
				$tbl_schulen[time_wen]='$_POST[wen_hour]:$_POST[wen_minute]', 
				$tbl_schulen[time_thu]='$_POST[thu_hour]:$_POST[thu_minute]', 
				$tbl_schulen[time_fri]='$_POST[fri_hour]:$_POST[fri_minute]', 
				$tbl_schulen[launch_mon]='$_POST[mon_launch]',
				$tbl_schulen[launch_tue]='$_POST[tue_launch]',
				$tbl_schulen[launch_wen]='$_POST[wen_launch]',
				$tbl_schulen[launch_thu]='$_POST[thu_launch]',
				$tbl_schulen[launch_fri]='$_POST[fri_launch]',
				ba_mon='$_POST[mon_betreuung]',
				ba_tue='$_POST[tue_betreuung]', 
				ba_wen='$_POST[wen_betreuung]', 
				ba_thu='$_POST[thu_betreuung]', 
				ba_fri='$_POST[fri_betreuung]', 
				essenspreis='$_POST[preisproessen]',
				kuendigung = ".array_sum($_POST['kuendigung']).",
				traeger = '$_POST[traeger]'
				WHERE
				$tbl_schulen[id]='$_POST[s_id]'
				";
			mysql_query($sql);
			
			$sql_class = new Sql;
			$sql_class->insertLog("schulen",$_POST['s_id'],$sql);
			
			if($_POST['v_action'] == "v_update"){
			
			
				for($i=0;$i < count($_POST['verdienst']) ;$i++){
					$_POST['essen'][$i] = isset($_POST['essen'][$i]) ? $_POST['essen'][$i] : 0;
					$sql = "
							UPDATE `verdienst` 
							SET 
							`beitrag` 		= '".$_POST['beitrag'][$i]."' ,
							`from` 			= ".$_POST['from'][$i].",
							`v_verdienst`	= '".$_POST['verdienst'][$i]."' ,
							`sozi`	= '".$_POST['essen'][$i]."' 
							WHERE v_id = ".$_POST['v_id'][$i]." ;
					";
					mysql_query($sql);
				}
			}else if($_POST['v_action'] == "v_insert"){
				$schul_id = $_POST['s_id'];
				$sql_class->insertVerdienst($_POST['verdienst'],$_POST['beitrag'],$_POST['from'],$_POST['essen'], $schul_id);
			} 	
		
		echo "<h2>Schule ge&auml;ndert</h2>";
		
?>	
		<a class="button" href="index.php?content=schule_neu">Schule erstellen</a><br />
		<br /><br />
		<a href="index.php?content=schulen_verwalten">Alle</a>&nbsp; 
<?php 
    	for($i=65;$i <= 89;$i++){
				echo "<a href=\"index.php?content=schulen_verwalten&b=". chr($i) ."\">&nbsp;".  chr($i) ."&nbsp;</a>&nbsp;";
		}
		echo "<br /><br />";
?>

		<table>
			<tr>
				<th>Schulname</th>
				<th>Ansprechpartner</th>
				<th></th>
			</tr>
		<?php
		
		    if(isset($_GET['b']) AND strlen($_GET['b'])==1){
			$sql = "SELECT *  FROM `$tbl_schulen[tbl]`
					WHERE `$tbl_schulen[schulname]` LIKE '".$_GET['b']."%'
					AND `$tbl_schulen[id]` != 0
					ORDER BY `$tbl_schulen[schulname]`";
			}
			else{
			$sql = "SELECT *  FROM `$tbl_schulen[tbl]` 
					WHERE `$tbl_schulen[id]` != 0
					ORDER BY $tbl_schulen[name];";
			}
			
			$result = mysql_query($sql);
			while($row = mysql_fetch_assoc($result)) {
                echo "<tr><td>".$row[$tbl_schulen['name']]."</td>";
                echo "<td>".$row[$tbl_schulen['ansprechpartner']]."</td>";
                echo "<td><a href=\"index.php?content=".$self."&p=".md5('details')."&s_id=".$row[$tbl_schulen['id']]."\">
                <img src=\"".$icon_path."search_48.png\" height=\"28\" width=\"28\" alt=\"details\">
                </a> </td>";
                echo "<td><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&s_id=".$row[$tbl_schulen['id']]."\">
                <img src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\">
                </a> </td></tr>";
            }
?>
	</table>
<?php 		
} elseif($page==md5('details')) {
	checkRights(200,$user);
	echo "<h2>Details</h2>";
	
	$sql = "SELECT *
			FROM `$tbl_schulen[tbl]`, $tbl_orte[tbl]
			WHERE $tbl_schulen[id] LIKE '$_GET[s_id]'
			AND $tbl_orte[id] = $tbl_schulen[plz]
			;";
	$result = mysql_query($sql);		
	$row = mysql_fetch_assoc($result);
	
?>
	<label  >Name:</label>
	<?php echo $row[$tbl_schulen['name']];?>
	<br /> 
	
	<label  >Stra&szlig;e:</label>
	<?php echo $row[$tbl_schulen['strasse']];?>
	<br /> 
	
	<label >PLZ:</label>
	<?php echo $row[$tbl_orte['plz']];?>
	<br /> 

	<label >Ort:</label>
	<?php echo $row[$tbl_orte['ort']];?>
	<br /> 	
	
	<label >Telefon:</label>
	<?php echo $row[$tbl_schulen['telefon']];?>
	<br /> 
	
	<label >Ansprechpartner:</label>
	<?php echo $row[$tbl_schulen['ansprechpartner']];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="index.php?content=schulen_verwalten&p=<?=md5('bearbeiten')?>&s_id=<?=$row[$tbl_schulen['id']]?>">
	<img src="<?=$icon_path?>pencil_48.png" height="28" width="28" alt="bearbeiten" title="bearbeiten">
	</a>
<?php
}
?>
