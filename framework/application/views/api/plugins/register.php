<div style="width:<?php echo $params['WIDTH']; ?>;height:<?php echo $params['HEIGHT']; ?>;">
	<div class="plugin-body">
	<?php
		if (isset($params['AGENT_HEADER']) && $params['AGENT_HEADER']) {
			include_once(ROOT.DS.'application'.DS.'views'.DS.'api'.DS.'plugins'.DS.'agent_header.inc.php');
		}
	?>
		<div class="plugin-header" id="contact-form-header">
			<span class="plugin-header-text"><?php echo $params['TITLE']; ?></span>
		</div>
		<form name="register" action="" method="post" onsubmit="return validate();">
		<table id="contact-form-layout">
			<tr>
				<td>Name</td>
				<td>
					<?php echo $form->formInput('name'); ?>
				</td>
			</tr>
			<tr>
				<td>Email/Phone</td>
				<td>
					<?php echo $form->formInput('email_phone'); ?>
				</td>
			</tr>
			<tr>
				<td>Looking for</td>
				<td>
					<div id="register-select-format-beds">
						<?php
						$beds = array(0=>'Studio',1=>'1 bedroom',2=>'2 bedroom',3,4,5,6=>'6+');
						echo $form->formSelect('bedrooms',$beds,1);
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td>Location</td>
				<td>
					<?php 
					if (($params['JAVASCRIPT'])) {
						echo $form->formInput('locale',null,null,null,'locale');
					} else {
						$theStates = returnStates();
						$theCities = performAction('locales','ajax_city',array('NY','php'));
						
						echo $form->formSelect('locale-city',$theCities,'locale');
						echo $form->formSelect('locale-state',$theStates,'NY','locale');
					}
					?>
				</td>
			</tr>
			<tr>
				<td>
					Rent ($)
				</td>
				<td>
					<?php echo $form->formInput('rent',null,7); ?>
					<span class="contact-form-options">
						<input type="checkbox" name="utilities_included" value="1" />
						<span class="contact-form-option">Utilities inc.</span>
					</span>
				</td>
			</tr>
			<tr>
				<td>Credit</td>
				<td>
					<div id="register-select-format-credit">
						<?php
						$credit = array(0=>'N/A',1=>'Excellent',2=>'Good',3=>'Marginal');
						echo $form->formSelect('credit',$credit,0);
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td>Program</td>
				<td>
					<div id="register-select-format-credit">
						<?php
						$program = array(0=>'No',1=>'Section 8',2=>'BAH',3=>'Other');
						echo $form->formSelect('program',$program,0);
						?>
					</div>
				</td>
			</tr>
		</table>
		<?php echo ($params['TMLS']) ? $form->formHidden('tmls',$params['TMLS']) : null; ?>
		<?php echo $form->formHidden('user_type',0); ?>
		<?php echo $form->formSubmit('submit','Contact Agent'); ?>
	</div>
	<?php
	if (isset($_POST['submit'])) {
		$requestArray = array(
			'API_URL'=> BASE_PATH.'/api/register',
			'API_ID'=>API_ID,
			'API_SECRET'=>API_SECRET,
			'INPUT_NAME'=>$_POST['name'],
			'INPUT_USER_TYPE'=>$_POST['user_type'],
			'INPUT_EMAIL_PHONE'=>$_POST['email_phone'],
			'INPUT_BEDROOMS'=>$_POST['bedrooms'],
			'INPUT_LOCALE'=>$_POST['locale'],
			'INPUT_RENT'=>$_POST['rent'],
			'INPUT_CREDIT'=>$_POST['credit'],
			'INPUT_PROGRAM'=>$_POST['program'],
			'INPUT_TMLS'=>$_POST['tmls'],
			'RETURN_TYPE'=>'json'
		);
		$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
		$request->execute();
		// we'll return the full api profile after, so we can sign them up if they wish
		$data = json_decode($request->getResponseBody(),true);
		printr($data);
	}
	?>
</div>