<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
    <?php
    echo $html->includeJs('source/tmls.sdk');
	$js = '';
	foreach($buildJs as $src) {
		$js.= $html->includeJs($src);
	}
	echo $js;
	$css = '';
	foreach($buildCss as $src) {
		$css.= $html->includeCss($src);
	}
	echo $css;
    ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (isset($tmls_title)) ? $tmls_title.' | ' : ''; ?>TenantMLS</title>
</head>
<body>
	<div id="tmls-ui-header">
		<div class="tmls-ui-container">
			<ul class="tmls-assorted-links">
				<li><a href="<?php echo BASE_PATH; ?>">Home</a></li>
				<?php if ($logged_in) : ?>
					<li><a href="#">Messages</a></li>
					<li><a href="#">Settings</a></li>
					<li><a href="<?php echo BASE_PATH.'/signout'; ?>">Sign Out</a></li>
				<?php endif; ?>
				<?php if (!$logged_in) : ?>
					<li><a href="<?php echo BASE_PATH.'/signin'; ?>">Sign In</a></li>
				<?php endif; ?>
			</ul>
			<a href="<?php echo BASE_PATH; ?>" id="tmls-logo-top"<?php echo ($logged_in) ? ' class="a"' : ''; ?>></a>
		</div>
	</div>
	<div class="tmls-ui-container">
		<div id="tmls-ui-sidebar-right">
			<?php if ($logged_in) : ?>
				<div class="tmls-user-menu">
					<a href="<?php echo BASE_PATH.'/users/profile/'.$tmls_user['User']['tmls_number']; ?>" class="a tmls-user-menu-content">
						<?php
						if ($tmls_user['User']['user_type']=='Agent') {
							$subtext = ($tmls_user['User']['agent_agency_name']) ? $tmls_user['User']['agent_agency_name']:'No Agency';
						} else {
							$subtext = 'Tenant';
						}

						if (count($tmls_user['Picture']) > 0) {
							foreach ($tmls_user['Picture'] as $picture) {
								if ($picture['default'])
									echo '<img src="' . $picture['thmb'] . '" />';
							}
		
						} else {
							echo '<img src="' . BASE_PATH . '/images/ui-default-noPic.png">';
						}
						echo '<span id="my-chat-name">'.$tmls_user['User']['first_name'],' ',$tmls_user['User']['middle_name'],' ',$tmls_user['User']['last_name'].'</span>';
						echo '<span class="subtext">',$subtext,'</span>';
						echo '<div class="clearfix-left"></div>';
						?>
					</a>
				</div>
				<hr />
				<div id="tmls-chat-roster">
					<span class="roster-title">Agents</span>
					<ul id="group-Agents"></ul>
					<span class="roster-title">Tenants</span>
					<ul id="group-Tenants"></ul>
				</div>
				<?php endif; if (!$logged_in) : ?>

				<?php endif; ?>
				<?php
					/*
					 *	Sidebar for sponsored ads / maps / etc. whatever really
					 */
				?>
		</div>
	</div>