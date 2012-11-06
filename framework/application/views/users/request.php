<?php echo $html->includeJs('source/tmls.request'); ?>
<div id="request-menu-page">
<form name="request_user" method="post" action="<?php echo BASE_PATH.'/requests/add'; ?>">
	<div class="ui-menu-title">
		Add <?php echo $profile['User']['user_type']; ?>
	</div>
	<span class="ui-menu-text">

		<?php if ($tmls_user['User']['user_type']=='Agent') : ?>
			I would like to represent
			<b><?php echo $profile['User']['first_name'].' '.$profile['User']['middle_name'].' '.$profile['User']['last_name']; ?></b> as a tenant
		<?php endif; ?>

		<?php if ($tmls_user['User']['user_type']=='Tenant') : ?>
			I would like 
			<b><?php echo $profile['User']['first_name'].' '.$profile['User']['middle_name'].' '.$profile['User']['last_name']; ?></b>
			to represent me as an agent
		<?php endif; ?>

	</span>
	<table class="ui-menu-table">
		<tr>
			<td>Type</td>
			<td>
				<select name="agency_type">
					<option name="O">Open Agency</option>
					<option name="X">Exclusive Agency</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Attachment</td>
			<td>
				<a href="#"><?php echo shorten('TenantMLS_Standard_Agency_Agreement.pdf',25); ?></a>
			</td>
		</tr>
	</table>
	<span class="ui-menu-controls">
		<input type="hidden" name="group" value="<?php echo ($tmls_user['User']['user_type']=='Agent') ? 'Tenants' : 'Agents'; ?>" /> 
		<input type="hidden" name="jid" value="<?php echo $profile['User']['tmls_number'].'@'.JABBER_SERVER; ?>" /> 
		<input type="hidden" name="name" value="<?php echo $profile['User']['first_name'].' '.$profile['User']['middle_name'].' '.$profile['User']['last_name']; ?>" /> 
		<input type="hidden" name="request_jid" value="<?php echo $tmls_user['User']['tmls_number'].'@'.JABBER_SERVER; ?>" /> 
		<input type="hidden" name="request_name" value="<?php echo $tmls_user['User']['first_name'].' '.$tmls_user['User']['middle_name'].' '.$tmls_user['User']['last_name']; ?>" /> 
		<input type="submit" name="request" value="Request" id="submit" class="ui-menu-button" />
		<input type="submit" name="request" value="Cancel" id="cancel" class="ui-menu-button" />
	</span>
</form>
</div>