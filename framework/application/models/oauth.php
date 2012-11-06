<?php
class OAuth extends Model {
	
	function assert_logged_in()
	{
		if (empty($_SESSION['authorized']))
		{
			//$uri = $_SERVER['REQUEST_URI'];
			header('Location: '.BASE_PATH.'/oauth/logon');
			exit();
		}
	}
	
	function assert_request_vars()
	{
		foreach(func_get_args() as $a)
		{
			if (!isset($_REQUEST[$a]))
			{
				header('HTTP/1.1 400 Bad Request');
				echo 'Bad request.';
				exit;
			}
		}
	}
	
	function assert_request_vars_all()
	{
		foreach($_REQUEST as $row)
		{
			foreach(func_get_args() as $a)
			{
				if (!isset($row[$a]))
				{
					header('HTTP/1.1 400 Bad Request');
					echo 'Bad request.';
					exit;
				}
			}
		}
	}
}
