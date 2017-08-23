<?php
session_start();
if(isset($_REQUEST["msg"]))
{
        echo "<script>alert('".$_REQUEST["msg"]."');</script>";
}
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
              $link="https://www.ashusharma.com/rtCamp/downloadSelected.php?test=1";
              $linkAll ="https://www.ashusharma.com/rtCamp/downloadAll.php?test=1";
              $link1="https://www.ashusharma.com/rtCamp/drive.php?test=1";
              for($i=0;$i<sizeof($x["data"]);$i++)
              {
                  ?><li class="span3">
                      <div class="thumbnail">
                  <?php
                  $res = $fb->get('/'.$x["data"][$i]["id"].'/photos?fields=picture,name', $accessToken);
                  $photo = $res->getDecodedBody()["data"];
                  echo '<img src="'.$photo[0]["picture"].'" >';
                  $link.="&c1[]=".$x["data"][$i]["id"];
                  $linkAll.="&c1[]=".$x["data"][$i]["id"];
                  $link1.="&c1[]=".$x["data"][$i]["id"];
                  
                  ?>
                          <div class="caption">
                              
                    <h5><input type="checkbox" name="c1[]" value="<?= $x["data"][$i]["id"] ?>" class="checkbox"><?php echo " ".$x["data"][$i]["name"].""; ?></h5>      
                    <script>
                    $(document).ready(function(){
                          $('mybtn<?= $i ?>.download').click(function(){
                                alert("Please wait your albums download soon..");
                                $('mybtn<?= $i ?>.download').html("<img src='loading.gif' height='30px' width='30px'/>");
                             $.get("downloadSelected.php?click=single&c1[]=<?= $x["data"][$i]["id"] ?>", function(data, status){
                                     window.location.href = data;
                                     $('mybtn<?= $i ?>.download').html("<small>Download Again!</small>");
                                }); 
                          });
                        });
                    </script>

                    <p><a href="album.php?token=<?= $x["data"][$i]["id"] ?>" class="btn btn-primary">View</a> 
                    <mybtn<?= $i?> class="btn download">Download</mybtn<?= $i ?>>
                    <a href="drive.php?c1[]=<?= $x["data"][$i]["id"] ?>">Move</a> </p>
                  </div>
                </div>
                  </li>

              <?php
              }
              ?>  
            </ul>
          
              <input name="action" type="submit" class="btn btn-success" value="Download Selected Albums!" onclick="alert('Click Ok and Please Wait..')" />
              <input name="action" type="submit" class="btn btn-success" value="Move Selected Albums!" onclick="alert('Click Ok and Please Wait..')"/> <!-- Naam badlu to downloadSelected.php ma pan id else ma change krvu -->
              <a href="<?= $linkAll?>" class="btn btn-success" onclick="alert('Click Ok and Please Wait..')">Download All</a>
              <a href="<?=$link1?>" class="btn btn-success" onclick="alert('Click Ok and Please Wait..')">Move All</a>
            </form>
    <script src="/js/jquery.min.js"></script>    
    
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>
