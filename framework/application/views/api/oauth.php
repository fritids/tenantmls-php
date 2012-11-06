<?php
$sessionCookie = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
$requestArray = array(
	'API_URL'=> BASE_PATH.'/oauth/v1/logon',
	'username'=>'sysadmin',
	'password'=>'sysadmin'
);
$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray, 'text/html');
$request->execute();
printr($request);
?>
<h4>JSON (Default)</h4>
<textarea name="output" style="width:800px; height:500px;"><?php echo $request->getResponseBody(); ?></textarea>