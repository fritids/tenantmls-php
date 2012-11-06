<?php
if (!$headers) echo $html->includeCss('tenantmls-sdk-forms');
if ($headers) :
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<style>
			body,html {margin:3px;padding:0;}
		</style>
		<?php echo $html->includeCss('tenantmls-sdk-forms'); ?>
		<title><?php echo $title; ?></title>
	</head>
	<body>
<?php endif; ?>
		<div class="tenantmls-forms-container">
			<div class="tenantmls-forms-header">
				
			</div>
			<form name="tenantmls-register" action="<?php echo BASE_PATH.'/users/signup'; ?>" method="post">
				<table class="tenantmls-forms-table">
					<?php switch($format) : case 'tenant' : ?>
						<tr>
							<td>Full name:</td>
							<td><input type="text" name="name" /></td>
						</tr>
						
					<?php break; case 'agent' : ?>
					
						Fields: Name, Occupants, Pets, Beds, Desired Locales, Can Afford, Credi
						Optional fields: Email - will give tenant access to account
					<?php break; case 'apply' : ?>
					
					<?php break; endswitch; ?>
				</table>
			</form>
		</div>
<?php if ($headers) : ?>
	</body>
</html>
<?php endif; ?>