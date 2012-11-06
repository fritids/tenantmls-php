<?php
class PicturesController extends Controller {
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


			$this->set('tmls_setting_loggedIn',true);
			$this->set('tmls_setting_sessionTmlsNumber',$data['Session']['user_id']);
			$this->set('tmls_user', $json_apidata);
		} else {
			$this->set('tmls_setting_loggedIn',false);
			$this->set('tmls_setting_sessionTmlsNumber',false);
		}
	}
	function afterAction() {
		
	}
	function delete() {
		if (isset($_POST['picture_id']) && isset($_POST['user_id'])) {
			// code to remove picture file
			$this->Picture->where('file_name',$_POST['picture_id']);
			$this->Picture->showHasOne();
			$data = $this->Picture->search();
			if ($data[0]['Picture']['default']) {
				$this->Picture->where('user_id',$_POST['user_id']);
				$picData = $this->Picture->search();
				$hasNotSetDefault = true;
				if(count($picData)>0) {
					foreach($picData as $pic) {
						if ($pic['Picture']['default']==0 && $hasNotSetDefault) {
							$this->Picture->id = $pic['Picture']['id'];
							$this->Picture->default = 1;
							$this->Picture->save();
							$hasNotSetDefault = false;
						}
					}
				}
			}
			$this->Picture->id = $data[0]['Picture']['id'];
			$this->Picture->delete();
		}
		header("Location:".BASE_PATH.'/users/view/');
	}
	
	function set_default() {
		if (isset($_POST['picture_id']) && isset($_POST['user_id'])) {

			$this->Picture->where('user_id',$_POST['user_id']);
			$data = $this->Picture->search();

			foreach($data as $pic) {
				$this->Picture->id = $pic['Picture']['id'];
				$this->Picture->default = 0;
				$this->Picture->save();
			}

			$this->Picture->where('file_name',$_POST['picture_id']);
			$data = $this->Picture->search();
			$this->Picture->id = $data[0]['Picture']['id'];
			$this->Picture->default = 1;
			$this->Picture->save();
		}
		//header("Location:".BASE_PATH.'/users/view/');
	}
	
	function take($user_id = null) {
		$this->render = 0;
		
		$requestArray = array(
			'API_URL'=> BASE_PATH.'/api/select/'.$user_id.'/pictures',
			'API_ID'=>API_ID,
			'API_SECRET'=>API_SECRET,
			'USER_SESSION'=>$session,
			'RETURN_TYPE'=>'json'
		);
		$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
		$request->execute();
		$xUserData = $json_apidata = json_decode($request->getResponseBody(),true);
		
		$this->Picture->user_id = $user_id;
		$this->Picture->file_extension = 'cam';
		
		$fileFolder = ROOT.DS.'public'.DS.'uploads'.DS.$user_id;
		// check for a folder
		if (!is_dir($fileFolder))
			mkdir($fileFolder);
				
		$fileName = md5($user_id.date("Ymdhis"));
		$fileExt = 'jpg';
		$uploadFile = $fileFolder.'/'.$fileName.'.'.$fileExt;
		
		$this->Picture->file_name = $fileName;
		// if we no pictures, set the first one default
		if(count($xUserData['Picture'])==0)
			$this->Picture->default = 1;
		// save
		$this->Picture->save();	
		
		$result = file_put_contents($uploadFile, file_get_contents('php://input') );

		$tmp_image = imagecreatefromjpeg($uploadFile);
		
		// force the reduction of the big pic...
		list($w, $h) = @getimagesize($uploadFile); // original dimensions
		$this->Picture->width = $w;
		$this->Picture->height = $h;
		// make a square image...
		$biggestSide = ($w > $h) ? $w : $h;
		// crop source size (%)
		$crop = $biggestSide*0.65;
		// crop source position
		$x = ($w - $crop) /2;
		$y = ($h - $crop) /2;
		
		// create large thumbnail (pixels)
		$imageSize = 320;
		$image = imagecreatetruecolor($imageSize, $imageSize);
		imagecopyresampled($image, $tmp_image, 0, 0, $x, $y, $imageSize, $imageSize, $crop, $crop);
		imagejpeg($image, $fileFolder.'/'.$fileName."_lthumb.jpg");
		imagedestroy($image); // free up memory
		
		// create thumbnail (pixels)
		$thumbSize = 120;
		$image = imagecreatetruecolor($thumbSize, $thumbSize);
		imagecopyresampled($image, $tmp_image, 0, 0, $x, $y, $thumbSize, $thumbSize, $crop, $crop);
		imagejpeg($image, $fileFolder.'/'.$fileName.'_thumb.jpg');
		
		// free up memory
		imagedestroy($image);
		
		if (!$result) {
			print "ERROR: Failed to write data to $fileName, check permissions\n";
			exit();
		} else {
			echo $user_id.'/'.$fileName;
		}

	}
	
	
	function upload($user_id = null) {
		$this->doNotRenderHeader = 1;
		$requestArray = array(
			'API_URL'=> BASE_PATH.'/api/select/'.$user_id,
			'API_ID'=>API_ID,
			'API_SECRET'=>API_SECRET,
			'RETURN_TYPE'=>'json'
		);
		$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
		$request->execute();
		$xUserData = $json_apidata = json_decode($request->getResponseBody(),true);
		$this->set('profile',$json_apidata);

		if (isset($_POST['upload-submit'])) {
			$user_id = $_POST['upload-user_id'];
			$uploadFile = $_FILES['upload-file'];
			$this->Picture->user_id = $user_id;
			// get path name

			if ($this->Picture->checkErrors($uploadFile)) {
				$fileFolder = ROOT.DS.'public'.DS.'uploads'.DS.$user_id;
				// check for a folder
				if (!is_dir($fileFolder))
					mkdir($fileFolder);
				
				// upload file info.
				$fileName = $uploadFile['name'];
				$ext = strtolower(substr(strrchr($fileName, '.'),1));
				$this->Picture->file_extension = $ext;
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
									$ratio = max(array(649/$w, 480/$h));
									// calculate new image size
									$newW = $w * $ratio;
									$newH = $h * $ratio;
									$this->Picture->width = $newW;
									$this->Picture->height = $newH;
									// create new image
									$image = imagecreatetruecolor($newW, $newH);
									imagecopyresampled($image, $tmp_image, 0, 0, 0, 0, $newW, $newH, $w, $h);
									imagejpeg($image, "$new_name.jpg");
									imagedestroy($image); // free up memory
									
									// make a square image...
									$biggestSide = ($w > $h) ? $w : $h;
									// crop source size (%)
									$crop = $biggestSide*0.7;
									// crop source position
									$x = ($w - $crop) /2;
									$y = ($h - $crop) /2;
									
									// create large thumbnail (pixels)
									$imageSize = 320;
									$image = imagecreatetruecolor($imageSize, $imageSize);
									imagecopyresampled($image, $tmp_image, 0, 0, $x, $y, $imageSize, $imageSize, $crop, $crop);
									imagejpeg($image, $new_name."_lthumb.jpg");
									imagedestroy($image); // free up memory
									
									// create thumbnail (pixels)
									$thumbSize = 120;
									$image = imagecreatetruecolor($thumbSize, $thumbSize);
									imagecopyresampled($image, $tmp_image, 0, 0, $x, $y, $thumbSize, $thumbSize, $crop, $crop);
									imagejpeg($image, $new_name.'_thumb.jpg');
									
									// free up memory
									imagedestroy($image);
									imagedestroy($tmp_image);
									
									// set the path
									$this->Picture->file_name = $new_image_name;
									// if we no pictures, set the first one default
									if(count($xUserData['Picture'])==0)
										$this->Picture->default = 1;
									// save
									$this->Picture->save();			
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
			if (isset($uploaded) && file_exists($uploaded)) { unlink($uploaded); }
			if (isset($logging) && $logging) {
				if (isset($error)) {
					$Log.= "\n$error\n";
				} else {
					$Log.= "\n$success\n";
				}
				fwrite($fLog, $Log);
				fclose($fLog);
			}
			if (isset($error)) {
				echo $error;
			} else {
				header("Location:".BASE_PATH.'/pictures/view/'.$user_id.'/'.$new_image_name);
				//echo '<img src="'.BASE_PATH.'/uploads/'.$user_id.'/'.$new_image_name.'.jpg" />';
			}
		}
	}
	function view($user_id = null,$file_name = null) {
		$this->doNotRenderHeader = 1;
		if (isset($file_name))
			$this->set('xPictureId',$file_name);
		if ($user_id) {
			// Get the user picture
			$requestArray = array(
				'API_URL'=> BASE_PATH.'/api/select/'.$user_id.'/pictures',
				'API_ID'=>API_ID,
				'API_SECRET'=>API_SECRET,
				'RETURN_TYPE'=>'json'
			);
			$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
			$request->execute();
			$data = json_decode($request->getResponseBody(),true);
			$this->set('profile',$data);
		}
		$this->set('xUserId',$user_id);
	}
	function gallery($user_id = null) {
		$this->doNotRenderHeader = 1;
		if ($user_id) {
			// Get the user picture
			$requestArray = array(
				'API_URL'=> BASE_PATH.'/api/select/'.$user_id.'/pictures',
				'API_ID'=>API_ID,
				'API_SECRET'=>API_SECRET,
				'RETURN_TYPE'=>'json'
			);
			$request = new RestRequest($requestArray['API_URL'],'POST',$requestArray);
			$request->execute();
			$data = json_decode($request->getResponseBody(),true);
			$data['tmls_number'] = $user_id;
			$this->set('profile',$data);

		}
	}
	/*
	function get($user_id) {
		$this->doNotRenderHeader = 1;
		if ($user_id)
			$xUserData[0] = performAction('users','fetch',array($user_id));
		else
			$xUserData =  performAction('users','check_user',array($_COOKIE['tmls_uniq_sess']));
		$this->set('xUserData', $xUserData[0]);
	}
	*/
}
?>	