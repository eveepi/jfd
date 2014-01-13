<?php
include("../../class.sql.php");
include("../../config.php");
include("../../variablen.php");


$sql = "INSERT INTO `$tbl_klassen[tbl]` (
		`$tbl_klassen[id]` ,
		`$tbl_klassen[bezeichnung_intern]` ,
		`$tbl_klassen[bezeichnung]` ,
		`$tbl_klassen[status]`
		)
		VALUES (
			NULL , 
			'$_POST[name](".date("Y").")', 
			'$_POST[name]', 
			1
		);";

$result = mysql_query($sql);
$sql_class = new Sql;

$class_id = $sql_class->getLastId();

$sql_class->insertLog($tbl_klassen['tbl'],$class_id ,$sql);

echo $class_id ;

?>