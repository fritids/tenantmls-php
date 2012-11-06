<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    
    <?php
    echo $html->includeJs('source/tmls.sdk');
    if (!isset($buildJs)) {
        echo $html->includeJs('jquery-1.7.2.min');
		/*
		 * Jquery UI
		 */
		echo $html->includeJs('jquery-ui-1.8.22.custom.min');
		echo $html->includeJs('OpenLayers');
		echo $html->includeJs('generic');
        //echo $html->includeJs('ajax-locales');
		//echo $html->includeJs('ajax-save');
		echo $html->includeJs('ui.profile');
		echo $html->includeJs('ui.mainpage');
		//echo $html->includeJs('jquery.autocomplete');

		// XMPP Framework I'm going to need
		echo $html->includeJs('strophe.min');
		echo $html->includeJs('source/tmls.xmpp');
	} else {
		$js = '';
		foreach($buildJs as $src) {
			$js.= $html->includeJs($src);
		}
		echo $js;
	}

	if (isset($buildCss)) {
		$css = '';
		foreach($buildCss as $src) {
			$css.= $html->includeCss($src);
		}
		echo $css;
	} else {
		echo $html->includeCss('ui.xmpp');
		echo $html->includeCss('ui.framework');
	    echo $html->includeCss('ui.mainNav');
	    echo $html->includeCss('ui.sidebar');
	    echo $html->includeCss('ui.profile');
	    //echo $html->includeCss('ui.messages');
		echo $html->includeCss('ui.signin');
		echo $html->includeCss('OpenLayers');
		echo $html->includeCss('ui-tenantmls/jquery-ui-1.8.22.custom');
	}
    ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (isset($tmls_title)) ? $tmls_title.' | ' : ''; ?>TenantMLS</title>
</head>

<body>
	<div id="tmls-chat-roster"><ul></ul></div>
	<div id="tmls-chat-bar"><ul></ul></div>
	<div id="application">
	    <div id="ui-mainNav">
	        <div class="ui-container">
	            <div id="ui-mainNav-search">
	        		<form action="" method="post">
						<div id="ui-mainNav-search-bar-container">
							<a href="<?php echo BASE_PATH; ?>/search/" id="search-submit">Search</a>
						</div>
					</form>
	        	</div>
	        	<ul id="ui-mainNav-nav">
	                <?php
	                if ($logged_in) {
	                	/*
	                	$newMsgCount = 0;
						if (count($xUserData['Message'])>0) {
							foreach($xUserData['Message'] as $msg) {
								if ($msg['Message']['read']==0)
									$newMsgCount++;
							}
						}
						*/
						$newMsgCount = 0;
						$newMessages = ($newMsgCount) ? '<span id="alert-new-messages">'.$newMsgCount.'</span>' : '';
	                
	                    $dropDownElements = '<ul>';
						$dropDownElements.= '<li><a href="'.BASE_PATH.'/users/view/dashboard">Dashboard</a></li>';
						$dropDownElements.= '<li><a href="'.BASE_PATH.'/users/view/about_me">My Profile</a></li>';
						$dropDownElements.= '<li><a href="'.BASE_PATH.'/users/view/my_tenants">My Tenants</a></li>';
						$dropDownElements.= '<li><a href="'.BASE_PATH.'/messages/view">Messages</a></li>';
						$dropDownElements.= '<li><a href="'.BASE_PATH.'/users/view/settings">Settings</a></li>';
						$dropDownElements.= '<li><a href="'.BASE_PATH.'/signout">Sign Out</a></li>';
						$dropDownElements.= '</ul>';
	                    echo '<li class="open-dropdown">'.$newMessages.'<span>Hi, <a href="'.BASE_PATH.'/users/view/"><b class="blue">'.$tmls_user['User']['first_name'].' '.$tmls_user['User']['last_name'].'</b></a></span><div id="dropdown_menu" class="ui-dropdown-menu"><div class="ui-dropdown-menu-tip"></div>'.$dropDownElements.'</div></li>';
					} else
	                    echo '<li><a href="'.BASE_PATH.'/signin"><b class="blue">Sign In</b></a></li>';
	                ?>
	            </ul>
	            <a href="<?php echo (isset($xUserData)) ? BASE_PATH.'/users/view/dashboard' : BASE_PATH; ?>" id="ui-mainNav-logo"></a>
	            
	        </div>
	    </div>
	    <div class="ui-framework-topNav-spacer"></div>