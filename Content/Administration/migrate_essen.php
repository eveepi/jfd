<?php
	checkRights(250,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('get');
	$self = 'restore_bank';
    echo "<h1>Essen fÃ¼r einen â‚¬uro migrieren</h1>";
    
    $schulen = getSchulen();
    
    //dump($schulen);
    
    while($schule = mysql_fetch_assoc($schulen)){
		echo '<hr/>'.$schule['schul_name'].'<br/><br/>';
		//dump($schule);
		$sql = "SELECT *
				FROM `verdienst`
				WHERE schul_id = $schule[schul_id]
				ORDER BY v_verdienst DESC, `from` DESC
				";
			
		$verdienst = mysql_query($sql);
		
		$betreuungsauftraege = getBetreuungsauftraege($schule['schul_id']);
		
		while($ba = mysql_fetch_assoc($betreuungsauftraege)){
			//dump($ba);
			updateBA($ba['ba_id'], checkEssen($ba['ba_jahreseinkommen'], $verdienst));
		}
    }

function getBetreuungsauftraege($schul_id){
	$sql = "SELECT `ba_id`, 
				`ba_sozialleistungen`, 
				`ba_jahreseinkommen`
			FROM `betreuungsauftraege` 
			WHERE ba_schul_ID = $schul_id;";
	return mysql_query($sql); 
}

function getSchulen(){
	$sql = "SELECT `schul_id`, `schul_name`
			FROM `schulen`;";
	return mysql_query($sql); 
}

function updateBA($ba_id, $essen){
	$sql = "UPDATE `betreuungsauftraege` 
			SET `ba_zuschuss_essen` = '$essen'
			WHERE `ba_id` = '$ba_id' ;";
	//mysql_query($sql);
	
	echo $sql."<br/>";
}

function checkEssen($jahreseinkommen, $result){
	// essen für 1 € falls hartz4 etc.
	if($jahreseinkommen == 0 || getBeitragsGruppenByResult($jahreseinkommen, $result)){
		return 1;
	}else{
		return 0;
	}
}

function getBeitragsGruppenByResult($jahreseinkommen, $result){
	$Zuschuss_essen = 0;
	// alle verdienst eintrÃ¤ge durch gehen und den passenden beitrag zum jahreseinkommen ermitteln
	while($row = mysql_fetch_object($result)){
		if($row->from ){
			if($jahreseinkommen > $row->v_verdienst){
				$Zuschuss_essen = $row->sozi;
			}
		}else{
			if($jahreseinkommen <= $row->v_verdienst){
				$Zuschuss_essen = $row->sozi;
			}
		}
	}
	return $Zuschuss_essen;
}
		
?>