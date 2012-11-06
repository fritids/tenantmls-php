<div style="width:<?php echo $params['WIDTH']; ?>;height:<?php echo $params['HEIGHT']; ?>;">
	<?php if (isset($xUserTmls)) : ?>
	<div class="plugin-body">
		<?php
		if (isset($params['AGENT_HEADER']) && $params['AGENT_HEADER']) {
			include_once(ROOT.DS.'application'.DS.'views'.DS.'api'.DS.'plugins'.DS.'agent_header.inc.php');
		}
		?>
		<div id="tenantfinder-mainbar">
			<span id="tenantfinder-results-view">
				<?php $href = ($params['JAVASCRIPT']) ? 'javascript:void(0)' : '#'; ?>
				<a href="<?php echo $href; ?>">List</a>
				<a href="<?php echo $href; ?>">Photo</a>
			</span>
			<ul id="tenantfinder-nav">
				<li id="active">My Tenants</li>
				<li>Find Tenants</li>
			</ul>
			<div class="clearfix-left"></div>
		</div>
		<div id="tenantfinder-body">
			<div id="tenantfinder-content">
			<?php
			$results_id = ($params['RESULTS']=='photo') ? 'results-photo' : 'results-list';
			$str = '<ul class="tenantfinder-results" id="'.$results_id.'">';
			
			foreach($user['Tenants'] as $tenant) {
				$str.= '<li>';
				
				if(!empty($tenant['Picture'])) {
					foreach($tenant['Picture'] as $picture) {
						if ($picture['default']==1)
							$picture = (isset($picture['sthmb'])) ? $picture['sthmb'] : BASE_PATH.'/images/ui-default-noPic.png';
					}
				} else
					$picture = BASE_PATH.'/images/ui-default-noPic.png';
				$str.= '<img src="'.$picture.'" />';

				$str.= '<div class="results-information">';
				$str.= '<h5><a href="'.BASE_PATH.'/tmls/'.$tenant['User']['tmls_number'].'/" target="_parent">'.$tenant['User']['first_name'].' '.$tenant['User']['middle_name'].' '.$tenant['User']['last_name'].'</a>';
				$str.= '<span class="overview-agency">';
				$str.= ($tenant['Privileges']['exclusive']) ? '(Exclusive - '.date("m/d/y",strtotime($tenant['Privileges']['logged'])).')' : '(Open - '.date("m/d/y",strtotime($tenant['Privileges']['logged'])).')';
				$str.= '</span></h5>';

				$str.= '<span class="overview-information">';
				$str.= '<b>$'.$tenant['User']['tenant_max_rent'].'</b>';
				$beds = ($tenant['User']['tenant_desired_beds']) ? $tenant['User']['tenant_desired_beds'].'br' : 'studio';
				$str.= ' for a <b>'.$beds.'</b>';

				if (count($tenant['DesiredLocale'])>0) {
					$randomPull = rand(0,count($tenant['DesiredLocale'])-1);
					$str.= ' in <b><a target="_blank" href="'.BASE_PATH.'/search/'.$tenant['DesiredLocale'][$randomPull]['city_state'].'-'.$tenant['DesiredLocale'][$randomPull]['city_name'].'-'.$tenant['DesiredLocale'][$randomPull]['city_zip'].'/">';
					$str.= $tenant['DesiredLocale'][$randomPull]['city_name'].', '.$tenant['DesiredLocale'][$randomPull]['city_state'].'</a>';
					if (count($tenant['DesiredLocale'])-1>1)
						$str.= '</b> and <b><a href="javascript:void(0)">'.(count($tenant['DesiredLocale'])-1).' other areas</a></b>';
					elseif(count($tenant['DesiredLocale'])-1==1)
						$str.= '</b> and <b><a href="javascript:void(0)">'.(count($tenant['DesiredLocale'])-1).' other area</a></b>';
					else
						$str.= '</b>';
				} else
					$str.= '';
				
				$str.= '</span>';
				$str.= '</div>';

				$str.= '<div class="clearfix-left"></div>';
				$str.= '</li>';	
			}

			$str.= '</ul>';
			echo $str;
			
			?>
			</div>
			<div id="tenantfinder-sidebar">
				<h5>Add Area</h5>
				<?php
				/*
				$theStates = returnStates();
				$theCities = performAction('locales','ajax_city',array('NY','php'));
				$formElements = array();
				$formElements['Locale State'] = array(null,$form->formSelect('locale-state',$theStates,'NY'));
				$formElements['Locale City'] = array(null,$form->formSelect('locale-city',$theCities));
				if (!$params['JAVASCRIPT']) 
					$formElements['submit'] = array(null,$form->formSubmit('submit','Add'));
				echo $form->create(array('tenantmls-add-locale','','POST'),$formElements,false,false);
				
				*/
					if (($params['JAVASCRIPT'])) {
						echo $form->formInput('locale',null,null,null,'locale');
					} else {
						$theStates = returnStates();
						$theCities = performAction('locales','ajax_city',array('NY','php'));
						
						echo $form->formSelect('locale-city',$theCities,'locale');
						echo $form->formSelect('locale-state',$theStates,'NY','locale');
					}
				
				?>
				<hr />
				<h5>My Areas</h5>
				<?php
				$str ='<ul id="tenantfinder-sidebar-areas">';
				if (!empty($user['CoverageLocale'])) {
					foreach($user['CoverageLocale'] as $locale) {
						$str.= '<li>'.$locale['city_name'].', '.$locale['city_state'].'</li>';
					}
				} else
					$str.= '<li>No areas</li>';
				$str.='</ul>';
				echo $str;
				?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<?php endif; ?>
	<?php if (!isset($xUserTmls)) : ?>
		<div class="plugin-body">
			<div id="register-mainbar">
				<ul id="register-nav">
					<li id="active">For Tenants</li>
					<li>For Agents</li>
				</ul>
				<div class="clearfix-left"></div>
			</div>
			<div id="register-sidebar">
				<h5>Have an account?</h5>
				<div id="register-signin">
					<?php
					$formElements = array();
					$formElements['Email'] = array('<label>Email:</label> ',$form->formInput('email'));
					$formElements['Password'] = array('<label>Password:</label> ',$form->formPassword('password'));
					$formElements['submit'] = array(null,$form->formSubmit('submit','Sign In'));
					echo $form->create(array('tenantmls-signin','','POST'),$formElements,false,true);
					?>
				</div>
			</div>	
		</div>
	<?php endif; ?>
</div>
<?php #printr($user); ?>
