<?php 
		$Time_Frame = get_option("EWD_VAP_Time_Frame");
		$Client_ID = get_option('EWD_VAP_Client_ID');
		$Client_Secret = get_option('EWD_VAP_Client_Secret');
		$Developer_Key = get_option('EWD_VAP_Developer_Key');
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div><h2>Settings</h2>

<form method="post" action="admin.php?page=EWD-VAP-options&DisplayPage=Options&Action=UpdateOptions">
<table class="form-table">
<tr>
<th scope="row">Time Frame</th>
<td>
	<legend class="screen-reader-text"><label title='Time Frame'>Days to Get Data for:</label></legend>
	<input type='text' name='time_frame' value='<?php echo $Time_Frame; ?>' /><br />
	<p>Maximum is 365</p>
</td></tr>
<tr>
<th scope="row">Client ID</th>
<td>
	<legend class="screen-reader-text"><label title='Client ID'>Client ID:</label></legend>
	<input type='text' name='client_ID' value='<?php echo $Client_ID; ?>' /><br />
	<p>See plugin site for help.</p>
</td></tr>
<tr>
<th scope="row">Client Secret</th>
<td>
	<legend class="screen-reader-text"><label title='Client Secret'>Client Secret:</label></legend>
	<input type='text' name='client_secret' value='<?php echo $Client_Secret; ?>' /><br />
	<p>See plugin site for help.</p>
</td></tr>
<tr>
<th scope="row">Developer Key</th>
<td>
	<legend class="screen-reader-text"><label title='Developer Key'>Developer Key</label></legend>
	<input type='text' name='developer_key' value='<?php echo $Developer_Key; ?>' /><br />
	<p>See plugin site for help.</p>
</td>
</tr>
</table>


<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>