<?php echo $html->includeJs('ui.gallery'); ?>
<?php
$access = true;

$width = ($access || count($profile['Picture'])>0) ? 722 : 620;
?>
<div id="ui-gallery" style="width:<?php echo $width; ?>px;">
	<?php

		// Run the function once to render everything
		$thmbs='';
		if (count($profile['Picture'])>0) {
			foreach($profile['Picture'] as $picture) {
				$thmbs.= '<li><img src="'.$picture['thmb'].'" /></li>';
			}
		}
		$iframe_src = (count($profile['Picture'])==0 && $access) ? BASE_PATH.'/pictures/upload/'.$profile['tmls_number'] : BASE_PATH.'/pictures/view/'.$profile['tmls_number'];
	?>
	<div id="ui-gallery-bottom">
		
		
		<ul id="ui-gallery-pictures">
		<?php
		// Echo the thumbnails created above
		echo $thmbs;
		if ($access && count($profile['Picture'])<MAX_PICTURE_COUNT) { ?>
			<li><a href="javascript:void(0)" class="uploadNew" id="<?php echo $profile['tmls_number']; ?>">+</a></li>
		<?php } ?>
		</ul>
		<div class="clear"></div>
	</div>
	<iframe width="600" height="400" src="<?php echo $iframe_src; ?>" name="upload_file_target" id="ui-gallery-iframe" scrolling="no"></iframe>
</div>