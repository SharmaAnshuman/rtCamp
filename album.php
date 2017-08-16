<?php
session_start();
?>
<!DOCTYPE html>
    <html>
      <head>
        <title>Anshuman Sharma</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="./css/jquery.bxslider.css" rel="stylesheet" />
      </head>
      <body>
          <!-- Title start-->
          <div class="navbar">
            <div class="navbar-inner">
              <a class="brand" href="#">rtCamp</a>
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
        $albumID = $_GET['token'];
        
            $res = $fb->get('/'.$albumID.'/photos?fields=picture,name,height,width,images', $accessToken);
            ?>
          <div class='span12'>
              <center>
               <ul class="bxslider">
            <?php
            
            foreach($res->getDecodedBody()["data"] as $photo) 
            {
                echo '<li><img class="d-block img-fluid" src="'.$photo["images"][0]["source"].'"/></li>';
            } 
            ?>
               </ul>
              </center>
          </div>
                

          
<!-- jQuery library -->
<script src="./js/jquery-3.1.1.min.js"></script>
<!-- bxSlider Javascript file -->
<script src="./js/jquery.bxslider.js"></script>
<script>
	$(document).ready(function(){
		$('.bxslider').bxSlider({
			mode: 'horizontal',
			: 1,
			slideMargin: 40,
			infiniteLoop: true,
			slideWidth: 660,
			minSlides: 3,
			maxSlides: 3,
			spemoveSlidesed: 800,
		});
	});
</script>
