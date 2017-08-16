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
        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      </head>
      <body>
          <!-- Title start-->
         <div class="navbar">
            <div class="navbar-inner">
              <a class="brand" href="#">rtCamp</a>
              <ul class="nav">
                <li class="active"><a href="http://www.ashusharma.com/rtCamp/">Home</a></li>
                <li><a href="http://www.ashusharma.com/portfolio/">Portfolio</a></li>
                <li><a href="http://www.ashusharma.com/">About me</a></li>
              </ul>
            </div>
        </div>
          <!-- Title End -->

        
          <form action="downloadSelected.php">
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
                  ?>
                          <div class="caption">
                    <h5><input type="checkbox" name="c1[]" value="<?= $x["data"][$i]["id"] ?>" class="checkbox"><?php echo " ".$x["data"][$i]["name"].""; ?></h5>      
                    <script>
                    $(document).ready(function(){
                          $('mybtn<?= $i ?>').click(function(){
                                $('mybtn<?= $i ?>').html("<img src='loading.gif' height='30px' width='30px'/>");
                             $.get("downloadOne.php?token=<?= $x["data"][$i]["id"] ?>", function(data, status){
                                     window.location.href = data;
                                     $('mybtn<?= $i ?>').html("<small>Download Againe!</small>");
                                }); 
                          });
                        });
                    </script>

                    <p><a href="album.php?token=<?= $x["data"][$i]["id"] ?>" class="btn btn-primary">View</a> <mybtn<?= $i?> class="btn">Download</mybtn></p>
                  </div>
                </div>
                  </li>

              <?php
              }
              ?>  
            </ul>
          
              <input type="submit" class="btn btn-success" value="Download Selected Albums!"/>
            </form>
    <script src="/js/jquery.min.js"></script>    
    
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>