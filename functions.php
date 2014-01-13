<?php

function array_stripslashes(&$var)  //wird bei im parameter mitgelieferten variablen durchgef�hrt um unn�tige slashes zu entfernen
{
	if(is_string($var)) {
		$var = stripslashes($var);
	} else {
		if(is_array($var)) {
			foreach($var AS $key => $value) {
				array_stripslashes($var[$key]);
			}
		}
	}
}

function checkRights($berechtigung, $user, $return=0) {       //prüft ob man eingeloggt ist und ob die rechte ausreichen um die seite aufzurufen
	if(!$user->LoggedIn()) {                   //benötigten rechte werden im parameter mitgeliefert
		if($return == 1)
			return false;
		header("Location: index.php?content=login&p=".md5('needloggedon')."");
		die();
	}
	
	if($user->getStatus() < $berechtigung) {
		if($return == 1)
			return false;
		header("Location: index.php?content=login&p=".md5('norights')."");
		die();	
	} else {
		return true;
	}
}

function getlehrerrights($user){
if($user->getStatus()!= 200)
		{
			echo "Sie müssen sich erst einloggen!";
		} else {
			return 1;
		}
}

function getDateBox($pre,$sel=""){
	
	$tmp = explode("-",$sel);

	$day 	= empty($tmp[2])? "01":$tmp[2];
	$month 	= empty($tmp[1])? "01":$tmp[1];
	$year 	= empty($tmp[0])? "1990":$tmp[0];
	
	$selected = " selected=\"selected\" ";
	
	$html = "<select name=\"$pre"."_day\">";
	for($i=01;$i <= 31;$i++){
		if($day == $i)
			$html .= "<option $selected value=\"$i\">$i</option>";
		else
			$html .= "<option value=\"$i\">$i</option>";
	}
	$html .= '</select>';
	
	$html .= "<select name=\"$pre"."_month\" >";
	for($i=01;$i <= 12;$i++){
		if($month == $i)
			$html .= "<option $selected value=\"$i\">$i</option>";
		else
			$html .= "<option value=\"$i\">$i</option>";
	}
	$html .= '</select>';
	
	$html .= "<select name=\"$pre"."_year\">";
	for($i=1990;$i <= date('Y') - 4 ;$i++){
		if($year == $i)
			$html .= "<option $selected value=\"$i\">$i</option>";
		else
			$html .= "<option value=\"$i\">$i</option>";
	}
	$html .= '</select>';
	
	return $html;
}

function getDen($sel=""){
	require("variablen.php"); 
	$sql = "SELECT * FROM $tbl_aerzte[tbl] ";
	$result = mysql_query($sql);
	echo "<label for=\"schul_id\" >Arzt:</label>
		<select name=\"arzt\" id=\"arzt\" style=\"float:left;\">";
	
	while($row = mysql_fetch_assoc($result) )
	{
		if($sel == $row[$tbl_aerzte['id']]){
			$selected = 'selected="selected"';
		}

		echo "<option $selected value=\"".$row[$tbl_aerzte['id']]."\">".$row[$tbl_aerzte['name']]."</option>";
		
		$selected = '';
	}
	echo "</select>";
	echo '  <span style="display:block;float:left;" id="a_finsih">
	<img src="'.$icon_path.'arrow_left_green_48.png" height="28" width="28" alt="<--">
	Eingetragen!</span>';
	echo '  <span style="display:block;" id="a_action">&nbsp;<a href="javascript:void(0)" onclick="getDenForm()">
	<img src="'.$icon_path.'add_48.png" height="18" width="18" alt="hinzufügen">
	</a></span>';
	echo '<br /><div class="arztForm" id="arztForm"></div>';
	echo "<br /><br />";
}

function getTimeBox($h,$m,$s = "", $onchange = ""){
	
	if($s != 0){
		$arr = explode(":",$s);
		
		$s = $arr[0];
		$t = $arr[1];
	}
	else{
		$s = "";
		$t = "";
	}
	
	
	$html 	 = 	'<select name="'.$h.'" onchange="'.$onchange.'">';
	
		$html 	.= 	'<option ';
		$html 	.= 	($s == 12)?'selected="selected"':"";
		$html 	.= 	'>12</option>';
					
		$html 	.= 	'<option ';
		$html 	.= 	($s == 13)?'selected="selected"':"";
		$html 	.= 	'>13</option>';
		
		$html 	.= 	'<option ';
		$html 	.= 	($s == 14)?'selected="selected"':"";
		$html 	.= 	'>14</option>';
		
		$html 	.= 	'<option ';
		$html 	.= 	($s == 15)?'selected="selected"':"";
		$html 	.= 	'>15</option>';
		
		$html 	.= 	'<option ';
		$html 	.= 	($s == 16)?'selected="selected"':"";
		$html 	.= 	'>16</option>';
		
		$html 	.= 	'<option ';
		$html 	.= 	($s == 17)?'selected="selected"':"";
		$html 	.= 	'>17</option>';
		
		$html 	.= 	'<option ';
		$html 	.= 	($s == 18)?'selected="selected"':"";
		$html 	.= 	'>18</option>';
	
	$html 	.= 	'</select> : ';
	
	
	
	$html 	.= 	'<select name="'.$m.'" onchange="'.$onchange.'">';

		$html 	.= 	'<option ';
		$html 	.= 	($t == 00)?'selected="selected"':"";
		$html 	.= 	'>00</option>';
		
		$html 	.= 	'<option ';
		$html 	.= 	($t == 15)?'selected="selected"':"";
		$html 	.= 	'>15</option>';		
				
		$html 	.= 	'<option ';
		$html 	.= 	($t == 30)?'selected="selected"':"";
		$html 	.= 	'>30</option>';		
	
		$html 	.= 	'<option ';
		$html 	.= 	($t == 45)?'selected="selected"':"";
		$html 	.= 	'>45</option>';		
	
	$html 	.= 	'</select>';
	
	return $html;
}

function getTimeBoxY($pre,$s = ""){
	
	if(date("M") < 8){
		$now = date("Y");
	}else {
		$now = date("Y") + 1;
	}
	$tmp = date("Y") + 5;
	$html = '<select name="'.$pre.'">';
	
	for($i=0;$now <= $tmp ;$i++){
		$next = $now + 1;
		$html .= "<option value=\"$next-07-31\" ";
		$html .= ($s == "$next-07-31")?'selected="selected"':"";
		$html .= ">$now  / $next</option>";
		$now++;
	}
	$html .= '</select>';
	
	return $html;
}


function getPlzForm($input_plz,$input_ort,$ort='',$error=0){
	include("variablen.php");
	
	$sql = "SELECT * FROM `$tbl_orte[tbl]`
			WHERE `$tbl_orte[id]` = '".$ort."'
			";
	$result=mysql_query($sql);

	$row = mysql_fetch_assoc($result);
	if ($row){
		$plz = $row[$tbl_orte['plz']];
		$ort_name = $row[$tbl_orte['ort']];
	} else {
		$plz = "";
		$ort_name = "";
	}

	if(isset($_SESSION['e1_plz']) && $input_plz == "s_plz"){
		$plz = $_SESSION['s_plz'];
		$_SESSION['s_plz'] = "";
	}
	
	if(isset($_SESSION['e1_plz']) && $input_plz == "e1_plz"){
		$plz = $_SESSION['e1_plz'];
		$_SESSION['e1_plz'] = "";
	}
	
	$html = '
				<label for="'.$input_plz.'" >PLZ / Ort: *</label>
				<input maxlength="5" type="text" name="'.$input_plz.'" id="'.$input_plz.'" size="5" value="'.$plz.'" onchange="insert_ort(\''.$input_plz.'\',\''.$input_ort.'\','.$error.')" />
				<span id="'.$input_ort.'">';		
				
				$html .= "<select id='".$input_ort."' name='".$input_ort."' >";
	
				if ($plz != ""){
					$sql="SELECT * FROM ".$tbl_orte['tbl']." WHERE ".$tbl_orte['plz']." = ".$plz.";";
					$result=mysql_query($sql);
					while ($row = mysql_fetch_assoc($result)){
						$html .= "<option value='".$row[$tbl_orte['id']]."'";
						if ($ort == $row[$tbl_orte['id']] ) { $html .= "selected='selected'"; }
						$html .=">".$row[$tbl_orte['ort']]."</option>";
					}
				}else {
					$html .= "<option value=''>Bitte geben Sie eine Plz ein!</option>";
				}	
				$html .= "</select>";
		
	$html .= '</span>';
	return $html;
}

function getKlassenForm($input_klassen,$sel=""){
	include("variablen.php");
	
	$sql = "SELECT * FROM `$tbl_klassen[tbl]` WHERE `$tbl_klassen[status]` = 1";
	$result=mysql_query($sql);
	
	$selected = "selected=\"selected\"";

	$html = '
			<label for="'.$input_klassen.'" >Klasse:</label>				
			<select name="'.$input_klassen.'" id="'.$input_klassen.'">';
	$html .= '<option value="0">-- Bitte Auswählen --</option>';
	while ($row = mysql_fetch_assoc($result)){
		if($sel == $row[$tbl_klassen['id']])
			$html .= '<option value='.$row[$tbl_klassen['id']].' '.$selected.'>'.$row[$tbl_klassen['bezeichnung']].'</option>';
		else
			$html .= '<option value='.$row[$tbl_klassen['id']].'>'.$row[$tbl_klassen['bezeichnung']].'</option>';
	}
	$html .= '</select>';
	/*
	$html .= '<a href="javascript:void(0);" onclick="getClassForm(\''.$input_klassen.'\');" >';
	$html .= '	<img src="'.$icon_path.'add_48.png" height="18" width="18" alt="<--" />' ;
	$html .= '</a>';
	$html .= '<div style="display:none;" id="classForm"></div>';*/
	
	return $html;
}

function getBank($method="lastschrift", $name="", $vorname="", $blz="", $ktnr="" , $sonstige="" ,$input_method="bank_method", $input_name="bank_name", $input_vorname="bank_vorname", $input_blz="bank_blz", $input_ktnr="bank_ktnr", $input_sonstige="bank_sonstige"){
	
	if(isset($_SESSION['bank_method']) && $_SESSION['bank_method'] != ""){
	
		$name	 = 	$_SESSION['bank_name'];
		$vorname = 	$_SESSION['bank_vorname'];
		$blz 	 = 	$_SESSION['bank_blz'];
		$ktnr 	 = 	$_SESSION['bank_ktnr'];
		$sonstige = $_SESSION['bank_sonstige'];
		$method = 	$_SESSION['bank_method'];
		
		
		$_SESSION['bank_method'] = ""; 		
		$_SESSION['bank_name'] = ""; 			
		$_SESSION['bank_vorname'] = "";
		$_SESSION['bank_blz'] = ""; 			
		$_SESSION['bank_ktnr'] = ""; 			
		$_SESSION['bank_sonstige'] = "";		
	}
	
	$html ='<input type="radio" name="'.$input_method.'" value="lastschrift" onclick="getBank(this.value)"';
	if ($method == "lastschrift") { $html.='checked="checked"';}
	$html.='/> Lastschrifteinzug
			<input type="radio" name="'.$input_method.'" value="ueberweisung" onclick="getBank(this.value)"'; 
	if ($method == "ueberweisung") { $html.='checked="checked"';}
	$html.='/> &Uuml;berweisung
			<input type="radio" name="'.$input_method.'" value="sonstige" onclick="getBank(this.value)"'; 
	if ($method == "sonstige") { $html.='checked="checked"';}
	$html.='/> individuelle L&ouml;sung<br/> <br/> 
			<div id="lastschrift" ';
	if ($method != "lastschrift") { $html.='class="hidden"'; }
	$html.='>
				<label for="'.$input_name.'" >Name:</label>
				<input maxlength="50" type="text" name="'.$input_name.'" id="'.$input_name.'" value="'.$name.'"/>
				<br /> 
				
				<label for="'.$input_vorname.'" >Vorname:</label>
				<input maxlength="50" type="text" name="'.$input_vorname.'" id="'.$input_vorname.'" value="'.$vorname.'"/>
				<br /> 
				
				<label for="'.$input_blz.'" >Blz:</label>
				<input maxlength="50" type="text" name="'.$input_blz.'" id="'.$input_blz.'" value="'.$blz.'"/>
				<br /> 
				
				<label for="'.$input_ktnr.'" >Kontonummer:</label>
				<input maxlength="50" type="text" name="'.$input_ktnr.'" id="'.$input_ktnr.'" value="'.$ktnr.'"/>
				<br /> 
			</div>
			<div id="ueberweisung" ';
	if ($method != "ueberweisung") { $html.='class="hidden"'; }
	$html.='>
				Bitte &uuml;berweisen Sie wie sp&auml;ter angegeben den Beitrag auf das Konto des jfd-Rheine.
			</div>	
			<div id="sonstige" ';
	if ($method != "sonstige") { $html.='class="hidden"'; }
	$html.='>
				<label for="'.$input_sonstige.'" >Begr&uuml;ndung:</label>
				<textarea rows="5" name="'.$input_sonstige.'" id="'.$input_sonstige.'" >'.$sonstige.'</textarea>				
				<br /> 
			</div>	
	';
	return $html;
}

function checkEssensTage($code,$day){
	$days = str_split($code);
	if($days[0] == 1 && $day == "mo")
		return true;
	if($days[1] == 1 && $day == "di")
		return true;
	if($days[2] == 1 && $day == "mi")
		return true;
	if($days[3] == 1 && $day == "do")
		return true;
	if($days[4] == 1 && $day == "fr")
		return true;
		
	return false;
}
function dump($var){
	
	
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
}

function convertDate($date){
	$tmp = explode("-",$date);
	return $tmp[2] . "." . $tmp[1]  . "." . $tmp[0];
}

function getIcon($icon){
	include("variablen.php");
	
	switch ($icon) {
		case "bearbeiten": 
			$filename = "pencil_48.png";
			break; 
		case "l&ouml;schen": 
			$filename = "pencil_48.png"; 
			break; 
		case "details": 
			$filename = "search_48.png"; 
			break; 
	}
	
	$html = "<img src=\"".$icon_path.$filename."\" height=\"28\" width=\"28\" alt=\"".$icon."\">";
	
	return $html;
}

function getBeitrag($jahreseinkommen){
	$beitrag = 0; 
	$sql = "
		SELECT *
		FROM `verdienst`
		WHERE schul_id=$_SESSION[schul_id]
		ORDER BY v_verdienst DESC, `from` DESC
		";
	
	$result = mysql_query($sql);
	
	// alle verdienst einträge durch gehen und den passenden beitrag zum jahreseinkommen ermitteln
	while($row = mysql_fetch_object($result)){
		if($row->from ){
			if($jahreseinkommen > $row->v_verdienst){
				$beitrag = $row->beitrag;
			}
		}else{
			if($jahreseinkommen <= $row->v_verdienst){
				$beitrag = $row->beitrag;
			}
		}
		
	}
	return $beitrag;
}

function calcPay($jahreseinkommen,$ferien,$geschwister){
	
	// betreung ausrechnen
	$beitragSum = 0; // beitrag in summe fürs ganze jahr
	$beitrag = 0; // beitrag im monat
	
	$beitrag = getBeitrag($jahreseinkommen);
	
	$beitragSum = $beitrag * 10; // beiträge für das ganze Jahr-> die Beiträge in der Datenbank sind schon Abschlagsbeiträge

	// Bei einem Geschwisterkind mit Betreuungsauftrag verringert sich der Beitrag um die Hälfte, bei mehr als einem entfällt der Beitrag.
	if($geschwister == 1)	
		$beitragSum = $beitragSum / 2;
	if($geschwister > 1)
		$beitragSum = 0;
		
	// ferienbeitrag ist 120 Euro im Jahr
	if($ferien == 1)
		$beitragSum += 120;	

	return $beitragSum; // beitrag im jahr
}

function getBeitragsGruppen($jahreseinkommen){
	$sql = "SELECT *
			FROM `verdienst`
			WHERE schul_id=$_SESSION[schul_id]
			ORDER BY v_verdienst DESC, `from` DESC
			";
		
	$result = mysql_query($sql);
	$beitrag="";
	// alle verdienst einträge durch gehen und den passenden beitrag zum jahreseinkommen ermitteln
	while($row = mysql_fetch_object($result)){
		if($row->from ){
			if($jahreseinkommen > $row->v_verdienst){
				$beitrag = $row->sozi;
			}
		}else{
			if($jahreseinkommen <= $row->v_verdienst){
				$beitrag = $row->sozi;
			}
		}
	}
	return $beitrag;
}

function checkEinbehalt($essen, $jahreseinkommen, $kein_zuschuss, $kein_einbehalt){
	if(($jahreseinkommen==0 || getBeitragsGruppen($jahreseinkommen)) && !$kein_zuschuss && !$kein_einbehalt && $essen){ 
		return 1;
	}else{
		return 0;
	}
}

function calcEssen($essen, $zuschuss_essen, $kein_einbehalt = 0, $sozialleistungen = 0){
	// essens beiträge
	$essenSum = 0;
		
	// essenspreis aus der db holen
	$sql = new Sql;
	$school = $sql->getSchoolData($_SESSION['schul_id']);
	$beitrag = $school->essenspreis;
	$essenCount = 0;
	
	if(checkEssensTage($essen,"mo")){
		$essenCount++;
	}
	if(checkEssensTage($essen,"di")){
		$essenCount++;
	}
	if(checkEssensTage($essen,"mi")){
		$essenCount++;
	}
	if(checkEssensTage($essen,"do")){
		$essenCount++;
	}
	if(checkEssensTage($essen,"fr")){
		$essenCount++;
	}
	// essen für 1 €
	if($zuschuss_essen){
		if($essenCount && ($kein_einbehalt || !$sozialleistungen)){
			return 20; // 1€ * 5 Tage * 40 WOchen / 10 Abschläge
		}else{
			return 0;
		}
	}else{
		$essenSum = $beitrag * $essenCount;
		return round($essenSum * 40 / 10); // Essenspreis pro Woche * 40 Wochen / 10 Abschläge
	}
	
	
}

function getSozial($code){

	if(pow(2,0) <= $code){
		return true;
	}
	else if(pow(2,1) <= $code){
		return true;
	}
	else if(pow(2,2) <= $code){
		return true;
	}
	return false;
}

function calc($hartz4, $wohngeld, $schulbuch, $verdienst, $urlaubsgeld, $vermietung, $kinderreich, $werbungskosten ){
	// init jahreseinkommen
	$jahreseinkommen = 0;
	
	// falls eine checkbox angehackt is, fallen keine gebühren an, daher wert 0
	if( $hartz4 || $wohngeld || $schulbuch ){
		$jahreseinkommen = 0;
	}
	else if( $verdienst != "" ){
		$jahreseinkommen = $verdienst* 12;
	
		if( $urlaubsgeld != "" ){
			$jahreseinkommen += $urlaubsgeld; 
		}

		if( $vermietung != "" ){
			$jahreseinkommen += $vermietung * 12; 
		}
		if( $werbungskosten != "" ){
			$jahreseinkommen -= $werbungskosten; 
		}
		if( $kinderreich){
			$jahreseinkommen -= KINDERFREIBETRAG;
		}	
	}
	if($jahreseinkommen < 0)
		$jahreseinkommen = 0;
		
	return $jahreseinkommen;
}

function sendMsg($status, $action, $schoolname, $name, $user, $pre = NULL, $past = NULL){
	
	if($user->getUsername() != "ingepiepel"){
	
		$update_mail = "ingrid.piepel@jfd-rheine.de";	
		$date = date("m.d.y");
		$time = date("H:i");
		
		ob_start();
		
		echo "Es wurde in der Schule $schoolname am $date um $time der Betreuungsauftrag für $name ";
				
		if($action == "update"){
			$subject = "JFD-Rheine - Betreuungsauftrag geändert";
			echo "geaendert. <br/>";
		}else if($action == "insert"){
			$subject = "JFD-Rheine - Betreuungsauftrag angelegt";
			echo "angelegt. <br/>";

			if($status){
				echo "Alle Verdienstnachweise sind vorhanden, der Vertrag ist somit aktiv. <br/>";
			}else {
				echo "Es Wurden noch nicht alle Verdienstnachweise eingereicht, der Vertrag ist somit noch nicht aktiv. <br/>";
			}
		}
		
		if($pre != NULL && $past != NULL){
			if($pre == $past){
				//Keine Änderung vorgenommen, keine Benachrichtigung
				ob_end_clean();
				return 0;
			}
		
			echo "Änderungen:<br/>";
			
			if(compare_objects($past, $pre, array('ba_anfang', 'ba_ende'))){
				echo "Zeitraum: ".date_format($past->ba_anfang, "d.m.Y")." - ".date_format($past->ba_anfang, "d.m.Y")."<br/>";
			}
			
			if(compare_objects($past, $pre, array('b_name', 'b_blz', 'b_kntr', 'b_vorname', 'b_methode', 'b_sonstiges'))){
				echo "Neue Bankverbindung<br/>";
			}
			
			if(compare_objects($past, $pre, array('ba_montag', 'ba_dienstag', 'ba_mittwochh', 'ba_donnerstag', 'ba_freitag'))){
				echo "Neue Betreuungszeiten<br/>";
			}			
				
			if(compare_objects($past, $pre, array('ba_essenstage'))){
				echo "Neue Essenstage<br/>";
			}
			
			if(compare_objects($past, $pre, array('ba_allergien', 'ba_allergien_text'))){
				echo "Allergien<br/>";
			}			
			
			if(compare_objects($past, $pre, array('ba_moslemisch'))){
				if($past->ba_moslemisch){
					echo "Moslimsches Essen bestellt<br/>";
				}else{
					echo "Moslimsches Essen abbestellt<br/>";
				}
			}			

			if(compare_objects($past, $pre, array('ba_ferien'))){
				if($past->ba_ferien){
					echo "Ferienbetreuung wurde gebucht<br/>";
				}else{
					echo "Ferienbetreuung wurde abbestellt<br/>";
				}
			}			
			
			if(compare_objects($past, $pre, array('ba_geschwister'))){
				echo "Die Anzahl der Geschwister mit Betreuungsauftrag wurde geändert(".$past->ba_geschwister.").<br/>";
			}			

			if(compare_objects($past, $pre, array('ba_zuschuss_essen'))){
				if($past->ba_zuschuss){
					echo "Essen für 1€ wurde aktiviert.<br/>";
				}else{
					echo "Essen für 1€ wurde deaktiviert.<br/>";
				}
			}
			
			if(compare_objects($past, $pre, array('ba_einkommensjahresausgleich', 'ba_sozialleistungen', 'ba_jahreseinkommen', 'ba_kinder', 'ba_brutto_monat', 'ba_unterhalt_monat', 'ba_grafikationen', 'ba_vermietung', 'ba_werbungskosten', 'ba_sonstige_einnahmen_monat'))){
				echo "Die Einkommensverhältnisse wurden geändert.<br/>";
			}			

			if(compare_objects($past, $pre, array('ba_besonderheiten'))){
				echo "Besonderheiten: ".$past->ba_besonderheiten."<br/>";
			}			
			
			if(compare_objects($past, $pre, array('ba_status'))){
				if($past->ba_status){
					echo "Der Auftrag wurde aktiviert.<br/>";
				}else{
					echo "Der Auftrag wurde deaktiviert.<br/>";
				}
			}				
			/* TODO: ba_sozial, ba_zuschuss_essen */		
			
		}				
		$text = ob_get_clean();

		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= 'From: Schulbetreuung jfd-Rheine <no-replay@jfd-rheine.de>' . "\r\n";	
		
		mail($update_mail, $subject, $text, $header);
	}
}

function compare_objects($ob1, $ob2, $keys){
	foreach ($keys AS $key){
		if($ob1->$key != $ob2->$key){
			return 1;
		}
	}
	return 0;
}

?>