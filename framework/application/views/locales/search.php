<?php echo $html->includeJs('ui.search'); ?>
	<div class="tmls-ui-search">
		<input type="text" name="locale" id="locale" value="<?php echo str_replace('-',' ',str_replace('--',', ',$query)); ?>" />
		<input type="submit" name="search" id="submit" value="" />
	</div>
	<div id="ui-search-sidebar-container">
		<span class="ui-search-sidebar-title">
			Housing Type
		</span>
		<ul class="ui-search-sidebar-filters">
			<li>
				<input type="checkbox" name="wholeHouse" value="true" /> Whole house
			</li>
			<li>
				<input type="checkbox" name="apartment" value="true" /> Apartment
			</li>
		</ul>
		
		<span class="ui-search-sidebar-title">
			Pets
		</span>
		<ul class="ui-search-sidebar-filters">
			<li>
				<input type="checkbox" name="dogsOK" value="true" /> Has dog
			</li>
			<li>
				<input type="checkbox" name="catsOK" value="true" /> Has cat
			</li>
			<li>
				<input type="checkbox" name="otherOK" value="true" /> Has other pets
			</li>
		</ul>
		
		<span class="ui-search-sidebar-title">
			Program
		</span>
		<ul class="ui-search-sidebar-filters">
			<li>
				<input type="checkbox" name="program" value="false" /> None
			</li>
			<li>
				<input type="checkbox" name="programSec8" value="true" /> Section 8
			</li>
			<li>
				<input type="checkbox" name="programBAH" value="true" /> BAH
			</li>
			<li>
				<input type="checkbox" name="programOther" value="true" /> Other
			</li>
		</ul>
		
		<span class="ui-search-sidebar-title">
			Credit
		</span>
		<ul class="ui-search-sidebar-filters">
			<li>
				<input type="checkbox" name="credit" value="false" /> N/A
			</li>
			<li>
				<input type="checkbox" name="creditMarginal" value="true" /> Marginal
			</li>
			<li>
				<input type="checkbox" name="creditGood" value="true" /> Good
			</li>
			<li>
				<input type="checkbox" name="creditExcellent" value="true" /> Excellent
			</li>
		</ul>
		
		<span class="ui-search-sidebar-title">
			Verification
		</span>
		<ul class="ui-search-sidebar-filters">
			<li>
				<input type="checkbox" name="verifyCredit" value="true" /> Credit Report
			</li>
			<li>
				<input type="checkbox" name="verifyBackground" value="true" /> Background Check
			</li>
			<li>
				<input type="checkbox" name="verifyIncome" value="true" /> Proof of Income
			</li>
		</ul>
	</div>
	
	<div id="ui-search-results-container">
		<div id="ui-search-results-sort-by">
			<span id="sort-by-text">Sort by:</span>
			<select name="results-sort-by">
				<option value="best-match">Best Match</option>
			</select>
		</div>
		<ul id="ui-search-results">
			<?php
			$str = '';
			foreach($data as $result) {
				$str.= '<li>';
				// Picture
            	if (count($result['Picture'])>0) {
            		foreach($result['Picture'] as $picture) {
            			if ($picture['Picture']['default'])
							$str.= '<div class="tenant-picture-frame"><img src="'.BASE_PATH.'/uploads/'.$result['User']['id'].'/'.$picture['Picture']['file_name'].'_thumb.jpg" /></div>';
            		}
            	} else {
                	$str.= '<div class="tenant-picture-frame"><img src="'.BASE_PATH.'/images/ui-default-noPic.png"></div>';
            	}
				// Tenant Information
				$str.= '<div class="result-information">';
					// Name
					$name = '';
		    		if ($result['User']['first_name'])
						$name.= $result['User']['first_name'].' ';
					if ($result['User']['middle_name'])
						$name.= $result['User']['middle_name'].' ';
					if ($result['User']['last_name'])
						$name.= $result['User']['last_name'];
					$str.= '<a href="'.BASE_PATH.'/tmls/'.$result['User']['tmls_number'].'" class="search-result-title e">'.$name.'</a>';
					// Verified
					$str.='<span class="verified">Credit verified</span><span class="verified">Income verified</span>';
					// Looking for...
					$canAfford = $result['User']['tenant_max_rent'];
					if (count($result['DesiredLocale'])>0) {
						$randomPull = rand(0,count($result['DesiredLocale'])-1);
						$hlocations = ' in <b><a href="'.BASE_PATH.'/search/'.$result['DesiredLocale'][$randomPull]['city_state'].'-'.$result['DesiredLocale'][$randomPull]['city_name'].'-'.$result['DesiredLocale'][$randomPull]['city_zip'].'/">';
						$hlocations.= $result['DesiredLocale'][$randomPull]['city_name'].', '.$result['DesiredLocale'][$randomPull]['city_state'].'</a>';
						if (count($result['DesiredLocale'])-1>1)
							$hlocations.= '</b> and <b><a href="javascript:void(0)">'.(count($result['DesiredLocale'])-1).' other areas</a></b>';
						elseif(count($result['DesiredLocale'])-1==1)
							$hlocations.= '</b> and <b><a href="javascript:void(0)">'.(count($result['DesiredLocale'])-1).' other area</a></b>';
						else
							$hlocations.= '</b>';
					} else
						$hlocations = '';
					$desiredHousing = explode(',',$result['User']['tenant_desired_housing']);
					$desiredHousingChoices = (in_array(1,$desiredHousing)) ? 'house/' : '';
					$desiredHousingChoices.= (in_array(2,$desiredHousing)) ? 'apartment/' : '';
					$desiredHousingChoices = substr($desiredHousingChoices,0,strlen($desiredHousingChoices)-1);
                    $str.= '<span class="result-summary"><b>$'.$canAfford.'</b> for a <b>'.$result['User']['tenant_desired_beds'].' bedroom '.$desiredHousingChoices.'</b> '.$hlocations.'</span>';
				$str.= '</div>';
				// Agent Information
				if (count($result['Agents'])>0) {
					foreach($result['Agents'] as $agent) {
						// agent picture
	                	if (count($agent['Picture'])>0) {
	                		foreach($agent['Picture'] as $agent_picture) {
	                			if ($agent_picture['default'])
									$str.= '<div class="agent-picture-frame"><img src="'.$agent_picture['thmb'].'" /></div>';
	                		}
	                	} else {
		                	$str.= '<div class="agent-picture-frame"><img src="'.BASE_PATH.'/images/ui-default-noPic.png"></div>';
	                	}
	                	// agent name
	                	$name = '';
			    		if ($agent['User']['first_name'])
							$name.= $agent['User']['first_name'].' ';
						if ($agent['User']['middle_name'])
							$name.= $agent['User']['middle_name'].' ';
						if ($agent['User']['last_name'])
							$name.= $agent['User']['last_name'];
						$str.= '<div class="result-agent-information">';
	                	$str.= '<span class="agent-result-title"><a class="e" href="'.BASE_PATH.'/tmls/'.$agent['User']['tmls_number'].'/">'.$name.'</a></span>';
						$str.= '<h5>'.$agent['User']['agent_agency_name'].'</h5>';
						$str.= '</div>';
					}
				}
				$str.= '<div class="clear-left"></div>';
				$str.= '</li>';
			}
			echo $str;
			?>
		</ul>
		<div class="clear"></div>
	</div>