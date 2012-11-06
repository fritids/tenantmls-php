<?php
class OAuthController extends Controller {
	
	function beforeAction() {
		$this->doNotRenderHeader = 1;
		/*
		 * Simple 'user management'
		 */
		define ('USERNAME', 'sysadmin');
		define ('PASSWORD', 'sysadmin');
		/*
		 * Always announce XRDS OAuth discovery
		 */
		header('X-XRDS-Location: http://' . $_SERVER['SERVER_NAME'] . '/services.xrds');

		require_once (ROOT . DS . 'library' . DS . 'oauth' . DS . '1.0' . DS . 'OAuthServer.php');
		require_once (ROOT . DS . 'library' . DS . 'oauth' . DS . '1.0' . DS . 'OAuthStore.php');
		/*
		 * Initialize OAuthStore
		 */
		$info = parse_url(getenv('DB_DSN'));
		($GLOBALS['db_conn'] = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) || die(mysql_error());
		mysql_select_db(basename('oauth_v1'), $GLOBALS['db_conn']) || die(mysql_error());
		unset($info);
		OAuthStore::instance('MySQL', array('conn' => $GLOBALS['db_conn']));
		/*
		 * Session
		 */
		session_start();
	}
	function afterAction() {
		
	}
	
	function index() {
		
	}
	
	function hello() {
		$authorized = false;
		$server = new OAuthServer();
		try
		{
			if ($server->verifyIfSigned())
			{
				$authorized = true;
			}
		}
		catch (OAuthException2 $e)
		{
		}
		
		if (!$authorized)
		{
			header('HTTP/1.1 401 Unauthorized');
			header('Content-Type: text/plain');
			
			echo "OAuth Verification Failed: " . $e->getMessage();
			die;
		}
		
		// From here on we are authenticated with OAuth.
		
		header('Content-type: text/plain');
		echo 'Hello, world!';
	}
	
	function logon() {
		if (isset($_POST['username']) && isset($_POST['password']))
		{
			if ($_POST['username'] == USERNAME && $_POST['password'] == PASSWORD)
			{
				$_SESSION['authorized'] = true;
				if (!empty($_REQUEST['goto']))
				{
					header('Location: ' . $_REQUEST['goto']);
					die;
				}
				echo "Logon succesfull.";
				die;
			}
		}
	}
	function oauth($path = null) {
		$server = new OAuthServer();
		switch($path)
		{
		case 'request_token':
			$server->requestToken();
			exit;
		
		case 'access_token':
			$server->accessToken();
			exit;
		
		case 'authorize':
			# logon
		
			$this->Oauth->assert_logged_in();
		
			try
			{
				$server->authorizeVerify();
				$server->authorizeFinish(true, 1);
			}
			catch (OAuthException2 $e)
			{
				header('HTTP/1.1 400 Bad Request');
				header('Content-Type: text/plain');
				
				echo "Failed OAuth Request: " . $e->getMessage();
			}
			exit;
		
			
		default:
			header('HTTP/1.1 500 Internal Server Error');
			header('Content-Type: text/plain');
			echo "Unknown request";
		}
	}
}
