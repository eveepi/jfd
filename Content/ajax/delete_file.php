<?php
	sleep(59);
	unlink(getcwd()."/../../".$_POST['filename']);
	echo "Die Datei wurde aus Sicherheitsgr&uuml;nden gel&ouml;scht.<br/> Bitte laden Sie die Seite erneut um die Datei herunterladen zu k&ouml;nnen.";
?>
