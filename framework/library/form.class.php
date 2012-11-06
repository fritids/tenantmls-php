<?php
class Form {
	
	function create($formParams = array(), $formElements = array(), $showLabels = true, $createJs = false) {
        list($formName, $formAction, $formMethod) = $formParams;

        $data = '<form action="'.BASE_PATH.$formAction.'" method="'.$formMethod.'" name="'.$formName.'">';
        foreach($formElements as $label=>$element) {
			list($elementName,$elmt) = $element;
			if ($elementName)
				$data.= "$elementName";
        	$data.=$elmt;
			
        }
        $data.= '</form>';
		
       	return $data;
    }
	
	function formInput($name = null, $value = null, $size = null, $class = null, $id = null) {
		$nameStr = ($name) ? ' name="'.$name.'"' : '';
		$valueStr = ($value) ? ' value="'.$value.'"' : '';
		$classStr = ($class) ? ' class="'.$class.'"' : '';
		$idStr = ($id) ? ' id="'.$id.'"' : '';
		$sizeStr = ($size) ? ' size="'.$size.'"' : '';
		
		$str = '<input type="text"'.$nameStr.$valueStr.$classStr.$idStr.$sizeStr.' />';
		return $str;
	}
	function formPassword($name = null, $value = null, $size = null, $class = null, $id = null) {
		$nameStr = ($name) ? ' name="'.$name.'"' : '';
		$valueStr = ($value) ? ' value="'.$value.'"' : '';
		$classStr = ($class) ? ' class="'.$class.'"' : '';
		$idStr = ($id) ? ' id="'.$idStr.'"' : '';
		$sizeStr = ($size) ? ' size="'.$size.'"' : '';
		
		$str = '<input type="password"'.$nameStr.$valueStr.$classStr.$idStr.$sizeStr.' />';
		return $str;
	}
	function formHidden($name = null, $value = null, $class = null, $id = null) {
		$nameStr = ($name) ? ' name="'.$name.'"' : '';
		$valueStr = ($value) ? ' value="'.$value.'"' : '';
		$classStr = ($class) ? ' class="'.$class.'"' : '';
		$idStr = ($id) ? ' id="'.$idStr.'"' : '';
		
		$str = '<input type="hidden"'.$nameStr.$valueStr.$classStr.$idStr.' />';
		return $str;
	}
	
	function formSelect($name = null, $values = array(), $selected = null, $class = null, $id = null) {
		$nameStr = ($name) ? ' name="'.$name.'"' : '';
		$classStr = ($class) ? ' class="'.$class.'"' : '';
		$idStr = ($id) ? ' id="'.$idStr.'"' : '';
		$str = '<select'.$nameStr.$classStr.$idStr.'>';
		foreach($values as $k=>$v) {
			$select = ($selected==$k) ? ' selected="SELECTED"' : '';
			$str.= '<option value="'.$k.'"'.$select.'>'.$v.'</option>';
		}
		$str.= '</select>';
		return $str;
	}
	
	function formCheckbox($name = null, $values = array(), $checked = array(), $class = null, $id = null) {
		
	}
	
	function formRadio($name = null, $values = array(), $checked = null, $class = null, $id = null) {
		
	}
	
	function formTextarea($name = null, $cols = null, $rows = null, $value = null, $class = null, $id = null) {
		$nameStr = ($name) ? ' name="'.$name.'"' : '';
		$classStr = ($class) ? ' class="'.$class.'"' : '';
		$idStr = ($id) ? ' id="'.$idStr.'"' : '';
		$cols = ' cols="'.$cols.'"';
		$rows = ' rows="'.$rows.'"';
		$str = '<textarea'.$nameStr.$classStr.$idStr.$cols.$rows.'>';
		$str.= $value;
		$str.= '</textarea>';
		return $str;
	}
	
	function formSubmit($name = null, $value = null, $class = null, $id = null) {
		$nameStr = ($name) ? ' name="'.$name.'"' : '';
		$valueStr = ($value) ? ' value="'.$value.'"' : '';
		$classStr = ($class) ? ' class="'.$class.'"' : '';
		$idStr = ($id) ? ' id="'.$idStr.'"' : '';
		$str = '<input type="submit"'.$nameStr.$valueStr.$classStr.$idStr.' />';
		return $str;
		
	}
}
?>