<?php
/* The file contains all of the functions which make changes to the VAP tables */

/* Adds a single new segment to the VAP database */
function Add_EWD_VAP_Segment($Segment_Name, $Segment_Start_Date, $Segment_End_Date) {
		global $wpdb;
		global $ewd_vap_adv_segments_table_name;
		
		$wpdb->insert( $ewd_vap_adv_segments_table_name, 
						array( 'Segment_Name' => $Segment_Name,
									 'Segment_Start_Date' => $Segment_Start_Date,
									 'Segment_End_Date' => $Segment_End_Date)
					 );
		$update = __("Segment has been successfully created.", 'EWD_VAP');
		return $update;
}

/* Edits a single segment with a given ID in the VAP database */
function Edit_EWD_VAP_Segment($Segment_ID, $Segment_Name, $Segment_Start_Date, $Segment_End_Date) {
		global $wpdb;
		global $ewd_vap_adv_segments_table_name;
		
		$wpdb->update(
						$ewd_vap_adv_segments_table_name,
						array( 'Segment_Name' => $Segment_Name,
									 'Segment_Start_Date' => $Segment_Start_Date,
									 'Segment_End_Date' => $Segment_End_Date),
						array( 'Segment_ID' => $Segment_ID)
		);
		$update = __("Segment has been successfully edited.", 'EWD_VAP');
		return $update;
}

/* Deletes a single category with a given ID in the UPCP database */
function Delete_EWD_VAP_Segment($Segment_ID) {
		global $wpdb;
		global $ewd_vap_adv_segments_table_name;
		
		$wpdb->delete(
						$ewd_vap_adv_segments_table_name,
						array('Segment_ID' => $Segment_ID)
					);

		$update = __("Segment has been successfully deleted.", 'EWD_VAP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $update;
}

function Update_EWD_VAP_Options() {
		update_option("EWD_VAP_Time_Frame", min($_POST['time_frame'], 365));
		update_option("EWD_VAP_Client_ID", $_POST['client_ID']);
		update_option("EWD_VAP_Client_Secret", $_POST['client_secret']);
		update_option("EWD_VAP_Developer_Key", $_POST['developer_key']);
		
		$update = __("Options have been successfully updated.", 'EWD_VAP');
		return $update;
}

?>