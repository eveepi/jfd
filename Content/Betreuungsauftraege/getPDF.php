<?php

	/*		!!!!! WICHTIG !!!!!
	 * 			Keine Ausgaben (echo's etc.!! )
	 *    Dateiencoding muss auf ANSII Stehen!
	 * 
	 */
	include("../../config.php");
	include("../../class.user.php");
	include("../../class.sql.php");
	include("../../functions.php");
	
	$user = new CUser();
	$sql = new Sql;
			
	$_SESSION['schule'] = isset($_SESSION['schule']) ? $_SESSION['schule'] : $user->getSchule();
	
	$logoPath = "../../img/pdf_logo.png";
	$logoWidth = 38;
	
	$data = $sql->getUserData($_SESSION['pdf_sid']);
	$school = $sql->getSchoolData($data->ba_schul_ID);
	$traeger = $school->traeger;
	
	/* K�ndigungsgr�nde konfigurieren */
	$kuendigung = $school->kuendigung;
	
	$arbeitsplatz = false;
	$einkommensreduzierung = false;
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
		$einkommensreduzierung = true;
	}

	if($kuendigung >= 1){
		$kuendigung -= 1;
		$arbeitsplatz = true;
	}
	
	$essen_flag = 0;
	if(strpos($data->ba_essenstage,"1") !== false){
		$essen_flag = 1;
	}	
	
    define('FPDF_FONTPATH','../../pdf/font/');
	include("../../pdf/fpdf.php");
	
	//echo "debug:" . $data->s_name;
	
	// beitr�ge ausrechnen, betreuung,ferien und essen 
	$summeBe = calcPay($data->ba_jahreseinkommen, $data->ba_ferien, $data->ba_geschwister); // summe betreuung

	$summeEssen = calcEssen($data->ba_essenstage, $data->ba_zuschuss_essen, $data->ba_sozial, $data->ba_sozialleistungen);
	$abschlag = $summeBe / 10; // jahresbeitrag wird auf 10 abschl�ge verteilt
	
	// verdiensttabelle auslesen
	$sql_verdienst = "SELECT *
					  FROM `verdienst`
					  WHERE schul_id=$_SESSION[schul_id]
					  ORDER BY v_verdienst ASC,`from` ASC";
	
	$verdienst_result = mysql_query($sql_verdienst);
	
	$pdf = new FPDF();
	/*
	 * Erste Seite
	 */
	$pdf->AddPage();
	
	//standards
	$pdf->SetMargins(20,20);
	$pdf->SetFont('Arial','',10);
	
	$pdf->Cell(70,5,"",0,0,'L');
	$pdf->Cell(70,5,"",0,0,'L');
	$pdf->Ln(5);
	
	
	$pdf->Cell(70,5,"Jugend- und Familiendienst e.V.",0,0,'L');
	$pdf->Cell(70,5,"im Auftrage",0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(70,5,"Wadelheimer Chaussee 195",0,0,'L');
	$pdf->Cell(70,5,"der ".utf8_decode($traeger) ,0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(70,5,"",0,0,'L');
	$pdf->Cell(70,5,"- Schulverwaltung -",0,0,'L');
	$pdf->Ln(10);
	$pdf->MultiCell(180,5,"48432 Rheine \nTel.-Nr. 05971-91448-19 \n             05971-91448-28",0,'L');
	
	$pdf->Ln(10);
	
	$pdf->Image($logoPath, 160, 15,$logoWidth);
	
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(180,5,"Antrag",0,0,'C');
	$pdf->Ln(5);
	$pdf->SetFillColor(217,217,217);
	$pdf->Cell(180,1,"",0,0,'L',1);
	$pdf->Ln(5);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,1,"Ich/ Wir m�chte/n das Angebot der ".utf8_decode($traeger)." zur",0,0,'L');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','U',12);
	$pdf->Cell(180,1,"Teilnahme an der \"Offenen Ganztagsschule\"",0,0,'C');
	
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	$pdf->Cell(180,1,"in der ".utf8_decode($school->schul_name)." nutzen.",0,0,'L');
	$pdf->Ln(5);
		
	$tmp = explode("-",$data->ba_anfang);
	$startYear = $tmp[2] . "." . $tmp[1] . "." . $tmp[0];

	$tmp = explode("-",$data->ba_ende);
	$endYear = $tmp[2] . "." . $tmp[1] . "." . $tmp[0];
	
	//$pdf->MultiCell(180,4,"Diese Anmeldung ist f�r das Schuljahr $thisYear/$nextYear verbindlich.",0,'L');
	$pdf->MultiCell(180,4,"Diese Anmeldung ist vom $startYear bis zum $endYear verbindlich.",0,'L');
// ------ 1. 	Erziehungsberechtigte
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(180,10,'Erziehungsberechtigte',0,0,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(8);
	
	$pdf->Cell(50,10,'Name, Vorname:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$data->e_name, $data->e_vorname"),0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(50,10,'Stra�e:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$data->e_strasse"),0,0,'L');

	$ort = $sql->getOrtById($data->e_plz);

	$pdf->Ln(5);
	$pdf->Cell(50,10,'PLZ, Ort:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$ort->o_plz, $ort->o_ort"),0,0,'L'); // <== PLZ + ORT!
	$pdf->Ln(5);
	$pdf->Cell(45,10,'Tel. (evtl. auch dienstl.):',0,0,'L');
	
	$tel_nr 	= $data->e_fon_privat;
	$tel_nr 	.= ($data->e_fon_dienst)? ", ". $data->e_fon_dienst:"";
	
	$pdf->Cell(5,10,"",0,0,'L');
	$pdf->Cell(155,10,"$tel_nr",0,0,'L');
		
	/*$pdf->Ln(5);	
	$pdf->Cell(45,10,'Mobil:',0,0,'L');	
	$pdf->Cell(155,10,"$data->e_fon_privat",0,0,'L');*/
	
	$pdf->Ln(8);
	
// ------ 2. 	Erziehungsberechtigte
	// nur wenn einer angegeben wurde
	if($data->e_name2 != "" && $data->e_vorname2 != "" && $data->e_fon_dienst2 != "" ){
//		$pdf->Cell(180,10,'2. Erziehungsberechtigte',0,0,'C');
//		$pdf->Ln(10);
		
		$pdf->Cell(50,10,'Name, Vorname:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$data->e_name2, $data->e_vorname2"),0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(45,10,'Tel. (dienstl.):',0,0,'L');
		$tel_nr	= $data->e_fon_dienst2;
		
		$pdf->Cell(5,10,"",0,0,'L');
		$pdf->Cell(155,10,"$tel_nr",0,0,'L');
		$pdf->Ln(8);
	}
	
// ------ Kind
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(180,10,'Kind',0,0,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(10);
	
	$pdf->Cell(50,10,'Name, Vorname:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$data->s_name, $data->s_vorname"),0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(50,10,'Stra�e:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$data->s_strasse"),0,0,'L');
	
	// plz holen
	$ort = $sql->getOrtById($data->s_plz);

	$pdf->Ln(5);
	$pdf->Cell(50,10,'PLZ, Ort:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$ort->o_plz, $ort->o_ort"),0,0,'L'); // <== PLZ + ORT!
  
  	$pdf->Ln(5);
	$pdf->Cell(50,10,'Geburtsdatum:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode($data->geburtsdatum),0,0,'L');
	
	if($data->k_bezeichnung){
		$pdf->Ln(5);
		$pdf->Cell(50,10,'Klasse:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode($data->k_bezeichnung),0,0,'L');
	}
	
	$pdf->Ln(5);
	$pdf->Cell(50,10,'KIJU-Nr:',0,0,'L');
	$pdf->Cell(155,10,'KIJU-'.$school->schul_kuerzel.'-'.$data->s_id,0,0,'L');	

	$pdf->Ln(10);
	
// ------ Tabelle mit Beitr�gen
	$pdf->Cell(180,5,'Die 12 Monatsbeitr�ge werden verteilt auf 10 Abbuchungen!',0,0,'L');
	$pdf->Ln(10);	

	if(!$data->ba_ferien){
		// tabellen kopf
		$pdf->Cell(50,10,'Jahres- Bruttoeinkommen',1,0,"C");
		$pdf->Cell(30,10,'Beitrag',1,"C");
		$pdf->Ln(10);
		
		$x = false; // falls ein kreuz gesetzt wird, d�rfen keine weiteren mehr gemacht werden
		while($row = mysql_fetch_object($verdienst_result)){
			//  1 = bis // 0 = mehr
			if($row->from==0)
				$from = "Bis ";
			else
				$from = "Mehr ";
			
			$pdf->Cell(50,5, $from . number_format($row->v_verdienst, 2, ',', '.') . ' �',1,0,"C");
			$pdf->Cell(30,5, number_format($row->beitrag , 2, ',', '.') . ' � ',1,0,"R");
			
			// bis
			if($row->from==0){
				// K�chstchen
				if($data->ba_jahreseinkommen <= $row->v_verdienst && !$x){
					$pdf->Ln(1);
					$pdf->Cell(55,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,'X',1,0,"L"); // kasten
					$x = true;
				}else{
					$pdf->Ln(1);
					$pdf->Cell(55,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,' ',1,0,"L"); // kasten
				}
			// mehr
			}else{
				// K�chstchen
				if($data->ba_jahreseinkommen > $row->v_verdienst && !$x){
					$pdf->Ln(1);
					$pdf->Cell(55,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,'X',1,0,"L"); // kasten
					$x = true;
				}else{
					$pdf->Ln(1);
					$pdf->Cell(55,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,' ',1,0,"L"); // kasten
				}
			}	
			
			$pdf->Ln(4);
		}
	}else{
		// tabellen kopf
		$pdf->Cell(50,10,'Jahres- Bruttoeinkommen',1,0,"C");
		$pdf->Cell(30,10,'Beitrag',1,"C");
		$pdf->Cell(30,10,'Ferienbetreuung',1,"C");
		$pdf->Ln(10);
		
		$x = false; // falls ein kreuz gesetzt wird, d�rfen keine weiteren mehr gemacht werden
		while($row = mysql_fetch_object($verdienst_result)){
			//  1 = bis // 0 = mehr
			if($row->from==0)
				$from = "Bis ";
			else
				$from = "Mehr ";
			
			$pdf->Cell(50,5,$from . $row->v_verdienst . ' �',1,0,"C");
			$pdf->Cell(30,5, number_format($row->beitrag , 2, ',', ' ') . ' � ',1,0,"R");
			$pdf->Cell(30,5, number_format($row->beitrag + 12 , 2, ',', ' ') . ' � * ',1,0,"R");
			
			// bis
			if($row->from == 0){
				// K�chstchen
				if($data->ba_jahreseinkommen <= $row->v_verdienst && !$x){
					$pdf->Ln(1);
					$pdf->Cell(85,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,'X',1,0,"L"); // kasten
					$x = true;
				}else{
					$pdf->Ln(1);
					$pdf->Cell(85,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,' ',1,0,"L"); // kasten
				}
			// mehr
			}else{
				// K�chstchen
				if($data->ba_jahreseinkommen > $row->v_verdienst && !$x){
					$pdf->Ln(1);
					$pdf->Cell(85,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,'X',1,0,"L"); // kasten
					$x = true;
				}else{
					$pdf->Ln(1);
					$pdf->Cell(85,1,' ',0,0,"L"); // abstand
					$pdf->Cell(3,3,' ',1,0,"L"); // kasten
				}
			}	
			
			$pdf->Ln(4);
		}
	}
	$pdf->Ln(6);
	$ferien = "";
	if($data->ba_ferien){
		$ferien = " (incl. 12� pro Abschlag f�r die Ferienbetreuung)";
	}
	$pdf->Ln(2);

	$pdf->Ln(5);

	// ausgabe
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(180,5,"F�r das 2. teilnehmende Kind vermindert sich der Elternbeitrag um die H�lfte. Das 3. teilnehmende Kind ist vom Beitrag befreit. Der Beitrag zur Ferienbetreuung reduziert sich nicht bei Geschwisterkindern.",0,"L");
	$pdf->Ln(5);
	$pdf->Cell(4,5,' ',1,0,'L');
	$pdf->Cell(30,5,'		1. Geschwisterkind',0,0,'L');
	$pdf->Cell(40,5,'		',0,0,'L');
	$pdf->Cell(4,5,' ',1,0,'L');
	$pdf->Cell(34,5,'		2. Geschwisterkind',0,0,'L');
	
	if($data->ba_geschwister != 0){
		$pdf->Ln(0.1);
		if($data->ba_geschwister == 1)
			$pdf->Cell(74,5,'X',0,0,'L'); // kreuz
		
		if($data->ba_geschwister > 1){
			$pdf->Cell(74,5,'',0,0,'L'); // abstand
			$pdf->Cell(10,5,'X',0,0,'L'); // kreuz
		}
			
	}
	
	$pdf->AddPage(); 
	$pdf->SetMargins(20,20);
	
	$pdf->Ln(10);
	$pdf->MultiCell(180,5,"Ma�gebend ist das Einkommen in der Angabe vorangegangenen Kalenderjahr. Abweichend davon ist das Zw�lffache des Einkommens des letzten Monats zugrunde zu legen, wenn es voraussichtlich auf Dauer h�her oder niedriger ist als das Einkommen des vorangegangenen Kalenderjahres; wird das Zw�lffache des Einkommens des letzten Monats zugrunde gelegt, so sind auch Eink�nfte hinzuzurechnen, die zwar nicht im letzten Monat bezogen wurden, aber im laufenden Jahr anfallen. Der Elternbeitrag ist ab dem Kalenderjahr nach Eintritt der �nderung neu festzusetzen. ",1,"L");
	$pdf->Ln(5);

	$pdf->SetFont('Arial','',11);
	if($data->ba_ferien){
		$pdf->MultiCell(180,5,"* Die Kosten f�r das Mittagessen sind zus�tzlich. Der Einzug des Essensgeldes erfolgt direkt zwischen den Eltern/Erziehungsberechtigten und dem Lieferanten des Essens.",0,'L');
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"** Zus�tzlich sind die Kosten f�r die Verpflegung in den Ferien vor Ferienbeginn f�llig.",0,'L');
		$pdf->Ln(10);
	}
	
	//$pdf->Ln(10);
	$pdf->Cell(180,5,'Informationen zum Datenschutz',0,0,'L');
	$pdf->Ln(10);
	$pdf->MultiCell(180,4,"Zur Sicherstellung eines reibungslosen Gesch�ftsablaufes speichern wir die von uns erhobenen Daten und verarbeiten diese weiter, wir erhalten �ffentliche F�rdermittel. Zur Erlangung der F�rdermittel geben wir Ihre Daten im Rahmen der einschl�gigen Richtlinien teilweise an die jeweiligen F�rdermittelgeber weiter. 
Dar�ber hinaus werden Fotos, Zeichnungen und Texte mit Nennung des Vornamens des/der Kindes/r in unserer Festschrift, in B�chern, Fachzeitschriften, Rundfunk, Fernsehen und vor allem im Internet oder mittels CD-ROM-Datentr�gern ver�ffentlicht.",0,'L');
	$pdf->Ln(10);
	
	$pdf->Cell(70,10,'________________________________',0,0,'L');
	$pdf->Cell(15,2,' 		',0,"L");	// Abstand

	
	$pdf->Cell(70,10,'    __________________________________',0,0,'L');

	$pdf->Ln(5);
	$pdf->Cell(70,10,'(Datum)',0,0,'L');
	$pdf->Cell(16,2,' 		',0,"L");	// Abstand
	$pdf->Cell(85,10,'    (Unterschrift der Erziehungsberechtigten)',0,0,'L');
	
	$pdf->Ln(20);
	
	$pdf->MultiCell(180,5,"Die Anmeldung ist f�r das jeweilige Schuljahr verbindlich. Eine vorzeitige K�ndigung ist ausgeschlossen, es sei denn, es liegt einer der folgenden Gr�nde vor:",0,'L');
	
	$pdf->Ln(5);
	
	if($arbeitsplatz)
		$pdf->MultiCell(180,5,'- Verlust des Arbeitsplatzes',0,'L');	
	if($einkommensreduzierung)
		$pdf->MultiCell(180,5,'- Reduzierung des monatlichen Einkommens um mindestens 20 % gegen�ber dem bei Anmeldung des Kindes erzielten Einkommens',0,'L');
	if($umzug)
		$pdf->MultiCell(180,5,'- bei Umzug der Familie und damit verbundenem Schulwechsels des Kindes',0,'L');
	if($haerte)
		$pdf->MultiCell(180,5,'- wenn Anzeichen erkennbar sind, dass eine weitere Teilnahme am Nachmittagsangebot eine unzumutbare H�rte f�r das Kind darstellt.',0,'L');
	
	$pdf->Ln(5);
	
	
	$pdf->MultiCell(180,5,'In diesen F�llen ist eine vorzeitige K�ndigung mit einer Frist von 6 Wochen zum Quartalsende m�glich. Die K�ndigung muss schriftlich unter Beif�gung entsprechender Nachweise erfolgen.',0,'L');
		
	
	/*
	* 3. Tagen an den Betreuung stattfindet und gegessen wird etc.
	*/
	$pdf->AddPage(); 
	$pdf->SetMargins(20,20);
	
	$pdf->MultiCell(180,5,
						"Jugend- und Familiendienst e.V.\nWadelheimer Chaussee 195\n48432 Rheine\n\nTel.-Nr. 05971-91448-19 \n             05971-91448-28"
						,0,'L'
		);
		
	$pdf->Ln(10);
	
	$pdf->Image($logoPath, 160, 20,$logoWidth);
	
	// ------ Kind
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(180,10,'Kind',0,0,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(10);
	
	$pdf->Cell(50,10,'',0,0,'L');
	$pdf->Cell(50,10,'Name,Vorname:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$data->s_name, $data->s_vorname"),0,0,'L');
	$pdf->Ln(5);
	$pdf->Cell(50,10,'',0,0,'L');
	$pdf->Cell(50,10,'Stra�e:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$data->s_strasse"),0,0,'L');
	
	// plz holen
	$ort = $sql->getOrtById($data->s_plz);

	$pdf->Ln(5);
	$pdf->Cell(50,10,'',0,0,'L');
	$pdf->Cell(50,10,'PLZ, Ort:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$ort->o_plz, $ort->o_ort"),0,0,'L'); // <== PLZ + ORT!
	
	if($data->k_bezeichnung != ""){
		$pdf->Ln(5);
		$pdf->Cell(50,10,'',0,0,'L');
		$pdf->Cell(50,10,'Klasse:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$data->k_bezeichnung"),0,0,'L'); // <== PLZ + ORT!
	}
	$pdf->Ln(5);
	$pdf->Cell(50,10,'',0,0,'L');
	$pdf->Cell(50,10,'Schule:',0,0,'L');
	$pdf->Cell(155,10,utf8_decode("$school->schul_name"),0,0,'L'); // <== PLZ + ORT!

	$pdf->Ln(20);
	
	$pdf->Cell(20,10,"Die Betreuung findet jeweils an folgenden Tagen statt:",0,0,'L');
	$pdf->Ln(10);
	
	$pdf->Cell(20,5,"",1,0,'L');
	// tabellen kopf
	$pdf->Cell(50,5,'Betreuung',1,0,"L");
	if($essen_flag){
		$pdf->Cell(30,5,'Essen',1,"L");
	}
	$pdf->Ln(5);
	
	if($data->ba_montag != "0"){
		$pdf->Cell(20,5,"Montag",1,0,'L');
		$pdf->Cell(50,5,"bis $data->ba_montag Uhr",1,0,"L");
		if($essen_flag){
			if(checkEssensTage($data->ba_essenstage,"mo")){
				$pdf->Cell(30,5,'Ja',1,"C");
			}else{
				$pdf->Cell(30,5,'',1,"C");
			}
		}
		
		$pdf->Ln(5);
	}
	if($data->ba_dienstag != "0"){
		$pdf->Cell(20,5,"Dienstag",1,0,'L');
		$pdf->Cell(50,5,"bis $data->ba_dienstag Uhr",1,0,"L");
		if($essen_flag){
			if(checkEssensTage($data->ba_essenstage,"di")){
				$pdf->Cell(30,5,'Ja',1,"C");
			}else{
				$pdf->Cell(30,5,'',1,"C");
			}
		}	
		$pdf->Ln(5);
	}
	if($data->ba_mitwoch != "0"){
		$pdf->Cell(20,5,"Mittwoch",1,0,'L');
		$pdf->Cell(50,5,"bis $data->ba_mitwoch Uhr",1,0,"L");
		if($essen_flag){
			if(checkEssensTage($data->ba_essenstage,"mi")){
				$pdf->Cell(30,5,'Ja',1,"C");
			}else{
				$pdf->Cell(30,5,'',1,"C");
			}
		}
		$pdf->Ln(5);
	}
	if($data->ba_donnerstag != "0"){
		$pdf->Cell(20,5,"Donnerstag",1,0,'L');
		$pdf->Cell(50,5,"bis $data->ba_donnerstag Uhr",1,0,"L");
		if($essen_flag){
			if(checkEssensTage($data->ba_essenstage,"do")){
				$pdf->Cell(30,5,'Ja',1,"C");
			}else{
				$pdf->Cell(30,5,'',1,"C");
			}
		}
		$pdf->Ln(5);
	}
	if($data->ba_freitag != "0"){
		$pdf->Cell(20,5,"Freitag",1,0,'L');
		$pdf->Cell(50,5,"bis $data->ba_freitag Uhr",1,0,"L");
		if($essen_flag){
			if(checkEssensTage($data->ba_essenstage,"fr")){
				$pdf->Cell(30,5,'Ja',1,"C");
			}else{
				$pdf->Cell(30,5,'',1,"C");
			}
		}
	}
	
	$pdf->Ln(10);
	$pdf->Cell(50,5,"Die Kosten f�r die Betreuung betragen ".number_format($abschlag, 2, ',', ' ')." � pro Abschlag$ferien.",0,"L");
	$pdf->Ln(5);
	if($essen_flag){
		if($data->ba_zuschuss_essen && $data->ba_sozialleistungen == pow(2,0) && $data->ba_sozial != 1){
			$pdf->Cell(50,5,"F�r das Essensgeld fallen 20,00 � pro Abschlag an, diese werden vom Sozialamt �ber die Sozialhilfe gezahlt.",0,"L");
			$pdf->Ln(5);	
		}else{
			$pdf->Cell(50,5,"Die Kosten f�r das Essen betragen ".number_format($summeEssen, 2, ',', ' ')." � pro Abschlag.",0,"L");	
			$pdf->Ln(5);	
		}
	}
	$pdf->Cell(50,5,"Gesamt: ".number_format($summeEssen + $abschlag, 2, ',', ' ')." � pro Abschlag.",0,"L");
	
	$pdf->Ln(20);
	
	if($data->ba_allergien_text != ""){
		$pdf->Cell(50,5,"Folgende Allergien sind vorhanden:",0,"L");
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,$data->ba_allergien_text,0,"L");
		$pdf->Ln(20);
	}
	if($data->ba_besonderheiten != ""){
		$pdf->Cell(50,5,"Besonderheiten:",0,"L");
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,$data->ba_besonderheiten,0,"L");
		$pdf->Ln(20);
	}
	
	$pdf->Cell(70,10,'________________________________',0,0,'L');
	$pdf->Cell(15,2,' 		',0,"L");	// Abstand

	
	$pdf->Cell(70,10,'    __________________________________',0,0,'L');

	$pdf->Ln(5);
	$pdf->Cell(70,10,'(Datum)',0,0,'L');
	$pdf->Cell(16,2,' 		',0,"L");	// Abstand
	$pdf->Cell(85,10,'    (Unterschrift der Erziehungsberechtigten)',0,0,'L');
	
	$pdf->Ln(20);
	
	$pdf->Ln(10);
	/*
	* 4. Seite Finanz krams
	*/
		
	$pdf->AddPage(); 
	$pdf->SetMargins(20,20);
	
	$pdf->MultiCell(180,5,
						"Jugend- und Familiendienst e.V.\nWadelheimer Chaussee 195\n48432 Rheine\n\nTel.-Nr. 05971-91448-19 \n             05971-91448-28"
						,0,'L'
	);
		
	$pdf->Ln(10);
	
	$pdf->Image($logoPath, 160, 20,$logoWidth);
	
	$pdf->SetFont('Arial','B',11);
		
	if($data->b_methode == "lastschrift"){
	
		$pdf->Cell(180,5,'Einzugserm�chtigung',0,0,'C');
	
		$pdf->SetFont('Arial','',10);
	
		$pdf->Ln(15);
		
		$pdf->MultiCell(180,5,'Hiermit erm�chtige ich den von der '.utf8_decode($traeger).' beauftragten Jugend- und Familiendienst e.V. widerruflich, die von mir zu entrichtenden Zahlungen wegen der Kosten f�r die Teilnahme an der "Offenen Ganztagsschule" bei F�lligkeit zu Lasten meines Kontos:',0,'L');
		
		$pdf->Ln(5);
		
		$pdf->Cell(180,5,"Konto Nr.:  $data->b_kntr",0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(180,5,"Blz: $data->b_blz",0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(180,5, utf8_decode("Kontoinhaber: $data->b_vorname $data->b_name"),0,0,'L');
		
		$pdf->Ln(10);

		$pdf->MultiCell(180,5,'mittels Lastschrift einzuziehen. Wenn mein Konto die erforderliche Deckung nicht aufweist, besteht seitens des kontof�hrenden Kreditinstituts keine Verpflichtung zur Einl�sung.',0,'L');	
	}elseif($data->b_methode == "sepa"){
	
		$pdf->Cell(180,5,'Einzugserm�chtigung',0,0,'C');
	
		$pdf->SetFont('Arial','',10);
	
		$pdf->Ln(15);
		
		$pdf->MultiCell(180,5,'Hiermit erm�chtige ich den von der '.utf8_decode($traeger).' beauftragten Jugend- und Familiendienst e.V. widerruflich, die von mir zu entrichtenden Zahlungen wegen der Kosten f�r die Teilnahme an der "Offenen Ganztagsschule" bei F�lligkeit zu Lasten meines Kontos:',0,'L');
		
		$pdf->Ln(5);
		
		$pdf->Cell(180,5,"IBAN:  $data->b_iban",0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(180,5,"BIC: $data->b_bic",0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(180,5, utf8_decode("Kontoinhaber: $data->b_holder"),0,0,'L');
		
		$pdf->Ln(10);

		$pdf->MultiCell(180,5,'mittels Lastschrift einzuziehen. Wenn mein Konto die erforderliche Deckung nicht aufweist, besteht seitens des kontof�hrenden Kreditinstituts keine Verpflichtung zur Einl�sung.',0,'L');	
	}
	elseif($data->b_methode == "ueberweisung"){
	
		$pdf->Cell(180,5,'�berweisung',0,0,'C');
	
		$pdf->SetFont('Arial','',10);
	
		$pdf->Ln(15);
		
		$pdf->MultiCell(180,5,'Ich werde den Abschlag von September bis Juni auf das nachfolgend genannte Konto des Jugend- und Familiendienstes e. V. �berweisen. Als Verwendungszweck ist die folgende Sch�ler-Nummer anzugeben: KIJU-'.$school->schul_kuerzel.'-'.$data->s_id.'.',0,'L');
		$pdf->Ln(5);

		$pdf->Cell(20,5,'BIC:',0,0,'L');
		$pdf->Cell(150,5,"WELADED1RHN",0,0,'L');
		$pdf->Ln(5);

		$pdf->Cell(20,5,'IBAN:',0,0,'L');
		$pdf->Cell(150,5,"DE66 4035 0005 0000 0705 40",0,0,'L');
		$pdf->Ln(5);		
		
		$pdf->Cell(20,5,'Institut:',0,0,'L');
		$pdf->Cell(150,5,"Stadtsparkasse Rheine",0,0,'L');	
		$pdf->Ln(15);	
		
		//TODO: Pr�fen bei welchen Schulen dies angezeigt werden soll!
		if(1){
			$pdf->MultiCell(180,5,'Die Abschl�ge f�r das Mittagessen �berweisen Sie bitte unter Angabe des Verwendungszwecks "KIJU-'.$school->schul_kuerzel.'-'.$data->s_id.'" auf folgendes Konto:',0,'L');
			$pdf->Ln(5);
	
			$pdf->Cell(20,5,'BIC:',0,0,'L');
			$pdf->Cell(150,5,"WELADED1RHN",0,0,'L');
			$pdf->Ln(5);
	
			$pdf->Cell(20,5,'IBAN:',0,0,'L');
			$pdf->Cell(150,5,"DE49 4035 0005 0000 0500 88",0,0,'L');
			$pdf->Ln(5);		
			
			$pdf->Cell(20,5,'Institut:',0,0,'L');
			$pdf->Cell(150,5,"Stadtsparkasse Rheine",0,0,'L');	
			$pdf->Ln(15);	
		}	
	}
	elseif($data->b_methode == "sonstige"){
	
		$pdf->Cell(180,5,'Sonstige',0,0,'C');
	
		$pdf->SetFont('Arial','',10);
	
		$pdf->Ln(15);
	
		$pdf->MultiCell(180,5,'Ich bitte um eine individuelle Regelung (z. B. anderer Zahlungstermin, anderer Rechnungsempf�nger) Als Verwendungszweck ist die folgende Sch�ler Nummer anzugeben: '.$data->s_id.'.',0,'L');
		
		$pdf->Ln(5);
		
		$pdf->MultiCell(180,5,utf8_decode($data->b_sonstiges),0,'L');
	}
	
	$pdf->Ln(5);
	$pdf->Cell(70,10,'________________________________',0,0,'L');
	$pdf->Cell(15,2,' 		',0,"L");	// Abstand

	
	$pdf->Cell(70,10,'    __________________________________',0,0,'L');

	$pdf->Ln(5);
	$pdf->Cell(70,10,'(Ort, Datum)',0,0,'L');
	$pdf->Cell(16,2,' 		',0,"L");	// Abstand
	$pdf->Cell(85,10,'    (Unterschrift)',0,0,'L');
	
	/*
	* 4. Seite
	*/
	// 4. seite nur ausgeben wenn auch gegessen wird

	if($essen_flag){  //Muss �berarbeitet werden, ist veraltet
		$pdf->AddPage(); 
		$pdf->SetMargins(20,20);
		
		$pdf->MultiCell(180,5,
						"Jugend- und Familiendienst e.V.\nWadelheimer Chaussee 195\n48432 Rheine\n\nTel.-Nr. 05971-91448-19 \n             05971-91448-28"
						,0,'L'
		);
			
		$pdf->Ln(10);
		
		$pdf->Image($logoPath, 160, 20,$logoWidth);
		
		$pdf->SetFont('Arial','B',11);
		
		$pdf->Cell(70,5,'Information zur Gemeinschaftsverpflegung',0,0,'L');
		
		$pdf->Ln(15);
		
		$pdf->SetFont('Arial','',10);
		
		$pdf->Cell(70,5,'Liebe Eltern/Erziehungsberechtigten,',0,0,'L');
		
		$pdf->Ln(10);
		$pdf->MultiCell(180,5,"nachstehend geben wir Ihnen noch einige kurze Informationen, die die Gemeinschaftsverpflegung betreffen:",0,'L');
		
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"1.) Der Essenspreis betr�gt pro Mittagessen momentan ".number_format($school->essenspreis, 2, ",", ".")." �.",0,'L');
		
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"2.) Den zu �berweisenden monatlichen Beitrag hierf�r entnehmen Sie bitte dem Betreuungsantrag.",0,'L');
		
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"3.) Bitte �berweisen Sie pro Kind auf folgendes Konto bei der Stadtsparkasse Rheine:",0,'L');
		
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"IBAN: DE49 4035 0005 0000 0500 88               BIC: WELADED1RHN",0,'L');
				
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"4.) Als Verwendungszweck geben Sie bitte unbedingt, die im Antrag angegebene  KIJU-Nr. (KIJU-$school->schul_kuerzel-$data->s_id), sowie den Nachnamen und direkt anschlie�end den Vornamen Ihres Kindes an.",0,'L');
		
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"5.) Wir empfehlen Ihnen, falls m�glich, pro Kind einen Dauerauftrag einzurichten, um zu vermeiden, dass, falls nicht gen�gend Guthaben auf dem Konto Ihres Kindes ist, Ihr Kind nicht an der Gemeinschaftsverpflegung teilnehmen kann.",0,'L');
		
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"6.) Bei Krankheit oder Unterrichtsausfall k�nnen Sie Ihr Kind t�glich bis 10.00 Uhr vom Essen abmelden. Jedes abgemeldete Essen wird nat�rlich nicht berechnet.",0,'L');
		
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"Bei R�ckfragen wenden Sie sich bitte an uns. Sie erreichen uns unter der Tel.-Nr. des Service-B�ros:",0,'L');
		$pdf->Ln(5);
		$pdf->MultiCell(180,5,"Serviceb�ro des jfd",0,'L');
		$pdf->MultiCell(180,5,"05971-91448-19",0,'L');
		$pdf->MultiCell(180,5,"Schleupestra�e 13",0,'L');
		$pdf->MultiCell(180,5,"48431 Rheine",0,'L');		
	}
	
	/*
	*	Formular f�r den Einbehalt des Essensgeldes anzeigen
	*	wenn gegessen wird && Daf�r der Zuschuss bezahlt wird && Sozialleistungen empfangen werden && der Einbehalt nicht ausgeschlossen wurde
	*/
	if($essen_flag && $data->ba_zuschuss_essen && $data->ba_sozialleistungen == pow(2,0) && $data->ba_sozial != 1){
		$pdf->AddPage(); 
		$pdf->SetMargins(20,20);
		
		$pdf->MultiCell(180,5,
						"Jugend- und Familiendienst e.V.\nWadelheimer Chaussee 195\n48432 Rheine\n\nTel.-Nr. 05971-91448-19 \n             05971-91448-28"
						,0,'L'
		);
			
		$pdf->Ln(10);
		
		$pdf->Image($logoPath, 160, 20,$logoWidth);
		
		$pdf->SetFont('Arial','',11);
		
		$pdf->Cell(50,10,'Name:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$data->e_name, $data->e_vorname"),0,0,'L');
		$pdf->Ln(5);
		
		$pdf->Cell(50,10,'Adresse:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$data->e_strasse"),0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(50,10,'',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$ort->o_plz, $ort->o_ort"),0,0,'L');
		$pdf->Ln(5);
		
		if($data->k_bezeichnung){
			$pdf->Cell(50,10,'Klasse:',0,0,'L');
			$pdf->Cell(155,10,utf8_decode($data->k_bezeichnung),0,0,'L'); // <== PLZ + ORT!
			$pdf->Ln(5);
		}
		
		$pdf->Cell(50,10,'Schule:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode($school->schul_name) ,0,0,'L');
		
		$pdf->Ln(5);
		$pdf->Cell(50,10,'KIJU-Nr:',0,0,'L');
		$pdf->Cell(155,10,'KIJU-'.$school->schul_kuerzel.'-'.$data->s_id,0,0,'L');			
		
		$pdf->Ln(20);
		
		$pdf->SetFont('Arial','B',11);
		$pdf->Cell(155,10,"Einverst�ndniserkl�rung",0,0,'L');
		$pdf->Ln(10);
		
		$pdf->SetFont('Arial','',11);
		
		$pdf->MultiCell(155,
						5,
						"Mein Kind ".utf8_decode("$data->s_vorname $data->s_name")." besucht den offenen Ganztag der oben genannten Schule und erh�lt ein Mittagessen. Es entstehen Kosten in H�he von 20,00 � monatlich.",
						0,
						'L'
		);
		$pdf->Ln(10);
		
		
		$tmp = explode("-",$data->ba_anfang);
		$nextYear = $tmp[0] + 1;
		
		$pdf->MultiCell(155,
						5,
						"Zur Zeit erhalte ich Leistungen nach SGB II und erkl�re mein Einverst�ndnis, dass diese Leistungen monatlich um 20,00 � verringert werden und dieser Betrag ab ".convertDate($data->ba_anfang)." bis ".convertDate($data->ba_ende)." direkt an den Tr�ger des offenen Ganztags, den Jugend- und Familiendienst e.V. f�r das Mittagessen meines Kindes �berwiesen wird.",
						0,
						'L'
		);
		
		$pdf->Ln(20);
		$pdf->Cell(70,10,'________________________________',0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(70,10,'Unterschrift des Erziehungsberechtigten',0,0,'L');
	}	
	
	/*
	* Beitragsgruppen
	*/
	// pr�fen ob das jahreseinkommen in die beitragsgruppen f�llt
	if($data->ba_zuschuss_essen){
		$pdf->AddPage(); 
		$pdf->SetMargins(20,50);
		
		$pdf->SetFont('Arial','',10);
		
		$pdf->MultiCell(180,5,
						"Jugend- und Familiendienst e.V.\nWadelheimer Chaussee 195\n48432 Rheine\n\nTel.-Nr. 05971-91448-19 \n             05971-91448-28"
						,0,'L'
		);
		
		$pdf->Ln(10);
		
		$pdf->Image($logoPath, 160, 20,$logoWidth);
		
		$pdf->SetFont('Arial','B',11);
		
		$pdf->Cell(180,10,'Antrag',0,0,'C');
		$pdf->Ln(10);
		
		$pdf->Cell(180,10,'auf Zuschuss zur Gemeinschaftsverpflegung',0,0,'C');
		$pdf->Ln(10);
		
		$pdf->SetFont('Arial','',11);
		
		$pdf->Cell(180,10,'f�r',0,0,'C');
		$pdf->Ln(20);
		
		// ---
		
		$pdf->Cell(50,10,'Name,Vorname:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$data->s_name, $data->s_vorname"),0,0,'L');
		$pdf->Ln(5);
		$pdf->Cell(50,10,'Stra�e:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$data->s_strasse"),0,0,'L');
		
		// plz holen
		$ort = $sql->getOrtById($data->s_plz);

		$pdf->Ln(5);
		$pdf->Cell(50,10,'PLZ, Ort:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode("$ort->o_plz,$ort->o_ort"),0,0,'L'); // <== PLZ + ORT!
		
		if($data->k_bezeichnung){
			$pdf->Ln(5);
			$pdf->Cell(50,10,'Klasse:',0,0,'L');
			$pdf->Cell(155,10,utf8_decode($data->k_bezeichnung),0,0,'L'); // 
		}
	
		$pdf->Ln(5);
		$pdf->Cell(50,10,'Schule:',0,0,'L');
		$pdf->Cell(155,10,utf8_decode($school->schul_name),0,0,'L'); 
		
		$pdf->Ln(5);
		$pdf->Cell(50,10,'KIJU-Nr:',0,0,'L');
		$pdf->Cell(155,10,'KIJU-'.$school->schul_kuerzel.'-'.$data->s_id,0,0,'L');	
					
		$pdf->Ln(20);
		
		$pdf->MultiCell(155,
						5,
						"Ich beantrage einen Zuschuss aus Landes- und Kommunalmitteln zur Teilnahme meines Kindes an der Gemeinschaftsverpflegung im Rahmen der Offenen Ganztagsschule.",
						0,
						'L'
		);
		$pdf->Ln(10);
		$pdf->MultiCell(155,
						5,
						"Den f�lligen Elternbeitrag von j�hrlich 200,00 � werde ich in Raten von 10 x 20,00 � entrichten.",
						0,
						'L'
		);
		$pdf->Ln(10);
	
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B',11);
		
		/*$pdf->MultiCell(155,
						5,
						"Bei Verlust des Essensausweisen f�llt f�r die Neuerstellung eine Geb�hr in H�he von 3,00 � an.",
						0,
						'L'
		);*/
		$pdf->SetFont('Arial','',11);
		$pdf->Ln(20);
		
		$pdf->Cell(70,10,'________________________________',0,0,'L');
		$pdf->Cell(15,2,' 		',0,"L");	// Abstand

		
		$pdf->Cell(70,10,'    __________________________________',0,0,'L');

		$pdf->Ln(5);
		$pdf->Cell(70,10,'(Datum)',0,0,'L');
		$pdf->Cell(16,2,' 		',0,"L");	// Abstand
		$pdf->Cell(85,10,'    (Unterschrift der Erziehungsberechtigten)',0,0,'L');
	}
	
	$pdf->Output("Auftrag.pdf","D");
?>