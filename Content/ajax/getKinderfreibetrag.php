<?php
include('../../variablen.php');
include('../../config.php');
include('../../class.sql.php');

	$sql = new Sql;
	echo $sql->getKinderfreibetrag();
?>