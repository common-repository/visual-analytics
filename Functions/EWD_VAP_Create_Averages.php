<?php

function EWD_VAP_Create_Averages() {
global $wpdb;
global $ewd_vap_ga_data_table_name, $ewd_vap_adv_segments_table_name;
global $Visit_Total;
$Time_Frame = get_option("EWD_VAP_Time_Frame");

$Counter = 0;
$posts = $wpdb->get_results("SELECT ID, post_author, post_type, post_date_gmt, post_modified_gmt FROM $wpdb->posts WHERE post_status='publish'");
foreach ($posts as $post) {
		$Authors[] = $post->post_author;
		
		$date = date('Y-m-d', strtotime($post->post_date_gmt));
		$Post_Date_Count[$date]++;
		$Post_Date_Count_Plus_One[$date]++;
		$Author_Post_Count[$post->post_author][$date]++;
		$Modified_Date_Count[$date]++;
		
		$object_ids = $wpdb->get_results("SELECT term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id='" . $post->ID . "'");
		foreach ($object_ids as $object) {
				$Objects[] = $object->term_taxonomy_id;
				$Object_Post_Count[$object->term_taxonomy_id][$date]++;
		}
		
		
		$Counter++;
}

$Segments = $wpdb->get_results("SELECT Segment_Name, Segment_Start_Date, Segment_End_Date FROM $ewd_vap_adv_segments_table_name");

$Unique_Authors = array_unique($Authors);
$Unique_Objects = array_unique($Objects);
unset($Authors);
unset($Objects);

foreach ($Unique_Authors as $Author) {
		$Author_Login = $wpdb->get_row("SELECT meta_value FROM $wpdb->usermeta WHERE user_id='" . $Author . "' AND meta_key='nickname'");
		$Author_First_Name = $wpdb->get_row("SELECT meta_value FROM $wpdb->usermeta WHERE user_id='" . $Author . "' AND meta_key='first_name'");
		$Author_Last_Name = $wpdb->get_row("SELECT meta_value FROM $wpdb->usermeta WHERE user_id='" . $Author . "' AND meta_key='last_name'");
		$Authors[$Author]['Nickname'] = $Author_Login->meta_value;
		$Authors[$Author]['First_Name'] = $Author_First_Name->meta_value;
		$Authors[$Author]['Last_Name'] = $Author_Last_Name->meta_value;
}

foreach ($Unique_Objects as $Object) {
		$Object_Name = $wpdb->get_row("SELECT name FROM $wpdb->terms WHERE term_id='" . $Object . "'");
		$Objects[$Object] = $Object_Name->name;
}

while ($Time_Frame > 0) {
		$TimeStamp = time()-24*3600*$Time_Frame;
		$PredictDate = date('Y-m-d', $TimeStamp);
		$PredictDate_PlusOne = date('Y-m-d', $TimeStamp+24*3600);
		
		$Countries = $_POST['Graph_Countries'];
		$Metric = $_POST['Graph_Metric'];
		
		if ($Metric == "Pageviews") {$Field = "GA_Pageviews";}
		else {$Field = "GA_Visitors";}
		
		if ($Countries != "All" and $Countries != "") {$GA_Data = $wpdb->get_var("SELECT " . $Field . " FROM $ewd_vap_ga_data_table_name WHERE GA_Country IN ('" . $Countries . "') AND GA_Date='" . $PredictDate . "'");}
		else {$GA_Data = $wpdb->get_var("SELECT SUM(" . $Field . ") FROM $ewd_vap_ga_data_table_name WHERE GA_Date='" . $PredictDate . "'");}
		
		if (($Post_Date_Count[$PredictDate]+0) > 0){$Stats['VisitCount']['PostDay'] += $GA_Data; $Stats['DayCount']['PostDay']++;}
		if (($Post_Date_Count[$PredictDate_PlusOne]+0) > 0){$Stats['VisitCount']['PostDayPlusOne'] += $GA_Data; $Stats['DayCount']['PostDayPlusOne']++;}
		if (($Modified_Date_Count[$PredictDate]+0) > 0){$Stats['VisitCount']['ModifiedDay'] += $GA_Data; $Stats['DayCount']['ModifiedDay']++;}
		if (($Modified_Date_Count[$PredictDate_PlusOne]+0) > 0){$Stats['VisitCount']['ModifiedDayPlusOne'] += $GA_Data; $Stats['DayCount']['ModifiedDayPlusOne']++;}
		
		foreach ($Author_Post_Count as $ID => $Author) {if (($Author[$PredictDate]+0) > 0){$Auth_Stats[$Authors[$ID]['Nickname']]['VisitCount'] += $GA_Data; $Auth_Stats[$Authors[$ID]['Nickname']]['DayCount']++;}}
		foreach ($Object_Post_Count as $ID => $Object) {if (($Object[$PredictDate]+0) > 0){$Cat_Stats[$Objects[$ID]]['VisitCount'] += $GA_Data; $Cat_Stats[$Objects[$ID]]['DayCount']++;}}
		
		foreach ($Segments as $Segment) {
				if ($Segment->Segment_Start_Date <= $PredictDate and $Segment->Segment_End_Date >= $PredictDate) {$Segment_Stats[$Segment->Segment_Name]['VisitCount'] += $GA_Data; $Segment_Stats[$Segment->Segment_Name]['DayCount']++;}
		}
		
		if (date('D', $TimeStamp) == "Mon") {$Stats['VisitCount']['Monday'] += $GA_Data; $Stats['DayCount']['Monday']++;}
		if (date('D', $TimeStamp) == "Tue") {$Stats['VisitCount']['Tuesday'] += $GA_Data; $Stats['DayCount']['Tuesday']++;}
		if (date('D', $TimeStamp) == "Wed") {$Stats['VisitCount']['Wednesday'] += $GA_Data; $Stats['DayCount']['Wednesday']++;}
		if (date('D', $TimeStamp) == "Thu") {$Stats['VisitCount']['Thursday'] += $GA_Data; $Stats['DayCount']['Thursday']++;}
		if (date('D', $TimeStamp) == "Fri") {$Stats['VisitCount']['Friday'] += $GA_Data; $Stats['DayCount']['Friday']++;}
		if (date('D', $TimeStamp) == "Sat") {$Stats['VisitCount']['Saturday'] += $GA_Data; $Stats['DayCount']['Saturday']++;}
		if (date('D', $TimeStamp) == "Sun") {$Stats['VisitCount']['Sunday'] += $GA_Data; $Stats['DayCount']['Sunday']++;}
		
		$Stats['VisitCount']['Total'] += $GA_Data;
		$Stats['DayCount']['Total']++;
		
		$Visit_Total += $GA_Data;
		
		$Time_Frame--;
}

$DisplayType = $_POST['Graph_Option'];
if ($DisplayType == "") {$DisplayType = "Days";}
//$DisplayTotal = "Yes";

if ($DisplayType == "Posts") {
	  if ($Stats['DayCount']['PostDay'] > 0) {$Averages['Post Day'] = round($Stats['VisitCount']['PostDay'] / $Stats['DayCount']['PostDay'],2);}
		else {$Averages['Post Day'] = "N/A";}
		if ($Stats['DayCount']['PostDayPlusOne'] > 0) {$Averages['Post Day + 1'] = round($Stats['VisitCount']['PostDayPlusOne'] / $Stats['DayCount']['PostDayPlusOne'],2);}
		else {$Averages['Post Day + 1'] = "N/A";}
		if ($Stats['DayCount']['ModifiedDay'] > 0) {$Averages['Modified Day'] = round($Stats['VisitCount']['ModifiedDay'] / $Stats['DayCount']['ModifiedDay'],2);}
		else {$Averages['Modified Day'] = "N/A";}
		if ($Stats['DayCount']['ModifiedDayPlusOne'] > 0) {$Averages['Modified Day + 1'] = round($Stats['VisitCount']['ModifiedDayPlusOne'] / $Stats['DayCount']['ModifiedDayPlusOne'],2);}
		else {$Averages['ModifiedDay + 1'] = "N/A";}
}

elseif ($DisplayType == "Days") {
		if ($Stats['DayCount']['Monday'] > 0) {$Averages['Monday'] = round($Stats['VisitCount']['Monday'] / $Stats['DayCount']['Monday'],2);}
		else {$Averages['Monday'] = "N/A";}
		if ($Stats['DayCount']['Tuesday'] > 0) {$Averages['Tuesday'] = round($Stats['VisitCount']['Tuesday'] / $Stats['DayCount']['Tuesday'],2);}
		else {$Averages['Tuesday'] = "N/A";}
		if ($Stats['DayCount']['Wednesday'] > 0) {$Averages['Wednesday'] = round($Stats['VisitCount']['Wednesday'] / $Stats['DayCount']['Wednesday'],2);}
		else {$Averages['Wednesday'] = "N/A";}
		if ($Stats['DayCount']['Thursday'] > 0) {$Averages['Thursday'] = round($Stats['VisitCount']['Thursday'] / $Stats['DayCount']['Thursday'],2);}
		else {$Averages['Thursday'] = "N/A";}
		if ($Stats['DayCount']['Friday'] > 0) {$Averages['Friday'] = round($Stats['VisitCount']['Friday'] / $Stats['DayCount']['Friday'],2);}
		else {$Averages['Friday'] = "N/A";}
		if ($Stats['DayCount']['Saturday'] > 0) {$Averages['Saturday'] = round($Stats['VisitCount']['Saturday'] / $Stats['DayCount']['Saturday'],2);}
		else {$Averages['Saturday'] = "N/A";}
		if ($Stats['DayCount']['Sunday'] > 0) {$Averages['Sunday'] = round($Stats['VisitCount']['Sunday'] / $Stats['DayCount']['Sunday'],2);}
		else {$Averages['Sunday'] = "N/A";}
}

elseif ($DisplayType == "Categories") {
		if (is_array($Cat_Stats)) {
			  foreach ($Cat_Stats as $Cat_Name => $Category_Stats) {
						if ($Category_Stats['DayCount'] > 0) {$Averages[$Cat_Name] = round($Category_Stats['VisitCount'] / $Category_Stats['DayCount'],2);}
						else {$Averages[$Cat_Name] = "N/A";}
				}
		}
}

elseif ($DisplayType == "Authors") {
		foreach ($Auth_Stats as $Auth_Name => $Author_Stats) {
				if ($Author_Stats['DayCount'] > 0) {$Averages[$Auth_Name] = round($Author_Stats['VisitCount'] / $Author_Stats['DayCount'],2);}
				else {$Averages[$Auth_Name] = "N/A";}
		}
}

elseif ($DisplayType == "CustomEvents") {
		if (is_array($Segment_Stats)) {
			  foreach ($Segment_Stats as $Segment_Name => $Segment_Stats) {
						if ($Segment_Stats['DayCount'] > 0) {$Averages[$Segment_Name] = round($Segment_Stats['VisitCount'] / $Segment_Stats['DayCount'],2);}
						else {$Averages[$Segment_Name] = "N/A";}
				}
		}
}

if ($DisplayTotal == "Yes") {
	  if ($Stats['DayCount']['Total'] > 0) {$Averages['Total'] = round($Stats['VisitCount']['Total'] / $Stats['DayCount']['Total'],2);}
		else {$Averages['Total'] = "N/A";}
}

return $Averages;
}

?>