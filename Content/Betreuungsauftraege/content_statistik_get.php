<?php 
	$filename = md5('tmp')."/".$_SESSION['schul_id']."_statistik.csv";
?>

<style>
	div#header {
		display:none;	
	}
	div#navi {
		display:none;
	}
	div#footer {
		display:none;
	}
	div#content{
		margin: 5px;
	}
	div#footer {
		display:none;
	}
</style>
<script>
	function show_info(){
		$("#info").text("Sie haben 60 Sekunden Zeit zum Herunterladen der Datei, danach wird diese aus Sicherheitsgründen gelöscht.");
	}
	
	$(document).ready(function () {
		show_info();
		$.post("Content/ajax/delete_file.php", 
			{ filename: '<?php echo $filename; ?>'},
			function(data){ 
				$("#download").css("display", "none");
				$("#info").html(data);
			}
		);
	});
</script>

<?php
	checkRights(50, $user);

	if(date("m") < 8){
		$tmp = date("Y");
	}else {
		$tmp = date("Y") + 1;
	}

	$_POST['year'] 	= (isset($_POST['year'])) ? $_POST['year'] : $tmp;

    $all_schools = (isset($_POST['all_schools']) && checkRights(200, $user, 1)) ? $_POST['all_schools'] : 0;
    $mealo = (isset($_POST['mealo']) && checkRights(200, $user, 1)) ? $_POST['mealo'] : 0;
    
    
    $page = isset($_GET['p']) ? $_GET['p'] : md5('get');
	$self = 'statistik_get';
    echo "<h1>Statistik herunterladen</h1>";
	
	$zeitraum = "Schuljahr ".($_POST['year']-1)."/".$_POST['year'];
	
	$end = date($_POST['year']."-07-31");
	$begin = date(($_POST['year']-1)."-08-01");
	
	$file = fopen($filename, "w");	
	fwrite($file, "\xEF\xBB\xBF");
		
	if($all_schools){
		fwrite($file, "Zeitraum:;".$zeitraum.";\n;;\n");
		fwrite($file, ";Schüler-ID;Vorname;Nachname;Schule;Klasse;Beitragsgruppe;Ferienbetreuung;Essen;Bezahlung;SEPA;Anfang;Ende;Besonderheiten;\n");
	}	
	
	$sql = "SELECT * FROM $tbl_schulen[tbl]
			WHERE $tbl_schulen[id] = $_SESSION[schul_id]
			OR ($all_schools 
				AND $tbl_schulen[id] NOT IN(0,49))"; // JFD und Testschule ausschließen 
	$result_schools = mysql_query($sql);		
	$y = 0;
	while($school = mysql_fetch_assoc($result_schools)){
	  	
		$sql = "SELECT betreuungsauftraege.*, schueler.*, bankverbindungen.*, erziehungsberechtigte.*, klassen.*, plz_schueler.o_plz AS schueler_plz,
					plz_schueler.o_ort AS schueler_ort, plz_erziehungsberechtigte.o_ort AS erziehungsberechtigte_ort, plz_erziehungsberechtigte.o_plz AS erziehungsberechtigte_plz
				FROM betreuungsauftraege
					INNER JOIN schueler ON betreuungsauftraege.ba_schueler_ID = schueler.s_id
					INNER JOIN erziehungsberechtigte ON schueler.s_e_id = erziehungsberechtigte.e_id
					LEFT JOIN klassen ON schueler.s_klassen_id = klassen.k_id
					LEFT JOIN bankverbindungen ON bankverbindungen.b_id = betreuungsauftraege.ba_bankverbindungs_id 
					LEFT JOIN orte AS plz_schueler ON schueler.s_plz = plz_schueler.o_id
					LEFT JOIN orte AS plz_erziehungsberechtigte ON erziehungsberechtigte.e_plz = plz_erziehungsberechtigte.o_id
				WHERE betreuungsauftraege.ba_schul_id = $school[schul_id]
					AND ba_anfang < '$end'
					AND ba_ende > '$begin'
					AND ba_status = 1
				ORDER BY s_name, s_vorname";
				
		$result = mysql_query($sql);
		
		// verdiensttabelle auslesen
		$sql_verdienst =   "SELECT *
							FROM `verdienst`
							WHERE schul_id = $school[schul_id]
							ORDER BY v_verdienst ASC,`from` ASC";
		
		if(!$all_schools){
			fwrite($file, $school[$tbl_schulen['name']].";\nZeitraum:;".$zeitraum.";\n;;\n");
			fwrite($file, ";Schüler-ID;Schüler Vorname;Schüler Nachname;Klasse;Beitragsgruppe;Ferienbetreuung;Essen;Bezahlung;SEPA;Anfang;Ende;Besonderheiten;");
			if(mealo){
				fwrite($file, "Schüler-Strasse;Schüler PLZ;Schüler Ort;Erziehungsberechtiger Vorname;Erziehungsberechtiger Nachname;Erziehungsberechtiger Strasse;Erziehungsberechtiger PLZ;Erziehungsberechtiger Ort;Erziehungsberechtiger Telefon;Erziehungsberechtiger Telefon2;Essenstage");
			}
			fwrite($file, "\n");
		}

		while($row = mysql_fetch_assoc($result)){

			$y++;
			fwrite($file, $y.";".$row[$tbl_betreuungsauftraege['schueler_id']].";");	
			fwrite($file, $row[$tbl_schueler['vorname']].";");
			fwrite($file, $row[$tbl_schueler['name']].";");
			if($all_schools){
				fwrite($file, $school['schul_name'].";");
			}
			
			
			fwrite($file, $row['k_bezeichnung'].";");
			
			$verdienst_result = mysql_query($sql_verdienst);
			$i = 1;
			while($row_v = mysql_fetch_assoc($verdienst_result)){
				if($row['ba_jahreseinkommen'] <= $row_v['v_verdienst']){
					fwrite($file, $i);
					break;
				}elseif($row_v['from'] == 1){
					fwrite($file, $i);
					break;
				}
				$i++;
			}
			fwrite($file, ";");
	
			if ($row['ba_ferien']){
				fwrite($file, "ja;");
			}else{
				fwrite($file, "nein;");
			}
			
			if ($row['ba_essenstage']){
				fwrite($file, "ja;");
			}else{
				fwrite($file, "nein;");
			}
			
			fwrite($file, ucfirst($row['b_methode']).";");
			
			fwrite($file, "KIJU-".$school[$tbl_schulen['kuerzel']]."-".$row[$tbl_betreuungsauftraege['schueler_id']].";");
			
			//if($row['ba_anfang'] > $begin){
				fwrite($file, "Ab ".date("d.m.Y",strtotime($row['ba_anfang'])).";");
			//}
			//if($row['ba_ende'] < $end){
				fwrite($file, "Bis ".date("d.m.Y",strtotime($row['ba_ende'])).";");
			//}
			
			if($row['ba_status'] == 0){
				fwrite($file, "Vertrag ist nicht aktiviert.;");
			}else{
				fwrite($file, ";");
			}
		
			if(mealo){
				fwrite($file, $row['s_strasse'].";");
				fwrite($file, $row['schueler_plz'].";");
				fwrite($file, $row['schueler_ort'].";");
				fwrite($file, $row['e_vorname'].";");
				fwrite($file, $row['e_name'].";");
				fwrite($file, $row['e_strasse'].";");
				fwrite($file, $row['erziehungsberechtigte_plz'].";");
				fwrite($file, $row['erziehungsberechtigte_ort'].";");
				fwrite($file, "'".$row['e_fon_privat']."';");
				fwrite($file, "'".$row['e_fon_dienst']."';");
				fwrite($file, $row['ba_essenstage'].";");
			}			
			
			fwrite($file, "\n");
		}
	}
	fclose($file);
	
	echo '<a class="button" id="download" href="'.$filename.'">Herunterladen</a><br/><br/>';
	
	echo "<p id='info'></p>";

 
?>
