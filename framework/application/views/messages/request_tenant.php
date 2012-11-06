<?php echo $html->includeJs('form.requestTenant'); ?>
<?php echo $html->includeCss('ui.message'); ?>

<div class="ui-message-header">
	<h4>Request Tenant</h4>
</div>
<div id="ui-message-container">
	<form action="" method="post">
		<table id="ui-message-table">
			<tr>
				<td>
					<ul id="ui-message-sideBar">
						<li><h4><input type="radio" name="request_type" value="O" checked="CHECKED" />
							Open Tenant</h4>
							<span class="option-description">Request to represent this tenant openly. A tenant may have multiple agents working to find him/her the right rental but only one will earn a commission.</span>
						</li>
						<li><h4><input type="radio" name="request_type" value="X" />
							Exclusive Tenant</h4>
							<span class="option-description">Request to represet this tenant in an exclusive agency. You will have fidiucary responsibility to find this tenant a suitable rental to earn a commission.</span>
						</li>
					</ul>
				</td>
				<td>
					<table id="ui-message-compose-table">
						<tr>
							<td>Message</td>
						</tr>
						<tr id="message_body">
							<td><textarea name="body" rows="5"></textarea></td>
						</tr>
						<tr>
							<td></td>
						</tr>
						<tr>
							<td id="ui-message-privilege">
								<table id="ui-message-privilege-options">
									<tr>
										<td><h5>Commission</h5></td>
										<td><h5>Request Privileges</h5></td>
									</tr>
									<tr>
										<td>
											<select name="request_commission">
												<option value="T" selected="SELECTED">Tenant pays</option>
												<option value="T">Landlord pays</option>
												<option value="O">Owner pays</option>
												<option value="N">No commission</option>
											</select>
										</td>
										<td>
											<input type="checkbox" name="request_privileges_view" value="1" checked="CHECKED" /> View full profile<br />
											<input type="checkbox" name="request_privileges_edit" value="1" checked="CHECKED" /> Edit tenant profile<br />
											<input type="checkbox" name="request_privileges_upload" value="1" checked="CHECKED" /> Upload files for tenant
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td id="ui-message-submit-container">
								<input type="submit" name="request_tenant" id="ui-message-submit" value="Send Message" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="user_id" value="<?php echo $xUserId; ?>" />
	</form>
</div>