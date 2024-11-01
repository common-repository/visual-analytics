<?php
function Add_Edit_EWD_VAP_Segment() {
		global $wpdb, $ewd_feup_fields_table_name;
		
		$Segment_ID = $_POST['Segment_ID'];
		$Segment_Name = $_POST['Segment_Name'];
		$Segment_Start_Date = $_POST['Segment_Start_Date'];
		$Segment_End_Date = $_POST['Segment_End_Date'];

		if (!isset($error)) {
				// Pass the data to the appropriate function in Update_Admin_Databases.php to create the product 
				if ($_POST['action'] == "Add_Segment") {
					  $user_update = Add_EWD_VAP_Segment($Segment_Name, $Segment_Start_Date, $Segment_End_Date);
				}
				// Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product 
				else {
						$user_update = Edit_EWD_VAP_Segment($Segment_ID, $Segment_Name, $Segment_Start_Date, $Segment_End_Date);
				}
				$user_update = array("Message_Type" => "Update", "Message" => $user_update);
				return $user_update;
		}
		// Return any error that might have occurred 
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Mass_Delete_EWD_VAP_Segments() {
		$Segments = $_POST['Segments_Bulk'];
		
		if (is_array($Segments)) {
				foreach ($Segments as $Segment) {
						if ($Segment != "") {
								Delete_EWD_VAP_Segment($Segment);
						}
				}
		}
		
		$update = __("Segments have been successfully deleted.", 'EWD_FEUP');
		$user_update = array("Message_Type" => "Update", "Message" => $update);
		return $user_update;
}

?>
