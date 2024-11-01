<?php
/*
Plugin Name: Visual Analytics Plugin
Plugin URI: http://www.EtoileWebDesign.com/visual-analytics/
Description: A plugin that uses multivariate regression to create graphs that break down what authors, posts, days of the week, etc. create the most traffic for your site
Author: Tim Ruse
Author URI: http://www.EtoileWebDesign.com/
Text Domain: EWD_VAP
Version: 0.1
*/

global $EWD_VAP_db_version;
global $ewd_vap_ga_data_table_name, $ewd_vap_adv_segments_table_name;
global $wpdb;
global $message;
global $Full_Version;
global $Visit_Total;
$ewd_vap_ga_data_table_name = $wpdb->prefix . "EWD_VAP_GA_Data";
$ewd_vap_adv_segments_table_name = $wpdb->prefix . "EWD_VAP_Adv_Segments";
$EWD_VAP_db_version = "0.4";

define('WP_DEBUG', true);
$wpdb->show_errors();

/* When plugin is activated */
register_activation_hook(__FILE__,'Install_EWD_VAP');
//register_activation_hook(__FILE__,'Initial_EWD_VAP_Options');

/* When plugin is deactivation*/
register_deactivation_hook( __FILE__, 'Remove_EWD_VAP' );

/* Creates the admin menu for the contests plugin */
if ( is_admin() ){
	  add_action('admin_menu', 'EWD_VAP_Plugin_Menu');
		add_action('admin_head', 'EWD_VAP_Admin_Options');
		add_action('admin_init', 'Add_EWD_VAP_Scripts');
		add_action('widgets_init', 'Update_EWD_VAP_Content');
		add_action('admin_notices', 'EWD_VAP_Error_Notices');
}

function Remove_EWD_VAP() {
  	/* Deletes the database field */
		delete_option('EWD_VAP_db_version');
}


/* Admin Page setup */
function EWD_VAP_Plugin_Menu() {
		add_menu_page('Visual Analytics Plugin', 'Visual Analytics', 'administrator', 'EWD-VAP-options', 'EWD_VAP_Output_Options',null , '50.7');
}

/* Add localization support */
function EWD_VAP_localization_setup() {
		load_plugin_textdomain('EWD_VAP', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}
add_action('after_setup_theme', 'EWD_VAP_localization_setup');

// Add settings link on plugin page
function EWD_VAP_plugin_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=EWD-VAP-options">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'EWD_VAP_plugin_settings_link' );

/* Put in the pretty permalinks filter */
add_filter( 'query_vars', 'add_query_vars_filter' );

function Add_EWD_VAP_Scripts() {
		if (isset($_GET['page']) && $_GET['page'] == 'EWD-VAP-options') {
			  $url_one = plugins_url("visual-analytics/js/Admin.js");
				$url_two = plugins_url("visual-analytics/js/graphs.js");
				wp_enqueue_script('PageSwitch', $url_one, array('jquery'));
				wp_enqueue_script('GraphSwitch', $url_two, array('jquery'));
		}
}


function EWD_VAP_Admin_Options() {
		$url = plugins_url("visual-analytics/css/Admin.css");
		echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}

add_action('activated_plugin','save_vap_error');
function save_vap_error(){
		update_option('plugin_error',  ob_get_contents());
		file_put_contents("Error.txt", ob_get_contents());
}

/*$Full_Version = get_option("EWD_VAP_Full_Version");

if (isset($_POST['Upgrade_To_Full'])) {
	  add_action('admin_init', 'Upgrade_To_Full');
}*/

include "Functions/Error_Notices.php";
include "Functions/EWD_VAP_Create_Averages.php";
include "Functions/EWD_VAP_Create_Graph.php";
include "Functions/EWD_VAP_Output_Options.php";
include "Functions/EWD_VAP_Update_GA_Data.php";
include "Functions/Install_EWD_VAP.php";
include "Functions/Prepare_Data_For_Insertion.php";
include "Functions/Process_Ajax.php";
include "Functions/Update_Admin_Databases.php";
include "Functions/Update_EWD_VAP_Content.php";
include "Functions/Update_EWD_VAP_Tables.php";

// Updates the UPCP database when required
if (get_option('EWD_VAP_db_version') != $EWD_VAP_db_version) {
	  Update_EWD_VAP_Tables();
}

/*if (get_option("EWD_FEUP_Update_RR_Rules") == "Yes") {
	  add_filter( 'query_vars', 'add_query_vars_filter' );
		add_filter('init', 'EWD_FEUP_Rewrite_Rules');
		update_option("EWD_FEUP_Update_RR_Rules", "No");
}*/
?>