
<?php
	checkRights(250,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('get');
	$self = 'restore_bank';
    echo "<h1>Bankverbindungen wiederherstellen</h1>";

	$sql = "SELECT l_dump, l_record_id FROM betreuungsauftraege
			INNER JOIN bankverbindungen ON ba_bankverbindungs_ID = b_id
				AND b_methode = ''
			INNER JOIN log ON l_record_id = ba_bankverbindungs_ID
				AND l_table = 'bankverbindungen'
			;";
	$result = mysql_query($sql);

	while($row = mysql_fetch_assoc($result)){
	
		$query = str_replace("INSERT", "REPLACE", $row['l_dump']);
		$query = str_replace("`bankverbindungen` (", "`bankverbindungen` (`b_id`,", $query);
		$query = str_replace("VALUES (", "VALUES (".$row['l_record_id'].",", $query);
		
		//$result_query = mysql_query($query);
		echo $query."<br>";

	}
		
?>