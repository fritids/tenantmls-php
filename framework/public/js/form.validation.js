/**
* Getting the closest parent with the given tag name.
*/
function getParentByTagName(obj, tag)
{
	var obj_parent = obj.parentNode;
	if (!obj_parent) return false;
	if (obj_parent.tagName.toLowerCase() == tag) return obj_parent;
	else return getParentByTagName(obj_parent, tag);
}

function validate(){
	var err = []
	// Check in reverse order in order to focus proper field
    if(document.register.rent.value == 0) {
    	var obj = document.register.rent;
    	obj.focus();
    	obj.parentNode.parentNode.style.color = '#BC0003';
    	err.push(obj);
    }
	if(document.register.locale.value == "") {
    	var obj = document.register.locale;
    	obj.focus();
    	obj.parentNode.parentNode.style.color = '#BC0003';
    	err.push(obj);
    }
	if(document.register.email_phone.value == "") {
    	var obj = document.register.email_phone;
    	obj.focus();
    	obj.parentNode.parentNode.style.color = '#BC0003';
    	err.push(obj);
    }
    if(document.register.name.value == "") {
    	var obj = document.register.name;
    	obj.focus();
    	obj.parentNode.parentNode.style.color = '#BC0003';
    	err.push(obj);
    }
    
    if (err.length>0)
    	return false;
    else
    	return true;
    
}