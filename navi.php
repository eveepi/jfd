<?php


	if(!$user->LoggedIn()){
	
		//Login
	
		echo "<form action=\"index.php?content=login&p=".md5('login')."\" method=\"post\">";
		echo "  <input type=\"text\" name=\"username\" value=\"Anmeldename\" onfocus=\"if(this.value=='Anmeldename')this.value=''\" onblur=\"if(this.value=='')this.value='Anmeldename'\" /> <br />"; 
		echo "  <input type=\"password\" name=\"password\" value=\"xxx\" onfocus=\"if(this.value=='xxx')this.value=''\" onblur=\"if(this.value=='')this.value='xxx'\" /> <br />"; 
		echo "  <input type=\"submit\" name=\"login\" value=\"Login\"/>"; 
		echo "</form>";
    } else {
		
		?>
		<input maxlength="50" type="text" name="test" id="test" class="hidden" value=""/>    <!--  Firefox bug, interpretiert diese kombination einmal je seite als Login -->
		<input maxlength="30" type="password" name="test" id="test" class="hidden"  value=""/>
		<?php
		echo "<a id=\"logout\"href=\"index.php?content=login&p=".md5('logout')."\">Abmelden</a><br/>";
		echo "<div class='schoolname'>".$schoolName."</div>";
    
        if($user->getStatus()>=250) {		//Administrator
			?>
			<ul class="navi">
                <li class=header>Schulen</li>
                <li><a href="index.php?content=schule_neu">Schule anlegen</a></li>
                <li><a href="index.php?content=schulen_verwalten">Schulen verwalten</a></li>            
                
                <li class=header>Mitarbeiter</li>
                <li><a href="index.php?content=bearbeiter_neu">Mitarbeiter Anlegen</a></li>
                <li><a href="index.php?content=bearbeiter_verwalten">Mitarbeiter Verwalten</a></li>
                
                <li class=header>Auftr&auml;ge</li>
                <li><a href="index.php?content=betreuungsauftraege_neu">Betreungsauftrag Anlegen</a></li>
                <li><a href="index.php?content=betreuungsauftraege_verwalten">Betreuungsauftr&auml;ge Verwalten</a></li>
                <li><a href="index.php?content=statistik">Statistik</a></li>   
				<li><a href="index.php?content=betreuungsauftraege_suche">Suche</a></li>          
                <li class=header>Verwaltung</li>
                <li><a href="index.php?content=bankverbindungen_verwalten">Bankverbindungen verwalten</a></li>   
                <li><a href="index.php?content=aerzte_verwalten">&Auml;rzte Verwalten</a></li>
                <li><a href="index.php?content=klassen_verwalten">Klassen Verwalten</a></li>
                
                <li class=header>Sonstiges</li>
                <li><a href="index.php?content=pwa">Passwort &auml;ndern</a></li>
                <li><a href="index.php?content=select_schule">Schule ausw&auml;hlen</a></li>
                <li><a href="index.php?content=bna">Begr&uuml;ssungsnachricht<br/>&auml;ndern</a></li>
                <li><a href="files/help.pdf" target="_blank">Hilfe</a></li>
 
                <li class=header>Administration</li>
                <!--
                <li><a href="index.php?content=restore_bank">Bankverbindungen wiederherstellen</a></li>
                <li><a href="index.php?content=migrate_essen">Essen für 1€</a></li>-->

            </ul> 			
            <?php
		}
        elseif($user->getStatus()>=200) {		//User
			?>
			<ul class="navi">
                <?php if($_SESSION['schul_id']==0){ ?>
                <li class=header>Schulen</li>
                <li><a href="index.php?content=schule_neu">Schule anlegen</a></li>
                <li><a href="index.php?content=schulen_verwalten">Schulen verwalten</a></li>         
                
                <li class=header>Mitarbeiter</li>
                <li><a href="index.php?content=bearbeiter_neu">Mitarbeiter Anlegen</a></li>
                <li><a href="index.php?content=bearbeiter_verwalten">Mitarbeiter Verwalten</a></li>
                <?php } elseif($_SESSION['schul_id']){ ?>
                <li class=header>Auftr&auml;ge</li>
                <li><a href="index.php?content=betreuungsauftraege_neu">Betreungsauftrag Anlegen</a></li>
                <li><a href="index.php?content=betreuungsauftraege_verwalten">Betreuungsauftr&auml;ge Verwalten</a></li>
                <li><a href="index.php?content=statistik">Statistik</a></li>      
                <li><a href="index.php?content=betreuungsauftraege_suche">Suche</a></li> 
                <li class=header>Verwaltung</li>
                <li><a href="index.php?content=bankverbindungen_verwalten">Bankverbindungen verwalten</a></li>   
                <li><a href="index.php?content=klassen_verwalten">Klassen Verwalten</a></li>
                <?php } ?>
                <li class=header>Sonstiges</li>
                <li><a href="index.php?content=pwa">Passwort &auml;ndern</a></li>
                <li><a href="index.php?content=select_schule">Schule ausw&auml;hlen</a></li>
                <li><a href="index.php?content=property">Programmeinstellungen</a></li>
                <li><a href="index.php?content=bna">Begr&uuml;ssungsnachricht<br/>&auml;ndern</a></li>
                <li><a href="files/help.pdf" target="_blank">Hilfe</a></li>
                
            </ul> 			
			<?php
		}
        elseif($user->getStatus()>=50) {		//User
        		
			?>
			<ul class="navi">                     
                
                <li class=header>Auftr&auml;ge</li>
                <li><a href="index.php?content=betreuungsauftraege_neu">Betreungsauftrag Anlegen</a></li>
                <li><a href="index.php?content=betreuungsauftraege_verwalten">Betreuungsauftr&auml;ge Verwalten</a></li>
                <li><a href="index.php?content=statistik">Statistik</a></li>  
				<li><a href="index.php?content=betreuungsauftraege_suche">Suche</a></li>                                       
                
                <li class=header>Sonstiges</li>
                <li><a href="index.php?content=pwa">Passwort &auml;ndern</a></li>
                <li><a href="files/help.pdf" target="_blank">Hilfe</a></li>
                
            </ul> 			
			<?php
		}
		elseif($user->getStatus()>=40) {
        
		}
    }
?>

