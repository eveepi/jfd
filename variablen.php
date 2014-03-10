<?php

	//Settings
	$icon_path = "icon/";

	//Navigation
    $dateien_content = array();
    $dateien_content['home'] 							= "Content/content_home.php";
    $dateien_content['login'] 							= "Content/content_login.php";
    
    //Aufträge
    $dateien_content['betreuungsauftraege_verwalten'] 	= "Content/Betreuungsauftraege/content_auftraege_verwalten.php";
    $dateien_content['statistik'] 						= "Content/Betreuungsauftraege/content_statistik.php";
    $dateien_content['statistik_get'] 					= "Content/Betreuungsauftraege/content_statistik_get.php";
    $dateien_content['betreuungsauftraege_neu'] 		= "Content/Betreuungsauftraege/content_auftraege_neu.php"; 
    $dateien_content['betreuungsauftraege_PDF'] 		= "Content/Betreuungsauftraege/getPDF.php";  
    $dateien_content['betreuungsauftraege_suche'] 		= "Content/Betreuungsauftraege/betreuungsauftraege_suche.php";  
    
    //SchÜler
    $dateien_content['schueler_neu'] 					= "Content/Schueler/content_schueler_neu.php";
    $dateien_content['schueler_verwalten'] 				= "Content/Schueler/content_schueler_verwalten.php";
    
    //Schulen
    $dateien_content['schule_neu'] 						= "Content/Schulen/content_schule_neu.php";
    $dateien_content['schulen_verwalten'] 				= "Content/Schulen/content_schulen_verwalten.php";
    
    //Erziehungsberechtigte
    $dateien_content['erziehungsberechtigte_neu'] 		= "Content/Erziehungsberechtigte/content_erziehungsberechtigte_neu.php";
    $dateien_content['erziehungsberechtigte_verwalten']	= "Content/Erziehungsberechtigte/content_erziehungsberechtigte_verwalten.php";
    
    //Bearbeiter
    $dateien_content['bearbeiter_verwalten'] 			= "Content/Bearbeiter/content_bearbeiter_verwalten.php";
    $dateien_content['bearbeiter_neu'] 					= "Content/Bearbeiter/content_bearbeiter_neu.php";

    //Verwalten
    $dateien_content['aerzte_verwalten'] 				= "Content/Verwalten/content_aerzte_verwalten.php";
    $dateien_content['klassen_verwalten'] 				= "Content/Verwalten/content_klassen_verwalten.php";
    
    //Administration
    $dateien_content['restore_bank'] 					= "Content/Administration/restore_bank.php";   
    $dateien_content['migrate_essen'] 					= "Content/Administration/migrate_essen.php";   

    //Sonstiges
    $dateien_content['pwa'] 							= "Content/Sonstiges/content_pwa.php";   
    $dateien_content['select_schule'] 					= "Content/Sonstiges/content_select_schule.php";   
    $dateien_content['termin'] 							= "Content/Sonstiges/content_termin.php";
    $dateien_content['bna'] 							= "Content/Sonstiges/content_bna.php";
    $dateien_content['property'] 						= "Content/Sonstiges/content_property.php";

    //Bankverbindungen
    $dateien_content['bankverbindungen_verwalten'] 		= "Content/Bankverbindungen/content_bankverbindungen_verwalten.php";
    $dateien_content['bankverbindungen_get'] 			= "Content/Bankverbindungen/content_bankverbindungen_get.php";
							
    //Datenbank
    
    //Tabellen
    //Mitarbeiter
    $tbl_mitarbeiter = array();
    $tbl_mitarbeiter['tbl'] 							= "mitarbeiter";
    $tbl_mitarbeiter['username'] 						= "m_username";
    $tbl_mitarbeiter['name'] 							= "m_name";
    $tbl_mitarbeiter['vorname'] 						= "m_vorname";
    $tbl_mitarbeiter['email'] 							= "m_email";
    $tbl_mitarbeiter['status'] 							= "m_status";
    $tbl_mitarbeiter['pwd'] 							= "m_pwd";
    $tbl_mitarbeiter['schul_id'] 						= "m_schul_id";
    
    //Termine
    $tbl_termine = array();
    $tbl_termine['tbl'] 								= "termine";
    $tbl_termine['id'] 									= "t_id";
	$tbl_termine['date'] 								= "t_date";
	$tbl_termine['betreff'] 							= "t_betreff";
	$tbl_termine['nachricht'] 							= "t_nachricht";
	
	//Schueler
	$tbl_schueler = array();
	$tbl_schueler['tbl'] 								= "schueler";
	$tbl_schueler['id'] 								= "s_id";
	$tbl_schueler['name'] 								= "s_name";
	$tbl_schueler['vorname'] 							= "s_vorname";
	$tbl_schueler['strasse'] 							= "s_strasse";
	$tbl_schueler['plz'] 								= "s_plz";
	$tbl_schueler['geburtsdatum'] 						= "s_geburtsdatum";
	$tbl_schueler['schul_id'] 							= "s_schul_id";
	$tbl_schueler['klassen_id'] 						= "s_klassen_id";
	$tbl_schueler['e_id'] 								= "s_e_id";
	
	//Schulen
	$tbl_schulen = array();
	$tbl_schulen['tbl'] 								= "schulen";
	$tbl_schulen['id'] 									= "schul_id";
	$tbl_schulen['name'] 								= "schul_name";
	$tbl_schulen['strasse'] 							= "schul_strasse";
	$tbl_schulen['plz'] 								= "schul_plz";
	$tbl_schulen['telefon'] 							= "schul_telefon";
	$tbl_schulen['ansprechpartner'] 					= "schul_ansprechpartner";
	$tbl_schulen['kuerzel'] 							= "schul_kuerzel";
	$tbl_schulen['time_mon'] 							= "time_mon";
	$tbl_schulen['time_tue'] 							= "time_tue";
	$tbl_schulen['time_wen'] 							= "time_wen";
	$tbl_schulen['time_thu'] 							= "time_thu";
	$tbl_schulen['time_fri'] 							= "time_fri";
	$tbl_schulen['launch_mon'] 							= "launch_mon";
	$tbl_schulen['launch_tue'] 							= "launch_tue";
	$tbl_schulen['launch_wen'] 							= "launch_wen";
	$tbl_schulen['launch_thu'] 							= "launch_thu";
	$tbl_schulen['launch_fri'] 							= "launch_fri";
	
	//Orte
	$tbl_orte = array();
	$tbl_orte['tbl'] 									= "orte";
	$tbl_orte['id'] 									= "o_id";
	$tbl_orte['plz'] 									= "o_plz";
	$tbl_orte['ort'] 									= "o_ort";
	
	//Erziehungsberechtigte
	$tbl_erziehungsberechtigte = array();
	$tbl_erziehungsberechtigte['tbl'] 					= "erziehungsberechtigte";
	$tbl_erziehungsberechtigte['id'] 					= "e_id";
	$tbl_erziehungsberechtigte['schul_id'] 				= "e_schul_id";
	$tbl_erziehungsberechtigte['name'] 					= "e_name";
	$tbl_erziehungsberechtigte['vorname'] 				= "e_vorname";
	$tbl_erziehungsberechtigte['strasse'] 				= "e_strasse";
	$tbl_erziehungsberechtigte['plz'] 					= "e_plz";
	$tbl_erziehungsberechtigte['fon_privat'] 			= "e_fon_privat";
	$tbl_erziehungsberechtigte['fon_dienst'] 			= "e_fon_dienst";
	$tbl_erziehungsberechtigte['name2'] 				= "e_name2";
	$tbl_erziehungsberechtigte['vorname2'] 				= "e_vorname2";
	$tbl_erziehungsberechtigte['fon_dienst2'] 			= "e_fon_dienst2";
	
	//Betreuungsauftraege
	$tbl_betreuungsauftraege = array();
	$tbl_betreuungsauftraege['tbl'] 					= "betreuungsauftraege";
	$tbl_betreuungsauftraege['id'] 						= "ba_id";
	$tbl_betreuungsauftraege['schueler_id'] 			= "ba_schueler_ID";
	$tbl_betreuungsauftraege['schul_id'] 				= "ba_schul_ID";
	$tbl_betreuungsauftraege['anfang'] 					= "ba_anfang";
	$tbl_betreuungsauftraege['ende'] 					= "ba_ende";
	$tbl_betreuungsauftraege['arzt_id'] 				= "ba_arzt_ID";
	$tbl_betreuungsauftraege['bankverbindungs_id'] 		= "ba_bankverbindungs_ID";
	$tbl_betreuungsauftraege['montag'] 					= "ba_montag";
	$tbl_betreuungsauftraege['dienstag'] 				= "ba_dienstag";
	$tbl_betreuungsauftraege['mitwoch'] 				= "ba_mitwoch";
	$tbl_betreuungsauftraege['donnerstag'] 				= "ba_donnerstag";
	$tbl_betreuungsauftraege['freitag'] 				= "ba_freitag";
	$tbl_betreuungsauftraege['essenstage'] 				= "ba_essenstage";
	$tbl_betreuungsauftraege['moslemisch'] 				= "ba_moslemisch";
	$tbl_betreuungsauftraege['allergien'] 				= "ba_allergien";
	$tbl_betreuungsauftraege['besonderheiten'] 			= "ba_besonderheiten";
	$tbl_betreuungsauftraege['sozialleistungen'] 		= "ba_sozialleistungen";
	$tbl_betreuungsauftraege['jahreseinkommen'] 		= "ba_jahreseinkommen";
	$tbl_betreuungsauftraege['ferien'] 					= "ba_ferien";
	$tbl_betreuungsauftraege['allergien_text'] 			= "ba_allergien_text";
	$tbl_betreuungsauftraege['geschwister'] 			= "ba_geschwister";
	$tbl_betreuungsauftraege['ba_zuschuss'] 			= "ba_zuschuss";
	$tbl_betreuungsauftraege['ba_sozial'] 				= "ba_sozial";
	
	//Klassen
	$tbl_klassen = array();
	$tbl_klassen['tbl'] 								= "klassen";
	$tbl_klassen['id'] 									= "k_id";
	$tbl_klassen['bezeichnung_intern'] 						= "k_bezeichnung_intern";
	$tbl_klassen['bezeichnung'] 						= "k_bezeichnung";
	$tbl_klassen['schul_id'] 							= "k_schul_id";
	$tbl_klassen['status'] 								= "k_status";
	
	//Ärzte
	$tbl_aerzte = array();
	$tbl_aerzte['tbl'] 									= "aerzte";
	$tbl_aerzte['id'] 									= "ae_id";
	$tbl_aerzte['name'] 								= "ae_name";
	$tbl_aerzte['telefon'] 								= "ae_telefon";
	$tbl_aerzte['strasse'] 								= "ae_strasse";
	$tbl_aerzte['plz'] 									= "ae_plz";
	$tbl_aerzte['schul_id'] 							= "ae_schul_id";

	//bankverbindungen
	$tbl_bankverbindungen = array();
	$tbl_bankverbindungen['tbl'] 						= "bankverbindungen";
	$tbl_bankverbindungen['id'] 						= "b_id";
	$tbl_bankverbindungen['name'] 						= "b_name";
	$tbl_bankverbindungen['vorname'] 					= "b_vorname";
	$tbl_bankverbindungen['blz'] 						= "b_blz";
	$tbl_bankverbindungen['ktnr'] 						= "b_kntr";
	$tbl_bankverbindungen['methode'] 					= "b_methode ";
	$tbl_bankverbindungen['sonstiges'] 					= "b_sonstiges";
	$tbl_bankverbindungen['holder'] 					= "b_holder";
	$tbl_bankverbindungen['iban'] 						= "b_iban";
	$tbl_bankverbindungen['bic'] 						= "b_bic";			
	$tbl_bankverbindungen['schul_id'] 					= "b_schul_id";
	
	//log
	$tbl_log = array();
	$tbl_log['tbl'] 									= "log";
	$tbl_log['id'] 										= "l_id";
	$tbl_log['user'] 									= "l_user";
	$tbl_log['table'] 									= "l_table";
	$tbl_log['record_id'] 								= "l_record_id";
	$tbl_log['time'] 									= "l_time";
	$tbl_log['dump'] 									= "l_dump";
	$tbl_log['description'] 							= "l_description";

?>