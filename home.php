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
        ?>
         
      <ul class="thumbnails">
        <?php
        for($i=0;$i<sizeof($x["data"]);$i++)
        {
            ?><li class="span3">
                <div class="thumbnail">
            <?php
            $res = $fb->get('/'.$x["data"][$i]["id"].'/photos?fields=picture,name', $accessToken);
            $photo = $res->getDecodedBody()["data"];
            echo '<img src="'.$photo[0]["picture"].'" >';
            //echo "<h4>".$x["data"][$i]["name"]."</h4>";
            ?>
                    <div class="caption">
              <h5><?php echo "<h4>".$x["data"][$i]["name"]."</h4>"; ?></h5>      
              <p><a href="album.php?token=<?= $x["data"][$i]["id"] ?>" class="btn btn-primary">View</a> <a href="download.php?token=<?= $x["data"][$i]["id"] ?>" class="btn">Download</a></p>
            </div>
          </div>
            </li>
                    
        <?php
        }
        ?>  </ul>
    
         
    <script src="/js/jquery.min.js"></script>    
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>