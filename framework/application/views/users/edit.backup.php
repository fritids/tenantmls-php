<?php echo $html->includeCss('ui.profile'); ?>
<form action="" name="editProfile" id="ui-profile-editProfileForm">
<span id="overview-description">
	<span id="description">
		<textarea name="editProfile-description" rows="10"><?php echo $xUserData['Profile']['description']; ?></textarea>
    </span>
</span>
<table id="overview-information">
    <tr class="overview-information-title">
        <td>General</td><td></td>
    </tr>
    <tr>
        <td>TMLS # (<?php echo $html->link('?',''); ?>)</td><td><?php echo $xUserData['User']['tmls_number']; ?></td>
    </tr>
    <tr>
    	<td>Name</td>
    	<td>
    		<input type="text" name="editProfile-name" id="editProfile-name" value="<?php echo $xUserData['Profile']['first_name'].' '.$xUserData['Profile']['last_name']; ?>" />
    	</td>
    </tr>
    <tr>
        <td>Contact #</td>
        <td>
        	<input type="text" name="editProfile-cell_1" class="editProfile-phone" value="<?php echo substr($xUserData['Profile']['contact_cell'],0,3); ?>" /> -
        	<input type="text" name="editProfile-cell_2" class="editProfile-phone" value="<?php echo substr($xUserData['Profile']['contact_cell'],3,3); ?>" /> -
        	<input type="text" name="editProfile-cell_3" class="editProfile-phone" value="<?php echo substr($xUserData['Profile']['contact_cell'],6,4); ?>" />
        </td>
    </tr>
    <tr>
        <td>Email</td>
        <td>
        	<input type="text" name="editProfile-email" id="editProfile-email" value="<?php echo $xUserData['User']['email']; ?>" />
        	<input type="hidden" name="editProfile-old-email" value="<?php echo $xUserData['User']['email']; ?>" />
    	</td>
    </tr>
    <tr>
        <td>Agency</td>
        <td>
        	<input type="text" name="editProfile-agency" id="editProfile-agency" value="<?php echo $xUserData['Profile']['agent_agency_name']; ?>" />
    	</td>
    </tr>
    <tr>
        <td>Office #</td>
        <td>
        	<input type="text" name="editProfile-work_1" class="editProfile-phone" value="<?php echo substr($xUserData['Profile']['contact_work'],0,3); ?>" /> -
        	<input type="text" name="editProfile-work_2" class="editProfile-phone" value="<?php echo substr($xUserData['Profile']['contact_work'],3,3); ?>" /> -
        	<input type="text" name="editProfile-work_3" class="editProfile-phone" value="<?php echo substr($xUserData['Profile']['contact_work'],6,4); ?>" />
    	</td>
    </tr>
    <tr>
        <td>Status</td>
        <td>
        	<select name="editProfile-agent_status">
        		<?php
        		echo ($xUserData['Profile']['agent_status']==0) ? '<option value="0" selected="SELECTED">Licensed Real Estate Agent</option>' : '<option value="0">Licensed Real Estate Agent</option>';
				echo ($xUserData['Profile']['agent_status']==1) ? '<option value="1" selected="SELECTED">Licensed Associate Broker</option>' : '<option value="1">Licensed Associate Broker</option>';
				echo ($xUserData['Profile']['agent_status']==2) ? '<option value="2" selected="SELECTED">Licensed Real Estate Broker</option>' : '<option value="2">Licensed Real Estate Broker</option>';
				?>
        	</select>
        </td>
    </tr>
    <tr>
        <td>License Information</td>
        <td>
        	<label>License Number</label>
        	<input type="text" name="agent_license_number" value="<?php echo $xUserData['Profile']['agent_license_number']; ?>" /><br />
        	<label>License State</label>
        	<select name="editProfile-agent_license_state">
        		<?php
        		$theStates = returnStates();
				foreach($theStates as $key=>$state) {
					echo '<option value="'.$key.'">'.$state.'</option>';
				}
        		?>
        	</select>
        </td>
    </tr>
</table>
</form>