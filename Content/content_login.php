<?php
    $page = isset($_GET['p']) ? $_GET['p'] : md5('login');
    
    if($page==md5('login')) {
        if(!$user->LoggedIn(FALSE)) {
			if(isset($_POST['username'])AND isset($_POST['password'])){
				if($user->Login($_POST['username'],$_POST['password'])){
					header("Location: index.php?content=login&p=".md5('loggedin')."");
				} else {
					header("Location: index.php?content=login&p=".md5('wrongpw')."");	
				}
			} else {
				echo "<p class=\"error\">Bitte melden Sie sich mit dem Eingabefelder auf der linken Seite an.</p>";
			}
		} else {
			echo "<p class=\"error\"> Sie sind bereits angemeldet.</p>";
		}
    
    } elseif($page==md5('loggedin')) {
            header("Location: index.php?content=home");
    
    } elseif($page==md5('loggedout')) {
		echo "<p class=\"error\">Sie haben sich erfolgreich abgemeldet.</p>";
    
    } elseif($page==md5('wrongpw')) {
		echo "<p class=\"error\">Falsches Passwort / Benutzername.</p>";
    
    } elseif($page==md5('logout')) {
        $user->Logout();
		header("Location: index.php?content=login&p=".md5('loggedout')."");    
    } elseif($page==md5('needloggedon')) {
		echo "<p class=\"error\">Sie m&uuml;ssen sich anmelden um diese Funktion zu nutzen.<p/>";    
    } elseif($page==md5('norights')) {
		echo "<p class=\"error\">Sie haben nicht gen&uuml;gend Rechte um diese Seite anzuzeigen.<p/>";    }
?> 