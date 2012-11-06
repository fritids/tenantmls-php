<?php echo $html->includeCss('source/tmls.framework'); ?>
<?php echo $html->includeJs('jquery-1.7.2.min'); ?>
<?php echo $html->includeJs('ui.gallery'); ?>
<?php echo $html->includeJs('webcam'); ?>
<div class="ui-picture">
<div id="ui-picture-UploadPage">
	<div id="content-upload-file">
		<h4>Upload a picture/video from computer</h4>
		<h5>(jpg, jpeg, png, gif, mov, wmv, avi, mpg, mpeg)</h5>
		<form action="<?php echo BASE_PATH.'/pictures/upload/'.$profile['User']['tmls_number']; ?>" method="post" enctype="multipart/form-data" id="ui-picture-uploadForm">
			<input type="file" name="upload-file" />
			<input type="hidden" name="upload-user_id" value="<?php echo $profile['User']['tmls_number']; ?>" />
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
			<input type="submit" name="upload-submit" id="ui-picture-uploadForm-submit" value="Upload" />
		</form>
	<hr />
	<h4>Take a picture from webcam</h4>
	<h5>Click the camera icon below to show the webcam preview</h5>
	<a href="javascript:void(0)" id="showTakePicture">&nbsp;</a>
	</div>
	<div id="content-take-picture">
		<!-- First, include the JPEGCam JavaScript Library -->
		<!-- Configure a few settings -->
		<script language="JavaScript">
			webcam.set_quality( 90 ); // JPEG quality (1 - 100)
			webcam.set_shutter_sound( true ); // play shutter click sound
		</script>
		<!-- Next, write the movie to the page at 320x240 -->
		<script language="JavaScript">
			document.write( webcam.get_html(600, 400) );
		</script>
		<!-- Some buttons for controlling things -->
		<br/><form>
		<?php
		$snapshot_url = BASE_PATH.'/pictures/take/'.$profile['User']['tmls_number'];
		$onclick = 'onClick="take_snapshot(\''.$snapshot_url.'\')"';
		?>
			<input type="button" value="Take Snapshot" <?php echo $onclick; ?>>
		</form>
		<!-- Code to handle the server response (see test.php) -->
		<script language="JavaScript">
			webcam.set_hook( 'onComplete', 'my_completion_handler' );
			function take_snapshot(api_url) {
				// take snapshot and upload to server
				webcam.set_api_url(api_url);
				document.getElementById('upload_results').innerHTML = '<h1>Uploading...</h1>';
				webcam.snap();
			}
			
			function my_completion_handler(image_url) {
				parent.document.getElementById("ui-gallery-pictures").innerHTML = '<li><img src="'+TenantMLS.BASE_PATH+'/uploads/'+image_url+'_thumb.jpg" /></li>'+parent.document.getElementById("ui-gallery-pictures").innerHTML;
				window.location = TenantMLS.BASE_PATH+'/pictures/view/'+image_url;
			}
		</script>
		<div id="upload_results" style="background-color:#eee;"></div>
	</div>
	<div class="clear"></div>
</div>
</div>