<?php
session_start();
require_once( 'Facebook/autoload.php' );
 
$fb = new Facebook\Facebook([
  'app_id' => '644534512422310',
  'app_secret' => '26f02ddf3fcba817420c32bed87ce610',
  'default_graph_version' => 'v2.10',
]);
 
$helper = $fb->getRedirectLoginHelper();
 
$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
$loginUrl = $helper->getLoginUrl('localhost/rtCamp/callback.php', $permissions);
header("location: ".$loginUrl);
 
?>