<?php
session_start();
require_once './Facebook/autoload.php';

        $fb = new Facebook\Facebook([
          'app_id' => $_SESSION['APPID'],
          'app_secret' => $_SESSION['APPSID'],
          'default_graph_version' => $_SESSION['VERID'],
          ]);
        $helper = $fb->getRedirectLoginHelper();
        $accessToken = $helper->getAccessToken();
        $_SESSION["fb"]= $accessToken->getValue();
        header("Location: home.php");
?>
        