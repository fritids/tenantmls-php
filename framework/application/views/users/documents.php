
<?php
$onclick = 'onclick="open_window(\'/uploads/add/'.$document['User']['tmls_number'].'\')"';
?>
<div id="tmls-ui-body-content">
	<a class="button" href="javascript:void(0)" id="uploadUser"<?php echo $onclick; ?>>Upload New</a>
	<div id="upload-dialog"></div>
	<h4>Documents</h4>
</div>
	<?php
	$str='';

	// Get the groups
	$groups = array();
	foreach($document['Document'] as $doc) {
		if (!isset($groups[$doc['upload_type']]))
			$groups[$doc['upload_type']]=array();
	}

	foreach($groups as $group=>$files) {
		switch($group) {
			case 'agency-form' : $grouptitle='Agency Forms'; break;
			case 'other' : $grouptitle='Other'; break;
		}
		$str.='<span class="ui-documents-list-title">'.$grouptitle.'</span>';
		$str.='<table class="ui-documents-list">';
		foreach($document['Document'] as $doc) {
			if ($doc['upload_type']==$group) {
				$str.='<tr>';
					$str.='<td><input type="checkbox" name="" /></td>';

					$str.='<td>';
					$str.='<span class="ui-documents-icon">';
					switch($doc['file_extension']) {
						case 'pdf' :
							 $str.='<img src="'.BASE_PATH.'/images/ui/mime-application-pdf.png" />';
						break;

						case 'doc' :
						case 'docx' :
							 $str.='<img src="'.BASE_PATH.'/images/ui/mime-application-doc.png" />';
						break;
					}
					$str.='</span>';
					$str.='</td>';

					$str.='<td>';
					$str.='<a href="'.$doc['url'].'">'.$doc['title'].'</a>';
					$str.='<h5>Uploaded: '.date("F d, Y", strtotime($doc['logged'])).'</h5>';
					$str.='</td>';

					$str.='<td>';
					$str.='<span class="ui-documents-description">'.$doc['description'].'</span>';
					$str.='</td>';

				$str.='</tr>';
			}
		}
		$str.='</table>';
	}
	echo $str;
	?>
</table>
<?php

printr($document);

?>