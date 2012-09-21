<?php

$config['application_name'] = 'DevFest Jakarta 1';
$config['client_id'] = '292106599527.apps.googleusercontent.com';
$config['client_secret'] = 'W5oQlAYlHiglzBP5qJOr0T7X';
$config['redirect_uri'] = 'http://thinkwebdev1.net/devfest/index.php';
$config['api_key'] = 'AIzaSyBccUVDzfmXdEGtv3hPa5bLVnE6i_X4vG0';
$config['access_type'] = 'offline';
$config['scopes'] = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/analytics.readonly';

require './google-api-php-client/src/apiClient.php';
require './google-api-php-client/src/contrib/apiOauth2Service.php';
require './google-api-php-client/src/contrib/apiAnalyticsService.php';

$googleauth = new apiClient();
$googleauth->setApplicationName($config['application_name']);
$googleauth->setClientId($config['client_id']);
$googleauth->setClientSecret($config['client_secret']);
$googleauth->setRedirectUri($config['redirect_uri']);
$googleauth->setDeveloperKey($config['api_key']);
$googleauth->setAccessType($config['access_type']);
$googleauth->setScopes($config['scopes']);

date_default_timezone_set('Asia/Jakarta');

$userdata = new apiOauth2Service($googleauth);
