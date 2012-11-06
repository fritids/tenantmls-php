<?php
require_once (ROOT . DS . 'config' . DS . 'config.php');
require_once (ROOT . DS . 'config' . DS . 'routing.php');
require_once (ROOT . DS . 'config' . DS . 'inflection.php');
require_once (ROOT . DS . 'library' . DS . 'shared.php');

// RestRequest class - used for making API calls
require_once (ROOT . DS . 'library' . DS . 'restrequest.class.php');

// Set the session save path
session_save_path(ROOT.DS.'tmp'.DS.'sessions');

// OAuth v2.0 draft - not yet fully implemented - BRAND new technology
/*
require_once (ROOT . DS . 'library' . DS . 'oauth2' . DS . 'OAuth2.inc');
require_once (ROOT . DS . 'library' . DS . 'oauth2' . DS . 'PDOOAuth2.inc');
require_once (ROOT . DS . 'library' . DS . 'oauth2' . DS . 'OAuth2Client.inc');
require_once (ROOT . DS . 'library' . DS . 'oauth2' . DS . 'OAuth2Exception.inc');
*/