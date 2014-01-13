<?php
//Klasse cUser
//von: Simon Evers, Benjamin Cremer
//Datum: 07.01.07
//
//Bescheibung:
//Diese Klasse behandelt die gesamte Rechteverwaltung
//
//
ob_start();

class CUser
{
    
    //Daten f�r die User-Tabelle
    //
    var $c_usertable; 		//Name der Usertabelle
    var $c_field_username; 	//Name des Feldes für den Usernamen
    var $c_field_password;	//Name des Feldes für das Passwort
    var $c_field_status;	//Name des Feldes für den Status/Berechtigung
    var $c_field_schule;	//Name des Feldes für die Schulid
    
    //Allgemeine Variablen
    //
    var $c_logged_in = false; //Eingeloggt, ja oder nein
    
      
    
    
    function CUser($table = 'mitarbeiter', $username = 'm_username', $password = 'm_pwd ', $status = 'm_status', $schule = 'm_schul_id')
    {
        session_start();
        if(!isset($_SESSION['IP']) OR !isset($_SESSION['time'])) {  
			$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION['time'] = time();
			$_SESSION['selected_schule'] = 0;
        }       
        if($_SESSION['IP'] != $_SERVER['REMOTE_ADDR'] && 0) {		//Vergleicht die IP-Adresse mit der beim login gespeicherten IP
			echo "<HTML><HEAD><title>JFD-Rheine - Fehler</title><?xml version=\"1.0\" encoding=\"utf-8\"?></HEAD><body></body>";
            echo "<p class=\"error\">\n";
            echo "    Sie dürfen nicht die Sitzung von einem\n";
            echo "    anderen Mitarbeiter benutzen. Bitte folgen sie\n";
            echo "    dem Link um zur Login-Seite zu gelangen.\n";
            echo "    <a href=\"index.php\">Zur&uuml;ck zur Homepage</a>\n";
            echo "</p>\n</body></HTML>";
            session_destroy();
            die(); // Aus Sicherheitsgründen die Abarbeitung sofort beenden
        }
        if($_SESSION['time'] <= time() - 7200) {
			echo "<HTML><HEAD><title>JFD-Rheine - Fehler</title><?xml version=\"1.0\" encoding=\"utf-8\"?></HEAD><body></body>";
            echo "<p class=\"error\">\n";
            echo "    Ihre Session wurde beendet, weil Sie zu lange keine Aktion mehr durchgef&uuml;hrt haben<br/>";
            echo "    Bitte melden Sie sich erneut an.<br/>";
            echo "    <a href=\"index.php\">Zur&uuml;ck zur Homepage</a>\n";
            echo "</p>\n</body></HTML>";        
            session_destroy(); // Aus Sicherheitsgründen die Abarbeitung sofort beenden
            die();
        }
        
        $_SESSION['time'] = time();
        
        $this->c_usertable = $table;
        $this->c_field_username = $username;
        $this->c_field_password = $password;
        $this->c_field_status = $status;
        $this->c_field_schule = $schule;
   
    }
      
      
    function login($username, $password)
    {
    
		$username = str_replace ( "'", "", $username); //SQL-Injections verhindern

        $sql = "SELECT
          ".$this->c_field_username." 
        FROM
          ".$this->c_usertable."
        WHERE
          ".$this->c_field_username." = '".$username."' AND
          ".$this->c_field_password."= '".md5($password)."';";

        $result = mysql_query($sql) OR die(mysql_error());
        $row = mysql_fetch_assoc($result); 
        
        if ($row[$this->c_field_username]) {
            $_SESSION['sessionid'] = $row[$this->c_field_username];
            $this->c_logged_in = true;
            session_regenerate_id();
        } else {
            $this->c_logged_in = false;
        }
        return $this->c_logged_in;
    }
    
    function getUsername()
    {
        if($this->loggedIn()) {
			return $_SESSION['sessionid'];
        } else{
			return false;
		}
        
    }
    
    function loggedIn()
    {
        if (isset($_SESSION['sessionid'])) {
            $this->c_logged_in = true;
        } else {
            $this->c_logged_in = false;
        }
        return $this->c_logged_in;
    }
    
	
	function pwa($username, $aktpw,$newpw1,$newpw2) {       //Passwort ändern
		
		if($newpw1==$newpw2){
            if(strlen($newpw1)>=5) {
                $aktpw=md5($aktpw);
                $newpw1=md5($newpw1);
                $sql= "SELECT ".$this->c_field_password." FROM ".$this->c_usertable." WHERE ".$this->c_field_username." LIKE '".$username."';";
                $result=mysql_query($sql);
                $row = mysql_fetch_assoc($result);
                if($row["m_pwd"]==$aktpw){
                    $sql = "UPDATE ".$this->c_usertable." SET ".$this->c_field_password." = '$newpw1' WHERE ".$this->c_field_username." = '".$username."';";
                    mysql_query($sql);
                    $return = 1; //PW geändert    
                } else {
                    $return = 2; //altes PW falsch
                }    
            } else {
                $return = 3;//Passwort zu kurz   
            }
		} else {
            $return = 4; //Neue Passwörter stimmen nicht überein 
		}
	return $return;
	}
	
	function resetPW($username) {           //Funktion für Lehrer um Passw�rter zur�ckzusetzen auf "default"
        $sql = "
                UPDATE ".$this->c_usertable." 
                SET ".$this->c_field_password." = '".md5('default')."' 
                WHERE ".$this->c_field_username." = '".$username."'  ;";
        mysql_query($sql) OR die(mysql_error());
	}

	
	
    function getStatus()        //Holt den Wert von b_status um Berechtigung zu überprüfen
    {
		if($this->LoggedIn()){
			$sql = "SELECT
					".$this->c_field_status."
				FROM
					".$this->c_usertable."
				WHERE
					".$this->c_field_username." = '".$_SESSION['sessionid']."';";
			$result = mysql_query($sql) OR die(mysql_error());
			$row = mysql_fetch_assoc($result); 
			$status = $row[$this->c_field_status];
			
			return $status;
		}else{
			return 0;
		}
    }
    
    function getSchule()        //Holt die Schulid aus der Datenbank
    {
		$sql = "SELECT
					".$this->c_field_schule."
				FROM
					".$this->c_usertable."
				WHERE
					".$this->c_field_username." = '".$_SESSION['sessionid']."';";
		$result = mysql_query($sql) OR die(mysql_error());
		$row = mysql_fetch_assoc($result); 
		$schule = $row[$this->c_field_schule];
		
		if ($schule != 0){
			$_SESSION['schul_id']= $schule;
		} else {
			$_SESSION['schul_id']= $_SESSION['selected_schule'];
		}
		return $schule;
    }

    
    function logout()       //löscht das $_SESSION-Array vollständig und beendet die session
    {
        $_SESSION = array();
        session_unregister('sessionid');
        session_unset();
        session_destroy();
        return true;
    }
    
    function getklasse()
    {
		$sql = "SELECT b_klasse from tblbearbeiter WHERE b_username = '".$_SESSION['sessionid']."'";
		$row = mysql_fetch_assoc(mysql_query($sql));
		$klasse = $row['b_klasse'];
		return $klasse;
    }
   
    function getSchuleName($id){
    	$sql = "
				SELECT *
				FROM `schulen`
				WHERE `schul_id` = $id
				";
    	
    	$result = mysql_query($sql);
    	$row = mysql_fetch_object($result);
    	return $row->schul_name;
    }
}
ob_end_clean();
?>