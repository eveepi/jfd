<?php
checkRights(200,$user);
?>
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
	
	echo "<h1> Neue Schule anlegen </h1>";
	
    $page = isset($_GET['p']) ? $_GET['p'] : md5('anlegen');
    $self = 'schule_neu';
    
    if($page==md5('eintragen')) {

		
		$_POST['mon_betreuung'] = isset($_POST['mon_betreuung']) ? $_POST['mon_betreuung'] : '0';
		$_POST['tue_betreuung'] = isset($_POST['tue_betreuung']) ? $_POST['tue_betreuung'] : '0';
		$_POST['wen_betreuung'] = isset($_POST['wen_betreuung']) ? $_POST['wen_betreuung'] : '0';
		$_POST['thu_betreuung'] = isset($_POST['thu_betreuung']) ? $_POST['thu_betreuung'] : '0';
		$_POST['fri_betreuung'] = isset($_POST['fri_betreuung']) ? $_POST['fri_betreuung'] : '0';
		
		$sql_class = new Sql;
		$schul_id = $sql_class->insertSchule( $_POST['username'], $_POST['schulname'], $_POST['strasse'], $_POST['ort'], $_POST['telefon'], $_POST['email'], $_POST['ansprechpartner'], $_POST['pwd1'], 
											  $_POST['mon_hour'], $_POST['mon_minute'], $_POST['tue_hour'], $_POST['tue_minute'], $_POST['wen_hour'], $_POST['wen_minute'], $_POST['thu_hour'], $_POST['thu_minute'], $_POST['fri_hour'], $_POST['fri_minute'], 
											  $_POST['mon_launch'], $_POST['tue_launch'], $_POST['wen_launch'], $_POST['thu_launch'], $_POST['fri_launch'], 
											  $_POST['mon_betreuung'], $_POST['tue_betreuung'], $_POST['wen_betreuung'], $_POST['thu_betreuung'], $_POST['fri_betreuung'], 
											  $_POST['preisproessen'], array_sum($_POST['kuendigung']), $_POST['traeger'], $_POST['kuerzel']);
								  
		$sql_class->insertVerdienst($_POST['verdienst'],$_POST['beitrag'],$_POST['from'],$_POST['essen'],$schul_id);
	        
			
	        if ( $schul_id == 0) {
				echo "<h2>Fehler</h2>";
				echo "<p>Die Schule konnte nicht angelegt werden. Der Name für den Mitarbeiter der Schule ist schon vergeben.";
			}
			else {
				echo "<h2>Eingetragen</h2>";
				echo "<p>Die Schule wurde erfolgreich angelegt.</p>";
			}
	
    }	
    if($page==md5('anlegen')) {
	?>
			<form action="index.php?content=<?php echo $self;?>&p=<?php echo md5('eintragen');?>" name="school_form" id="school_form" method="post">
				
				<fieldset>
					<legend>Schuldaten</legend>
					<label for="username" >Username:</label>
					<input maxlength="50" type="text" name="username" id="username" />
					<br /> 
					
					<label for="schulname" >Schulname:</label>
					<input maxlength="50" type="text" name="schulname" id="schulname" />
					<br /> 
	
					<label for="telefon" >Telefon:</label>
					<input maxlength="50" type="text" name="telefon" id="telefon"  />
					<br /> 
					
					<label for="email" >E-Mail:</label>
					<input maxlength="50" type="text" name="email" id="email"  />
					<br /> 
	
					<label for="strasse" >Stra&szlig;e:</label>
					<input maxlength="50" type="text" name="strasse" id="strasse"  />
					<br /> 
					
					<?php echo getPlzForm("plz","ort"); ?><br />
					
					<label for="ansprechpartner" >Ansprechpartner:</label>
					<input maxlength="50" type="text" name="ansprechpartner" id="ansprechpartner" />
					<br /> 

					<label for="kuerzel" >Kürzel:</label>
					<input maxlength="50" type="text" name="kuerzel" id="kuerzel" />
					<br /> 
					
					<label for="pwd1" >Passwort:</label>
					<input maxlength="30" type="password" name="pwd1" id="pwd1" value=""/>
					<br />		
							
					<label for="pwd2" >Passwort Wiederholen:</label>
					<input maxlength="30" type="password" name="pwd2" id="pwd2" value=""/>
					<br />	
				</fieldset>
				
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
							<td><input type="checkbox" name="mon_betreuung" id="mon_betreuung" checked="checked" value="1" onchange="display_betreuung('mon');"/>&nbsp;&nbsp;&nbsp;
							<span id="mon"><?php echo getTimeBox("mon_hour", "mon_minute"); ?></span></td>
							<td><select name="mon_launch"><option value="1">Ja</option><option value="0">Nein</option></select></td>
						</tr>
						<tr>
							<td>Dienstag</td>
							<td><input type="checkbox" name="tue_betreuung" id="tue_betreuung" checked="checked" value="1" onchange="display_betreuung('tue');"/>&nbsp;&nbsp;&nbsp;
							<span id="tue"><?php echo getTimeBox("tue_hour", "tue_minute"); ?></span></td>
							<td><select name="tue_launch"><option value="1">Ja</option><option value="0">Nein</option></select></td>
						</tr>
						<tr>
							<td>Mittwoch</td>
							<td><input type="checkbox" name="wen_betreuung" id="wen_betreuung" checked="checked" value="1" onchange="display_betreuung('wen');"/>&nbsp;&nbsp;&nbsp;
							<span id="wen"><?php echo getTimeBox("wen_hour", "wen_minute"); ?></span></td>
							<td><select name="wen_launch"><option value="1">Ja</option><option value="0">Nein</option></select></td>
						</tr>
						<tr>
							<td>Donnerstag</td>
							<td><input type="checkbox" name="thu_betreuung" id="thu_betreuung" checked="checked" value="1" onchange="display_betreuung('thu');"/>&nbsp;&nbsp;&nbsp;
							<span id="thu"><?php echo getTimeBox("thu_hour", "thu_minute"); ?></span></td>
							<td><select name="thu_launch"><option value="1">Ja</option><option  value="0">Nein</option></select></td>
						</tr>
						<tr>
							<td>Freitag</td>
							<td><input type="checkbox" name="fri_betreuung" id="fri_betreuung" checked="checked" value="1" onchange="display_betreuung('fri');"/>&nbsp;&nbsp;&nbsp;
							<span id="fri"><?php echo getTimeBox("fri_hour", "fri_minute"); ?></span></td>
							<td><select name="fri_launch"><option value="1">Ja</option><option  value="0">Nein</option></select></td>
						</tr>
					</table>
				
					<br/>
					<label for="preisproessen" >Preis pro Essen:</label>
					<input type="text" name="preisproessen" id="preisproessen"  value=""/>
					<br /> 
					
					<label for="traeger" >Tr&auml;ger:</label>
					<input type="text" name="traeger" id="traeger"  value="Stadt Rheine"/>
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
						<tr>
							<td>
								<select name="from[]">
									<option value="0">bis</option>
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
											<td><input type="checkbox" value="1" name="essen[]" /></td>
										</tr>
									';
							}
						?>
						
					</table>
				</fieldset>
			
				<fieldset>
					<legend>K&uuml;ndigungsgr&uuml;nde</legend>
					<table>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="1" checked="checked"></td>
							<td><label>Verlust des Arbeitsplatzes</label></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="2" checked="checked"></td>
							<td><label>Reduzierung des Einkommens um 20%</label></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="4" checked="checked"></td>
							<td><label>Umzug mit Schulwechsel</label></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="kuendigung[]" value="8" checked="checked"></td>
							<td><label>unzumutbare Härte</label></td>
						</tr>					
					</table>
				</fieldset>
				<br /> 
				
				<input type="submit" class="formular" value="Weiter" /> 
			</form>
	
<?php    
    } 
?>	