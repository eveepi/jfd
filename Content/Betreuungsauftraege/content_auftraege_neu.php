<?php
	checkRights(40,$user);
	
    $page = isset($_GET['p']) ? $_GET['p'] : md5('anlegen');  
    
    $self = 'betreuungsauftraege_neu';
	echo "<h1> Neuen Auftrag anlegen </h1>";

    if($page==md5('eintragen')) {
		
		if(isset($_POST['saveData']) && $_POST['saveData']){
			$_SESSION['saveData'] 		= 1;
			
			$_SESSION['s_nachname'] 	= $_POST['s_nachname'];
			$_SESSION['s_strasse']  	= $_POST['s_strasse']; 
			$_SESSION['s_plz']  		= $_POST['s_plz']; 
			
			$_SESSION['e1_nachname'] 	=  $_POST['e1_nachname'];
			$_SESSION['e1_vorname'] 	=  $_POST['e1_vorname'];
			$_SESSION['e1_vorname'] 	=  $_POST['e1_vorname'];
			$_SESSION['e1_strasse'] 	=  $_POST['e1_strasse'];
			$_SESSION['e1_plz'] 		=  $_POST['e1_plz'];
			$_SESSION['e1_fon_privat'] 	=  $_POST['e1_fon_privat'];
			$_SESSION['e1_fon_dienst'] 	=  $_POST['e1_fon_dienst'];
			
			$_SESSION['e2_nachname'] 	=  $_POST['e2_nachname'];
			$_SESSION['e2_vorname'] 	=  $_POST['e2_vorname'];
			$_SESSION['e2_fon_dienst'] 	=  $_POST['e2_fon_dienst'];
			
			$_SESSION['geschwister'] 	=  $_POST['geschwister'];
			$_SESSION['kinder'] 		=  $_POST['kinder'];
			
			$_SESSION['einkommens2'] 	=  $_POST['einkommens2'];
			$_SESSION['einkommens'] 	=  $_POST['einkommens'];
			
			$_SESSION['hartz4']			= (isset($_POST['hartz4'])) ? $_POST['hartz4'] : 0;
			$_SESSION['schulbuch']		= (isset($_POST['schulbuch'])) ? $_POST['schulbuch'] : 0;
			$_SESSION['agb']			= (isset($_POST['agb'])) ? $_POST['agb'] : 0;$_POST['agb'];
			$_SESSION['zuschuss_pdf']	= (isset($_POST['zuschuss_pdf'])) ? $_POST['zuschuss_pdf'] : 0;
			$_SESSION['zuschuss_essen']	= (isset($_POST['zuschuss_essen'])) ? $_POST['zuschuss_essen'] : 0;
			$_SESSION['sozial_pdf']		= (isset($_POST['sozial_pdf'])) ? $_POST['sozial_pdf'] : 0;		
			
			$_SESSION['agb'] 			=  $_POST['agb'];
			
			$_SESSION['bank_method'] 	=  $_POST['bank_method'];
			$_SESSION['bank_name'] 		=  $_POST['bank_name'];
			$_SESSION['bank_vorname'] 	=  $_POST['bank_vorname'];
			$_SESSION['bank_blz'] 		=  $_POST['bank_blz'];
			$_SESSION['bank_ktnr'] 		=  $_POST['bank_ktnr'];
			$_SESSION['bank_sonstige'] 	=  $_POST['bank_sonstige'];
			$_SESSION['bank_holder'] 	=  $_POST['bank_holder'];
			$_SESSION['bank_iban'] 		=  $_POST['bank_iban'];
			$_SESSION['bank_bic'] 		=  $_POST['bank_bic'];		
			
			$_SESSION['calcEinkommen']	= 	$_POST['calcEinkommen'];			
			$_SESSION['verdienst']		= 	$_POST['verdienst'];			
			$_SESSION['unterhalt']		= 	$_POST['unterhalt'];			
			$_SESSION['urlaubsgeld']	= 	$_POST['urlaubsgeld'];			
			$_SESSION['vermietung']		= 	$_POST['vermietung'];			
			$_SESSION['werbungskosten']	= 	$_POST['werbungskosten'];	
			$_SESSION['sonst_einkuenfte']= 	$_POST['sonst_einkuenfte'];	
		}
		
		$sql = new Sql;
		
		// init optional felder
		$_POST['e2_nachname'] 	= (isset($_POST['e2_nachname']))?$_POST['e2_nachname']:"";
		$_POST['e2_vorname']	= (isset($_POST['e2_vorname']))?$_POST['e2_vorname']:"";
		$_POST['e2_fon_privat']	= (isset($_POST['e2_fon_privat']))?$_POST['e2_fon_privat']:"";
		
		$e_id = $sql->insertEltern($_POST['e1_nachname'],$_POST['e1_vorname'],$_POST['e1_strasse'],$_POST['e1_ort'],$_POST['e1_fon_privat'],$_POST['e1_fon_dienst'],$_POST['e2_nachname'],$_POST['e2_vorname'],$_POST['e2_fon_privat'],$_SESSION['schul_id']);
		
		$s_id = $sql->insertSchueler($_POST['s_nachname'],$_POST['s_vorname'],$_POST['s_strasse'],$_POST['s_ort'],$_POST['bday_year']."-".$_POST['bday_month']."-".$_POST['bday_day'],$_SESSION['schul_id'],$e_id,$_POST['s_klasse']);
    
		if($_POST['start'] == "manuell"){
			$tmp = explode("/",$_POST['start_date']);
			$anfang = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}elseif($_POST['start'] == "now"){
			$anfang = date("Y-m-d");
		}elseif ($_POST['start'] == "this"){
			if(date("m") >= 8){
				$tmp = date("Y");	
			}else {
				$tmp = date("Y") - 1;	
			}
			$anfang = "$tmp-08-1";	
		}elseif ($_POST['start'] == "next"){
			if(date("m") >= 8){
				$tmp = date("Y") + 1;	
			}else {
				$tmp = date("Y");	
			}
			$anfang = "$tmp-08-1";
		}else{
			$anfang = $tmp;
		}
	
		if($_POST['ende'] == "manuell"){
			$tmp = explode("/",$_POST['end_date']);
			$ende = $tmp[2]."-".$tmp[1]."-".$tmp[0];
		}else{
			$ende = $_POST['ende'];
		}
		
		//init der zeiten
		$mo = (isset($_POST['day_mo']))? $_POST['time_h_mo'] . ":" . $_POST['time_m_mo'] : "0";
		$di = (isset($_POST['day_di']))? $_POST['time_h_di'] . ":" . $_POST['time_m_di'] : "0";
		$mi = (isset($_POST['day_mi']))? $_POST['time_h_mi'] . ":" . $_POST['time_m_mi'] : "0";
		$do = (isset($_POST['day_do']))? $_POST['time_h_do'] . ":" . $_POST['time_m_do'] : "0";
		$fr = (isset($_POST['day_fr']))? $_POST['time_h_fr'] . ":" . $_POST['time_m_fr'] : "0";
		
		// init essenstage
		$essen  = "";
		$essen .= (isset($_POST['day_essen_mo'])) ? "1": "0";
		$essen .= (isset($_POST['day_essen_di'])) ? "1": "0";
		$essen .= (isset($_POST['day_essen_mi'])) ? "1": "0";
		$essen .= (isset($_POST['day_essen_do'])) ? "1": "0";
		$essen .= (isset($_POST['day_essen_fr'])) ? "1": "0";
		
		$moslemisch = (isset($_POST['moslemisch'])) ? "1": "0";
		$allergien_text   = (isset($_POST['allergie'])) ? $_POST['allergieText']: ""; // Hinweis ausgeben das das Essen unter Umständen nicht möglich ist oder so
		$_POST['allergie'] = (isset($_POST['allergie'])) ? 1: 0;
		$ferien = (isset($_POST['ferien'])) ? 1 : 0 ;
		
		// init sozialleistungen
		$sozial = 0;
		$sozial += (isset($_POST['hartz4'])) 	? pow(2,0) : 0;
		$sozial += (isset($_POST['wohngeld']))  ? pow(2,1) : 0;
		$sozial += (isset($_POST['schulbuch'])) ? pow(2,2) : 0;
		
		// init jahreseinkommen
		$jahreseinkommen = $_POST['einkommens2'];
	
		$status = (isset($_POST['agb']) && $_POST['agb'] == 1) ? 1 : 0; 
		
		// flags für die pdf
		$sozial_pdf 	= (isset($_POST['sozial_pdf']))? $_POST['sozial_pdf'] : 0;
		$zuschuss_pdf 	= (isset($_POST['zuschuss_pdf']))? $_POST['zuschuss_pdf'] : 0;
		$zuschuss_essen	= (isset($_POST['zuschuss_essen']))? $_POST['zuschuss_essen'] : 0;
		//Zuschuss wird immer angenommen, Möglichkeit entfernt
		$zuschuss_pdf = 0;
		
		$b_id = $sql->insertBank($_POST['bank_name'],$_POST['bank_blz'],$_POST['bank_ktnr'],$_POST['bank_vorname'],$_POST['bank_method'], $_POST['bank_sonstige'], $_POST['bank_holder'], $_POST['bank_iban'], $_POST['bank_bic']);
		
		$sql->insertBetreuungsauftrag($s_id, $_SESSION['schul_id'], $anfang, $ende, $mo, $di, $mi, $do, $fr, $essen, $moslemisch, $_POST['allergie'], 
							$allergien_text, $sozial, $jahreseinkommen, $ferien, $_POST['geschwister'], "$_POST[arzt]", "", $b_id, $status, $sozial_pdf, 
							$zuschuss_pdf, $zuschuss_essen, $_POST['kinder'], $_POST['einkommens'], $_POST['verdienst'], $_POST['unterhalt'], $_POST['urlaubsgeld'], 
							$_POST['vermietung'], $_POST['werbungskosten'], $_POST['sonst_einkuenfte']);
		
		sendMsg($status, "insert", $schoolName, $_POST['s_vorname']." ".$_POST['s_nachname'],$user);
			
		$_SESSION['pdf_sid'] = $s_id;
		echo "<h2>Auftrag wurde eingetragen!</h2>";
		echo "Bitte drucken Sie die folgende PDF aus und unterschreiben diese. Ohne Unterschrift ist der Vertrag nicht gültig.<br/>";
		echo "<a href=\"$dateien_content[betreuungsauftraege_PDF]\">PDF</a>";
    }
    elseif($page == md5('anlegen')) { 
	?>
		
<script type="text/javascript">
	$(document).ready(function() {
	
		kostenberechnungTab("jahresausgleich");
		var calc_status = "jahresausgleich";
	
		$("#allergieText").hide();
		
		<?php
			if(isset($_SESSION['saveData']) && $_SESSION['saveData']){
				echo "calc();";
				echo "insert_ort('s_plz','s_ort',0);";
				echo "insert_ort('e1_plz','e1_ort',0);";
				
				if(isset($_SESSION['hartz4']) AND $_SESSION['hartz4'] AND isset($_SESSION['schulbuch']) AND $_SESSION['schulbuch'])
					echo "kostenberechnungTab('sozial');";
				else
					echo "kostenberechnungTab('jahresausgleich');";
			}
		?>
		
		date_status = 1;
		date_endstatus = 1;
		
		window.onbeforeunload = confirmExit; //Sicherheitsabfrage vorm Schließen der Seite
		setNeedToConfirm();
		
		$("#auftrag").validate(
			{
				rules: {
					s_nachname : {
						required	: true
					},
					s_vorname : {
						required	: true
					},
					s_strasse : {
						required	: true
					},
					s_ort: {
						required	: false
					},
					e1_nachname: {
						required	: true
					},
					e1_vorname: {
						required	: true
					},
					e1_fon_privat: {
						required	: true
					},
					e1_ort: {
						required	: false
					},
					e1_strasse: {
						required	: true
					},
					accept:{
						required	: true
					}
				},
				messages: {
					s_nachname		: "Bitte einen Nachnamen angeben!",
					s_vorname 		: "Bitte einen Vornamen angeben!",
					s_strasse 		: "Bitte eine Stra&szlig;e angeben!",
					s_ort 			: "Bitte einen Ort angeben!",
					
					e1_nachname: {
						required	: "Bitte einen Nachnamen angeben!"
					},
					e1_vorname: {
						required	: "Bitte einen Vornamen angeben!"
					},
					e1_ort: {
						required	: "Bitte einen Ort angeben!"
					},
					e1_strasse: {
						required	: "Bitte geben Sie eine Stra&szlig;e ein!"
					},
					e1_fon_privat: {
						required	: "Bitte eine Telefonnummer angeben!"
					},
					accept:{
						required	: "Bitte stimmen Sie folgendem Text zu.<br/>"
					}
				}
			} 
		);
	});
	
	function kostenberechnungTab(el){
		
		$("div#jahresausgleich").css("display", "none");
		$("div#berechnung").css("display", "none");
		$("div#sozial").css("display", "none");
		
		calc_status = el;
		calc();
		$("div#"+el).css("display", "block");
	}
	
	function showText(){
		$("#allergieText").toggle();
	}

	function change_dateform(){
		if (date_status){
			$("div#date").html('<input name="start_date" id="start_date" class="date-pick"/><input type="hidden" name="start" value="manuell" />');
			$('.date-pick').datePicker({clickInput:true, startDate:'01/01/2000'});
			date_status = 0;
		}else{
			$("div#date").html('<select name="start"><option value="this">Rückwirkend zu diesem Schuljahresbeginn</option><option value="now" selected="selected">Ab sofort</option><option value="next">Kommendes Schuljahr</option></select>');
			date_status = 1;
		}
	}

	function change_enddateform(){
		if (date_endstatus){
			$("div#enddate").html('<input name="end_date" id="end_date" class="date-picker"/><input type="hidden" name="ende" value="manuell" />');
			$('.date-picker').datePicker({clickInput:true, startDate:'01/01/2000'});
			date_endstatus = 0;
		}else{
			$("div#enddate").html('<?php echo getTimeBoxY("ende");?>');
			date_endstatus = 1;
		}
	}

	$(function() {
		$('label').tooltip(
			{
			delay : 0
			}
		);	
	});

	function check_time(hour, minute, day){
		<?php
			$sql = new Sql;
			$school = $sql->getSchoolData($_SESSION["schul_id"]);
			// $school wird weiter unten auch verwendet, für ba und essen
		?>
		time = parseInt(document.getElementsByName(hour)[0].value) * 100 + parseInt(document.getElementsByName(minute)[0].value);
			
		switch (day) {			
		case "mon": 	
					<?php
						$time = explode(":",$school->time_mon); 
					?>
						max_time = <?php echo $time[0]; echo $time[1];?>;
						max_hour = <?=$time[0]?>;
						max_minute = <?=$time[1]?>;
						tag = "Montags";
						break;
	 
		case "tue": 	
					<?php
						$time = explode(":",$school->time_tue);
					?>
						max_time = <?php echo $time[0]; echo $time[1];?>;
						max_hour = <?=$time[0]?>;
						max_minute = <?=$time[1]?>;
						tag = "Dienstags";
						break;
	 
		case "wen": 	
					<?php
						$time = explode(":",$school->time_wen);
					?>
						max_time = <?php echo $time[0]; echo $time[1];?>;
						max_hour = <?=$time[0]?>;
						max_minute = <?=$time[1]?>;
						tag = "Mittwochs";
						break;
	 
		case "thu": 	
					<?php
						$time = explode(":",$school->time_thu);
					?>
						max_time = <?php echo $time[0]; echo $time[1];?>;
						max_hour = <?=$time[0]?>;
						max_minute = <?=$time[1]?>;
						tag = "Donnerstags";
						break;
	 
		case "fri": 	
					<?php
						$time = explode(":",$school->time_fri);
					?>
						max_time = <?php echo $time[0]; echo $time[1];?>;
						max_hour = <?=$time[0]?>;
						max_minute = <?=$time[1]?>;




						tag = "Freitags";
						break;
	  }
	if (time > max_time){
		alert(tag + " ist die Betreuung bis maximal " + max_hour + ":" + max_minute + " Uhr möglich.");
		document.getElementsByName(hour)[0].value = max_hour;
		document.getElementsByName(minute)[0].value = max_minute;
	}

		
	}
</script>	
	
		<br />
		<h3>Sch&uuml;ler</h3><?php 

		/* Wenn die Schule kein Essen anbietet kann der Teil auch ausgeblendet werden */
		if(!($school->launch_mon || $school->launch_tue || $school->launch_wen || $school->launch_thu || $school->launch_fri)){
			?>
			<style>
			.essen{
				display: none;
			}
			</style>
			<?php
		}	

	if(isset($_SESSION['saveData']) && $_SESSION['saveData']){
		
		// 
		$s_nachname 		= $_SESSION['s_nachname'];
		$s_strasse 			= $_SESSION['s_strasse']; 
		
		$e1_nachname 		= $_SESSION['e1_nachname'];
		$e1_vorname 		= $_SESSION['e1_vorname']; 	
		$e1_strasse 		= $_SESSION['e1_strasse']; 	
		$e1_plz 			= $_SESSION['e1_plz']; 		
		$e1_fon_privat 		= $_SESSION['e1_fon_privat'];
		$e1_fon_dienst 		= $_SESSION['e1_fon_dienst']; 		
		
		$e2_nachname 		= $_SESSION['e2_nachname']; 		
		$e2_vorname 		= $_SESSION['e2_vorname']; 		
		$e2_fon_dienst 		= $_SESSION['e2_fon_dienst']; 		
		
		$geschwister 		= (int)$_SESSION['geschwister'];
		$geschwister++;
		
		$kinder 			= $_SESSION['kinder']; 
		
		$einkommen 			= $_SESSION['einkommens']; 		
		$hartz4				= $_SESSION['hartz4'];
		$schulbuch			= $_SESSION['schulbuch'];
		$zuschuss_pdf 		= $_SESSION['zuschuss_pdf'];
		$zuschuss_essen		= $_SESSION['zuschuss_essen'];
		$sozial_pdf 		= $_SESSION['sozial_pdf'];
		
		$agb 				= $_SESSION['agb']; 				
		
		$bank_method 		= $_SESSION['bank_method']; 		
		$bank_name 			= $_SESSION['bank_name']; 			
		$bank_vorname 		= $_SESSION['bank_vorname'];
		$bank_blz 			= $_SESSION['bank_blz']; 			
		$bank_ktnr 			= $_SESSION['bank_ktnr'];
		$bank_iban 			= $_SESSION['bank_iban']; 			
		$bank_bic 			= $_SESSION['bank_bic']; 			
		$bank_sonstige 		= $_SESSION['bank_sonstige'];	
		
		$calcEinkommen 		= 	$_SESSION['calcEinkommen'];			
		$verdienst 			= 	$_SESSION['verdienst'];			
		$unterhalt 			= 	$_SESSION['unterhalt'];			
		$urlaubsgeld 		= 	$_SESSION['urlaubsgeld'];			
		$vermietung 		= 	$_SESSION['vermietung'];			
		$werbungskosten 	=	$_SESSION['werbungskosten'];	
		$sonst_einkuenfte 	= 	$_SESSION['sonst_einkuenfte'];		
		
		// set values to default
		$_SESSION['saveData'] = 0;

		$_SESSION['s_nachname'] = "";
		$_SESSION['s_strasse'] = ""; 	
		
		$_SESSION['e1_nachname'] = "";
		$_SESSION['e1_vorname'] = ""; 	
		$_SESSION['e1_strasse'] = ""; 	
		$_SESSION['e1_fon_privat'] = "";
		$_SESSION['e1_fon_dienst'] = ""; 		
		
		$_SESSION['e2_nachname'] = ""; 		
		$_SESSION['e2_vorname'] = ""; 		
		$_SESSION['e2_fon_dienst'] = ""; 		
		
		$_SESSION['geschwister'] = ""; 		
		$_SESSION['kinder'] = ""; 	
		
		$_SESSION['calcEinkommen'] = ""; 		
		
		$_SESSION['agb'] = ""; 				
		$_SESSION['hartz4'] = "";
		$_SESSION['schulbuch'] = "";
		$_SESSION['zuschuss_pdf'] = "";
		$_SESSION['zuschuss_essen'] = "";
		$_SESSION['sozial_pdf'] = "";
		
		$_SESSION['agb'] = "";	
		
		$_SESSION['calcEinkommen']	= 	"";			
		$_SESSION['verdienst']		= 	"";			
		$_SESSION['unterhalt']		= 	"";			
		$_SESSION['urlaubsgeld']	= 	"";			
		$_SESSION['vermietung']		= 	"";			
		$_SESSION['werbungskosten']	= 	"";	
		$_SESSION['sonst_einkuenfte']= 	"";
					
	}else{
		$s_nachname = "";
		$s_strasse = "";
	
		$e1_nachname = "";
		$e1_vorname = "";	
		$e1_vorname = "";
		$e1_strasse = ""; 	
		$e1_fon_privat = "";		
		$e1_fon_dienst = "";
		
		$e2_nachname = "";
		$e2_vorname = "";
		$e2_fon_dienst = "";
		
		$geschwister = "";
		$kinder="";
		
		$einkommen = "";
		$hartz4		= "";
		$schulbuch	= "";
		$zuschuss_pdf = "";
		$zuschuss_essen = "";
		$sozial_pdf="";
		
		$agb = "";
		
		$calcEinkommen 		= 0;			
		$verdienst 			= 0;			
		$unterhalt 			= 0;			
		$urlaubsgeld 		= 0;			
		$vermietung 		= 0;			
		$werbungskosten 	= 0;	
		$sonst_einkuenfte 	= 0;		
	}
?>

		<form action="index.php?content=<?php echo $self ?>&p=<?php echo md5('eintragen');?>" method="post" id="auftrag" onsubmit="return checkAufsichtsperson();">
		
			<input type="hidden" name="schul_id" id="schul_id" value="<?php echo $_SESSION['schul_id']; ?>" />
			<div style="text-align:right;">
				<span>Geschwisterkinder anlegen</span><input type="checkbox" name="saveData" value="1" />
			</div>
			<fieldset>
				<label for="s_nachname" id="l_nachname" >Nachname: *</label>
				<input maxlength="50" type="text" name="s_nachname" id="s_nachname" value="<?php echo $s_nachname ?>" class="required" /><br /> 
				
				<label for="s_vorname" >Vorname: *</label>
				<input maxlength="50" type="text" name="s_vorname" id="s_vorname" value=""/><br /> 
				
				<label for="s_strasse" >Strasse / Nr.: *</label>
				<input maxlength="50" type="text" name="s_strasse" id="s_strasse" value="<?php echo $s_strasse ?>"/><br />
				
				<?php echo getPlzForm("s_plz","s_ort"); ?><br />
				<?php echo getKlassenForm("s_klasse"); ?><br />
				<br />
				<label for="s_geburtsdatum" >Geburtsdatum: *</label>
				<?php
					echo getDateBox("bday");
				?>
				<br /> 
			</fieldset>
			<br />
			<div style="height: 30px;"> 
				<h3 style="display:inline;">Erziehungsberechtigten</h3>
				
			</div>
			<fieldset>
				<h4>1. Erziehungsberechtigten <a href="javascript:void(0);" onclick="applySchuelerData();"><img src="icon/box_download_48.png" border="0" width="24" height="24" /></a></h4>
				<label for="e1_nachname" >Nachname: *</label>
				<input  maxlength="50" type="text" name="e1_nachname" id="e1_nachname" value="<?php echo $e1_nachname; ?>" /><br /> 
				
				<label for="e1_vorname" >Vorname: *</label>
				<input maxlength="50" type="text" name="e1_vorname" id="e1_vorname" value="<?php echo $e1_vorname; ?>"/><br /> 
				
				<label for="e1_strasse" >Strasse/Nr: *</label>
				<input maxlength="50" type="text" name="e1_strasse" id="e1_strasse" value="<?php echo $e1_strasse; ?>"/><br /> 
				
				<?php echo getPlzForm("e1_plz","e1_ort"); ?><br />
				
				<label for="e1_fon_privat" >Telefon privat: *</label>
				<input maxlength="50" type="text" name="e1_fon_privat" id="e1_fon_privat" value="<?php echo $e1_fon_privat; ?>"/><br />
				
				<label for="e1_fon_dienst" >Telefon dienstlich:</label>
				<input maxlength="50" type="text" name="e1_fon_dienst" id="e1_fon_dienst" value="<?php echo $e1_fon_dienst; ?>"/><br />
				<br />
				<h4>2. Erziehungsberechtigten <a href="javascript:void(0);" onclick="$('#e2_nachname').val($('#s_nachname').val());"><img src="icon/box_download_48.png" border="0" width="24" height="24" /></a></h4>
				<label for="e2_nachname" >Nachname:</label>
				<input  maxlength="50" type="text" name="e2_nachname" id="e2_nachname" value="<?php echo $e2_nachname; ?>"/><br /> 
				
				<label for="e2_vorname" >Vorname:</label>
				<input maxlength="50" type="text" name="e2_vorname" id="e2_vorname" value="<?php echo $e2_vorname; ?>"/><br /> 
				
				<label for="e2_fon_dienst" >Telefon dienstlich:</label>
				<input maxlength="50" type="text" name="e2_fon_dienst" id="e2_fon_dienst" value="<?php echo $e2_fon_dienst; ?>"/><br />
			</fieldset>
			<br />
			<h3>Geschwister mit Betreungsauftrag</h3>
			<fieldset>
				<label for="geschwister">Keins</label>
				<input type="radio" name="geschwister" id="geschwister" value="0" checked="checked" <?php if($geschwister == 0){echo 'checked="checked"';}else if($geschwister==""){echo 'checked="checked"';} ?> /><br />
				<label for="geschwister">1</label>
				<input type="radio" name="geschwister" id="geschwister" value="1" <?php if($geschwister == 1){echo 'checked="checked"';} ?> /><br />
				<label for="geschwister">2</label>
				<input type="radio" name="geschwister" id="geschwister" value="2" <?php if($geschwister == 2){echo 'checked="checked"';} ?> /><br />
				<label for="geschwister">mehr als 2</label>
				<input type="radio" name="geschwister" id="geschwister" value="3" <?php if($geschwister == 3){echo 'checked="checked"';} ?> /><br />
			</fieldset>
			<br />
			<h3>Kostenberechnung</h3>
			<fieldset>
				<input type="hidden" name="einkommens2" id="einkommens2" value="" />
				
				<div id="kostenberechnung">
					<ul>
						<li class="tab"><a class="tab" href="javascript: kostenberechnungTab('jahresausgleich');">Einkommensjahresausgleich</a></li>
						<li class="tab"><a class="tab" href="javascript: kostenberechnungTab('berechnung');">Einkommen berechnen</a></li>
						<li class="tab"><a class="tab" href="javascript: kostenberechnungTab('sozial');">Sozialhilfe</a></li>
					</ul>
				
					<div id="jahresausgleich">
						<label title="Bei Angabe entfällt die Ausfüllung der anderen Felder">Einkommensjahresausgleich</label>
						<input type="text" name="einkommens" id="einkommens" onchange="calc();" value="<?php echo $einkommen; ?>" />
					</div>
					<div id="berechnung">
						<label title="Monatliches Brutto Gehalt" >Brutto Verdienst (monatl.)</label>
						<input type="text" name="verdienst" id="verdienst" value="<?php echo $verdienst;?>" onchange="calc();" />
						<br />
						<label title="Monatlicher Unterhalt" >Unterhalt (monatl.)</label>
						<input type="text" name="unterhalt" id="unterhalt" value="<?php echo $unterhalt;?>" onchange="calc();" />
						<br />
						<label title="Sämtliche Bonuszahlungen die Sie insgesammt für ein Jahr erhalten">Grafikationen <br />(Urlaubs- /Weihnachtsgeld)</label>
						<input type="text" name="urlaubsgeld" id="urlaubsgeld" value="<?php echo $urlaubsgeld;?>" onchange="calc();" />
						<br />
						<br />
						<label title="Monatliche Vermietung beziehungsweise Verpachtung">Monatliche Vermietung/Verpachtung </label>
						<input type="text" name="vermietung" id="vermietung" value="<?php echo $vermietung;?>" onchange="calc();" />
						<br />
						<br />
						<label title="Werbungskosten werden vom Gehalt abgezogen">Werbungskosten</label>
						<input type="text" name="werbungskosten" id="werbungskosten" value="<?php echo $werbungskosten;?>" onchange="calc();" />
						<br />
						<label title="Einkünfte im Sinne des §2 Abs. 1 und 2 Einkommensteuergesetzes (Einkünfte aus Gewerbebetrieb, Einkünfte aus Kapitalvermögen, sonstige Einkünfte im Sinne des § 22 EStG; sonstige Einnahmen, Einnahmen nach dem Arbeitsförderungsgesetz, Elterngeld, Krankengeld, Renten, Mutterschaftsgeld, Hilfe zum Lebensunterhalt nach dem SGB II, Wohngeld sonstiges)">Sonstige montl. Einnahmen</label>
						<input type="text" name="sonst_einkuenfte" id="sonst_einkuenfte" value="<?php echo $sonst_einkuenfte;?>" onchange="calc();" />
					</div>
					<div id="sozial">
						<label title="Bei Angabe entfallen die Kosten des Betreuungsauftrages">ALG 2 Empf.</label>
						<input type="checkbox" name="hartz4" id="hartz4" onclick="calc();" value="1" <?php if($hartz4 == 1)echo 'checked="checked"'; ?> />
						<br />
						<!--<label title="Bei Angabe entfallen die Kosten des Betreuungsauftrages">Schulbuchbefreiung</label>-->
						<input type="checkbox" name="schulbuch" id="schulbuch" onclick="calc();" value="1" style="display: none;" <?php if($schulbuch == 1)echo 'checked="checked"'; ?> />		
					</div>
					
				</div>
				<br/>
				<br/><!--
				<label title="Falls Sie in die entsprechenden Beitragsgruppen fallen, können Sie einen Zuschuss zur Gemeinschafftsverpflegung erhalten.">Kein Zuschuss zur Gemeinschaftsverpflegung</label>-->
				<input type="hidden" name="zuschuss_pdf" id="zuschuss" value="1" <?php if($zuschuss_pdf)echo "checked='checked'"; ?> />
				<!--<br />
				<br />-->
				<label class="" for="zuschuss_essen" title="Wenn Sie die Bedienungen für das Essen für Einen Euro erfüllen können Sie diese Box anhaken.">Essen für 1€</label>
				<input class="" type="checkbox" name="zuschuss_essen" id="zuschuss_essen" value="1" <?php if($zuschuss_essen)echo "checked='checked'"; ?> />
				<br/>
				<label class="essen" title="Ankreuzen, wenn das Essensgeld per Lastschrift, Überweisung oder bar gezahlt wird. Freilassen, wenn das Sozialamt die 20,00 € einbehält.">Kein Einbehalt des Essensgeldes</label>
				<input class="essen" type="checkbox" name="sozial_pdf" id="sozial" value="1" <?php if($sozial_pdf)echo "checked='checked'"; ?> />
				<br class="essen"/>
				<br/>
				<label title="Wieviele Kinder haben Sie, die für den Kinderfreibetrag zu berücksichtigen sind?" >Kinder</label>
				<select name="kinder" id="kinder" onchange="calc();">
					<?php 
						for($i = 1; $i <= 20; $i++){
							if($i == $kinder)
								$selected = 'selected="selected"';

							echo "<option value='".$i."' $selected>".$i.".</option>";
							$selected = "";
						}
					?>
				</select>
				<br />
				<br />	
				<label title="Das gesamte Einkommen, das für die Berechnung zugrunde gelegt wird">Einkommen</label>
				<input type="text" id="calcEinkommen" name="calcEinkommen" value="<?php echo $calcEinkommen;?>" readonly="readonly">
				<br />	
				<br />	
				<label title="Der Vertrag kann auch ohne Verdienstnachweise gespeichert werden, ist dann aber noch nicht aktiv.">Verdienstnachweise sind als Kopie vorhanden, best&auml;tigt durch Aufsichtsperson</label>
				<input type="checkbox" name="agb" id="agb" value="1" <?php if($agb == 1)echo 'checked="checked"'; ?> />
			</fieldset>
			<br />
			<h3>Betreuueungsauftrag</h3>
			<fieldset>
				<label for="" >Anfang:</label>
				<div id="date" style="display: inline;">
					<select name="start">
						<option value="this">Rückwirkend zu diesem Schuljahresbeginn</option>
						<option value="now" selected="selected">Ab sofort</option>
						<option value="next">Kommendes Schuljahr</option>
					</select>
				</div>
				<?php 
					echo "<img title=\"Selbst eingeben\" src=\"".$icon_path."refresh_48.png\" height=\"24\" width=\"24\" alt=\"Selbst eingeben\" style=\"cursor: pointer;\" onclick=\"change_dateform();\" class=\"icon\">"; 
				?>
				<br /><br />
				<label for="" >Ende:</label>
				<div id="enddate" style="display: inline;">
				<?php
					echo getTimeBoxY("ende");	
				?>
				</div>
				<?php 
					echo "<img title=\"Selbst eingeben\" src=\"".$icon_path."refresh_48.png\" height=\"24\" width=\"24\" alt=\"Selbst eingeben\" style=\"cursor: pointer;\" onclick=\"change_enddateform();\" class=\"icon\">"; 
				?>
				<br /><br />
				<?php 
					//echo getDen(); Kein Arzt gewünscht..
				?>
				<input type="hidden" name="arzt" id="arzt" value="0" />
				<label>Betreuungstage</label>
				<table style="width:200px;">
						<tr>
							<th colspan="2">Tag</th>
							<th>Endzeit</th>
						</tr>
					<?php if($school->ba_mon){?>
						<tr>
							<td>Mo</td>
							<td><input type="checkbox" id="day_mo" name="day_mo" value="mo" onclick="$('#ba_mo_td').toggle();" /></td>
							<td style="display:none;" id="ba_mo_td">
								<?php
									echo getTimeBox("time_h_mo","time_m_mo", "16:30", "check_time('time_h_mo','time_m_mo','mon')") . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if($school->ba_tue){?>
						<tr>
							<td>Di</td>
							<td><input type="checkbox" id="day_di" name="day_di" value="di" onclick="$('#ba_di_td').toggle();" /></td>
							<td style="display:none;" id="ba_di_td">
								<?php
									echo getTimeBox("time_h_di","time_m_di", "16:30", "check_time('time_h_di','time_m_di', 'tue')") . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if($school->ba_wen){?>
						<tr>
							<td>Mi</td>
							<td><input type="checkbox" id="day_mi" name="day_mi" value="mi" onclick="$('#ba_mi_td').toggle();" /></td>
							<td style="display:none;" id="ba_mi_td">
								<?php
									echo getTimeBox("time_h_mi","time_m_mi", "16:30", "check_time('time_h_mi','time_m_mi', 'wen')") . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if($school->ba_thu){?>
						<tr>
							<td>Do</td>
							<td><input type="checkbox" id="day_do" name="day_do" value="do" onclick="$('#ba_do_td').toggle();" /></td>
							<td style="display:none;" id="ba_do_td">
								<?php
									echo getTimeBox("time_h_do","time_m_do", "16:30", "check_time('time_h_do','time_m_do', 'thu')") . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if($school->ba_fri){?>
						<tr>
							<td>Fr</td>
							<td><input type="checkbox" id="day_fr" name="day_fr" value="fr" onclick="$('#ba_fr_td').toggle();" /></td>
							<td style="display:none;" id="ba_fr_td">
								<?php
									echo getTimeBox("time_h_fr","time_m_fr", "16:30", "check_time('time_h_fr','time_m_fr', 'fri')") . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if(!$school->ba_mon && !$school->ba_tue && !$school->ba_wen && !$school->ba_thu && !$school->ba_fri){	?>
						<tr>
							<td>Noch keine Tage angelegt!</td>
						</tr>
					<?php } ?>
				</table>
				<br />
				<label>Ferienbetreuung</label>
				<input type="checkbox" name="ferien" id="ferien" value="1" />
				<sub>Jahresbeitrag 120 &euro;</sub>
			</fieldset>
			<br />
			<h3>Zahlungsform</h3>
			<fieldset>
				<?php
					echo getBank();
				?>
			</fieldset>
			<br />
			<?php if(!$school->launch_mon && !$school->launch_tue && !$school->launch_wen && !$school->launch_thu && !$school->launch_fri){	?>
				<style>
					.essen{
						display: none;
					}
				</style>
			<?php } ?>
			<h3 class="essen">Essen</h3>
			<fieldset class="essen">
			<label>Essenstage</label>	
				<table style="width:200px;">
					<?php if($school->launch_mon){?>
						<tr>
							<td>Mo</td>
							<td><input type="checkbox" name="day_essen_mo" value="mo" /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_tue){?>
						<tr>
							<td>Di</td>
							<td><input type="checkbox" name="day_essen_di" value="di" /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_wen){?>
						<tr>
							<td>Mi</td>
							<td><input type="checkbox" name="day_essen_mi" value="mi" /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_thu){?>
						<tr>
							<td>Do</td>
							<td><input type="checkbox" name="day_essen_do" value="do" /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_fri){?>
						<tr>
							<td>Fr</td>
							<td><input type="checkbox" name="day_essen_fr" value="fr" /></td>
						</tr>
					<?php }?>
				</table>
				<label>Moslemisches Essen</label>
				<input type="checkbox" name="moslemisch" value="1" id="moslemisch" />
				<br />
				<label>Allergien / Lebensmittelunvertr&auml;glichkeiten</label>
				<input type="checkbox" name="allergie" value="1" id="allergie" onclick="showText();" />
				<textarea rows="5" cols="5" name="allergieText" id="allergieText"></textarea>
			</fieldset>
			
			<h3>Bestätigung</h3>
			<fieldset>
				<input type="checkbox" name="accept" id="accept" value="1" /> 
				<span>Ich bestätige, dass alle Angaben richtig sind. Aus meinen Angaben folgt ein rechtsgültiger Vertrag zwischen mir und dem jfd-Rheine e.V..</span>
			</fieldset>
			<br />
			<input type="submit" value="Auftrag anlegen" onclick="disable_unload();" name="send" id="send" />
			<br />
			<br />
		</form>
	<?php
    } 
?>