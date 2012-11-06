<?php
class SessionsController extends Controller {
	function beforeAction() {
		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : null;
		if ($data = performAction('sessions', 'check', array($session))) {
			// Get the signed in user information
			$id = $data['Session']['user_id'];
			$requestArray = array(
				'API_URL'=> BASE_PATH.'/api/select/'.$id,
				'API_ID'=>API_ID,
				'API_SECRET'=>API_SECRET,
				'USER_SESSION'=>$session,
				'RETURN_TYPE'=>'json'
			);
			$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
			$request->execute();
			$json_apidata = json_decode($request->getResponseBody(),true);


			$this->set('logged_in',true);
			$this->set('tmls_user', $json_apidata);
		} else {
			$this->set('logged_in',false);
		}

		$this->set('buildJs', array(
		));
		$this->set('buildCss', array(
			'source/tmls.framework'
		));
	}
	function afterAction() {
		
	}
	function check($uniq = null) {
		$this->Session->where('uniq',$uniq);
		$this->Session->setLimit(1);
		$this->Session->showHasOne();
		$data = $this->Session->search();
		if (count($data)==1)
			return $data[0];
		else
			return false;
	}
	/*
	function register_session($user_id = null, $uniq_sess = null, $secret) {
		if ($secret==md5('test_code#')) {
			$this->Session->user_id = $user_id;
			$this->Session->uniq = $uniq_sess;
			$this->Session->logged = date("Y-m-d H:i:s");
			$this->Session->ip = $_SERVER['REMOTE_ADDR'];
			$this->Session->save();
		}
	}
	*/
	function signin() {
		if (isset($_POST['loginForm_submit'])) {
			$data = performAction('users','check_login',array($_POST['loginForm_email'],md5($_POST['loginForm_password'])));
            if ($data) {
                $uniq_sess = md5($_POST['loginForm_email'].md5($_POST['loginForm_password']).microtime());
        		$this->Session->user_id = $data['User']['id'];
        		$this->Session->uniq = $uniq_sess;
				$this->Session->logged = date("Y-m-d H:i:s");
				$this->Session->ip = $_SERVER['REMOTE_ADDR'];
                //echo $uniq_sess;
                $this->Session->save();

                // Set cookies
                setcookie('tmls_uniq_sess',$uniq_sess,time()+60*60*24*30,'/'); // 30 days
                setcookie('tmls',$data['User']['id'],time()+60*60*24*30,'/');
                /*
                 * AS OF RIGHT NOW (AS IN, FOR FUCKING LATER)
                 * THERE IS NO ENCRYPTION WHEN STORING USER INFO
                 * IN THE FOLLOWING COOKIE
                 *
                 * I KNOW THAT'S FUCKING TERRIBLE, FIX IT PLZ
                 */
                setcookie('tmls_jsess',$data['User']['id'].'@'.JABBER_SERVER.'-'.md5($_POST['loginForm_password']),time()+60*60*24*30,'/');

            	header("Location: ".BASE_PATH);
            } else {
                $error = 'Incorrect login information, please try again';
                $this->set('error',$error);
        	}
        }
	}
	function signout() {
		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : false;
        if ($session) {
        	$this->Session->where('uniq',$session);
			$this->Session->setLimit(1);
			$this->Session->showHasOne();
			$data = $this->Session->search();
			//print_r($data);
			$this->Session->id = $data[0]['Session']['id'];
			$this->Session->delete();
            setcookie('tmls_uniq_sess',1,time()-3600,'/'); # USE FALSE FOR LOCALHOST
			unset($_COOKIE['tmls_uniq_sess']);
			unset($_COOKIE['tmls']);
			unset($_COOKIE['tmls_jsess']);
			//unset($_SESSION);
            header("Location:".BASE_PATH);
        }
	}
	/* 
	 * Faux function
	 */
	 function register() {
	 	// LOL I DO NOTHING!
	 }
}
?>
