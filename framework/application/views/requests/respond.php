<?php echo $html->includeJs('source/tmls.request'); ?>
<form name="respond_user" method="post" action="<?php echo BASE_PATH.'/privileges/create'; ?>">
	<div class="ui-menu-title">
		Respond
	</div>
	<span class="ui-menu-text">

		<?php if ($request['User']['user_type']=='Agent') : ?>
			<b><?php echo $request['User']['first_name'].' '.$request['User']['middle_name'].' '.$request['User']['last_name']; ?></b>
			would like to represent you in an
			<b><?php echo ($request['Request']['request_type']=='O') ? 'open' : 'exclusive'; ?> agency</b>
		<?php endif; ?>

		<?php if ($request['User']['user_type']=='Tenant') : ?>
			I would like to represent
			<b><?php echo $request['User']['first_name'].' '.$request['User']['middle_name'].' '.$request['User']['last_name']; ?></b>
			as a tenant
		<?php endif; ?>
		<br /><br />
		<a href="#"><?php echo 'TenantMLS_Standard_Agency_Agreement.pdf'; ?></a>
	</span>
	<table class="ui-menu-table">
		<tr>
			<td><input type="checkbox" name="agree_document" value="1" /></td>
			<td>I agree to the terms specified in the attached document above
			</td>
		</tr>
	</table>
	<span class="ui-menu-controls">
		<input type="hidden" name="group" value="<?php echo ($tmls_user['user_type']=='Agent') ? 'Tenants' : 'Agents'; ?>" /> 
		<input type="hidden" name="request_agency_type" value="<?php echo $request['Request']['request_type']; ?>" />
		<input type="hidden" name="request_id" value="<?php echo $request['Request']['id']; ?>" />
		<input type="hidden" name="from_jid" value="<?php echo $request['Request']['from_id'].'@'.JABBER_SERVER; ?>" /> 
		<input type="hidden" name="from_name" value="<?php echo $request['User']['first_name'].' '.$request['User']['middle_name'].' '.$request['User']['last_name']; ?>" /> 
		<input type="hidden" name="to_jid" value="<?php echo $tmls_user['tmls_number'].'@'.JABBER_SERVER; ?>" /> 
		<input type="hidden" name="to_name" value="<?php echo $tmls_user['first_name'].' '.$tmls_user['middle_name'].' '.$tmls_user['last_name']; ?>" /> 
		<input type="submit" name="request" value="Accept" id="accept" class="ui-menu-button" />
		<input type="submit" name="request" value="Ignore" id="cancel" class="ui-menu-button" />
	</span>
</form>