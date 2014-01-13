<?php
  header('Content-Type: text/html; charset=utf-8');
	if(isset($_GET['content']) && $_GET['content'] == "betreuungsauftraege_PDF"){
		 //pdf geschichten
		define('FPDF_FONTPATH','pdf/font/');
		include_once("pdf/fpdf.php");
	}

    ob_start();
    error_reporting(E_ERROR); // alle Fehler anzeigen
    //error_reporting(E_ALL); // alle Fehler anzeigen, auch notices usw. 

    //INCLUDES
    include_once("variablen.php");
    include_once("constant.php");
    include_once("config.php");
    include_once("class.user.php");
    include_once("class.sql.php");   
    include_once("functions.php");

    //Wenn magic_qoutes aktiv ist unnötige Slashes entfernen
    if(get_magic_quotes_gpc()) {
        array_stripslashes($_GET);
        array_stripslashes($_POST);
        array_stripslashes($_COOKIE);
    }

    //Ein Objekt der Klasse CUser ableiten
    $user = new CUser();
    if($user->LoggedIn()){
		$_SESSION['schul_id'] = isset($_SESSION['schul_id']) ? $_SESSION['schul_id'] : $user->getSchule();
	
		// get schoolname
		$sql = new Sql;
		$schoolData = $sql->getSchoolData( $_SESSION['schul_id']);
		$schoolName = $schoolData->schul_name;
	} else {
		$schoolName = "";
	}
?>


<!DOCTYPE html 
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
     <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="stylesheet" href="css/style.css" type="text/css" title="Standard" media="screen, projection" />
			<link rel="stylesheet" href="includes/jquery.tooltip.css" type="text/css" title="Standard" media="screen, projection" />
			<link rel="stylesheet" href="css/datePicker.css" type="text/css"/>
			<link rel="stylesheet" href="css/print.css" type="text/css"  media="print" />
            <title>Schulbetreuung - Jugend- und Familiendienst Rheine e.V.</title>
            <script src="includes/jquery.js" type="text/javascript"></script>
			<script src="includes/jquery.date.js" type="text/javascript"></script>
			<script src="includes/jquery.datePicker.js" type="text/javascript"></script>
            <script src="includes/jquery.form.js" type="text/javascript"></script>
            <script src="includes/jquery.validate.js" type="text/javascript"></script>
            <script src="includes/functions.js" type="text/javascript"></script>
			<script src="includes/jquery.tooltip.js" type="text/javascript"></script>
    </head>
<body>
	<!--<input type="hidden" name="schul_id" id="schul_id" value="<?php echo $_SESSION['schul_id']; ?>" />-->
	<noscript>
		<style>
		div#header, div#container1, div#footer{
			display:none;
		}
		</style>
		<div><span>Bitte aktivieren Sie JavaScript um diese Seite zu nutzen!</span></div>
	</noscript>
<div id="header">
	<?php
		include("head.php");
	?>
			
</div>

<div id="container1">
	<div id="container2">

		<div id="navi">
			<?php
				include("navi.php");
			?>
		</div>
		
		<div id="content">
		
			<?php
			
				if($user->getStatus() == 250){
					if(isset($_GET['content']) AND isset($dateien_content[$_GET['content']]))
						{
							include($dateien_content[$_GET['content']]);			
						}
						else
						{
							include($dateien_content['home']);
						}
				}else{
					try{
						if(isset($_GET['content']) AND isset($dateien_content[$_GET['content']]))
						{
							include($dateien_content[$_GET['content']]);
						}
						else
						{
							include($dateien_content['home']);
						}
					}catch (Exception $e) {
						echo "Es ist ein Fehler aufgetreten!<hr />";
						echo $e->getMessage();
						echo ' in '.$e->getFile().', line: '.
						$e->getLine().'.';
						// Eventuell weiterführende Fehlerbehandlung...
					}
				}
			?>
		</div>
		<div class="clear">&nbsp;</div>
	</div>
	<br/>
</div>

<div id="footer">
	<?php
		include("footer.php");
	?>
</div>
 </body>
</html>
