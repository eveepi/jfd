<?php
checkRights(200,$user);
?>
<script type="text/javascript">
	$(document).ready(function() {
		
		$("#neuerbearbeiter").validate({
				rules: {
					b_username:{
						required	: true,
					},
					b_vorname:{
						required	: true,
					},
					b_nachname:{
						required	: true,
					},
					email:{
						required	: true,
						email		: true
					},
					b_pwd1:{
						required	: true,
					},
					b_pwd2:{
						required	: true,
						equalTo		: "#b_pwd1"
					},
				},
				messages:{
					b_username:{
						required	: "Bitte einen Usernamen eingeben!",
					},
					b_vorname:{
						required	: "Bitte einen Vornamen angeben!",
					},
					b_nachname:{
						required	: "Bitte einen Nachnamen angeben!",
					},
					email:{
						required	: "Bitte eine E-Mail Adresse angeben!",
						email		: "Bitte eine g&uuml;ltige E-Mail Adresse angeben!"
					},
					b_pwd1:{
						required	: "Bitte ein Passwort angeben!",
					},
					b_pwd2:{
						required	: "Bitte das Passwort best&auml;tigen!",
						equalTo		: "Passw&ouml;rter stimmen nicht &Uuml;berein!"
					},
				}
			}
		);
	});
</script>
<?php
    $page = isset($_GET['p']) ? $_GET['p'] : md5('anlegen');
    $self = 'bearbeiter_neu';
    
    $_SESSION['nachname'] = isset($_SESSION['nachname']) ? $_SESSION['nachname'] : '';
    $_SESSION['vorname'] = isset($_SESSION['vorname']) ? $_SESSION['vorname'] : '';
    $_SESSION['username'] = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $_SESSION['email'] = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $_SESSION['status'] = isset($_SESSION['status']) ? $_SESSION['status'] : '';
    
	echo "<h1> Neuen Mitarbeiter anlegen </h1>";
	
	if($page==md5('eintragen')) {
    
		$_SESSION['nachname'] = $_POST['b_nachname'];
		$_SESSION['vorname'] = $_POST['b_vorname'];
		$_SESSION['username'] = $_POST['b_username'];
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['pwd1'] = $_POST['b_pwd1'];
		$_SESSION['pwd2'] = $_POST['b_pwd2'];
		$_SESSION['status'] = $_POST['b_status'];
		
		$sql = "SELECT *
		FROM mitarbeiter
		WHERE ".$tbl_mitarbeiter['username']."  like '".$_SESSION['username']."' ";
		
		$result = mysql_query($sql);

		$row = mysql_fetch_assoc($result);
		
		// prüfen ob benutzer schon vorhanden
		if ($row[$tbl_mitarbeiter['username']] == $_SESSION['username']){
 			echo "<h2>Benutzername schon vorhanden!<br />";
		} else  { // wenn nicht, db eintrag vornehmen
			$_SESSION['pwd1']=md5($_SESSION['pwd1']);
			$sql="
				INSERT INTO `$tbl_mitarbeiter[tbl]` 
				( `$tbl_mitarbeiter[username]` , `$tbl_mitarbeiter[vorname]` , `$tbl_mitarbeiter[name]`, 
				`$tbl_mitarbeiter[email]`, `$tbl_mitarbeiter[pwd]`, `$tbl_mitarbeiter[status]` )
				VALUES (
				'$_SESSION[username]', '$_SESSION[vorname]', '$_SESSION[nachname]',  '$_SESSION[email]', '$_SESSION[pwd1]', '$_SESSION[status]'
				);";
	        $result = mysql_query($sql);
	        
	        if (!$result) {
				echo "<h2>Fehler</h2>";
				echo "<p>Der Mitarbeiter konnte nicht angelegt werden, <br />bitte kontaktieren Sie den Systemadministrator und leiten sie den folgenden Text an ihn weiter:<br />
				".$sql."</p>";
			}
			else {
				$sql_class = new Sql;
				$sql_class->insertLog("mitarbeiter",$_SESSION['username'],$sql);
					
				echo "<h2>Eingetragen</h2>";
				echo "<p>Der Mitarbeiter wurde erfolgreich angelegt.</p>";
			}
			$_SESSION['nachname'] = '';
			$_SESSION['vorname'] = '';
			$_SESSION['username'] = '';
			$_SESSION['email'] = '';
			$_SESSION['pwd1'] = '';
			$_SESSION['pwd2'] = '';
			$_SESSION['status'] = '';
	}
	}
    if($page==md5('anlegen')) {
	?>
		<h2>Neuer Bearbeiter</h2>
		<fieldset>
			<form action="index.php?content=<?=$self?>&p=<?php echo md5('eintragen');?>" name="neuerbearbeiter" id="neuerbearbeiter" method="post">
			
				<input maxlength="50" type="text" name="test" id="test" class="hidden" value=""/>    <!--  Firefox bug, interpretiert diese kombination einmal je seite als Login -->
				<input maxlength="30" type="password" name="test" id="test" class="hidden"  value=""/>
			
				<label for="b_username" >Username:</label>
				<input maxlength="50" type="text" name="b_username" id="b_username" value="<?php echo $_SESSION['username'];?>"/>
				<br /> 
				
				<label for="b_vorname" >Vorname:</label>
				<input maxlength="50" type="text" name="b_vorname" id="b_vorname" value="<?php echo $_SESSION['vorname'];?>"/>
				<br /> 
				
				<label for="b_nachname" >Nachname:</label>
				<input maxlength="50" type="text" name="b_nachname" id="b_nachname" value="<?php echo $_SESSION['nachname'];?>"/>
				<br /> 

				<label for="email" >eMail-Adresse:</label>
				<input maxlength="50" type="text" name="email" id="email"  value="<?php echo $_SESSION['email'];?>"/>
				<br /> 

				<label for="b_pwd1" >Passwort:</label>
				<input maxlength="30" type="password" name="b_pwd1" id="b_pwd1" value=""/>
				<br />				
                <label for="b_pwd2" >Passwort Wiederholen:</label>
				<input maxlength="30" type="password" name="b_pwd2" id="b_pwd2" value=""/>
				<br />	
                <br />	
                <label for="b_status" >Status:</label>
                <select name="b_status">
                    <option value="0" <?php if($_SESSION['status']==0) echo "selected=\"selected\"";?> >Inaktiv</option>
                    <option value="50" <?php if($_SESSION['status']==50) echo "selected=\"selected\"";?>>Schule</option>
                    <option value="200" <?php if($_SESSION['status']==200) echo "selected=\"selected\"";?>>JFD</option>
                </select>
				<br />
				
				<input type="submit" class="formular" value="Weiter" /> 
			</form>
		</fieldset>
<?php
}
?>