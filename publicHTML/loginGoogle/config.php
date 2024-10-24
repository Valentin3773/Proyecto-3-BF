<?php

//start session on web page
session_start();

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('957659811171-f67e2adiua15ske62tecu8q0kf3m8fup.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://clinica-local.ddns.net/Proyecto-3-BF/publicHTML/loginGoogle/index.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');

?>