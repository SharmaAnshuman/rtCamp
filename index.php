
<?php
session_start();
if(isset($_GET["logout"])) {
    session_destroy();
    header("Location: index.php");
}
 $_SESSION["APPID"]="644534512422310";
 $_SESSION["APPSID"]="26f02ddf3fcba817420c32bed87ce610";
 $_SESSION["VERID"]="v2.10";
?>
<!DOCTYPE html>
    <html>
      <head>
        <title>Anshuman Sharma</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
      </head>
      <body>
          <!--Title start -->          
        <div class="navbar">
            <div class="navbar-inner">
              <a class="brand" href="#">rtCamp</a>
              <ul class="nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="https://www.ashusharma.com/portfolio/">Portfolio</a></li>
                <li><a href="https://www.ashusharma.com/">About me</a></li>
                  <?php
				if(isset($_SESSION['fb']))
				{
					?><li><a href="https://www.ashusharma.com/rtCamp/index.php?logout=t">Logout</a></li><?php
				}
				?>
              </ul>
            </div>
        </div>
        <!--Title end -->          
          <div class="container-fluid">
            <div class="row-fluid">
              <div class="span2">
                Anshuman Sharma
              </div>
              <div class="span10">
                  <a class="btn btn-primary" href="loginWithFacebook.php" >Login with Facebook</a>
              </div>
            </div>
          </div>
          
          <script src="./js/jquery.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
      </body>
    </html>
