<?php
class EmployersController extends Controller {
	function beforeAction() {
		$this->doNotRenderHeader = 1;
	}
	function afterAction() {
		
	}
	function returnUserId($employer_id = null) {
		$this->Employer->id = $employer_id;
		$data = $this->Employer->search();
		return $data['Employer']['user_id'];
	}
	function setUploadId($employer_id = null,$upload_id = null) {
		$this->Employer->id = $employer_id;
		$this->Employer->upload_id = $upload_id;
		$this->Employer->save();
	}
	function getPictureFromId($employer_id = null) {
		$this->Employer->id = $employer_id;
		$this->Employer->showHasOne();
		$data = $this->Employer->search();

		return BASE_PATH.'/uploads/'.$data['Upload']['user_id'].'/'.$data['Upload']['file_name'].'.jpg';
	}
	function add($user_id = null) {
		if ($user_id)
			$data = performAction('users', 'fetch', array($user_id));
		$this->set('data',$data);
		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
		if ($checkData = performAction('sessions', 'check', array($session))) {
			$xUserData = performAction('users', 'fetch', array($checkData['User']['id']));
			$this->set('xUserData',$xUserData);
		}
		
		if (isset($_POST['company_name'])) {
			$this->Employer->user_id = $user_id;
			if ($_POST['company_name'])
				$this->Employer->company_name = $_POST['company_name'];
			if ($_POST['employer_name'])
				$this->Employer->employer_name = $_POST['employer_name'];
			if ($_POST['employer_number_1'] && $_POST['employer_number_2'] && $_POST['employer_number_3'])
				$this->Employer->employer_number = $_POST['employer_number_1'].$_POST['employer_number_2'].$_POST['employer_number_3'];
			if ($_POST['start_year'])
				$this->Employer->start_year = $_POST['start_year'];
			if ($_POST['start_month'])
				$this->Employer->start_month = $_POST['start_month'];
			if (isset($_POST['end_year']))
				$this->Employer->end_year = $_POST['end_year'];
			if (isset($_POST['end_month']))
				$this->Employer->end_month = $_POST['end_month'];
			if ($_POST['pay_amount'])
				$this->Employer->pay_amount = $_POST['pay_amount'];
			if ($_POST['pay_period'])
				$this->Employer->pay_period = $_POST['pay_period'];
			if ($_POST['description'])
				$this->Employer->description = $_POST['description'];
			if (isset($_FILES['file_upload'])) {
				// handling file processing here;
			}
			$this->Employer->save();
		}
	}
}
?>