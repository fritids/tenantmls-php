<?php
class UploadsController extends Controller {
	function beforeAction() {
		$this->doNotRenderHeader = 1;
	}
	function afterAction() {

	}
	function add($tmls=null) {
		$this->set('tmls',$tmls);

		$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
		if ((!empty($id) && is_numeric(substr($id, 1, strlen($id) - 1))) || ((empty($id) || !is_numeric($id)) && $data = performAction('sessions', 'check', array($session)))) {
			$id = ((!empty($id) && is_numeric(substr($id, 1, strlen($id) - 1)))) ? $id : $data['User']['id'];
			$requestArray = array(
				'API_URL'=> BASE_PATH.'/api/select/'.$id.'/custom',
				'API_ID'=>API_ID,
				'API_SECRET'=>API_SECRET,
				'USER_SESSION'=>$session,
				'RETURN_TYPE'=>'json',
				'OPTION_INCLUDE_USER'=>true,
				'OPTION_INCLUDE_PROFILE'=>true
			);
			$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
			$request->execute();
			$json_apidata = json_decode($request->getResponseBody(),true);
			$this->set('upload',$json_apidata);
			
		} else {
			header("HTTP/1.0 404 Not Found");
		}

		$acceptedFileTypes = array(
			'doc','docx',
			'pdf',
			'txt','rtf',
			'jpeg','jpg','gif','png',
			'xls','xlsx'
		);
		if (isset($_POST['submit_file_upload'])) {
			$this->Upload->title = $_POST['title'];
			$this->Upload->description = $_POST['description'];
			$this->Upload->upload_type = $_POST['upload_type'];
			$this->Upload->upload_dir = 'uploads';
			$this->Upload->logged = date("Y-m-d H:i:s");
			$this->Upload->user_id = $tmls;

			$fileFolder = ROOT.DS.'public'.DS.'uploads'.DS.$tmls;
			// check for a folder
			if (!is_dir($fileFolder))
				mkdir($fileFolder);

			$uploadFile = $_FILES['upload-file'];
			// upload file info.
			$fileName = $uploadFile['name'];
			$tempFile = $uploadFile['tmp_name'];

			$ext = strtolower(substr(strrchr($fileName, '.'),1));
			$this->Upload->file_extension = $ext;
			$fileName = preg_replace("/[^a-z0-9_ \'\-]/i", '', substr($fileName,0,-strlen($ext)-1)) . ".$ext"; // safe name
			$this->Upload->file_name = $fileName;

			if (in_array($ext,$acceptedFileTypes)) {
				move_uploaded_file($tempFile, ROOT.DS.'public'.DS.'uploads'.DS.$tmls.DS.$fileName);
				$this->Upload->save();
				header('Location:'.BASE_PATH.'/documents');
			} else {
				$error = 'Document must be a supported file';
			}
		}
	}
	function upload($object_id = null, $type = null) {
		$this->set('type',$type);
		$this->set('object_id',$object_id);
		$acceptedTypes = array(
			'employer-picture',
			'background-check',
			'credit-score',
			'employer-income',
			'other'
		);

		if (in_array($type,$acceptedTypes)) {
			$this->Upload->upload_dir = 'uploads';
			$this->Upload->upload_type = str_replace('-','_',$type);
			switch($type){
				case 'employer-picture' :
				if (isset($_POST['upload'])) {
					$user_id = performAction('employers','returnUserId',array($object_id));
					$this->Upload->user_id = $user_id;
					$uploadFile = $_FILES['upload-file'];

					if ($this->Upload->checkErrors($uploadFile)) {
						$fileFolder = ROOT.DS.'public'.DS.'uploads'.DS.$user_id;
						// check for a folder
						if (!is_dir($fileFolder))
							mkdir($fileFolder);
						
						// upload file info.
						$fileName = $uploadFile['name'];
						$ext = strtolower(substr(strrchr($fileName, '.'),1));
						$this->Upload->file_extension = $ext;
						$fileName = preg_replace("/[^a-z0-9_ \'\-]/i", '', substr($fileName,0,-strlen($ext)-1)) . ".$ext"; // safe name
						//
						$tempFile = $uploadFile['tmp_name'];
						$mimeType = strtolower($uploadFile['type']);
						
						// log setup
						$logging = true;
						if ($logging) {
							$logFile = "/var/www/tmp/Upload_".$user_id.".log";
							$fLog = @fopen($logFile, 'w');
							if ($fLog === FALSE) {
								unset($logging);
							} else {
								$Log = "user_id=[".$user_id."]\n";
								$Log.= "fileName=[$fileName]\n";
								$Log.= "tempFile=[$tempFile]\n";
								$Log.= "mimeType=[$mimeType]\n\n";
							}
						}
						// check size
						if (filesize($tempFile) <= MAX_FILE_SIZE) {
						
							// allowed extensions
							if (in_array($ext, array('jpg','jpeg','png','gif'))) {
								
								// allowed MIME types
								$PicTypes = array("image/jpeg","image/gif","image/png");
								
								if (in_array($mimeType, $PicTypes)) {
								
									// move uploaded file to user folder
									$uploaded = $fileFolder.'/'.basename($tempFile);
									if (move_uploaded_file($tempFile, $uploaded)) {
										// new filename
										$new_image_name = md5(microtime().$user_id);
										$new_name = "$fileFolder/".$new_image_name;
										if (isset($logging)) {
											$Log.= "uploaded=[$uploaded]\n";
											$Log.= "newName= [$new_name]\n";
										}
									
									//-------------------------------------  PICTURE UPLOAD  -------------------------------------
										if (in_array($mimeType, $PicTypes)) {
											// create a tempoary image for processing
											switch ($ext) {
												case 'jpeg':
												case 'jpg' : $tmp_image = imagecreatefromjpeg($uploaded); break;
												case 'gif' : $tmp_image = imagecreatefromgif($uploaded);  break;
												case 'png' : $tmp_image = imagecreatefrompng($uploaded);  break;
											}
											// force the reduction of the big pic...
											list($w, $h) = @getimagesize($uploaded); // original dimensions
											// find the appropriate ratio to apply
											$ratio = max(array(75/$w, 50/$h));
											// calculate new image size
											$newW = $w * $ratio;
											$newH = $h * $ratio;
											// create new image
											$image = imagecreatetruecolor($newW, $newH);
											imagecopyresampled($image, $tmp_image, 0, 0, 0, 0, $newW, $newH, $w, $h);
											imagejpeg($image, "$new_name.jpg");
											imagedestroy($image); // free up memory
											// free up memory
											imagedestroy($tmp_image);
											
											// set the path
											$this->Upload->file_name = $new_image_name;
											// save
											$upload_id = $this->Upload->save(true);		
											performAction('employers','setUploadId',array($object_id,$upload_id));
											$success = "[$fileName] was uploaded successfully.";
										}
									} else {
										$error = 'An error occured during upload. Please try again.';
									}
								} else {
									$error = "File not uploaded. File type [$mimeType] is not allowed.";
								}
							} else {
								$error = "File not uploaded. Extension [.$ext] is not allowed.";
							}
						} else {
							$error = "Error uploading. The file [$fileName] exceeded 20MB.";
						}
					} else {
						$error = 'An error occured during upload. Please try again.';
					}
				}
				break;
				default : 

				break;
			}
		}
	}
}
?>