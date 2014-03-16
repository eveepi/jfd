<?php
	class Sql{
		
		protected $debug = false;

		function getBetreuungsauftraege($where = ""){
			$sql = "SELECT * FROM `betreuungsauftraege`
						INNER JOIN schueler ON s_id = ba_schueler_ID
						INNER JOIN erziehungsberechtigte ON s_e_id = e_id
						INNER JOIN bankverbindungen ON ba_bankverbindungs_ID = b_id
						INNER JOIN orte ON o_id = s_plz
					WHERE ba_schul_ID = 48 
					$where;";
					
			if($this->debug)
				echo $sql."<br/>";
				
			return mysql_fetch_object(mysql_query($sql));
		}
    
    
		function moveBetreuungsauftrag($baid, $schulid, $user){
		
			if($user->getStatus() >= 200){
			
				$sql = "SELECT s_e_id, s_id FROM `betreuungsauftraege` 
                INNER JOIN schueler ON ba_schueler_ID = s_id
                WHERE `ba_id` = '$baid';";					
				if($this->debug)
          echo $sql."<br/>";		

          
        $result = mysql_query($sql);
        $row = mysql_fetch_object($result);       
        
				$sql = "UPDATE `betreuungsauftraege` SET `ba_schul_ID`='$schulid' WHERE `ba_id`='$baid';";	        
				$result = mysql_query($sql);	
				$this->insertLog("betreuungsauftraege",$id,$sql);	  
         
        if($this->debug)
          echo $sql."<br/>";
           
				$sql = "UPDATE `erziehungsberechtigte` SET `e_schul_id`='$schulid' WHERE `e_id`='$row->s_e_id';";	        
				$result = mysql_query($sql);	
				$this->insertLog("erziehungsberechtigte",$id,$sql);	  
        
        if($this->debug)
          echo $sql."<br/>";				

				$sql = "UPDATE `schueler` SET `s_schul_id`='$schulid' WHERE `s_id`='$row->s_id';";	        
				$result = mysql_query($sql);	
				$this->insertLog("schueler",$id,$sql);	
                
        if($this->debug)
          echo $sql."<br/>";
          
				return 0;
			}else{
				return -1;
			}
		
		}    
    
		function deleteBetreuungsauftrag($id, $user){
		
			if($user->getStatus() >= 200){
			
				$sql = "DELETE `betreuungsauftraege`, bankverbindungen FROM `betreuungsauftraege`
						INNER JOIN bankverbindungen ON ba_bankverbindungs_ID  = b_id
						WHERE `ba_id` = '$id'
						AND ba_schul_ID = ".$_SESSION['schul_id'].";";					
						
				if($this->debug)
					echo $sql."<br/>";
					
				$result = mysql_query($sql);
				
				$this->insertLog("betreuungsauftraege",$id,$sql);	
			
				return 0;
			}else{
				return -1;
			}
		
		}
		
		function getSchuelerNameByBaID($id){
			
			$sql = "SELECT s_name, s_vorname FROM `schueler`
					INNER JOIN betreuungsauftraege ON ba_schueler_ID = s_id
					WHERE ba_id = '$id'
					LIMIT 1;";
					
			if($this->debug)
				echo $sql."<br/>";
				
			$result = mysql_query($sql);
			$row = mysql_fetch_object($result);
			
			return $row->s_vorname." ".$row->s_name;
		
		}
		
		// 	s_name 	s_vorname 	s_strasse 	s_plz 	s_geburtsdatum 	s_schul_id
		function insertSchueler($name,$vorname,$street,$plz,$bday,$schul_id,$e_id,$klassen_id=""){
			require("variablen.php");
			
			$sql = "INSERT INTO `$tbl_schueler[tbl]` 
					(  `$tbl_schueler[vorname]` , `$tbl_schueler[name]` , `$tbl_schueler[strasse]` , `$tbl_schueler[plz]` , `$tbl_schueler[geburtsdatum]`, `$tbl_schueler[schul_id]` ,$tbl_schueler[klassen_id],`s_e_id`)
					VALUES('$vorname','$name','$street','$plz','$bday','$schul_id','$klassen_id','$e_id')		
				   ";	
			if($this->debug)
				echo $sql."<br/>";
			mysql_query($sql);
			
			$s_id = $this->getLastId();
			
			$this->insertLog($tbl_schueler['tbl'],$s_id,$sql);
			
			return $s_id;
		}
		
		function insertSchule($username, $schulname, $strasse, $ort, $telefon, $email, $ansprechpartner, $passwort, 
							  $mon_hour, $mon_minute, $tue_hour, $tue_minute, $wen_hour, $wen_minute, $thu_hour, $thu_minute, $fri_hour, $fri_minute, 
						      $mon_launch, $tue_launch, $wen_launch, $thu_launch, $fri_launch, 
						      $mon_betreuung, $tue_betreuung, $wen_betreuung, $thu_betreuung, $fri_betreuung, 
						      $essenspreis, $kuendigung, $traeger, $kuerzel){
			require("variablen.php");
			
			$sql = "SELECT *
			FROM mitarbeiter
			WHERE ".$tbl_mitarbeiter['username']."  like '".$username."' ";
			
			$result = mysql_query($sql);
	
			$row = mysql_fetch_assoc($result);
			if ($row[$tbl_mitarbeiter['username']] == $username){
				return 0;
			}else { 
				$tmpPW = md5($passwort);
				$sql="INSERT INTO `$tbl_schulen[tbl]` 
					( `$tbl_schulen[id]` , `$tbl_schulen[name]` , `$tbl_schulen[strasse]`, 
					`$tbl_schulen[plz]`, `$tbl_schulen[telefon]`, `$tbl_schulen[ansprechpartner]` , `$tbl_schulen[kuerzel]`,
					$tbl_schulen[time_mon], $tbl_schulen[time_tue], $tbl_schulen[time_wen], $tbl_schulen[time_thu],$tbl_schulen[time_fri],
					$tbl_schulen[launch_mon], $tbl_schulen[launch_tue], $tbl_schulen[launch_wen], $tbl_schulen[launch_thu], $tbl_schulen[launch_fri], 
					ba_mon, ba_tue, ba_wen, ba_thu, ba_fri, essenspreis, kuendigung, traeger)
					VALUES (
					'', '$schulname',  '$strasse', '$ort', '$telefon', '$ansprechpartner', '$kuerzel',
					'$mon_hour:$mon_minute','$tue_hour:$tue_minute','$wen_hour:$wen_minute','$thu_hour:$thu_minute','$fri_hour:$fri_minute',
					'$mon_launch','$tue_launch','$wen_launch','$thu_launch','$fri_launch', 
					'$mon_betreuung','$tue_betreuung','$wen_betreuung','$thu_betreuung','$fri_betreuung','".str_replace(',','.', $essenspreis)."','$kuendigung','$traeger'
					);";
		
				$result = mysql_query($sql);
				
				$sql2="SELECT last_insert_id() AS id;";
				
				$result = mysql_query($sql2);
				
				$schul_id = mysql_fetch_assoc($result);
				$schul_id = $schul_id['id'];
				$this->insertLog("schulen",$schul_id,$sql);
				
							
				$sql="INSERT INTO `$tbl_mitarbeiter[tbl]` 
				( `$tbl_mitarbeiter[username]` , `$tbl_mitarbeiter[vorname]` , `$tbl_mitarbeiter[name]`, 
				`$tbl_mitarbeiter[email]`, `$tbl_mitarbeiter[pwd]`, `$tbl_mitarbeiter[status]`, `$tbl_mitarbeiter[schul_id]`)
				VALUES (
				'$username', '', '$schulname',  '$email', '".md5($passwort)."', '50', '$schul_id'
				);";
				$result = mysql_query($sql);
				
				$this->insertLog("mitarbeiter",$this->getLastId(),$sql);
				
				return $schul_id;
				
			}
		}
		
		function insertOrt($plz, $ort){
			require("variablen.php");
			
			//abchecken ob plz schon in db
			$sql = "SELECT * FROM `$tbl_orte[tbl]` WHERE `$tbl_orte[plz]`= '$plz'";
			$result = mysql_query($sql);
			
			
			$row = mysql_fetch_object($result);
			if($row->o_plz == ""){
				$sql = "INSERT INTO `$tbl_orte[tbl]` (`$tbl_orte[plz]`,`$tbl_orte[ort]`) VALUES('$plz','$ort')"; 
				mysql_query($sql);
			}
		}
		/*
		 * e_id  	 e_name  	 e_vorname  	e_strasse   e_ort	 e_fon_privat  	 e_fon_dienst  	 e_name2  	 e_vorname2  	 e_fon_dienst2 
		 */
		function insertEltern($name, $vorname, $strasse, $ort, $fon_p, $fon_d = "", $name2 = "", $vorname2 = "", $fon_d2 = "", $schul_id = 0){
			require("variablen.php");
			
			$sql = "INSERT INTO `$tbl_erziehungsberechtigte[tbl]` 
			($tbl_erziehungsberechtigte[name],
			$tbl_erziehungsberechtigte[vorname],
			$tbl_erziehungsberechtigte[strasse],
			$tbl_erziehungsberechtigte[plz],
			$tbl_erziehungsberechtigte[fon_privat],
			$tbl_erziehungsberechtigte[fon_dienst],
			$tbl_erziehungsberechtigte[name2],
			$tbl_erziehungsberechtigte[vorname2],
			$tbl_erziehungsberechtigte[fon_dienst2],
			$tbl_erziehungsberechtigte[schul_id]
			) 
			VALUES('$name', '$vorname', '$strasse', '$ort', '$fon_p', '$fon_d', '$name2', '$vorname2', '$fon_d2', $schul_id)";
			
			if($this->debug)
				echo $sql."<br/>";
				
			mysql_query($sql);
			$e_id = $this->getLastId();
			
			$this->insertLog($tbl_erziehungsberechtigte['tbl'],$e_id,$sql);
			
			return $e_id;
		}
		
		function getLastId(){
			$sql = "SELECT last_insert_id() as id";
			$row = mysql_fetch_object($result = mysql_query($sql));
			return $row->id;
		}
		
		function getKinderfreibetrag(){
			$sql = "SELECT kinderfreibetrag from config 
					WHERE id = 0;";
			$row = mysql_fetch_object($result = mysql_query($sql));
			
			if ($this->debug) 
				echo $sql."<br/>";
			
			return $row->kinderfreibetrag;
		}		
		
		function setKinderfreibetrag($betrag){
			$sql = "UPDATE config
					SET kinderfreibetrag = ".$betrag."
					WHERE id = 0";
			mysql_query($sql);
			
			if ($this->debug) 
				echo $sql."<br/>";
		}	
	
		function insertDen($name,$telefon,$strasse,$plz,$schul_id){
			$sql = "
				INSERT INTO `aerzte` (
					`ae_name` ,
					`ae_telefon` ,
					`ae_strasse` ,
					`ae_plz` ,
					`ae_schul_id`
				)
				VALUES (
					 '$name','$telefon', '$strasse', '$plz', '$schul_id'
				);
			";
			
			if($this->debug)
					echo $sql."<br/>";
			 mysql_query($sql);	
			 $this->insertLog("aerzte",$this->getLastId(),$sql,"");
		}
		
		function insertBetreuungsauftrag($sid, $school_id, $start, $ende, $mo, $di, $mi, $do, $fr, $essenstage, $moslemisch, $allergien, $allergien_text, 
			$sozialleistungen, $jahreseinkommen, $ferien, $geschwister, $den_id, $besonderheiten, $bank_id, $status, $sozial_pdf, $zuschuss_pdf, $zuschuss_essen, 
			$kinder, $einkommensjahresausgleich, $brutto_monat, $unterhalt_monat, $grafikationen, $vermietung, $werbungskosten, $sonstige_einnahmen_monat){
		
			$sql = "INSERT INTO `betreuungsauftraege` 
					SET
					`ba_id` = '0' , 
					`ba_zuschuss` = '$zuschuss_pdf',
					`ba_zuschuss_essen` = $zuschuss_essen, 
					`ba_sozial` = '$sozial_pdf' , 
					`ba_schueler_ID` = '$sid' , 
					`ba_schul_ID` = '$school_id' , 
					`ba_anfang` = '$start' , 
					`ba_ende` = '$ende', 
					`ba_arzt_ID` = '$den_id' , 
					`ba_bankverbindungs_ID` = '$bank_id' , 
					`ba_montag` = '$mo' , 
					`ba_dienstag` = '$di' , 
					`ba_mitwoch` = '$mi' , 
					`ba_donnerstag` = '$do' , 
					`ba_freitag` = '$fr' , 
					`ba_essenstage` = '$essenstage' , 
					`ba_moslemisch` = '$moslemisch' , 
					`ba_allergien` = '$allergien' , 
					`ba_allergien_text` = '$allergien_text' , 
					`ba_besonderheiten` = '$besonderheiten' , 
					`ba_sozialleistungen` = '$sozialleistungen' , 
					`ba_jahreseinkommen` = '$jahreseinkommen' , 
					`ba_einkommensjahresausgleich` = CAST('$einkommensjahresausgleich' AS DECIMAL) , 
					`ba_kinder` = '$kinder' , 
					`ba_brutto_monat` = CAST('$brutto_monat' AS DECIMAL) , 
					`ba_unterhalt_monat` = CAST('$unterhalt_monat' AS DECIMAL) , 
					`ba_grafikationen` = CAST('$grafikationen' AS DECIMAL) , 
					`ba_vermietung` = CAST('$vermietung'  AS DECIMAL), 
					`ba_werbungskosten` = CAST('$werbungskosten' AS DECIMAL), 
					`ba_sonstige_einnahmen_monat` = CAST('$sonstige_einnahmen_monat' AS DECIMAL) , 
					`ba_ferien` = '$ferien' , 
					`ba_geschwister` = '$geschwister' , 
					`ba_status` = '$status' ";
					
			if($this->debug)
				echo $sql."<br/>";
			mysql_query($sql);
			
			$this->insertLog("betreuungsauftraege",$this->getLastId(),$sql);
		}
	
		function getUserData($id){
			$sql = "SELECT schueler.*, erziehungsberechtigte.*, betreuungsauftraege.*, aerzte.*, bankverbindungen.*, klassen.*, date_format(schueler.s_geburtsdatum, '%d.%m.%Y') AS geburtsdatum
					FROM schueler
					INNER JOIN erziehungsberechtigte ON schueler.s_e_id = erziehungsberechtigte.e_id
					INNER JOIN betreuungsauftraege ON betreuungsauftraege.ba_schueler_ID = schueler.s_id
					LEFT JOIN aerzte ON ba_arzt_ID = ae_id
					LEFT JOIN bankverbindungen ON betreuungsauftraege.ba_bankverbindungs_ID = bankverbindungen.b_id
					LEFT JOIN klassen ON schueler.s_klassen_id = klassen.k_id
					WHERE schueler.s_id = $id
					";
			if($this->debug)
				echo "<pre>".$sql."</pre><br/>";
			return mysql_fetch_object(mysql_query($sql));
		}
		
		function getBaData($id){
			$sql = "SELECT * FROM betreuungsauftraege
					INNER JOIN schueler ON betreuungsauftraege.ba_schueler_ID = schueler.s_id
					INNER JOIN erziehungsberechtigte ON schueler.s_e_id = erziehungsberechtigte.e_id
					INNER JOIN bankverbindungen ON betreuungsauftraege.ba_bankverbindungs_ID = bankverbindungen.b_id
					WHERE betreuungsauftraege.ba_id = $id";
			
			if($this->debug)
				echo "<pre>".$sql."</pre><br/>";
			return mysql_fetch_object(mysql_query($sql));
		}
		
		function updateBa($id, $anfang, $ende, $arzt_id, $montag, $dienstag, $mitwoch, $donnerstag, $freitag, $essenstage, 
							$moslemisch, $allergien_text, $ferien, $geschwister, $allergien,  $description, $einkommen, 
							$sozial, $status, $sozial_pdf, $zuschuss, $zuschuss_essen, $kinder, $einkommensjahresausgleich, $brutto_monat, $unterhalt_monat, 
							$grafikationen, $vermietung, $werbungskosten, $sonstige_einnahmen_monat){
			
			$sql = "UPDATE 
						`betreuungsauftraege` 
					SET 
						`ba_anfang` 			= '$anfang',
						`ba_ende` 				= '$ende',
						`ba_arzt_ID` 			= '$arzt_id',
						`ba_montag` 			= '$montag',
						`ba_dienstag` 			= '$dienstag',
						`ba_mitwoch` 			= '$mitwoch',
						`ba_donnerstag` 		= '$donnerstag',
						`ba_freitag` 			= '$freitag',
						`ba_essenstage` 		= '$essenstage',
						`ba_moslemisch` 		= '$moslemisch',
						`ba_allergien` 			= '$allergien',
						`ba_allergien_text` 	= '$allergien_text',
						`ba_jahreseinkommen` 	= '$einkommen',
						`ba_ferien` 			= '$ferien' ,
						`ba_geschwister` 		= '$geschwister', 
						`ba_sozialleistungen` 	= '$sozial',
						`ba_status` 			=  $status ,
						`ba_zuschuss`			=  $zuschuss,
						`ba_zuschuss_essen`		=  $zuschuss_essen,
						`ba_sozial`				=  $sozial_pdf,
						`ba_einkommensjahresausgleich` = CAST('$einkommensjahresausgleich' AS DECIMAL) , 
						`ba_kinder` = '$kinder' , 
						`ba_brutto_monat` = CAST('$brutto_monat' AS DECIMAL) , 
						`ba_unterhalt_monat` = CAST('$unterhalt_monat' AS DECIMAL) , 
						`ba_grafikationen` = CAST('$grafikationen' AS DECIMAL) , 
						`ba_vermietung` = CAST('$vermietung'  AS DECIMAL), 
						`ba_werbungskosten` = CAST('$werbungskosten' AS DECIMAL), 
						`ba_sonstige_einnahmen_monat` = CAST('$sonstige_einnahmen_monat' AS DECIMAL)
						WHERE 
						`ba_id` = $id ;";
			
			if($this->debug)
				echo $sql."<br/>";
				
			mysql_query($sql);
			$this->insertLog("betreuungsauftraege", $id, $sql, $description);
		}
		
		function insertBank($name, $blz, $ktnr, $vorname, $methode, $sonstiges, $holder, $iban, $bic){
			$sql = "INSERT INTO `bankverbindungen` (
						`b_name` ,
						`b_blz` ,
						`b_kntr` ,
						`b_vorname` ,
						`b_methode` ,
						`b_sonstiges`,
						`b_holder`,
						`b_iban`,
						`b_bic`
						)
					VALUES (
						'$name', '$blz', '$ktnr', '$vorname', '$methode', '$sonstiges', '$holder', '$iban', '$bic'
					);
					";
			if($this->debug)
				echo $sql."<br/>";
		
			mysql_query($sql);
			
			$b_id = $this->getLastId();
			
			$this->insertLog("bankverbindungen", $b_id, $sql);
			return $b_id;
		}

		function updateBank($id, $name, $blz, $ktnr, $vorname, $methode, $sonstiges, $holder, $iban, $bic){
			$sql = "
					UPDATE `bankverbindungen` 
					SET 
					`b_name` = '$name',
					`b_blz` = '$blz',
					`b_kntr` = '$ktnr',
					`b_vorname` = '$vorname',
					`b_methode` = '$methode',
					`b_sonstiges` = '$sonstiges',
					`b_holder` = '$holder',
					`b_iban` = '$iban',
					`b_bic` = '$bic'					
					WHERE `b_id` = $id
					";
				
			mysql_query($sql);
			
			$this->insertLog("bankverbindungen", $id, $sql);
			
			if($this->debug)
				echo $sql."<br/>";
		}
		
		function insertLog($table, $record_id, $query, $description=""){
			$sql = "
					INSERT INTO `log` (
					`l_id` ,
					`l_user` ,
					`l_table` ,
					`l_record_id` ,
					`l_time` ,
					`l_dump` ,
					`l_description`
					)
					VALUES (
					NULL , '$_SESSION[sessionid]', '$table', '$record_id',
					NULL , '".mysql_real_escape_string($query)."', '$description'
					);

					";
		//		if($this->debug)
		//			echo $sql."<br/>";
			
			mysql_query($sql);
		}
		
		function getSchoolData($id){
			$sql = "
				SELECT *
				FROM `schulen`
				WHERE schul_id = $id
			";
			
			if($this->debug)
				echo $sql."<br/>";
			return mysql_fetch_object(mysql_query($sql));
		}
		
		function getOrtById($id){
			$sql = "
					SELECT *
					FROM `orte`
					WHERE `o_id` = $id
				";
			if($this->debug)
				echo $sql."<br/>";
			return mysql_fetch_object(mysql_query($sql));	
		}
	
		/*
			@param array $values
			@param array $beitrag
		*/
		function insertVerdienst($values,$beitrag,$from,$essen,$schul_id){
					
			for($i=0;$i < count($values) ;$i++){
				
				//schleife �berspringen
				if($values[$i] == "")
					continue; 
				$essen[$i] = (isset($essen[$i])) ? $essen[$i] : 0;
				
				$sql = "
							INSERT INTO `verdienst` (
								`v_id` ,
								`v_verdienst` ,
								`schul_id`,
								`beitrag`,
								`from`,
								`sozi`
							)
							VALUES (
								NULL , '$values[$i]', '$schul_id','$beitrag[$i]',$from[$i],$essen[$i]
							);
						";
						
						
				if($this->debug)
					echo $sql."<hr/>";
						
				mysql_query($sql);
					
				
				$v_id=$this->getLastId();
				$this->insertLog("Verdiensttabelle",$v_id,$sql);
			}
		}
		
		function getVerdienst($schul_id){
			$sql = "SELECT v_verdienst,beitrag
					FROM `verdienst`
					WHERE schul_id = $schul_id";
			
			if($this->debug)
				echo $sql."<hr/>";
			return mysql_fetch_array(mysql_query($sql));
		}
		
		function getUserDataByNameOrForename($schul_id, $name = "", $forename = ""){
			$name = ($name == "") ? "%" : $name; 
			$forename = ($forename == "") ? "%" : $forename; 	
		
			$sql = "SELECT *
					FROM schueler
					INNER JOIN betreuungsauftraege ON betreuungsauftraege.ba_schueler_ID = schueler.s_id
					WHERE (schueler.s_name LIKE '$name' AND schueler.s_vorname LIKE '$forename')
					AND schueler.s_schul_id = $schul_id;";
					
			if($this->debug)
				echo $sql."<br/>";
				
			return mysql_query($sql);
		}

		function getBetreuungsAuftragBySchuelerId($schueler_id){
			$sql = "SELECT * FROM schueler
					INNER JOIN betreuungsauftraege ON betreuungsauftraege.ba_schueler_ID = schueler.s_id
					WHERE s_id = $schueler_id;";
			if($this->debug)
				echo $sql."<br/>";
			return mysql_query($sql);
		}
		
		function getMitarbeiterData($username){
			$sql = "SELECT * FROM `mitarbeiter` WHERE m_username='$username'";
			
			if($this->debug)
				echo $sql."<br/>";
					
			return mysql_fetch_object(mysql_query($sql));	
		}
		
	}
	
	
		
?>