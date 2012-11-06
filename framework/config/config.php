<?php
/*
 * API Configuration
 */
define('API_ID','74632846563923036452742');
define('API_SECRET','^4hfu3jfjdsiqU#-w3T%G%_(gHDIE)_F&djGUIDIUrh$');

/*
 * Jabber (remains constant unless we move servers)
 */
define('JABBER_SERVER','jabber.tenantmls.com');
define('JABBER_DEFAULT_PORT', 5222);
# Stream to be used when connecting to Jabber
# default: messages -> users will chat and exchange conversations over messages
define('JABBER_DEFAULT_RESOURCE', 'messages');
# Used for when making HTML transactions such as updating information
# Change secret: http://jabber.tenantmls.com:9090/plugins/userservice/user-service.jsp
define('JABBER_SECRET','69ead038803a9ec276303cb36d984b0c3f245ce1'); #SHA-1 hash of "FREEtmls69+"

/*
 * Search Configuration
 */
define('PAGINATE_PAGE_LIMIT',20);
define('PAGINATE_RESULTS_LIMIT',15);

/*
 * TenantMLS Configuation
 */
define('MAX_FILE_SIZE',1000000);
define('MAX_PICTURE_COUNT',4);
define('MAX_REVIEW_STARS',5);
/*
 * Load server variables
 */
require_once('serverconfig.php');