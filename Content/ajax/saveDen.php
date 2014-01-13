<?php
include("../../class.sql.php");
include("../../config.php");

$sql = new Sql;

$sql->insertDen($_POST['name'],$_POST['telefon'],$_POST['strasse'],$_POST['plz'],$_POST['schul_id']);

$sql = "
		SELECT * FROM aerzte 
		WHERE
		ae_name 	= '$_POST[name]'		AND
		ae_telefon 	= '$_POST[telefon]' 	AND
		ae_strasse	= '$_POST[strasse]' 	AND
		ae_plz		= '$_POST[plz]' 		AND
		ae_schul_id	= '$_POST[schul_id]'
		";
$result = mysql_query($sql);
$row = mysql_fetch_object($result);
echo $row->ae_id;

?>