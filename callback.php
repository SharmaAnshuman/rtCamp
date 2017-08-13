<?php
    session_start();
    require_once( 'Facebook/autoload.php' );
    require_once( 'db.php' );

    $fb = new Facebook\Facebook([
      'app_id' => $_SESSION["APPID"],
      'app_secret' => $_SESSION["APPSID"],
      'default_graph_version' => $_SESSION["VERID"],
    ]);  
    
  $helper = $fb->getRedirectLoginHelper();  
  
    try 
    {  
        $accessToken = $helper->getAccessToken();  
    }
    catch(Facebook\Exceptions\FacebookResponseException $e) 
    {    
        echo 'Graph returned an error: ' . $e->getMessage();  
        exit;        
    } 
    catch(Facebook\Exceptions\FacebookSDKException $e) 
    {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();  
        exit;  
    }  
 
 
    try 
    {
      $response = $fb->get('/me?fields=id,name,email,first_name,last_name,gender,albums', $accessToken->getValue());
    } 
    catch(Facebook\Exceptions\FacebookResponseException $e) 
    {
      echo 'ERROR: Graph ' . $e->getMessage();
      exit;
    }
    catch(Facebook\Exceptions\FacebookSDKException $e) 
    {
      echo 'ERROR: validation fails ' . $e->getMessage();
      exit;
    }
    $me = $response->getGraphUser();
    
	try {
		$requestPicture = $fb->get('/me/picture?redirect=false&height=330',$accessToken); //getting user picture
		$requestProfile = $fb->get('/me',$accessToken); // getting basic info
                
		$picture = $requestPicture->getGraphUser();
		$profile = $requestProfile->getGraphUser();
                
            
                
               
                
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	
        
        
$albums = $fb->get('/me/albums', $accessToken);

print_r($albums);
/*
	$_SESSION["fbpic"]=  $picture['url'];
        $_SESSION['fbfname'] = $me->getFirstName();
        $_SESSION['fblname'] = $me->getLastName();
        $_SESSION['fbemail'] = $me->getEmail();
        $_SESSION['fbalbums'] = $albums;
        $_SESSION['token'] = $me->getProperty('albums');

        //header("Location: ./home.php");