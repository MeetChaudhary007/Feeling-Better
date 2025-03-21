<?php
require 'vendor/autoload.php';

# Add your client ID and Secret
$client_id = "366078561402-nrp44nk80122c0visjbret449ugsk36m.apps.googleusercontent.com";
$client_secret = "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"; 

$client = new Google\Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);

# redirection location is the path to login.php
$redirect_uri = 'http://localhost/medion/glogin.php';
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");