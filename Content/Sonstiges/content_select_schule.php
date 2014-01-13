<?php
	checkRights(200, $user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('auswaehlen');
	$self = 'select_schule';
    echo "<h1>Schule ausw&auml;hlen</h1>";
    
    if($page==md5('auswaehlen')) {
	?>
		<fieldset>
		
			<form action="index.php?content=<?php echo $self; ?>&p=<?php echo md5('eintragen');?>" method="post">
				
				<?php 

					$sql = "SELECT $tbl_mitarbeiter[schul_id] FROM `$tbl_mitarbeiter[tbl]`
							WHERE `$tbl_mitarbeiter[username]` = '".$user->getUsername()."'
							";
					$result = mysql_query($sql);
					$row = mysql_fetch_assoc($result);
					
					$sql = "SELECT * 
						 FROM $tbl_schulen[tbl]";
					$result = mysql_query($sql);
					
					echo "<label for=\"schul_id\" >Schule:</label>
							<select name=\"schul_id\" id=\"schul_id\">";
					while($row = mysql_fetch_assoc($result) )
					{
						if ($row[$tbl_schulen['id']] == $_SESSION['schul_id']){
							echo "<option selected=\"selected\" value=\"".$row[$tbl_schulen['id']]."\">".$row[$tbl_schulen['name']]."</option>";
						}else{
							echo "<option value=\"".$row[$tbl_schulen['id']]."\">".$row[$tbl_schulen['name']]."</option>";
						}
					}
					echo "</select><br /><br />";

				?>
				<input type="submit" class="formular" value="Ausw&auml;hlen" /> 
			</form>
		</fieldset>
	
	<?php

    }else {
		echo "Schule ausgew&auml;hlt.";
		$_SESSION['schul_id'] = $_POST['schul_id'];
		$_SESSION['selected_schule'] = $_POST['schul_id'];
		ob_clean();
		header('Location: index.php');
		
	}	