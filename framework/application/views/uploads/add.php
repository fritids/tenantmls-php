<div id="ui-upload-page">
<form name="upload_user" method="post" action="<?php echo BASE_PATH.'/uploads/add/'.$tmls; ?>" enctype="multipart/form-data">
	<div class="ui-menu-title">
		Add New Document
	</div>
	<table class="ui-menu-table">
		<tr>
			<td>File</td>
			<td><input type="file" name="upload-file" /></td>
		</tr>
		<tr>
			<td>Name</td>
			<td><input type="text" name="title" /></td>
		</tr>
		<tr>
			<td>Description</td>
			<td><textarea cols="20" rows="3" name="description"></textarea></td>
		</tr>
		<tr>
			<td>Group</td>
			<td>
				<select name="upload_type">
					<?php
					if ($upload['User']['user_type']=='Agent') {
						$options = array(
							'agency-form'=>'Agency Form',
							'other'=>'Other'
						);
					} else {
						$options = array(
							'background-check'=>'Background Check',
							'credit-report'=>'Credit Report',
							'income-proof'=>'Proof of Income',
							'other'=>'Other'
						);
					}
					foreach($options as $k=>$v) {
						echo '<option value="'.$k.'">'.$v.'</option>';
					}

					?>
				</select>
			</td>
		</tr>
	</table>
	<span class="ui-menu-controls">
		<input type="submit" name="submit_file_upload" value="Upload" id="accept" class="ui-menu-button" />
		<input type="submit" name="cancel_file_upload" value="Cancel" id="cancel" class="ui-menu-button" />
	</span>
</form>
</div>