<?php echo $html->includeCss('source/tmls.framework'); ?>
<?php echo $html->includeJs('source/tmls.sdk'); ?>
<?php echo $html->includeJs('jquery-1.7.2.min'); ?>
<?php echo $html->includeJs('ui.gallery'); ?>
<?php
$session = (isset($_COOKIE['tmls_uniq_sess'])) ? $_COOKIE['tmls_uniq_sess'] : '';
	/*
	 * Check access and then
	 *
	 * Verify true
	 */
	$access = true;
	$defaultPicture = 0;
	$thisPictureId = 0;
	foreach($profile['Picture'] as $picture) {
		if ($picture['default'])
			$defaultPicture = $picture['file_name'];
	}
?>
<div id="ui-gallery-frame">
	<div id="img-bg">
		<?php
		if (count($profile['Picture'])>0) {
			if (isset($xPictureId)) {
				foreach($profile['Picture'] as $pic) {
					if ($pic['file_name']==$xPictureId) {
						echo '<img src="'.$pic['url'].'" />';
						$isDefault = ($pic['default']) ? true : false;
						$thisPictureId = $pic['file_name'];
					}
				}
			} elseif ($defaultPicture) {
				foreach($profile['Picture'] as $pic) {
					if ($pic['file_name']==$defaultPicture) {
						echo '<img src="'.$pic['url'].'" />';
						$isDefault = ($pic['default']) ? true : false;
						$thisPictureId = $pic['file_name'];
					}
				}
			}
		} else {
			echo '<img src="'.BASE_PATH.'/images/ui-picture-default-large.jpg" />';
		}
		?>

		<?php if ($access) { ?>
		<input type="hidden" name="picture_id" value="<?php echo $thisPictureId; ?>" />
		<input type="hidden" name="user_id" value="<?php echo $xUserId; ?>" />
		<ul id="ui-gallery-controls">
			<?php if ($isDefault) { ?>
			<li><a href="javascript:void(0)"><span class="green">&#x2713;</span> Default</a></li>
			<?php } else { ?>
			<li id="setDefault"><a href="javascript:void(0)"><span class="red">&#x2713;</span> Set Default</a></li>
			<?php } ?>
			<li><a href="javascript:void(0)">⟲ Rotate Left</a></li>
			<li><a href="javascript:void(0)">Rotate Right ⟳</a></li>
			<li id="deletePic"><a href="javascript:void(0)">Delete Picture</a></li>
		</ul>
		<div class="clearfix-both"></div>
		<?php } ?>
	</div>
</div>