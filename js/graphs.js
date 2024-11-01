/* Update the graph when a different option is selected */
function UpdateGraph(Plugin_Url) {
		jQuery('#ewd-vap-graph').attr("src", Plugin_Url+"/visual-analytics/images/Loading.png");
		jQuery('#ewd-vap-visitor-total').html('');
		
		var graphoption = jQuery('#ewd-vap-graph-select').val();
		var countryoption = jQuery('#ewd-vap-country-select').val();
		var metricoption = jQuery('#ewd-vap-metric-select').val();
		var data = 'Graph_Option=' + graphoption + '&Graph_Countries=' + countryoption + '&Graph_Metric=' + metricoption + '&action=update_graph';
		jQuery.post(ajaxurl, data, function(response) {
				jQuery('#ewd-vap-graph').attr("src", Plugin_Url+"/visual-analytics/images/FilledGraph.png?"+graphoption);
				jQuery('#ewd-vap-metric-total').html(response.slice(0,-1));
				if (metricoption == "Visitors") {jQuery('#ewd-vap-metric-label').html("Total Visitors: ");}
				if (metricoption == "Pageviews") {jQuery('#ewd-vap-metric-label').html("Total Page Views: ");}
		});
}