<?php 
	$filename =md5('tmp')."/".$_SESSION['schul_id']."_".$_GET['b'].".csv";
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
			$("#info").text(data);
		}
	);
});

</script>

<?php
	checkRights(200,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('get');
	$self = 'bankverbindungen_get';
    echo "<h1>Bankverbindungen herunterladen</h1>";

  
			
	if($page==md5('exportieren')) { 
			$sql ="SELECT $tbl_schulen[name] FROM $tbl_schulen[tbl]
				   WHERE $tbl_schulen[id] = $_SESSION[schul_id]";
			$result = mysql_query($sql);			
		   $school = mysql_fetch_assoc($result);
		  
		$file = fopen($filename,"w");
		if($_GET['b']=="lastschrift"){
		
			echo "<h2>Lastschrift</h2>";
		
		    $sql ="SELECT * FROM $tbl_bankverbindungen[tbl], $tbl_betreuungsauftraege[tbl], $tbl_erziehungsberechtigte[tbl], $tbl_schueler[tbl]
				   WHERE $tbl_bankverbindungen[methode] = '$_GET[b]'
				   AND $tbl_betreuungsauftraege[schul_id]  = $_SESSION[schul_id]
				   AND $tbl_bankverbindungen[id] = $tbl_betreuungsauftraege[bankverbindungs_id]
				   AND $tbl_schueler[id] = $tbl_betreuungsauftraege[schueler_id]
				   AND $tbl_schueler[e_id] = $tbl_erziehungsberechtigte[id]
				   ";
			$result = mysql_query($sql);

			fwrite($file, $school[$tbl_schulen['name']].";Lastschrift;\n");
			fwrite($file, "Schüler Vorname;Schüler Nachname;Schüler-ID;Kontoinhaber Vorname;Kontoinhaber Nachname;Kontonummer;Bankleitzahl;Jahresrate Betreuung;Ferienbetreuung;Jahresrate Essen;Einbehalt von Sozialhilfe?;Rate;\n");
			
			while($row = mysql_fetch_assoc($result)){
				fwrite($file, $row[$tbl_schueler['vorname']].";");
				fwrite($file, $row[$tbl_schueler['name']].";");
				fwrite($file, $row[$tbl_betreuungsauftraege['schueler_id']].";");	
				fwrite($file, $row[$tbl_bankverbindungen['name']].";");
				fwrite($file, $row[$tbl_bankverbindungen['vorname']].";");
				fwrite($file, $row[$tbl_bankverbindungen['ktnr']].";");
				fwrite($file, $row[$tbl_bankverbindungen['blz']].";");
				$jahresrate = calcPay($row[$tbl_betreuungsauftraege['jahreseinkommen']],$row[$tbl_betreuungsauftraege['ferien']],$row[$tbl_betreuungsauftraege['geschwister']]);
				$essensbetrag = calcEssen($row[$tbl_betreuungsauftraege['essenstage']],$row[$tbl_betreuungsauftraege['jahreseinkommen']], $row[$tbl_betreuungsauftraege['ba_zuschuss']], $row[$tbl_betreuungsauftraege['ba_sozial']]);
				fwrite($file, $jahresrate."€;");
				if($row[$tbl_betreuungsauftraege['ferien']]){
					fwrite($file, "ja;");
				}else{
					fwrite($file, ";");
				}
				fwrite($file, 10 * $essensbetrag."€;");
				if (checkEinbehalt($row[$tbl_betreuungsauftraege['essenstage']],$row[$tbl_betreuungsauftraege['jahreseinkommen']], $row[$tbl_betreuungsauftraege['ba_zuschuss']], $row[$tbl_betreuungsauftraege['ba_sozial']])){
					fwrite($file, "ja;");
				}else{
					fwrite($file, ";");
				}
				fwrite($file, $jahresrate / 10 + $essensbetrag."€;\n");
			}
		} elseif ($_GET['b']=="ueberweisung"){
			
			echo "<h2>Überweisung</h2>";
		
		    $sql ="SELECT * FROM $tbl_bankverbindungen[tbl], $tbl_betreuungsauftraege[tbl], $tbl_erziehungsberechtigte[tbl], $tbl_schueler[tbl]
				   WHERE $tbl_bankverbindungen[methode] = '$_GET[b]'
				   AND $tbl_betreuungsauftraege[schul_id]  = $_SESSION[schul_id]
				   AND $tbl_bankverbindungen[id] = $tbl_betreuungsauftraege[bankverbindungs_id]
				   AND $tbl_schueler[id] = $tbl_betreuungsauftraege[schueler_id]
				   AND $tbl_schueler[e_id] = $tbl_erziehungsberechtigte[id]
				   ";
			$result = mysql_query($sql);

			fwrite($file, $school[$tbl_schulen['name']].";Überweisung;\n");
			fwrite($file, "Schüler Vorname;Schüler Nachname;Schüler-ID;Jahresrate Betreuung;Ferienbetreuung;Jahresrate Essen;Einbehalt von Sozialhilfe?;Rate;\n");
			
			while($row = mysql_fetch_assoc($result)){
				fwrite($file, $row[$tbl_schueler['vorname']].";");
				fwrite($file, $row[$tbl_schueler['name']].";");
				fwrite($file, $row[$tbl_betreuungsauftraege['schueler_id']].";");	
				$jahresrate = calcPay($row[$tbl_betreuungsauftraege['jahreseinkommen']],$row[$tbl_betreuungsauftraege['ferien']],$row[$tbl_betreuungsauftraege['geschwister']]);
				$essensbetrag = calcEssen($row[$tbl_betreuungsauftraege['essenstage']],$row[$tbl_betreuungsauftraege['jahreseinkommen']], $row[$tbl_betreuungsauftraege['ba_zuschuss']], $row[$tbl_betreuungsauftraege['ba_sozial']]);
				fwrite($file, $jahresrate."€;");
				if($row[$tbl_betreuungsauftraege['ferien']]){
					fwrite($file, "ja;");
				}else{
					fwrite($file, ";");
				}
				fwrite($file, 10 * $essensbetrag."€;");
				if (checkEinbehalt($row[$tbl_betreuungsauftraege['essenstage']],$row[$tbl_betreuungsauftraege['jahreseinkommen']], $row[$tbl_betreuungsauftraege['ba_zuschuss']], $row[$tbl_betreuungsauftraege['ba_sozial']])){
					fwrite($file, "ja;");
				}else{
					fwrite($file, ";");
				}
				fwrite($file, $jahresrate / 10 + $essensbetrag."€;\n");
			}
		} elseif ($_GET['b']=="sonstige"){
		
			echo "<h2>Sonstiges</h2>";
		
		    $sql ="SELECT * FROM $tbl_bankverbindungen[tbl], $tbl_betreuungsauftraege[tbl], $tbl_erziehungsberechtigte[tbl], $tbl_schueler[tbl]
				   WHERE $tbl_bankverbindungen[methode] = '$_GET[b]'
				   AND $tbl_betreuungsauftraege[schul_id]  = $_SESSION[schul_id]
				   AND $tbl_bankverbindungen[id] = $tbl_betreuungsauftraege[bankverbindungs_id]
				   AND $tbl_schueler[id] = $tbl_betreuungsauftraege[schueler_id]
				   AND $tbl_schueler[e_id] = $tbl_erziehungsberechtigte[id]
				   ";
			$result = mysql_query($sql);

			fwrite($file, $school[$tbl_schulen['name']].";Sonstiges;\n");
			fwrite($file, "Schüler Vorname;Schüler Nachname;Schüler-ID;Begründung;Jahresrate Betreuung;Ferienbetreuung;Jahresrate Essen;Einbehalt von Sozialhilfe?;Rate;\n");
			
			while($row = mysql_fetch_assoc($result)){
				fwrite($file, $row[$tbl_schueler['vorname']].";");
				fwrite($file, $row[$tbl_schueler['name']].";");
				fwrite($file, $row[$tbl_betreuungsauftraege['schueler_id']].";");
				fwrite($file, $row[$tbl_bankverbindungen['sonstiges']].";");	
				$jahresrate = calcPay($row[$tbl_betreuungsauftraege['jahreseinkommen']],$row[$tbl_betreuungsauftraege['ferien']],$row[$tbl_betreuungsauftraege['geschwister']]);
				$essensbetrag = calcEssen($row[$tbl_betreuungsauftraege['essenstage']],$row[$tbl_betreuungsauftraege['jahreseinkommen']], $row[$tbl_betreuungsauftraege['ba_zuschuss']], $row[$tbl_betreuungsauftraege['ba_sozial']]);
				fwrite($file, $jahresrate."€;");
				if($row[$tbl_betreuungsauftraege['ferien']]){
					fwrite($file, "ja;");
				}else{
					fwrite($file, ";");
				}
				fwrite($file, 10 * $essensbetrag."€;");
				if (checkEinbehalt($row[$tbl_betreuungsauftraege['essenstage']],$row[$tbl_betreuungsauftraege['jahreseinkommen']], $row[$tbl_betreuungsauftraege['ba_zuschuss']], $row[$tbl_betreuungsauftraege['ba_sozial']])){
					fwrite($file, "ja;");
				}else{
					fwrite($file, ";");
				}
				fwrite($file, $jahresrate / 10 + $essensbetrag."€;\n");
			}
		}
	fclose($file);

echo '<a class="button" id="download" href="'.$filename.'">Herunterladen</a><br/><br/>';

echo "<p id='info'></p>";

} 
?>
