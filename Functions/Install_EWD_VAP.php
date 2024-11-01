<?php
function Install_EWD_VAP() {
		/* Add in the required globals to be able to create the tables */
  	global $wpdb;
   	global $EWD_VAP_db_version;
		global $ewd_vap_ga_data_table_name, $ewd_vap_adv_segments_table_name;
    
		/* Create the Google Analytics data table */  
   	$sql = "CREATE TABLE $ewd_vap_ga_data_table_name (
  	GA_Data_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  	GA_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
		GA_Visitors mediumint(9) DEFAULT 0 NOT NULL,
		GA_Pageviews mediumint(9) DEFAULT 0 NOT NULL,
  	UNIQUE KEY id (GA_Data_ID)
    )
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
		/* Create the advanced sements data table */  
   	$sql = "CREATE TABLE $ewd_vap_adv_segments_table_name (
  	Segment_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  	Segment_Start_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
		Segment_End_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
		Segment_Name text DEFAULT '' NOT NULL,
  	UNIQUE KEY id (Segment_ID)
    )
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
 
   	add_option("EWD_VAP_db_version", $EWD_VAP_db_version);
		add_option("EWD_VAP_Time_Frame", 60);
		add_option("EWD_VAP_Full_Version", "Yes");
		add_option("EWD_VAP_GA_Update_Datetime", 0);
}
?>
