<?php
/* Debugging */
function printr($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

/*
 * Custom functions dedicated to TenantMLS (unrelated to framework)
 */
function startSession() {
	if (!isset($_COOKIE[ini_get('session.name')])) {
    	session_start();
	}
}

/** Check if environment is development and display errors **/

function setReporting() {
if (DEVELOPMENT_ENVIRONMENT == true) {
	error_reporting(E_ALL);
	ini_set('display_errors','On');
} else {
	error_reporting(E_ALL);
	ini_set('display_errors','Off');
	ini_set('log_errors', 'On');
	ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
}
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() {
if ( get_magic_quotes_gpc() ) {
	$_GET    = stripSlashesDeep($_GET   );
	$_POST   = stripSlashesDeep($_POST  );
	$_COOKIE = stripSlashesDeep($_COOKIE);
}
}

/** Check register globals and remove them **/

function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Secondary Call Function **/

function performAction($controller,$action,$queryString = null,$render = 0,$headers = 0) {
	$controllerName = ucfirst($controller).'Controller';
	$dispatch = new $controllerName($controller,$action);
	$dispatch->render = $render;
	$dispatch->doNotRenderHeader = $headers;
	if (method_exists($dispatch,$action)) {
		return call_user_func_array(array($dispatch,$action),$queryString);
	} else {
		require_once('../public/404.html');
	}
}

function redirectAction($controller,$action,$queryString = null,$returnUrl) {
	$controllerName = ucfirst($controller).'Controller';
	$dispatch = new $controllerName($controller,$action);
	$dispatch->doNotRenderHeader = 1;
	call_user_func_array(array($dispatch,$action),$queryString);
}
/** Routing **/

function routeURL($url) {
	global $routing;
	foreach ($routing as $pattern => $result) {
        if (preg_match($pattern, $url)) {
			return preg_replace($pattern, $result, $url);
		}
	}
	return ($url);
}

/** Main Call Function **/

function callHook() {
	global $url;
	global $default;
	
	$queryString = array();

	if (!isset($url)) {
		$controller = $default['controller'];
		$action = $default['action'];
	} else {
		$url = routeURL($url);
		$urlArray = array();
		$urlArray = explode("/",$url);
		$controller = $urlArray[0];
		array_shift($urlArray);
		if (isset($urlArray[0])) {
			$action = $urlArray[0];
			array_shift($urlArray);
		} else {
			$action = 'index'; // Default Action
		}
		$queryString = $urlArray;
	}
	$controllerName = ucfirst($controller).'Controller';
	if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($controllerName) . '.php')) {
		$dispatch = new $controllerName($controller,$action);
		if (method_exists($dispatch,$action)) {
			call_user_func_array(array($dispatch,"beforeAction"),$queryString);
			call_user_func_array(array($dispatch,$action),$queryString);
			call_user_func_array(array($dispatch,"afterAction"),$queryString);
		} else {
			$dispatch->doNotRenderHeader = 1;
			header("HTTP/1.0 404 Not Found");
			require_once('../public/404.html');
		}
	} else {
		header("HTTP/1.0 404 Not Found");
		require_once('../public/404.html');
	}
}

/** Autoload any classes that are required **/

function __autoload($className) {
	if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')) {
		require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
	} else {
		/* Error Generation Code Here */
		
	}
}

function returnAccountType($type) {
	$accounts = array(
        'Tenant'    =>  0,
        'Agent'     =>  1
    );
	return array_search($type,$accounts);
}
function returnStates($state = null) {
	$states = array('AL'=>"Alabama",
                'AK'=>"Alaska", 
                'AZ'=>"Arizona", 
                'AR'=>"Arkansas", 
                'CA'=>"California", 
                'CO'=>"Colorado", 
                'CT'=>"Connecticut", 
                'DE'=>"Delaware", 
                'DC'=>"District Of Columbia", 
                'FL'=>"Florida", 
                'GA'=>"Georgia", 
                'HI'=>"Hawaii", 
                'ID'=>"Idaho", 
                'IL'=>"Illinois", 
                'IN'=>"Indiana", 
                'IA'=>"Iowa", 
                'KS'=>"Kansas", 
                'KY'=>"Kentucky", 
                'LA'=>"Louisiana", 
                'ME'=>"Maine", 
                'MD'=>"Maryland", 
                'MA'=>"Massachusetts", 
                'MI'=>"Michigan", 
                'MN'=>"Minnesota", 
                'MS'=>"Mississippi", 
                'MO'=>"Missouri", 
                'MT'=>"Montana",
                'NE'=>"Nebraska",
                'NV'=>"Nevada",
                'NH'=>"New Hampshire",
                'NJ'=>"New Jersey",
                'NM'=>"New Mexico",
                'NY'=>"New York",
                'NC'=>"North Carolina",
                'ND'=>"North Dakota",
                'OH'=>"Ohio", 
                'OK'=>"Oklahoma", 
                'OR'=>"Oregon", 
                'PA'=>"Pennsylvania", 
                'RI'=>"Rhode Island", 
                'SC'=>"South Carolina", 
                'SD'=>"South Dakota",
                'TN'=>"Tennessee", 
                'TX'=>"Texas", 
                'UT'=>"Utah", 
                'VT'=>"Vermont", 
                'VA'=>"Virginia", 
                'WA'=>"Washington", 
                'WV'=>"West Virginia", 
                'WI'=>"Wisconsin", 
                'WY'=>"Wyoming");
	if ($state) {
		foreach($states as $short=>$long) {
			if ($state==$short)
				return $long;
			if ($state==$long)
				return $short;
		}
	}
	return $states;
}

function formatPhone($phone) {
	return '('.substr($phone,0,3).') '.substr($phone,3,3).'-'.substr($phone,6,4);
}
function generate_xml_from_array($array, $node_name) {
	$xml = '';
	if (is_array($array) || is_object($array)) {
		foreach ($array as $key=>$value) {
			if (is_numeric($key)) {
				$key = $node_name;
			}
				$xml .= '<' . $key . '>' . generate_xml_from_array($value, $node_name) . '</' . $key . '>';
		}
	} else {
		$xml = htmlspecialchars($array, ENT_QUOTES) . "";
	}
	return $xml;
}

function generate_valid_xml_from_array($array, $node_block='nodes', $node_name='node') {
	$xml = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
	$xml .= '<' . $node_block . '>';
	$xml .= generate_xml_from_array($array, $node_name);
	$xml .= '</' . $node_block . '>';
	return $xml;
}
function xml_encode($string) {
	$dom = new DOMDocument;
	$dom->preserveWhiteSpace = FALSE;
	$dom->loadXML(generate_valid_xml_from_array($string));
	$dom->formatOutput = TRUE;
	return $dom->saveXml();
}
function formatXmlString($xml) {
	  // add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
	  $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
	  
	  // now indent the tags
	  $token      = strtok($xml, "\n");
	  $result     = ''; // holds formatted version as it is built
	  $pad        = 0; // initial indent
	  $matches    = array(); // returns from preg_matches()
	  
	  // scan each line and adjust indent based on opening/closing tags
	  while ($token !== false) : 
	  
	    // test for the various tag states
	    
	    // 1. open and closing tags on same line - no change
	    if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) : 
	      $indent=0;
	    // 2. closing tag - outdent now
	    elseif (preg_match('/^<\/\w/', $token, $matches)) :
	      $pad-=3;
	    // 3. opening tag - don't pad this one, only subsequent tags
	    elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
	      $indent=2;
	    // 4. no indentation needed
	    else :
	      $indent = 0; 
	    endif;
	    
	    // pad the line with the required number of leading spaces
	    $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
	    $result .= $line . "\n"; // add to the cumulative result, with linefeed
	    $token   = strtok("\n"); // get the next token
	    $pad    += $indent; // update the pad size for subsequent lines    
	  endwhile; 
	  
	  return $result;
	}
	function shorten($string,$to) {
		return substr($string,0,$to).'...';
	}

/** GZip Output **/

function gzipOutput() {
    $ua = $_SERVER['HTTP_USER_AGENT'];

    if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ')
        || false !== strpos($ua, 'Opera')) {
        return false;
    }

    $version = (float)substr($ua, 30); 
    return (
        $version < 6
        || ($version == 6  && false === strpos($ua, 'SV1'))
    );
}

#gzipOutput() || ob_start("ob_gzhandler");
gzipOutput();

$cache =& new Cache();
$inflect =& new Inflection();

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();


?>
