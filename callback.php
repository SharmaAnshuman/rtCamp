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
        /*
        $res = $fb->get('/me/albums', $accessToken->getValue());
        echo "<pre>";
        $x = $res->getDecodedBody();
        //print_r($x["data"]);
        for($i=0;$i<sizeof($x["data"]);$i++)
        {
                echo "<center><h1>".$x["data"][$i]["name"]."</h1></center>";
            $res = $fb->get('/'.$x["data"][$i]["id"].'/photos?fields=picture,name', $accessToken->getValue());
            foreach($res->getDecodedBody()["data"] as $photo) 
            {
                echo '<img src="'.$photo["picture"].'"/><small>__________</small>';
            } 
            echo "<hr>";
        }
        
        //$me = $res->getGraphUser()->asArray();
        //$x = $me['albums'][0]['id'];
        //echo $x;
       /* */
        
        ?>
        