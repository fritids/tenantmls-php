<?php
if ($plugin) {
	/*
	 * Plugin properties
	 */
	$plugin_width 		= null;
	$plugin_height 		= null;
	$plugin_header 		= null;
	$plugin_javascript	= null;
	
	/*
	 * Set the params
	 */
	foreach($params as $property=>$value) {
		if ($property=='WIDTH' && is_numeric($value))
			$plugin_width = $value;
		if ($property=='HEIGHT' && is_numeric($value))
			$plugin_height = $value;
		if ($property=='HEADER' && is_numeric($value))
			$plugin_header = $value;
		if ($property=='JAVASCRIPT' && is_numeric($value))
			$plugin_javascript = $value;
	}
	include_once(ROOT.DS.'application'.DS.'views'.DS.'api'.DS.'plugins'.DS.'header.inc.php');
	switch(strtolower($plugin)) {
		case 'tenantfinder' :
		case 'tenant_finder' :
		case 'tenant-finder' :
			include_once(ROOT.DS.'application'.DS.'views'.DS.'api'.DS.'plugins'.DS.'tenantfinder.php');
			break;
		case 'register' :
			include_once(ROOT.DS.'application'.DS.'views'.DS.'api'.DS.'plugins'.DS.'register.php');
			break;
		case 'signup' :
			
	}
	include_once(ROOT.DS.'application'.DS.'views'.DS.'api'.DS.'plugins'.DS.'footer.inc.php');
} else {
	echo 'No plugin selected';
}
?>