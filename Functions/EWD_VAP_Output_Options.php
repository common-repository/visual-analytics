<?php
/* Creates the admin page, and fills it in based on whether the user is looking at
*  the overview page or an individual item is being edited */
function EWD_VAP_Output_Options() {
		global $wpdb, $error, $Full_Version, $Visit_Total;
		global $ewd_vap_ga_data_table_name, $ewd_vap_adv_segments_table_name;
		
		if (isset($_GET['DisplayPage'])) {
			  $Display_Page = $_GET['DisplayPage'];
		}
		include( plugin_dir_path( __FILE__ ) . '../html/AdminHeader.php');
		include( plugin_dir_path( __FILE__ ) . '../html/MainScreen.php');
		include( plugin_dir_path( __FILE__ ) . '../html/AdminFooter.php');
}
?>