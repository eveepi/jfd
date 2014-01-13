<?php
checkRights(40,$user);
?>
	<?php
    $page = isset($_GET['p']) ? $_GET['p'] : md5('aendern');
	echo "<h1>Passwort &auml;ndern</h1>";
	
    if($page==md5('aendern')) {
	?>
	<fieldset>
		<form action="index.php?content=pwa&p=<?php echo md5('speichern');?>" method="post">
					
			<input maxlength="50" type="text" name="test" id="test" class="hidden" value=""/>    <!--  Firefox bug, interpretiert diese kombination einmal je seite als Login -->
			<input maxlength="30" type="password" name="test" id="test" class="hidden"  value=""/>
					
					
			<label class="pwa" for="apasswort" >aktuelles Passwort:</label>
			<input maxlength="20" type="password" name="apasswort" id="apasswort" ><br /><br />	
			
			<label class="pwa" for="npasswort1" >neues Passwort:</label>
			<input maxlength="20" type="password" name="npasswort1" id="apasswort1" ><br />
			
			<label class="pwa" for="npasswort2" >Passwort best&auml;tigen:</label>
			<input maxlength="20" type="password" name="npasswort2" id="apasswort2" ><br /><br />
			
			<input type="submit" value="&Auml;ndern" /> 
		
		
		</form>
	</fieldset>
	<?php
	
	} elseif($page==md5('speichern')) {
		$tmp = $user->pwa($user->getUsername(), $_POST['apasswort'],$_POST['npasswort1'],$_POST['npasswort2']);
		
		if ($tmp == 1) { 	
			echo "Passwort ge&auml;ndert."; 
		} elseif ($tmp == 2) {
			echo "Ihr aktuelles Passwort ist falsch.";
		} elseif ($tmp == 3) {
			echo "Das Passwort muss mindestens 6 Zeichen lang sein.";
		} elseif ($tmp == 4) {
            echo "Die neuen Passw&ouml;rter sind nicht identisch!";
		}
	}
	?>