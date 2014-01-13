<?php
	checkRights(50, $user);
?>	
<script type="text/javascript">
	$(document).ready(function() {
		
		$("#schueler").validate({
				rules: {
					s_nachname:{
						required	: true,
					},
					s_vorname:{
						required	: true,
					},
					s_strasse:{
						required	: true,
					},
					s_plz:{
						required	: true,
						minlength	: 5	
					},
				},
				messages:{
					s_nachname:{
						required	: "Bitte einen Nachnamen angeben!"
					},
					s_vorname:{
						required	: "Bitte einen Vornamen angeben!"
					},
					s_strasse:{
						required	: "Bitte Stra&szlig;e und Hausnummer angeben!"
					},
					s_plz:{
						required	: "Bitte eine Postleitzahl angeben!",
						minlength	: "Postleitzahl muss mindestens 5 Zeichen haben!"
					},
				}
			}
		);
	});
</script>	
	
<?php	
    $page = isset($_GET['p']) ? $_GET['p'] : md5('anlegen');
	$self = 'schueler_neu';
    echo "<h1>Sch&uuml;ler anlegen</h1>";
    
    
    $_SESSION['nachname'] = isset($_SESSION['nachname']) ? $_SESSION['nachname'] : '';
    $_SESSION['vorname'] = isset($_SESSION['vorname']) ? $_SESSION['vorname'] : '';
    $_SESSION['strasse'] = isset($_SESSION['strasse']) ? $_SESSION['strasse'] : '';
    $_SESSION['plz'] = isset($_SESSION['plz']) ? $_SESSION['plz'] : '';
    $_SESSION['ort'] = isset($_SESSION['ort']) ? $_SESSION['ort'] : '';
    $_SESSION['tag'] = isset($_SESSION['tag']) ? $_SESSION['tag'] : '';
	$_SESSION['monat'] = isset($_SESSION['monat']) ? $_SESSION['monat'] : '';
	$_SESSION['jahr'] = isset($_SESSION['jahr']) ? $_SESSION['jahr'] : '';

    if($page==md5('anlegen')) {
	?>
		<h2>Neuer Sch&uuml;ler</h2>
		<fieldset>
		
		
		
			<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>" id="schueler" method="post">
				<label for="s_nachname" >Nachname:</label>
				<input maxlength="20" type="text" name="s_nachname" id="s_nachname" value="<?php echo $_SESSION['nachname'];?>"/><br /> 
				
				<label for="s_vorname" >Vorname:</label>
				<input maxlength="20" type="text" name="s_vorname" id="s_vorname" value="<?php echo $_SESSION['vorname'];?>"/><br /> 
				
				<label for="s_strasse" >Stra&szlig;e / Nr.:</label>
				<input maxlength="30" type="text" name="s_strasse" id="s_strasse" value="<?php echo $_SESSION['strasse'];?>"/><br />
				
				<?php echo getPlzForm("s_plz","k_ort", $_SESSION['ort']); ?><br />
				
				<?php echo getKlassenForm("s_klasse"); ?><br />
				<label for="s_geburtsdatum" >Geburtsdatum:</label>
				
				<?php
					echo getDateBox("s");
				?>
				<br />
				<input type="submit" class="formular" value="Absenden" /> 
			</form>
		</fieldset>
	
	<?php

    } elseif($page==md5('eintragen')) {
    
    	$_SESSION['nachname'] = $_POST['s_nachname'];
		$_SESSION['vorname'] = $_POST['s_vorname'];
		$_SESSION['strasse'] = $_POST['s_strasse'];
		$_SESSION['plz'] = $_POST['s_plz'];
		$_SESSION['ort'] = $_POST['k_ort'];
		$_SESSION['tag'] = $_POST['s_day'];
		$_SESSION['monat'] = $_POST['s_month'];
		$_SESSION['jahr'] = $_POST['s_year'];
		

		$sql="
			INSERT INTO `$tbl_schueler[tbl]` 
			( `$tbl_schueler[id]` , `$tbl_schueler[vorname]` , `$tbl_schueler[name]` , `$tbl_schueler[strasse]` , `$tbl_schueler[plz]` , `$tbl_schueler[geburtsdatum]`, `$tbl_schueler[schul_id]` )
			VALUES (NULL , '".$_SESSION['vorname']."', '".$_SESSION['nachname']."', '".$_SESSION['strasse']."', '".$_SESSION['ort']."', '".$_SESSION['jahr']."-".$_SESSION['monat']."-".$_SESSION['tag']."', '".$_SESSION['schul_id']."'
			);";

		$result=mysql_query($sql);

		
		if (!$result) {
			echo "<h2>Fehler</h2>";
			echo "<p>Der Sch&uuml;ler konnte nicht angelegt werden, <br />bitte kontaktieren Sie den Systemadministrator und leiten sie den folgenden Text an ihn weiter:<br />
			".$sql."</p>";
			}else {
				echo "<h2>Eingetragen</h2>";
				echo "<p>Der Sch&uuml;ler wurde erfolgreich eingetragen.</p>";
				
				$sql_class = new Sql;
				
				$_SESSION['s_id'] = $sql_class->getLastId();				
				
				$sql_class->insertLog("schueler",$_SESSION['s_id'],$sql);
			
			}
			$_SESSION['vorname'] = '';
			$_SESSION['nachname'] = '';
			$_SESSION['tag'] = '';
			$_SESSION['monat'] = '';
			$_SESSION['jahr'] = '';
			$_SESSION['strasse']= '';
			$_SESSION['plz'] = '';
			$_SESSION['ort'] = '';
		}