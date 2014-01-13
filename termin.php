<?php

	echo "<br /><h2>Termine:</h2>";       //$tag, $monat, $jahr von kalender.php
	$sql="	SELECT *                
			FROM `$tbl_termine[tbl]`
			WHERE $tbl_termine[date] >= '".date("Y-m-d")."'       
			ORDER BY $tbl_termine[date]
			;";                 //AND t_id != 1 //erste ID ist für den Notizblock reserviert

	$result=mysql_query($sql);
	$tmp = 0;
	while($row = mysql_fetch_assoc($result)) {
		$tmp = 1;
		echo "<fieldset>".convertDate($row[$tbl_termine['date']])."<br/>";
		echo "<a href=\"index.php?content=termin&p=".md5('showone')."&t_id=".$row[$tbl_termine['id']]." \">".$row[$tbl_termine['betreff']]."</a></fieldset>";
	}
	if ($tmp != 1){
	echo "Keine Termine vorhanden.";
	}
?>		