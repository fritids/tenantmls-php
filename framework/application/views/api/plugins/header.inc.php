<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $html->includeCss('plugins/plugin-sdk'); ?>
<?php
	if ($params['JAVASCRIPT']) {
		echo $html->includeJs('jquery-1.7.2.min');
		echo $html->includeJs('plugins/plugin-sdk');
		echo $html->includeJs('jquery.uniform.min');
		echo $html->includeCss('uniform.default');
		echo $html->includeJs('jquery-ui-1.8.21.custom.min');
		echo $html->includeCss('ui-lightness/jquery-1.8.21.custom');

		echo $html->includeJs('form.validation');
		echo $html->includeJs('ui.autocomplete');
	}
	if ((isset($params['TMLS']) && !empty($params['TMLS'])) || isset($xUserTmls)) {
		$params['TMLS'] = (isset($xUserTmls)) ? $xUserTmls : $params['TMLS'];
		$requestArray = array(
			'API_URL'=> BASE_PATH.'/api/select/'.$params['TMLS'],
			'API_ID'=>API_ID,
			'API_SECRET'=>API_SECRET,
			'RETURN_TYPE'=>'json'
		);
		$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
		$request->execute();
		$user = json_decode($request->getResponseBody(),true);
		//printr($user);
	}
?>
</head>
<body>
<?php if (isset($params['HEADER']) && $params['HEADER']) : ?>
<div style="width:<?php echo $params['WIDTH']; ?>;">
	<div class="plugin-header">
		<span class="plugin-header-text">TenantMLS is connecting agents and tenants</span>
	</div>
</div>
<?php endif; ?>