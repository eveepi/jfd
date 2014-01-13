<?php

	if($user->loggedin(false)) {
		echo "<h1>Notizblock</h1>";
		
		$sql="select `t_nachricht` from `termine` where `t_id` = '1';";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		
		echo "<p id=\"notiz\">".nl2br($row['t_nachricht'])."</p>";
		
	} else {
		?>
		<h1>Willkommen im Mitarbeiterbereich des jfd Rheine</h1>
		<p>
			Bitte melden Sie sich mit dem Formular auf der linken Seite an.
		</p>
		<?php
	}
?>