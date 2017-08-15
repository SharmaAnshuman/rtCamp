<?php
session_start();
?>
<!DOCTYPE html>
    <html>
      <head>
        <title>Anshuman Sharma</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
      </head>
      <body>
          <!-- Title start-->
          <div class="navbar">
            <div class="navbar-inner">
              <a class="brand" href="index.php">rtCamp</a>
              <ul class="nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Portfolio</a></li>
                <li><a href="#">About me</a></li>
              </ul>
            </div>
        </div>
          <!-- Title End -->

        
          
<?php
require_once './Facebook/autoload.php';

        $fb = new Facebook\Facebook([
          'app_id' => $_SESSION['APPID'],
          'app_secret' => $_SESSION['APPSID'],
          'default_graph_version' => $_SESSION['VERID'],
          ]);
        $accessToken = $_SESSION["fb"];        
        $res = $fb->get('/me/albums', $accessToken);
        $x = $res->getDecodedBody();
        echo "<ul class='thumbnails'>";
        for($i=0;$i<sizeof($x["data"]);$i++)
        {
            
            echo "<li class='span3'>";
            echo "<a href='album.php?token=".$x["data"][$i]["id"]."' class='thumbnail'>";
            //echo "<center><h4>".$x["data"][$i]["name"]."</h4></center>";
            $res = $fb->get('/'.$x["data"][$i]["id"].'/photos?fields=picture,name', $accessToken);
            $photo = $res->getDecodedBody()["data"];
            echo '<img src="'.$photo[0]["picture"].'" height="50%" width="100%"></br>';
            echo "<center><h4>".$x["data"][$i]["name"]."</h4></center>";
            echo "</a>";
            echo "</li>";
            
        }
        echo "</ul>";
        
        
        
       
        ?>
       
         
         
         
    <script src="/js/jquery.min.js"></script>    
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>