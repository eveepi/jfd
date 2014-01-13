<?php
	checkRights(50,$user);
	$page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$currentPage = (isset($_GET['currentPage']))?$_GET['currentPage']:1;
    $self = 'betreuungsauftraege_verwalten';
?>

<script type="text/javascript">
	$(document).ready(function () {	
	<?php if($page==md5('bearbeiten')){
	
		$sql = new Sql;
		$row = $sql->getBaData($_GET['baid']);		
		
		if($row->ba_einkommensjahresausgleich != 0){
			$art = "jahresausgleich";
		}elseif($row->ba_sozialleistungen){
			$art = "sozial";
		}else{
			$art = "berechnung";
		}?>

		$("#allergieText").hide();
		
		kostenberechnungTab("<?=$art?>");
		var calc_status = "<?=$art?>";
	<?php } ?>
		
		maxPage = $("#pageCount").val();
		currentPage = 1;
		initPage();
		showPage(currentPage);
	});
	
	function initPage(){
		for(i=1;i<=maxPage;i++){
			$(".page"+i).css("display","none");
		}
	}
	
	function checkDelete(id){
		if(confirm("Möchten Sie diesen Betreuungsauftrag wirklich löschen?")){
			window.location = '<?php echo "index.php?content=".$self."&p=".md5('delete')."&baid=" ?>' + id;
		}
	};
	
	function showPage(page){
		$(".page"+currentPage).css("display","none");
		$(".page"+page).css("display","");

		currentPage = page;
		$("#pagePicker>*").each(function(){
			$(this).removeAttr("selected");
			if ($(this).val() == page){
				$(this).attr("selected","selected");
			}
		})
		
		if(currentPage == 1){
			$("#page_left").css("display", "none");
		}else {
			$("#page_left").css("display", "table-cell");
		}
		
		if(currentPage == maxPage){
			$("#page_right").css("display", "none");
		}else {
			$("#page_right").css("display", "table-cell");
		}
	}
	
	function kostenberechnungTab(el){
		
		$("div#jahresausgleich").css("display", "none");
		$("div#berechnung").css("display", "none");
		$("div#sozial").css("display", "none");
		
		calc_status = el;
		calc();
		$("div#"+el).css("display", "block");
	}
	
	function changePage(){
		showPage($("#pagePicker>*:selected").val());
	}
	
	function nextPage(){
		if (currentPage != maxPage)
			showPage(currentPage + 1);
	}

	function prevPage(){
		if (currentPage != 1)
			showPage(currentPage - 1);
	}
	
	function showText(){
		$("#allergieText").toggle();
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

<h1> Betreuungsaufträge verwalten</h1>
<?php
	if($page==md5('pdf')){
		
		// schüler id in die session schreiben, da diese in der pdf generierung benötigt wird
		$_SESSION['pdf_sid'] = $_GET['sid'];
		header("location: $dateien_content[betreuungsauftraege_PDF]");		
	}
?>
<?php if($page==md5('verwalten')) {	

		$_POST['action'] = isset($_POST['action']) ? $_POST['action'] : '';

		if($_POST['action'] == "edit"){
			$sql = new Sql;	
		
			// daten aufbereitung für update		
			$tmp = explode("/",$_POST['start_date']);
			$anfang = $tmp[2]."-".$tmp[1]."-".$tmp[0];		
	
			$tmp = explode("/",$_POST['end_date']);
			$ende = $tmp[2]."-".$tmp[1]."-".$tmp[0];	
	
			$mo = (isset($_POST['day_mo']))? $_POST['time_h_mo'] . ":" . $_POST['time_m_mo'] : "0";
			$di = (isset($_POST['day_di']))? $_POST['time_h_di'] . ":" . $_POST['time_m_di'] : "0";
			$mi = (isset($_POST['day_mi']))? $_POST['time_h_mi'] . ":" . $_POST['time_m_mi'] : "0";
			$do = (isset($_POST['day_do']))? $_POST['time_h_do'] . ":" . $_POST['time_m_do'] : "0";
			$fr = (isset($_POST['day_fr']))? $_POST['time_h_fr'] . ":" . $_POST['time_m_fr'] : "0";
			
			$essen  = "";
			$essen .= (isset($_POST['day_essen_mo'])) ? "1": "0";
			$essen .= (isset($_POST['day_essen_di'])) ? "1": "0";
			$essen .= (isset($_POST['day_essen_mi'])) ? "1": "0";
			$essen .= (isset($_POST['day_essen_do'])) ? "1": "0";
			$essen .= (isset($_POST['day_essen_fr'])) ? "1": "0";
	
			$moslemisch = ( isset($_POST['moslemisch'])) ? $_POST['moslemisch']: "0";
			$_POST['allergie'] = (isset($_POST['allergie'])) ? 1: 0;
			$allergien_text   = ($_POST['allergie'] == 1) ? $_POST['allergieText']: ""; // Hinweis ausgeben das das Essen unter umständen nicht möglich ist oder so
			$ferien = (isset($_POST['ferien'])) ? 1 : 0 ;
			
			// init sozialleistungen
			$sozial = 0;
			$sozial += (isset($_POST['hartz4'])) 	? pow(2,0) : 0;
			$sozial += (isset($_POST['wohngeld']))  ? pow(2,1) : 0;
			$sozial += (isset($_POST['schulbuch'])) ? pow(2,2) : 0;
	
			$einkommensSum = $_POST['einkommens2'];
			
			//status
			$status = (isset($_POST['agb']) && $_POST['agb'] == 1) ? 1 : 0; 
			//Hole alte Daten für Vergleich mit den neuen für die Benachrichtigung
			$pre = $sql->getBaData($_POST['ba_id']);

			// flags für die pdf
			$sozial_pdf 	= (isset($_POST['sozial_pdf']))? $_POST['sozial_pdf'] : 0;
			$zuschuss_pdf 	= (isset($_POST['zuschuss_pdf']))? $_POST['zuschuss_pdf'] : 0;
			$zuschuss_essen = (isset($_POST['zuschuss_essen']))? $_POST['zuschuss_essen'] : 0;
			//$zuschuss_pdf derzeit nicht genutzt
			$zuschuss_pdf 	=  0;
			
			$sql->updateBa($_POST['ba_id'], $anfang, $ende,  $_POST['arzt'], $mo,  $di, $mi,  $do, $fr, $essen, $moslemisch, $allergien_text, 
							$ferien, $_POST['geschwister'], '', $_POST['changeReason'], $einkommensSum, $sozial, $status, $sozial_pdf, $zuschuss_pdf, $zuschuss_essen,
							$_POST['kinder'], $_POST['einkommens'], $_POST['verdienst'], $_POST['unterhalt'], $_POST['urlaubsgeld'], 
							$_POST['vermietung'], $_POST['werbungskosten'], $_POST['sonst_einkuenfte']);
							
			$sql->updateBank($_POST['bank_id'], $_POST['bank_name'], $_POST['bank_blz'], $_POST['bank_ktnr'], $_POST['bank_vorname'], $_POST['bank_method'], $_POST['bank_sonstige']);
			
			echo "Änderung wurde gespeichert!<br/><br/>";
			
			$name = $sql->getSchuelerNameByBaID($_POST['ba_id']);
			$past = $sql->getBaData($_POST['ba_id']);
			sendMsg($status, "update", $schoolName, $name, $user, $pre, $past);			
			
		}
		
		$_GET['gueltigkeit'] = isset($_GET['gueltigkeit']) ? $_GET['gueltigkeit'] : '1';
		$_GET['b'] = isset($_GET['b']) ? $_GET['b'] : '';
		$_GET['status'] = isset($_GET['status']) ? $_GET['status'] : 1;
?>

		
		<span>
			<a href="<?php echo "index.php?content=".$self."&status=".$_GET['status']."&gueltigkeit=".$_GET['gueltigkeit'] ?>">Alle</a>&nbsp;
			<?php
			for($i=65;$i <= 90;$i++){
				echo "<a href=\"index.php?content=".$self."&b=". chr($i)."&status=$_GET[status]&gueltigkeit=$_GET[gueltigkeit]\"";
				if (chr($i) == $_GET['b']) { echo "class=\"selected\" "; }
				echo ">&nbsp;".chr($i)."&nbsp;</a>&nbsp;";
			}
			?>
		</span>
		<br />
		<br />
    	
    	<?php
		echo "<div id='statusChooser'>";
		echo "<a href=\"index.php?content=".$self."&b=$_GET[b]&status=1&gueltigkeit=$_GET[gueltigkeit]\" ";
		if (1 == $_GET['status']) { echo "class=\"selected\" ";}
		echo ">Aktiviert</a> ";
		echo " <a href=\"index.php?content=".$self."&b=$_GET[b]&status=0&gueltigkeit=$_GET[gueltigkeit]\" ";
		if (0 == $_GET['status']) {	echo "class=\"selected\" "; }
		echo ">Deaktiviert</a></div><br />";		

		echo "<div id='gueltigkeitChooser'>";
		echo "<a href=\"index.php?content=".$self."&b=$_GET[b]&gueltigkeit=0&status=$_GET[status]\" ";
		if (0 == $_GET['gueltigkeit']) {	echo "class=\"selected\" ";}
		echo ">Abgelaufen</a> ";
		echo "<a href=\"index.php?content=".$self."&b=$_GET[b]&gueltigkeit=1&status=$_GET[status]\" ";
		if (1 == $_GET['gueltigkeit']) {	echo "class=\"selected\" ";}
		echo ">Aktuell</a> ";
		echo " <a href=\"index.php?content=".$self."&b=$_GET[b]&gueltigkeit=2&status=$_GET[status]\" ";
		if (2 == $_GET['gueltigkeit']) { echo "class=\"selected\" ";	}
		echo ">Zukünftig</a></div><br />";

    
	$sql = "SELECT 
			$tbl_betreuungsauftraege[id], 
			$tbl_betreuungsauftraege[essenstage],
			$tbl_betreuungsauftraege[montag],
			$tbl_betreuungsauftraege[dienstag],
			$tbl_betreuungsauftraege[mitwoch],
			$tbl_betreuungsauftraege[donnerstag],
			$tbl_betreuungsauftraege[freitag],
			$tbl_betreuungsauftraege[ferien], 	
			$tbl_schueler[id],
			$tbl_schueler[name], 
			$tbl_schueler[vorname]
			FROM 
			$tbl_betreuungsauftraege[tbl], 
			$tbl_schueler[tbl]
			WHERE 
			$tbl_betreuungsauftraege[schul_id] = $_SESSION[schul_id]
			AND $tbl_betreuungsauftraege[schueler_id] = $tbl_schueler[id]
			AND $tbl_schueler[name] like '$_GET[b]%'";
	if(isset($_GET['status']) AND $_GET['status'] == 0)
		$sql .= " AND ba_status=0 ";
	else
		$sql .= " AND ba_status=1 ";

	if(isset($_GET['gueltigkeit']) AND $_GET['gueltigkeit'] == 0)//vergangen
		$sql .= " AND ba_ende < NOW()";
	elseif(isset($_GET['gueltigkeit']) AND $_GET['gueltigkeit'] == 2)//zukünftig
		$sql .= " AND ba_anfang > NOW()";	
	else//aktuell
		$sql .= " AND ba_ende >= NOW() AND ba_anfang <= NOW()";		
	
	$userrights = $user->getStatus();
			
	$sql.= "ORDER BY 
			$tbl_schueler[name];";		
			
    $result = mysql_query($sql);
    
    if ($row = mysql_fetch_assoc($result)){
    ?>
     <table cellpadding='5' cellspacing='0'>
		<tr>
			<th>Name</th>
			<?php if ($userrights >= 200){echo "<th>Schüler-ID</th>";}?>
			<th>Betreuung</th>
			<th>Essen</th>
			<th>Ferienbetreuung</th>
			<th></th>
			<th></th>
			<?php if ($userrights >= 200){echo "<th></th><th></th>";}?>
		</tr>
		<?php 		
    	// init der variablen
		$betr = ($row[$tbl_betreuungsauftraege['montag']] != "" ||  $row[$tbl_betreuungsauftraege['dienstag']] != "" || $row[$tbl_betreuungsauftraege['mitwoch']] != "" || $row[$tbl_betreuungsauftraege['donnerstag']] != "" || $row[$tbl_betreuungsauftraege['freitag']] != "")? 1 : 0;
    	$essen = ($row[$tbl_betreuungsauftraege['essenstage']] > 0)? 1: 0;
    	$ferien = ($row[$tbl_betreuungsauftraege['ferien']] > 0)? 1: 0;
    	
    	if ($betr){	$betr = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28\" alt=\"ja\">";
    	} else { $betr = "";}
    	if ($essen){ $essen = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28\" alt=\"ja\" >";
    	} else { $essen = "";}
    	if ($ferien){ $ferien = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28\" alt=\"ja\" >";
    	} else { $ferien = "";}
		
		$i = 0;
		$page = 1;
		$pageCount = -1;
		
	do{
    	$i = ($i - 1) * ($i - 1);
    	$pageCount++;
    	if($pageCount == LIMIT_NUM){
			$pageCount = 0;
			$page++;
    	}
    	// init der variablen
		$betr = ($row[$tbl_betreuungsauftraege['montag']] != "" ||  $row[$tbl_betreuungsauftraege['dienstag']] != "" || $row[$tbl_betreuungsauftraege['mitwoch']] != "" || $row[$tbl_betreuungsauftraege['donnerstag']] != "" || $row[$tbl_betreuungsauftraege['freitag']] != "")? 1 : 0;
    	$essen = ($row[$tbl_betreuungsauftraege['essenstage']] > 0)? 1: 0;
    	$ferien = ($row[$tbl_betreuungsauftraege['ferien']] > 0)? 1: 0;
    	
    	if ($betr){	$betr = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28\" alt=\"ja\" >";
    	} else { $betr = "";}
    	if ($essen){ $essen = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28 alt=\"ja\" > ";
    	} else { $essen = "";}
    	if ($ferien){ $ferien = "<img src=\"".$icon_path."accepted_48.png\" height=\"28\" width=\"28\" alt=\"ja\" >";
    	} else { $ferien = "";}
		
        echo "<tr class=\"row".$i." page".$page."\">";
        echo "	<td>".$row[$tbl_schueler['name']].", ".$row[$tbl_schueler['vorname']]."</td>";
        if ($userrights >= 200){echo "<td>".$row[$tbl_schueler['id']]."</td>";}
        echo "	<td align=\"center\" class=\"betr\">$betr</td>";
        echo "	<td align=\"center\" class=\"essen\">$essen</td>";
        echo "	<td align=\"center\" class=\"ferien\">$ferien</td>"; 
        echo "	<td><a href=\"index.php?content=".$self."&p=".md5('pdf')."&sid=".$row[$tbl_schueler['id']]."\">";
		if($_GET["status"] == 1)
			echo "<img  title=\"Drucken\" src=\"".$icon_path."printer_48.png\" height=\"28\" width=\"28\" alt=\"pdf\" class=\"icon\">";
		echo "	</a></td>";
        echo "	<td align=\"center\"><a href=\"index.php?content=".$self."&p=".md5('bearbeiten')."&baid=".$row[$tbl_betreuungsauftraege['id']]."\">
				<img title=\"Bearbeiten\" src=\"".$icon_path."pencil_48.png\" height=\"28\" width=\"28\" alt=\"bearbeiten\" class=\"icon\">
				</a></td>";
		echo "	<td align=\"center\"><a href=\"index.php?content=".$self."&p=".md5('detail')."&baid=".$row[$tbl_betreuungsauftraege['id']]."\">
				<img title=\"Protokol\" src=\"".$icon_path."search_48.png\" height=\"28\" width=\"28\" alt=\"Protokol\" class=\"icon\">
				</a></td>
				
			";
		if ($userrights >= 200){
			echo   "<td align=\"center\"><a href=\"javascript: checkDelete('".$row[$tbl_betreuungsauftraege['id']]."');\">
					<img title=\"Löschen\" src=\"".$icon_path."cross_48.png\" height=\"28\" width=\"28\" alt=\"löschen\" class=\"icon\">
					</a></td>";
			echo   "<td align=\"center\"><a href=\"index.php?content=".$self."&p=".md5('move_select')."&baid=".$row[$tbl_betreuungsauftraege['id']]."\">
					<img title=\"Schüler verschieben\" src=\"".$icon_path."move.png\" height=\"28\" width=\"28\" alt=\"verschieben\" class=\"icon\">
					</a></td>";
		}
        echo "</tr>";
    }while($row = mysql_fetch_assoc($result)); 
    ?>
	</table>
	<hr />
	<!-- Seitenwähler -->
	<div align="left" id="pagePicker">
	<img title="vorherige Seite" id="page_left" src=<?php echo "'".$icon_path."arrow_left_green_48.png'"; ?> onclick="prevPage();" style="display: table-cell; vertical-align: middle; cursor: pointer;" height="28" width="28" alt="vorherige Seite" class="icon" >
	<select name="pagePicker" id="pagePicker" onchange="changePage();" style="display: table-cell; vertical-align: middle;">
	<?php 
	for($i=1; $i <= $page; $i++){
		echo "<option value='".$i."'>Seite ".$i."</option>";
	}
	?>
	</select>
	<img title="n&auml;chste Seite" id="page_right" src=<?php echo "'".$icon_path."arrow_right_green_48.png'"; ?> onclick="nextPage();" style="display: table-cell; vertical-align: middle; cursor: pointer;" height="28" width="28" alt="n&auml;chste Seite" class="icon">
	</div>

	<div align="right" id="printFooter">
		Alle drucken: <?php echo "<a href='javascript:void(0);' onclick='window.print();'> <img  title=\"Drucken\" src=\"".$icon_path."printer_48.png\" height=\"28\" width=\"28\" alt=\"pdf\"></a>"; ?>
	</div>
	<?php
    echo "<input type='hidden' name='pageCount' id='pageCount' value='".$page."'";	
    } else {
		echo "Es sind keine Einträge vorhanden";
		
    }


} elseif($page==md5('bearbeiten')) {
    
		echo "<h2>Betreuungsauftrag bearbeiten (Schüler-ID: ".$row->ba_schueler_ID.")</h2>";

		// init sozialleistungen
		$sozial = $row->ba_sozialleistungen;
		$hartz4 = false;
		$wohngeld = false;
		$schulbuch = false;
		
		if($sozial >= 4){
			$sozial -= 4;
			$schulbuch= true;
		}

		if($sozial >= 2){
			$sozial -= 2;
			$wohngeld = true;
		}

		if($sozial >= 1){
			$sozial -= 1;
			$hartz4 = true;
		}
		
		if(!($school->launch_mon || $school->launch_tue || $school->launch_wen || $school->launch_thu || $school->launch_fri)){
		?>
		<style>
		.essen{
			display: none;
		}
		</style>
		<?php
		}
		
?>
		<script type="text/javascript">
			$(document).ready(function () {
					if('<?php echo $row->ba_allergien_text ?>' != "")
						$("#allergieText").show();
					$('.date-pick').datePicker({clickInput:true, startDate:'01/01/2000'});
					//$('.date-picker').datePicker({clickInput:true, startDate:'01/01/2000'});
			});
		</script>
		<br />
		<a class="button" href="index.php?content=schueler_verwalten&p=7b77ba4ee8c2d1c9a348fee869eea6be&s_id=<?=$row->s_id ?>&back=<?=$_GET['baid']?>">Schüler bearbeiten</a>
		<a class="button" href="index.php?content=erziehungsberechtigte_verwalten&p=7b77ba4ee8c2d1c9a348fee869eea6be&s_id=<?=$row->e_id ?>&back=<?=$_GET['baid']?>">Erziehungsberechtigte bearbeiten</a>
		<br />
		<form action="index.php?content=<?=$self?>&p=<?php echo md5('verwalten');?>&status=<?php echo $row->ba_status; ?>" method="post">
			<input type="hidden" name="action" value="edit" />
			<input type="hidden" name="ba_id" value="<?php echo $_GET['baid']; ?>" />
			<input type="hidden" name="bank_id" value="<?php echo $row->ba_bankverbindungs_ID; ?>" />
			<input type="hidden" name="schul_id" id="schul_id" value="<?php echo $_SESSION['schul_id']; ?>" />
			<input type="hidden" name="einkommens2" id="einkommens2" value="<?=$row->ba_jahreseinkommen?>" />
			<br />
			<h3>Kostenberechnung</h3>
			<fieldset>
				<div id="kostenberechnung">
					<ul>
						<li class="tab"><a class="tab" href="javascript: kostenberechnungTab('jahresausgleich');">Einkommensjahresausgleich</a></li>
						<li class="tab"><a class="tab" href="javascript: kostenberechnungTab('berechnung');">Einkommen berechnen</a></li>
						<li class="tab"><a class="tab" href="javascript: kostenberechnungTab('sozial');">Sozialhilfe</a></li>
					</ul>
				
					<div id="jahresausgleich">
						<label title="Geben Sie hier Ihren letzen Einkommensjahresausgleich an. Wenn dieser nicht vorliegt wählen Sie 'Einkommen berechnen'">Einkommensjahresausgleich</label>
						<input type="text" name="einkommens" id="einkommens" onchange="calc();" value="<?=$row->ba_einkommensjahresausgleich?>"/>
					</div>
					<div id="berechnung">
						<label title="Monatliches Brutto-Gehalt" >Brutto Verdienst (monatl.)</label>
						<input type="text" name="verdienst" id="verdienst" value="<?=$row->ba_brutto_monat?>" onchange="calc();" />
						<br />
						<label title="Monatlicher erhaltener Unterhalt" >Unterhalt (monatl.)</label>
						<input type="text" name="unterhalt" id="unterhalt" value="<?=$row->ba_unterhalt_monat?>" onchange="calc();" />
						<br />
						<label title="Sämtliche Bonuszahlungen die Sie für das gesamte Jahr erhalten">Grafikationen <br />(Urlaubs- /Weihnachtsgeld)</label>
						<input type="text" name="urlaubsgeld" id="urlaubsgeld" value="<?=$row->ba_grafikationen?>" onchange="calc();" />
						<br />
						<br />
						<label title="Monatliche Einnahmen durch Vermietung beziehungsweise Verpachtung">Monatliche Vermietung/Verpachtung </label>
						<input type="text" name="vermietung" id="vermietung" value="<?=$row->ba_vermietung?>" onchange="calc();" />
						<br />
						<br />
						<label title="Geben Sie hier Ihre Werbungskosten für das gesamte Jahr an.">Werbungskosten</label>
						<input type="text" name="werbungskosten" id="werbungskosten" value="<?=$row->ba_werbungskosten?>" onchange="calc();" />
						<br />
						<label title="Einkünfte im Sinne des §2 Abs. 1 und 2 Einkommensteuergesetzes (Einkünfte aus Gewerbebetrieb, Einkünfte aus Kapitalvermögen, sonstige Einkünfte im Sinne des § 22 EStG; sonstige Einnahmen, Einnahmen nach dem Arbeitsförderungsgesetz, Elterngeld, Krankengeld, Renten, Mutterschaftsgeld, Hilfe zum Lebensunterhalt nach dem SGB II, Wohngeld sonstiges)">Sonstige montl. Einnahmen</label>
						<input type="text" name="sonst_einkuenfte" id="sonst_einkuenfte" value="<?=$row->ba_sonstige_einnahmen_monat?>" onchange="calc();" />
					</div>
					<div id="sozial">
						<label title="">ALG 2 Empf.</label>
						<input type="checkbox" name="hartz4" id="hartz4" onclick="calc();" <?php  if($hartz4)echo 'checked="checked"'; ?>/>
						<br />
						<!--<label title="">Schulbuchbefreiung</label>-->
						<input type="checkbox" name="schulbuch" id="schulbuch" onclick="calc();"  style="display: none;" <?php  if($schulbuch)echo 'checked="checked"'; ?>/>
					</div>
					
				</div>
				<!--
				<br/>
				<br/>
				<label title="Falls Sie in die entsprechenden Beitragsgruppen fallen, können Sie einen Zuschuss zur Gemeinschafftsverpflegung erhalten.">Kein Zuschuss zur Gemeinschaftsverpflegung</label>
				--><input type="hidden" name="zuschuss_pdf" id="zuschuss" value="1" <?php if($row->ba_zuschuss)echo 'checked="checked"'; ?> />
				<br />
				<label class="" for="zuschuss_essen" title="Wenn Sie die Bedienungen für das Essen für Einen Euro erfüllen können Sie diese Box anhaken.">Essen für 1€</label>
				<input class="" type="checkbox" name="zuschuss_essen" id="zuschuss_essen" value="1" <?php if($row->ba_zuschuss_essen)echo "checked='checked'"; ?> />
				<br/>				
				<label class="essen" title="Ankreuzen, wenn das Essensgeld per Lastschrift, Überweisung oder bar gezahlt wird. Freilassen, wenn das Sozialamt die 20,00 € einbehält.">Kein Einbehalt des Essensgeldes</label>
				<input class="essen" type="checkbox" name="sozial_pdf" id="sozial" value="1" <?php if($row->ba_sozial)echo 'checked="checked"'; ?> />
				<br class="essen"/>
				<br/>
				<label title="Wieviele Kinder haben Sie, die für den Kinderfreibetrag zu berücksichtigen sind?" >Kinder</label>
				<select name="kinder" id="kinder" onchange="calc();">
					<?php
					 
						for($i = 1; $i <= 20; $i++){
							if($i == $row->ba_kinder)
								$selected = 'selected="selected"';
							
							echo "<option value='".$i."' $selected>".$i.".</option>";
							$selected = "";	
						}
					?>
				</select>
				<br />
				<br />	
				<label title="Das gesamte Einkommen, das für die Berechnung zugrunde gelegt wird">Einkommen</label>
				<input type="text" id="calcEinkommen" name="calcEinkommen" readonly="readonly" value="<?=$row->ba_jahreseinkommen?>">
				<br />	
				<br />	
				<label title="Der Vertrag kann auch ohne Verdienstnachweise gespeichert werden, ist dann aber noch nicht aktiv.">Verdienstnachweise sind als Kopie vorhanden, best&auml;tigt durch Aufsichtsperson</label>
				<input type="checkbox" name="agb" id="agb" value="1" <? if($row->ba_status) echo 'checked="checked"';?>/>
			</fieldset>
			<h3>Geschwister mit Betreungsauftrag</h3>
			<fieldset>
				<label for="geschwister">Keins</label>
				<input type="radio" name="geschwister" id="geschwister" value="0" <?php if($row->ba_geschwister == 0)echo 'checked="checked"';  ?> /><br />
				<label for="geschwister">1</label>
				<input type="radio" name="geschwister" id="geschwister" value="1" <?php if($row->ba_geschwister == 1)echo 'checked="checked"';  ?> /><br />
				<label for="geschwister">2</label>
				<input type="radio" name="geschwister" id="geschwister" value="2" <?php if($row->ba_geschwister == 2)echo 'checked="checked"';  ?> /><br />
				<label for="geschwister">mehr als 2</label>
				<input type="radio" name="geschwister" id="geschwister" value="3" <?php if($row->ba_geschwister == 3)echo 'checked="checked"';  ?> /><br />
			</fieldset>
			<br />
			<br />
			<h3>Betreuungsaufträge</h3>
			<fieldset>
				<label for="" >Anfang:</label>
				<?php
					$tmp = explode("-",$row->ba_anfang);
					$anfang = $tmp[2]."/".$tmp[1]."/".$tmp[0];
				?>
				
				<input name="start_date" id="start_date" class="date-pick" value="<?php echo $anfang; ?>"/>
				
				<br /><br />
				<label for="" >Ende:</label>
				<?php
					$tmp = explode("-",$row->ba_ende);
					$ende = $tmp[2]."/".$tmp[1]."/".$tmp[0];
				?>
				
				<input name="end_date" id="end_date" class="date-pick" value="<?php echo $ende; ?>"/>
				<br /><br />
				<?php 
					//echo getDen($row->ae_id);
				?>
				<input type="hidden" name="arzt" id="arzt" value="0" />
				<label>Tage</label>
				
				<table style="width:200px;">
					<?php if($school->ba_mon){?>
						<tr>
							<td>Mo</td>
							<td><input type="checkbox" name="day_mo" value="mo" <?php if( $row->ba_montag > 0 )echo 'checked="checked"'; ?> onclick="$('#ba_mo_td').toggle();" /></td>
							<td <?php if( $row->ba_montag == 0 )echo 'style="display:none;"'; ?>  id="ba_mo_td">
								<?php
									echo getTimeBox("time_h_mo","time_m_mo",$row->ba_montag,"check_time('time_h_mo','time_m_mo','mon')") . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
						<?php if($school->ba_tue){?>
						<tr>
							<td>Di</td>
							<td><input type="checkbox" name="day_di" value="di" <?php if( $row->ba_dienstag > 0 )echo 'checked="checked"'; ?> onclick="$('#ba_di_td').toggle();" /></td>
							<td <?php if( $row->ba_dienstag == 0 )echo 'style="display:none;"'; ?>  id="ba_di_td">
								<?php
									echo getTimeBox("time_h_di","time_m_di",$row->ba_dienstag, "check_time('time_h_di','time_m_di', 'tue')")  . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if($school->ba_wen){?>
						<tr>
							<td>Mi</td>
							<td><input type="checkbox" name="day_mi" value="mi" <?php if( $row->ba_mitwoch > 0 )echo 'checked="checked"'; ?> onclick="$('#ba_mi_td').toggle();" /></td>
							<td <?php if( $row->ba_mitwoch == 0 )echo 'style="display:none;"'; ?>  id="ba_mi_td">
								<?php
									echo getTimeBox("time_h_mi","time_m_mi",$row->ba_mitwoch, "check_time('time_h_mi','time_m_mi', 'wen')")  . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if($school->ba_thu){?>
						<tr>
							<td>Do</td>
							<td><input type="checkbox" name="day_do" value="do" <?php if( $row->ba_donnerstag > 0 )echo 'checked="checked"'; ?> onclick="$('#ba_do_td').toggle();" /></td>
							<td  <?php if( $row->ba_donnerstag == 0 )echo 'style="display:none;"'; ?>  id="ba_do_td">
								<?php
									echo getTimeBox("time_h_do","time_m_do",$row->ba_donnerstag, "check_time('time_h_do','time_m_do', 'thu')")  . " Uhr";
								?>
							</td>
						</tr>
					<?php }?>
					<?php if($school->ba_fri){?>
						<tr>
							<td>Fr</td>
							<td><input type="checkbox" name="day_fr" value="fr" <?php if( $row->ba_freitag > 0 )echo 'checked="checked"'; ?> onclick="$('#ba_fr_td').toggle();" /></td>
							<td <?php if( $row->ba_freitag == 0 )echo 'style="display:none;"'; ?>  id="ba_fr_td">
								<?php
									echo getTimeBox("time_h_fr","time_m_fr",$row->ba_freitag, "check_time('time_h_fr','time_m_fr', 'fri')")  . " Uhr";
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
				<input type="checkbox" name="ferien" value="1" <?php if( $row->ba_ferien > 0 )echo 'checked="checked"'; ?> />
				<sup>Jahresbeitrag 120€</sup>
			</fieldset>
			<br />
			<h3>Zahlungsform</h3>
			<fieldset>
				<?php
					echo getBank($row->b_methode,  $row->b_name,   $row->b_vorname,   $row->b_blz,  $row->b_kntr ,  $row->b_sonstiges );
				?>
			</fieldset>
			<br />
			<?php if($school->launch_mon || $school->launch_tue || $school->launch_wen || $school->launch_thu || $school->launch_fri){	?>
			<h3>Essen</h3>
			<fieldset>
			<label>Tage</label>	
				<table style="width:200px;">
					<?php if($school->launch_mon){?>
						<tr>
							<td>Mo</td>
							<td><input type="checkbox" name="day_essen_mo" value="mo" <?php if(checkEssensTage($row->ba_essenstage,"mo"))echo 'checked="checked"'; ?> /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_tue){?>
						<tr>
							<td>Di</td>
							<td><input type="checkbox" name="day_essen_di" value="di" <?php if(checkEssensTage($row->ba_essenstage,"di"))echo 'checked="checked"'; ?> /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_wen){?>
						<tr>
							<td>Mi</td>
							<td><input type="checkbox" name="day_essen_mi" value="mi" <?php if(checkEssensTage($row->ba_essenstage,"mi"))echo 'checked="checked"'; ?> /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_thu){?>
						<tr>
							<td>Do</td>
							<td><input type="checkbox" name="day_essen_do" value="do" <?php if(checkEssensTage($row->ba_essenstage,"do"))echo 'checked="checked"'; ?> /></td>
						</tr>
					<?php }?>
					<?php if($school->launch_fri){?>
						<tr>
							<td>Fr</td>
							<td><input type="checkbox" name="day_essen_fr" value="fr" <?php if(checkEssensTage($row->ba_essenstage,"fr"))echo 'checked="checked"'; ?> /></td>
						</tr>
					<?php }?>
				</table>
				<label>Moslemisches Essen</label>
				<input type="checkbox" name="moslemisch" value="1" id="moslemisch" <?php if($row->ba_moslemisch > 0)echo 'checked="checked"'; ?> />
				<br />
				<label>Allergien / Lebensmittelunverträglichkeiten</label>
				<input type="checkbox" name="allergie" value="1" id="allergie" onclick="showText();" <?php if( $row->ba_allergien_text != "" )echo 'checked="checked"'; ?> />
				<textarea rows="5" cols="5" name="allergieText" id="allergieText"><?php echo $row->ba_allergien_text; ?></textarea>
			</fieldset>
			<?php } ?>
			<br />
			<h3>Änderungsbegründung</h3>
			<fieldset>
				<textarea name="changeReason"></textarea>
			</fieldset>
			<br />
			<input type="submit" value="Auftrag Ändern"  />
			<input type="reset" value="Felder zurück setzen"  />
			<br />
			<br />
		</form>
<?php
} elseif($page==md5('show')) {
    $sql = "SELECT *
			FROM `$tbl_betreuungsauftraege[tbl]`, `$tbl_schueler[tbl]`
			WHERE `$tbl_betreuungsauftraege[schul_id]` = $_SESSION[schul_id]
			AND `$tbl_betreuungsauftraege[schueler_id]` = `$tbl_schueler[id]`
			AND `$tbl_betreuungsauftraege[id]` = $_GET[baid];";
			
    $result = mysql_query($sql);
    $row = mysql_fetch_assoc($result);
	
        
		echo "<h2>Details</h2>";


} elseif($page == md5('detail')){

	echo "<h2>Details</h2>";
	
	 $sql = "SELECT *
			FROM `log`
			WHERE l_table = 'betreuungsauftraege'
			AND l_record_id = $_GET[baid]
			AND l_description != ''
			";
			
    $result = mysql_query($sql);
	
	echo "<table width=\"500\" border=\"1\">";
	echo "<tr>
			<th>Datum / Uhrzeit</th>
			<th>Begründung</th>
	     </tr>
		";
    while($row = mysql_fetch_assoc($result)){
		echo "	<tr>
					<td>$row[l_time]</td>
					<td>$row[l_description]</td>
				</tr>
			";
	}
	echo "</table>";
}elseif($page == md5('delete')){
		
	$sql = new Sql;
	
	if ($sql->deleteBetreuungsauftrag($_GET['baid'], $user) == 0){
		echo "<h2>Betreuungsauftrag gelöscht</h2>";
	}else{
		echo "Sie haben nicht Rechte um den Betreuungsauftrag zu löschen.";
	}
	
}elseif($page == md5('move_select')){
    checkRights(200, $user);
	?>
		<fieldset>
		
			<form action="index.php?content=<?php echo $self; ?>&p=<?php echo md5('move');?>" method="post">
				<input type="hidden" name="baid" value="<?php echo $_GET['baid'];?>">
				<?php
					
					$sql = "SELECT * 
						 FROM $tbl_schulen[tbl]";
					$result = mysql_query($sql);
					
					echo "<label for=\"schul_id\" >Ziel-Schule:</label>
							<select name=\"schul_id\" id=\"schul_id\">";
					while($row = mysql_fetch_assoc($result) )
					{
							echo "<option value=\"".$row[$tbl_schulen['id']]."\">".$row[$tbl_schulen['name']]."</option>";
					}
					echo "</select><br /><br />";

				?>
				<input type="submit" class="formular" value="Schüler verschieben" /> 
			</form>
		</fieldset>
	
	<?php
	
}elseif($page == md5('move')){
    checkRights(200, $user);
	$sql = new Sql;
	
	if ($sql->moveBetreuungsauftrag($_POST['baid'], $_POST['schul_id'], $user) == 0){
		echo "<h2>Der Schüler wurde verschoben.</h2>";
	}else{
		echo "Sie haben nicht genügent Rechte um den Schüler zu verschieben.";
	}
	
}
?>