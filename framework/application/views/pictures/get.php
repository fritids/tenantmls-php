<?php
//echo $html->includeCss('ui.core');
echo $html->includeCss('ui.gallery');
foreach($xUserData['Picture'] as $picture) {
	echo '<li><img src="'.BASE_PATH.'/uploads/'.$xUserData['User']['id'].'/'.$picture['Picture']['file_name'].'_thumb.jpg" /></li>';
}
?>