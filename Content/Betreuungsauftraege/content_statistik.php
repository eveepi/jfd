<?php
	checkRights(50,$user);

    $page = isset($_GET['p']) ? $_GET['p'] : md5('verwalten');
	$self = 'bankverbindungen_verwalten';
    echo "<h1>Statistik</h1>";

    if($page==md5('verwalten')) {	
		
		if(date("m") < 8){
			$selected = date("Y");
		}else {
			$selected = date("Y")+1;
		}
		
		echo "<form action='index.php?content=statistik_get&p=".md5('exportieren')."' target='_blank' method='POST'>";
			
		$html = '<label for="year">Schuljahr:</label><select name="year">';
		for($now = 2008; $now <= 2020 ;$now++){
			$next = $now + 1;
			$html .= "<option value=\"$next\" ";
			$html .= ($selected == "$next")?'selected="selected"':"";
			$html .= ">$now  / $next</option>";
		}
		$html .= '</select><br/>';		
		echo $html;
		
		if(checkRights(200, $user, 1)){
			?>
			<label>Alle Schulen exportieren:</label>
			<input type="checkbox" name="all_schools" value="1"/>
			<br/>
			
			<label>Meal-O Export:</label>
			<input type="checkbox" name="mealo" value="1"/>
			<br/>			
			<?php
		}
		
		echo "<input type='submit' value='Exportieren'>";
		echo "</form>";
	} 
?>
