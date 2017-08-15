<?php
session_start();
require_once './Facebook/autoload.php';

        $fb = new Facebook\Facebook([
          'app_id' => $_SESSION['APPID'],
          'app_secret' => $_SESSION['APPSID'],
          'default_graph_version' => $_SESSION['VERID'],
          ]);
        $accessToken = $_SESSION["fb"];        
        $albumID = $_GET['token'];
        
            $res = $fb->get('/'.$albumID.'/photos?fields=picture,name,height,width,images,id', $accessToken);
            if(!(is_dir("./fbDownload/$albumID/")))
                mkdir("./fbDownload/$albumID/",0777, true);
            
            foreach($res->getDecodedBody()["data"] as $photo) 
            {
                file_put_contents("./fbDownload/$albumID/".$photo['id'].".jpg", file_get_contents($photo["images"][0]["source"]));
                $zip = new ZipArchive;
                if ($zip->open(getcwd() . '/'.$albumID.'.zip', ZipArchive::CREATE) === TRUE) 
                {
                    $zip->addFile(getcwd() . "./fbDownload/$albumID/".$photo['id'].".jpg", "fbDownload/$albumID/".$photo['id'].".jpg");
                    $zip->close();
                    echo 'ok';
                }
                else 
                {
                    echo 'failed';
                }
            }
            
            ?>
<a href="<?= $albumID.".zip" ?>">Download All Albums</a>
            
                