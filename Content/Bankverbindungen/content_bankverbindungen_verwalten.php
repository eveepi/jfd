<?php
	checkRights(200,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'bankverbindungen_verwalten';
    echo "<h1>Bankverbindungen verwalten</h1>";

    if($page==md5('verwalten')) {	
    $_GET['b'] = isset($_GET['b']) ? $_GET['b'] : 'lastschrift';
    
    echo "<a href=\"index.php?content=".$self."&b=lastschrift\"";
    if ($_GET['b'] == 'lastschrift') { echo "class=\"selected\" "; }
    echo ">Lastschrift</a>&nbsp;&nbsp;&nbsp;";

    echo "<a href=\"index.php?content=".$self."&b=ueberweisung\"";
    if ($_GET['b'] == 'ueberweisung') { echo "class=\"selected\" "; }
    echo ">Überweisung</a>&nbsp;&nbsp;&nbsp;";
    
    echo "<a href=\"index.php?content=".$self."&b=sonstige\"";
    if ($_GET['b'] == 'sonstiges') { echo "class=\"selected\" "; }
    echo ">Sonstiges</a><br /><br />";
    
	if($_GET['b']=="lastschrift"){		  
		    
	    $sql ="SELECT * FROM $tbl_bankverbindungen[tbl], $tbl_betreuungsauftraege[tbl], $tbl_schueler[tbl] 
			   WHERE $tbl_bankverbindungen[methode] = 'lastschrift'
			   AND $tbl_betreuungsauftraege[schul_id]  = $_SESSION[schul_id]
			   AND $tbl_bankverbindungen[id] = $tbl_betreuungsauftraege[bankverbindungs_id]
			   AND $tbl_schueler[id] = $tbl_betreuungsauftraege[schueler_id]";
		$result = mysql_query($sql);
	
		if($row = mysql_fetch_assoc($result)){
			echo "
			<table cellpadding='5' cellspacing='0'>
				<tr>
					<th>Vorname</th>
					<th>Nachname</th>
					<th>Kontonummer</th>
					<th>Bankleitzahl</th>
				</tr>";			
			
		$i = 0;
		do{
			$i = ($i - 1) * ($i - 1);
			echo "<tr class=\"row".$i."\">";
			echo "  <td>".$row[$tbl_bankverbindungen['name']]."</td>";
			echo "  <td>".$row[$tbl_bankverbindungen['vorname']]."</td>";
			echo "  <td>".$row[$tbl_bankverbindungen['ktnr']]."</td>";
			echo "  <td>".$row[$tbl_bankverbindungen['blz']]."</td>";
			echo "</tr>";
			
		}while($row = mysql_fetch_assoc($result));
		
		echo "</table><br/>";
		
		echo "<a class='button' href='index.php?content=bankverbindungen_get&p=".md5('exportieren')."&b=".$_GET['b']."' target='_blank' >Exportieren</a> ";
		
		}else {
			echo "Keine Datensätze vorhanden.";
		}
	}elseif($_GET['b']=="sepa"){		  
		    
	    $sql ="SELECT * FROM $tbl_bankverbindungen[tbl], $tbl_betreuungsauftraege[tbl], $tbl_schueler[tbl] 
			   WHERE $tbl_bankverbindungen[methode] = 'sepa'
			   AND $tbl_betreuungsauftraege[schul_id]  = $_SESSION[schul_id]
			   AND $tbl_bankverbindungen[id] = $tbl_betreuungsauftraege[bankverbindungs_id]
			   AND $tbl_schueler[id] = $tbl_betreuungsauftraege[schueler_id]";
		$result = mysql_query($sql);
	
		if($row = mysql_fetch_assoc($result)){
			echo "
			<table cellpadding='5' cellspacing='0'>
				<tr>
					<th>Inhaber</th>
					<th>Kontonummer</th>
					<th>Bankleitzahl</th>
				</tr>";			
			
		$i = 0;
		do{
			$i = ($i - 1) * ($i - 1);
			echo "<tr class=\"row".$i."\">";
			echo "  <td>".$row[$tbl_bankverbindungen['holder']]."</td>";
			echo "  <td>".$row[$tbl_bankverbindungen['iban']]."</td>";
			echo "  <td>".$row[$tbl_bankverbindungen['bic']]."</td>";
			echo "</tr>";
			
		}while($row = mysql_fetch_assoc($result));
		
		echo "</table><br/>";
		
		echo "<a class='button' href='index.php?content=bankverbindungen_get&p=".md5('exportieren')."&b=".$_GET['b']."' target='_blank' >Exportieren</a> ";
		
		}else {
			echo "Keine Datensätze vorhanden.";
		}
	} elseif ($_GET['b']=="ueberweisung"){
		    
		    $sql ="SELECT * FROM $tbl_bankverbindungen[tbl], $tbl_betreuungsauftraege[tbl], $tbl_schueler[tbl]
				   WHERE $tbl_bankverbindungen[methode] = 'ueberweisung'
				   AND $tbl_betreuungsauftraege[schul_id]  = $_SESSION[schul_id]
				   AND $tbl_bankverbindungen[id] = $tbl_betreuungsauftraege[bankverbindungs_id]
				   AND $tbl_betreuungsauftraege[schueler_id] = $tbl_schueler[id]";
			$result = mysql_query($sql);
			
			if($row = mysql_fetch_assoc($result)){
				echo "
				<table cellpadding='5' cellspacing='0'>
					<tr>
						<th>Vorname</th>
						<th>Nachname</th>
						<th>ID (Kundennummer)</th>
					</tr>";
				
				$i = 0;
				do{
					$i = ($i - 1) * ($i - 1);
					echo "<tr class=\"row".$i."\">";
					echo "  <td>".$row[$tbl_schueler['vorname']]."</td>";
					echo "  <td>".$row[$tbl_schueler['name']]."</td>";
					echo "  <td>".$row[$tbl_betreuungsauftraege['id']]."</td>";
					echo "</tr>";	
				}while($row = mysql_fetch_assoc($result));

			echo "</table><br/>";
			
			echo "<a class='button' href='index.php?content=bankverbindungen_get&p=".md5('exportieren')."&b=".$_GET['b']."' target='_blank' >Exportieren</a> ";
			
			}else {
				echo "Keine Datensätze vorhanden.";
			}
		
		} elseif ($_GET['b']=="sonstige"){
		
			$sql ="SELECT * FROM $tbl_bankverbindungen[tbl], $tbl_betreuungsauftraege[tbl], $tbl_schueler[tbl]
				   WHERE $tbl_bankverbindungen[methode] = 'sonstige'
				   AND $tbl_betreuungsauftraege[schul_id]  = $_SESSION[schul_id]
				   AND $tbl_bankverbindungen[id] = $tbl_betreuungsauftraege[bankverbindungs_id]
				   AND $tbl_betreuungsauftraege[schueler_id] = $tbl_schueler[id]";
			$result = mysql_query($sql);
			
			if($row = mysql_fetch_assoc($result)){
				echo "
				<table cellpadding='5' cellspacing='0'>
					<tr>
						<th>Vorname</th>
						<th>Nachname</th>
						<th>ID (Kundennummer)</th>
						<th>Beschreibung</th>
					</tr>";
				
				$i = 0;
				do{
					$i = ($i - 1) * ($i - 1);
					echo "<tr class=\"row".$i."\">";
					echo "  <td>".$row[$tbl_schueler['vorname']]."</td>";
					echo "  <td>".$row[$tbl_schueler['name']]."</td>";
					echo "  <td>".$row[$tbl_betreuungsauftraege['id']]."</td>";
					echo "  <td>".$row[$tbl_bankverbindungen['sonstiges']]."</td>";
					echo "</tr>";
					
				}while($row = mysql_fetch_assoc($result));
				echo "</table><br/>";
				
				echo "<a class='button' href='index.php?content=bankverbindungen_get&p=".md5('exportieren')."&b=".$_GET['b']."' target='_blank' >Exportieren</a> ";
			
			}else {
				echo "Keine Datensätze vorhanden.";
			}
		
		}
} 
?>
