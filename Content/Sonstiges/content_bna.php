<?php
checkRights(200,$user);
?>
	<?php
    $page = isset($_GET['p']) ? $_GET['p'] : md5('aendern');
	echo "<h1>Begr&uuml;&szlig;ungsnachricht &auml;ndern</h1>";
	
    if($page==md5('aendern')) {
		$sql="select `$tbl_termine[nachricht]` from `$tbl_termine[tbl]` where `$tbl_termine[id]` = '1';";
		$result=mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$t_nachricht=$row[$tbl_termine['nachricht']];
		?>
		<form action="index.php?content=bna&p=<?php echo md5('speichern');?>" method="post">
			<textarea rows="20" name="t_nachricht"><?php echo $t_nachricht; ?></textarea>
			<input type="submit" value="Speichern">
		</form>
		<?php
	} elseif($page==md5('speichern')) {
		$sql="select `$tbl_termine[nachricht]` from `$tbl_termine[tbl]` where `$tbl_termine[id]` = '1';";
		$result=mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		
		$sql="UPDATE `$tbl_termine[tbl]` SET `t_nachricht` = '".$_POST['t_nachricht']."' 
		WHERE `t_id`= 1;";
		$result=mysql_query($sql);
		echo "<h2>Begr&uuml;&szlig;ungsnachricht ge&auml;ndert</h2>";
		
			$sql_class = new Sql;
			$sql_class->insertLog("termine",1,$sql);
	
	}
	?>