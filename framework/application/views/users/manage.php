<div class="ui-dashboard-container">
	<ul id="ui-manage-list">
	<?php
		$str = '';
		foreach($manage['Request'] as $request) {
			$str.= '<li>';
			if (count($request['Picture'])>0) {
				foreach($request['Picture'] as $picture) {
					if ($picture['default']==1)
						$str.= '<img src="'.$picture['thmb'].'" />';
				}
			} else {
				$str.= '<img src="'.BASE_PATH . '/images/ui-default-noPic.png" />';
			}
			$str.= '<h3>'.$request['User']['first_name'].' '.$request['User']['middle_name'].' '.$request['User']['last_name'].'</h3>';
			if ($request['User']['user_type']=='Agent')
				$str.= '<h5>Licensed Real Estate '.$request['User']['agent_status'].'</h5>';
			else
				$str.= '<h5>Tenant</h5>';
			$onclick = 'onclick="open_menu(\'/requests/respond/'.$request['Request']['id'].'\',\'respond\')"';
			$str.= '<a class="button" '.$onclick.' href="javascript:void(0)" id="respondUser">Respond</a><div id="respond-dialog"></div>';
			$str.= '</li>';
		}
		$which = ($manage['User']['user_type']=='Agent') ? 'Tenants' : 'Agents';
		foreach($manage[$which] as $user) {
			$str.= '<li>';
			if (count($user['Picture'])>0) {
				foreach($user['Picture'] as $picture) {
					if ($picture['default']==1)
						$str.= '<img src="'.$picture['thmb'].'" />';
				}
			} else {
				$str.= '<img src="'.BASE_PATH . '/images/ui-default-noPic.png" />';
			}
			$str.= '<h3>'.$user['User']['first_name'].' '.$user['User']['middle_name'].' '.$user['User']['last_name'].'</h3>';
			$str.= '<h5>';
			$str.= ($user['User']['user_type']=='Agent') ? 'Licensed Real Estate '.$user['User']['agent_status'] : 'Tenant';
			$str.= '</h5>';
			$str.= '<span id="ui-manage-links">';
			$str.= '<a href="'.BASE_PATH.'/tmls/'.$user['User']['tmls_number'].'" class="e">View</a> - ';
			$str.= '<a href="#">Share</a> - ';
			$str.= '<a href="#">Message</a> - ';
			$str.= '<a href="#">Options</a>';
			$str.= '</span>';
			$str.= '</li>';
		}
		echo $str;
	?>
	</ul>
</div>
<?php printr($manage); ?>