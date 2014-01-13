<?php                               //Kalenderscript von http://my-php.tk calendat 2.22 von Erik Holman
                                    //Angepasst von Simon Evers
	$calendar_script          = "kalender.php"; //The location of this script
	$calendar_language        = "de";       //The extension of the calendar language file.

	$content_background_color = "#b8b9bd";   //Background color of the column
	$content_font_color       = "#000000";   //The font color
	$content_font_size        = 12;          //Font-size in pixels
	$content_font_style       = "italic";    //Set to italic or normal
	$content_font_weight      = "normal";    //Set to bold or normal

	$today_background_color   = "#3a3a3a";   //Background color of the column
	$today_font_color         = "#fffdef";   //The font color
	$today_font_size          = 12;          //Font-size in pixels
	$today_font_style         = "normal";    //Set to italic or normal
	$today_font_weight        = "bold";      //Set to bold or normal

	$event_background_color   = "#FFFFFF";   //Background color of the column
	$event_background_color2  = "#EEEEEE";   //Background color of the 2nd column (event popup)
	$event_font_color         = "#000000";   //The font color
	$event_font_size          = 12;          //Font-size in pixels
	$event_font_style         = "normal";    //Set to italic or normal
	$event_font_weight        = "bold";      //Set to bold or normal
    $event_popup_width        = "250";       //Width  of the popup for the events
    $event_popup_height       = "350";       //Height of the popup for the events
	
	$head_background_color    = "#FFFFFF";   //Background color of the column
	$head_font_color          = "#3a3a3a";   //The font color
	$head_font_size           = 13;          //Font-size in pixels
	$head_font_style          = "normal";    //Set to italic or normal
	$head_font_weight         = "bold";      //Set to bold or normal
	
	$days_head_background_color = "#FFFFFF";   //Background color of the column
	$days_head_font_color       = "#3a3a3a";    //The font color
	$days_head_font_size        = 13;          //Font-size in pixels
	$days_head_font_style       = "normal";    //Set to italic or normal
	$days_head_font_weight      = "normal";      //Set to bold or normal
	
	$table_border             = 0;           //The border of the table
	$table_cellspacing        = 2;           //Cellspacing of the table
	$table_cellpadding        = 1;           //Cellpadding of the table
	$table_width              = '';          //Table width in pixels or %'s
	$table_height             = '';          //Table height in pixels or %'s
	
	$head_link_color          = "#3a3a3a";    //The color of the link for previous/next month
	
	$font_family = "Verdana";

	$language_file  = "kalender." . $calendar_language;	    //Language file into variable
	$fd             = fopen( $language_file, "r" );             //Open the language file
	$fd             = fread( $fd, filesize( $language_file ) ); //Read the opened file
	$language_array = explode( "\n" , $fd );                    //Put file info into array 

	$dayname   = array_slice($language_array,0,7); //Tage
	$monthname = array_slice($language_array,7);   //Monate
	if( isset( $_GET['date'] ) )
		list($month,$year) = explode("-",$_GET['date']);
	else
	{
		$month = date("m");
		$year  = date("Y");
	}

	$date_string = mktime(0,0,0,$month,1,$year); //The date string we need for some info... saves space ^_^

	$day_start = date("w",$date_string);  //The number of the 1st day of the week

	$QUERY_STRING = ereg_replace("&date=".$month."-".$year,"",$_SERVER['QUERY_STRING']);

	if( $month < 12 )
	{
		$next_month = $month+1;
		$next_date = $next_month."-".$year;
	}
	else
	{
		$next_year = $year+1;
		$next_date = "1-".$next_year;
		$next_month = 1;
	}
	if( $month > 1 )
	{
		$previous_month = $month-1;
		$next_month    = $month+1;
		$previous_date = $previous_month."-".$year;
	}
	else
	{
		$previous_year = $year-1;
		$previous_date = "12-".$previous_year;
		$previous_month = 12;
	}

	$table_caption_prev = $monthname[$previous_month-1] . " " . $year; // previous
	$table_caption      = $monthname[date("n",$date_string)-1] . " " . $year; // current
  if ($next_month == 13){
    $next_month = 1;
    $year++;
  }
	$table_caption_foll = $monthname[$next_month-1] . " " . $year;   // following
	
  echo "
		<style type=\"text/css\">
			a.cal_head
			{
				color: " . $head_link_color . ";
			}
			a.cal_head:hover
			{
				text-decoration: none;
			}
			.cal_head
			{
				background-color: " . $head_background_color . ";
				color:            " . $head_font_color . ";
				font-family:      " . $font_family . ";
				font-weight:      " . $head_font_weight . ";
				font-style:       " . $head_font_style . ";
			}
			.cal_days /*darussol*/
			{
				background-color: " . $days_head_background_color . ";
				color:            " . $days_head_font_color . ";
				font-family:      " . $font_family . ";
				font-weight:      " . $days_head_font_weight . ";
				font-style:       " . $days_head_font_style . ";
			}
			.cal_content
			{
				background-color: " . $content_background_color . ";
				color:            " . $content_font_color . ";
				font-family:      " . $font_family . ";
				font-weight:      " . $content_font_weight . ";
				font-style:       " . $content_font_style . ";
			}
			.cal_today
			{
				background-color: " . $today_background_color . ";
				color:            " . $today_font_color . ";
				font-family:      " . $font_family . ";
				font-weight:      " . $today_font_weight . ";
				font-style:       " . $today_font_style . ";
			}
 			.cal_event, a.cal_event /* e-man 17-06-04 */
			{
				background-color: " . $event_background_color . ";
				color:            " . $event_font_color . ";
				font-family:      " . $font_family . ";
				font-weight:      " . $event_font_weight . ";
				font-style:       " . $event_font_style . ";
			}
		</style>
  ";
	echo "<br><br><br>";
	echo "
		<table border=\"" . $table_border . "\" cellpadding=\"" . $table_cellpadding . "\" cellspacing=\"" . $table_cellspacing . "\" height:" . $table_height . "\" width=\"" . $table_width . "\">
			<tr>
				<td align=\"center\" class=\"cal_head\"><a class=\"cal_head\" href=\"" . $_SERVER['PHP_SELF'] . "?" . $QUERY_STRING . "&amp;date=" .
                $previous_date . "\" title=\"" . $table_caption_prev . "\">&laquo;</a></td>
				<td align=\"center\" class=\"cal_head\" colspan=\"5\">" . $table_caption . "</td>
				<td align=\"center\" class=\"cal_head\"><a class=\"cal_head\" href=\"" . $_SERVER['PHP_SELF'] . "?" . $QUERY_STRING . "&amp;date=" .
                $next_date . "\" title=\"" . $table_caption_foll . "\">&raquo;</a></td>
			</tr>
			<tr>
				<td class=\"cal_days\">".$dayname[0]."</td>
				<td class=\"cal_days\">".$dayname[1]."</td>
				<td class=\"cal_days\">".$dayname[2]."</td>
				<td class=\"cal_days\">".$dayname[3]."</td>
				<td class=\"cal_days\">".$dayname[4]."</td>
				<td class=\"cal_days\">".$dayname[5]."</td>
				<td class=\"cal_days\">".$dayname[6]."</td>
			</tr><tr>
			";

	//leere zellen
	for( $i = 0 ; $i < $day_start; $i++ )
	{
		echo "<td class=\"cal_content\">&nbsp;</td>";
	}
	
	$current_position = $day_start; 
	
	$total_days_in_month = date("t",$date_string); 


	for( $i = 1; $i <= $total_days_in_month ; $i++)
	{
		$class = "cal_content";
		
		if( $i == date("j") && $month == date("n") && $year == date("Y") ){
			$class = "cal_today";
		$tag=$i;
		$monat=$month;
		$jahr=$year;
		}
		$current_position++;
	 	$link_start = "";
		$link_end   = "";

    $date_stamp = $year."-".$month."-".sprintf( "%02d",$i);
    
		echo "<td align=\"center\" class=\"" . $class . "\">" . $link_start . $i . $link_end . "</td>";
		if( $current_position == 7 )
		{
			echo "</tr><tr>\n";
			$current_position = 0;
		}
	}
	
	$end_day = 7-$current_position;
	
	for( $i = 0 ; $i < $end_day ; $i++ )
		echo "<td class=\"cal_content\"></td>\n";
	
	echo "</tr></table>";  
	
?>