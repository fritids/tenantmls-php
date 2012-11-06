<?php echo $html->includeCss('form.sendMessage'); ?>
<?php echo $html->includeJs('form.sendMessage'); ?>
<span class="ui-popup-header">
	<h4>Send Message</h4>
</span>
<div id="ui-popup-sendMessage">
	<table id="ui-popup-sendMessage-table">
		<tr>
			<td>
				<table>
					<tr>
						<td>Title</td>
					</tr>
					<tr>
						<td><input type="text" name="message-title" /></td>
					</tr>
					<tr>
						<td>Message</td>
					</tr>
					<tr>
						<td><textarea name="message-body" rows="10"></textarea></td>
					</tr>
				</table>
			</td>
			<td>
				<ul id="ui-popup-sendMessage-template-options">
					<li><input type="radio" name="template-option" value="0" checked="CHECKED" /> Custom</li>
					<li><input type="radio" name="template-option" value="1" /> Request Information</li>
					<li><input type="radio" name="template-option" value="2" /> Represent Tenant</li>
					<li>
						<input type="radio" name="template-option" value="3" />
						<select name="template-option-message" disabled="disabled">
							<option value="0">To Tenants</option>
						</select>
					</li>
				</ul>
			</td>
		</tr>
	</table>
</div>