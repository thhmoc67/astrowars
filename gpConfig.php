 
<?php           //define Google Project Client ID , Client Secret , Callback URL
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '1085860202448-ekmeuhvmfa12bnmpt4mj2i4rfr2ab4fb.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'DbR5HKbksnUSrXdV13e38Oo1'; //Google client secret
$redirectURL = 'http://astrowars.in/'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('astrowars');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>