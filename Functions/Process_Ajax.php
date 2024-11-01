<?php
/* Processes the ajax requests being put out in the admin area and the front-end
*  of the UPCP plugin */

// Updates the order of items in the catalogue after a user has dragged and dropped them
function EWD_VAP_Create_New_Graph() {
		global $wpdb;
		global $ewd_vap_ga_data_table_name;
		global $Visit_Total;
		
		$Averages = EWD_VAP_Create_Averages();
		EWD_VAP_Create_Graph($Averages);
		echo $Visit_Total;
		update_option('EWD_VAP_Graph_Updated', time());
}
add_action('wp_ajax_update_graph', 'EWD_VAP_Create_New_Graph');
