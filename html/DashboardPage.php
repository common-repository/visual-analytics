<?php 
$Last_GA_Update = get_option('EWD_VAP_GA_Update_Datetime');
$Time_Frame = get_option("EWD_VAP_Time_Frame");

if (time() > ($Last_GA_Update + (12*3600))) {
		EWD_VAP_Update_GA_Data();
}

$Averages = EWD_VAP_Create_Averages();
EWD_VAP_Create_Graph($Averages);

?>
<!-- Upgrade to pro link box -->
<!--<?php if ($Full_Version != "Yes") { ?>
<div id="side-sortables" class="metabox-holder ">
<div id="upcp_pro" class="postbox " >
		<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("Full Version", 'EWD_VAP') ?></span></h3>
		<div class="inside">
				<ul><li><a href="http://www.etoilewebdesign.com/ultimate-product-catalogue-plugin/"><?php _e("Upgrade to the full version ", "EWD_VAP"); ?></a><?php _e("to take advantage of all the available features of the Ultimate Product Catalogue for Wordpress!", 'EWD_VAP'); ?></li>
				<div class="full-version-form-div">
						<form action="admin.php?page=EWD-FEUP-options" method="post">
								<div class="form-field form-required">
										<label for="Catalogue_Name"><?php _e("Product Key", 'EWD_VAP') ?></label>
										<input name="Key" type="text" value="" size="40" />
								</div>							
								<input type="submit" name="Upgrade_To_Full" value="<?php _e('Upgrade', 'EWD_VAP') ?>">
						</form>
				</div>
		</div>
</div>
</div>
<?php } ?>
<?php echo get_option('plugin_error');?>-->

<div id="side-sortables" class="metabox-holder ">
<div id="upcp_pro" class="postbox " >
		<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("Thank You!", 'EWD_VAP') ?></span></h3>
		<div class="inside">
				<ul>
						<li><?php _e("Thanks for being an early adopter! Anyone who installs before May 15th will always have access to new features, updates and full product support.", 'EWD_VAP'); ?></li>
						<li><a href="https://www.facebook.com/EtoileWebDesign"><?php _e("Follow us on Facebook ", "EWD_VAP");?></a><?php _e("to stay up to date with new features and plugins.", "EWD_VAP"); ?></li>
				</ul>
		</div>
</div>
</div>

<div class="wrap">
<div class='ewd-vap-header-info'>Daily Average Traffic</div>
<div class='ewd-vap-header-info'>
		<div id='ewd-vap-metric-total'><?php echo $Visit_Total; ?></div>
		<div id='ewd-vap-metric-label'>Total Visitors:</div>
</div>
<div class='ewd-vap-clear'></div>

<form action='#' method='post'>
		<select name='Graph_Type' onchange='UpdateGraph("<?php echo plugins_url(); ?>");' id='ewd-vap-graph-select' class='ewd-vap-dashboard-select'>
				<option value='Days'>Day of Week</option>
				<option value='Posts'>WordPress Events</option>
				<option value='Authors'>Author Posts</option>
				<option value='Categories'>Category Posts</option>
				<option value='CustomEvents'>Custom Events (Advanced)</option>
		</select>
		
		<select name='Countries' onchange='UpdateGraph("<?php echo plugins_url(); ?>");' id='ewd-vap-country-select' class='ewd-vap-dashboard-select'>
				<option value='All'>All Countries</option>
				<?php 
						$TimeStamp = time()-24*3600*$Time_Frame;
						$StartDate = date('Y-m-d', $TimeStamp);
						$EndDate = date('Y-m-d');
						$Countries = $wpdb->get_results("SELECT DISTINCT(GA_Country) FROM $ewd_vap_ga_data_table_name WHERE GA_Date BETWEEN '" . $StartDate . "' AND '" . $EndDate . "' ORDER BY GA_Country ASC");
						foreach ($Countries as $Country) {
								echo "<option value='" . $Country->GA_Country . "'>" . $Country->GA_Country . "</option>";
						}
				?>
		</select>
		
		<select name='Metric' onchange='UpdateGraph("<?php echo plugins_url(); ?>");' id='ewd-vap-metric-select' class='ewd-vap-dashboard-select'>
				<option value='Visitors'>Visitors</option>
				<option value='Pageviews'>Page Views</option>
		</select>
</form>

<?php if (isset($Visit_Total) and $Visit_Total != "" and $Visit_Total != 0) { ?>
<img src='<?php echo plugins_url(); ?>/visual-analytics/images/FilledGraph.png' alt='VAP-Graph' id='ewd-vap-graph'>
<?php } ?>

</div>
