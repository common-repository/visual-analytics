<?php
/* This file is the action handler. The appropriate function is then called based 
*  on the action that's been selected by the user. The functions themselves are all
* stored either in Prepare_Data_For_Insertion.php or Update_Admin_Databases.php */

function Update_EWD_VAP_Content() {
global $message;
if (isset($_GET['Action'])) {
				switch ($_GET['Action']) {
    				case "UpdateOptions":
        				$message = Update_EWD_VAP_Options();
								break;
						case "AddSegment":
						case "EditSegment":
        				$message = Add_Edit_EWD_VAP_Segment();
								break;
						case "DeleteSegment":
        				$message = Delete_EWD_VAP_Segment($_GET['Segment_ID']);
								break;
						case "MassDeleteSegments":
								$message = Mass_Delete_EWD_VAP_Segments();
								break;
						default:
								$message = __("The form has not worked correctly. Please contact the plugin developer.", 'UPCP');
								break;
				}
		}
}

?>