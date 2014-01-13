<?php
	checkRights(200, $user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('auswaehlen');
	$self = 'property';
	$sql = new Sql;
    echo "<h1>Programmeinstellungen</h1>";
    
    if($page==md5('eintragen')) {
		$sql->setKinderfreibetrag($_POST['kinderfreibetrag']);
		echo "<br/><span class='green'>Einstellungen gespeichert.</span><br/><br/>";	
    }
	?>
<script>
	$(document).ready(function() {		
		$("#property").validate(
			{
				rules: {
					kinderfreibetrag : {
						required: true,
						number: true
					}
				},
				messages: {
					kinderfreibetrag: "Bitte den Kinderfreibetrag als Zahl angeben!"
				}
			} 
		);
	});
</script>	

		<fieldset>
		
			<form action="index.php?content=<?php echo $self; ?>&p=<?php echo md5('eintragen');?>" id="property" method="post">
			
				<label for="e1_vorname" >Kinderfreibetrag:</label>
				<input type="text" value="<?php echo $sql->getKinderfreibetrag(); ?>" name="kinderfreibetrag" id="kinderfreibetrag"><br/>
				
				<input type="submit" class="formular" value="Speichern" /> 
			</form>
		</fieldset>	