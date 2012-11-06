<?php

$routing = array(

	// Route all TMLS numbers
	// ex: http://www.tenantmls.com/tmls/1000000000
	//	=> http://www.tenantmls.com/users/view/1000000000
	'/tmls\/(.*?)\/(.*?)/' => 'users/index/profile/\1/\2',
	'/r\/(.*?)\/(.*?)\/(.*?)/'=> '\1/\2/\3',
	// Route all TMLS searches
	// ex: http://www.tenantmls.com/search/NY-Sayville-11782
	// ex: http://www.tenantmls.com/s/NY-Sayville-11782
	//	=> http://www.tenantmls.com/locales/search/NY-Sayville-11782
	#'/search\/(.*?)\/(.*?)/' => 'locales/search/\1/\2',
	/*
	 * Simplified URLs for user handling
	 */
	'/dashboard/' => 'users/index/dashboard',
	'/profile/' => 'users/index/profile',
	'/manage/' => 'users/index/manage',
	'/documents/' => 'users/index/documents',
	'/settings/' => 'users/index/settings',
	'/messages/' => 'users/index/messages',
	'/search\/(.*?)/' => 'users/index/search/\1',

    '^signin^'=>'sessions/signin',
    '^signout^'=>'sessions/signout'
    #'^s^'=>'locales/search'
);

$default['controller'] = 'users';
$default['action'] = 'index';