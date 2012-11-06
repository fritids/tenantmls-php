<?php

class HTML {
	private $js = array();

	function shortenUrls($data) {
		$data = preg_replace_callback('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', array(get_class($this), '_fetchTinyUrl'), $data);
		return $data;
	}

	private function _fetchTinyUrl($url) { 
		$ch = curl_init(); 
		$timeout = 5; 
		curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url[0]); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout); 
		$data = curl_exec($ch); 
		curl_close($ch); 
		return '<a href="'.$data.'" target = "_blank" >'.$data.'</a>'; 
	}

	function sanitize($data) {
		return mysql_real_escape_string($data);
	}

	function link($text,$path,$prompt = null,$confirmMessage = "Are you sure?") {
		$path = str_replace(' ','-',$path);
		if ($prompt) {
			$data = '<a href="javascript:void(0);" onclick="javascript:jumpTo(\''.BASE_PATH.'/'.$path.'\',\''.$confirmMessage.'\')">'.$text.'</a>';
		} else {
			$data = '<a href="'.BASE_PATH.'/'.$path.'">'.$text.'</a>';	
		}
		return $data;
	}

	function includeJs($fileName) {
		$data = '<script src="'.BASE_PATH.'/js/'.$fileName.'.js"></script>';
		return $data;
	}

	function includeCss($fileName) {
                $data = '<link rel="stylesheet" type="text/css" media="screen" href="'.BASE_PATH.'/css/'.$fileName.'.css" />';
		return $data;
	}
	
	function formSelect($name = null, $values = array(), $selected = null, $id = null, $class = null) {
		if ($name) $displayName = ' name="'.$name.'"'; else $displayName = '';
		if ($id) $displayId = ' id="'.$id.'"'; else $displayId = '';
		if ($class) $displayClass = ' class="'.$class.'"'; else $displayClass = '';
		
		$data = '<select'.$displayName.$displayId.$displayClass.'>';
		foreach($values as $value => $name) {
			if ((string) $value==(string) $selected) {
				$data.= '<option value="'.$value.'" selected >'.$name.'</option>';
			} else {
				$data.= '<option value="'.$value.'">'.$name.'</option>';
			}
		}
		$data.= '</select>';
		
		return $data;
	}
	function formInput($type = 'text', $name = null, $value = null, $size = null, $id = null, $class = null) {
		if ($name) $displayName = ' name="'.$name.'"'; else $displayName = '';
		if ($id) $displayId = ' id="'.$id.'"'; else $displayId = '';
		if ($class) $displayClass = ' class="'.$class.'"'; else $displayClass = '';
		if ($value) $displayValue = ' value="'.$value.'"'; else $displayValue = '';
		if ($size) $displaySize = ' size="'.$size.'"'; else $displaySize = '';
		
		$data = "<input type=\"$type\"$displayName$displayId$displayClass$displayValue$displaySize />";
		return $data;
	}
	
    function createForm($formParams = array(), $formElements = array(), $wrapTable = false, $tableOrientation = 0) {
        list($formName, $formAction, $formMethod) = $formParams;
        $data = '<form action="'.BASE_PATH.'/'.$formAction.'" method="'.$formMethod.'" name="'.$formName.'">';
        foreach($formElements as $label=>$element) {
        	if ($label)
				$data.= "$label: ";
        	$data.=$element;
        }
        $data.= '</form>';
        return $data;
    }
}