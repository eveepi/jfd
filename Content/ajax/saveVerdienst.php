<?php
include("../../config.php");
include("../../class.sql.php");

$sql = new Sql;

$beitrag = array();
$values = array(); 
$from = array();

$beitrag[] 	= (isset($_POST['beitrag']))?$_POST['beitrag']:"";
$from[]		= (isset($_POST['from']))?$_POST['from']:"";
$schul_id =	$_POST['schul_id'];
$values[] 	= (isset($_POST['verdienst']))?$_POST['verdienst']:"";
$essen[] 	= (isset($_POST['essen']))?$_POST['essen']:"";

$sql->insertVerdienst($values,$beitrag,$from,$essen,$schul_id);
?>