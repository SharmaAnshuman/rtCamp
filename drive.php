<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/Facebook/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => $_SESSION['APPID'],
  'app_secret' => $_SESSION['APPSID'],
  'default_graph_version' => $_SESSION['VERID'],
  ]);

$fbAccessToken = isset($_SESSION["fb"]) ? $_SESSION["fb"] : null;        

function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

function userDirCreateOrFind($service, $name) {
    $pageToken = null;
    do {
      $response = $service->files->listFiles(array(
        'q' => "mimeType='application/vnd.google-apps.folder' and name='$name'",
        'spaces' => 'drive',
        'pageToken' => $pageToken,
        'fields' => 'nextPageToken, files(id, name)',
      ));
      foreach ($response->files as $file) {
          //e"Setting $file->name as $file->id <br/>";
          return $file->id;
      }
    } while ($pageToken != null);
        //echo "Creating $name<br/>";
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder'));
        return $service->files->create($fileMetadata, array(
            'fields' => 'id'))->id;
}

function albumDirCreateOrFind($service, $name, $parentId) {
    $pageToken = null;
    do {
      $response = $service->files->listFiles(array(
        'q' => "mimeType='application/vnd.google-apps.folder' and name='$name' and ('$parentId' in parents)",
        'spaces' => 'drive',
        'pageToken' => $pageToken,
        'fields' => 'nextPageToken, files(id, name)',
      ));
      foreach ($response->files as $file) {
          //echo "SETTING $file->name as $file->id <br/>";
          return $file->id;
      }
    } while ($pageToken != null);
        //echo "CREATING $name with $parentId<br/>";
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder'));
        $fileMetadata->setParents(array($parentId));
        return $service->files->create($fileMetadata, array(
            'fields' => 'id'))->id;
}

function fileCreateOrSkip($service, $name, $albumId, $data) {
    
    $pageToken = null;
    do {
      $response = $service->files->listFiles(array(
        'q' => "mimeType='image/jpeg' and name='$name' and ('$albumId' in parents)",
        'spaces' => 'drive',
        'pageToken' => $pageToken,
        'fields' => 'nextPageToken, files(id, name)',
      ));
      foreach ($response->files as $file) {
          return $file->id;
      }
    } while ($pageToken != null);
    
    $file = new Google_Service_Drive_DriveFile();

    $file->setName($name);
    $file->setMimeType('image/jpeg');
    $file->setParents(array($albumId));

    $createdFile = $service->files->create($file, array(
          'data' => $data,
          'mimeType' => 'image/jpeg',
          'uploadType' => 'multipart'
        ));    
}

try {

    define('APPLICATION_NAME', 'Drive API PHP Quickstart');
    define('CREDENTIALS_PATH', '~/.credentials/drive-php-quickstart.json');
    define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');

    define('SCOPES', implode(' ', array(
      Google_Service_Drive::DRIVE_FILE)
    ));
    $client = new Google_Client();
    $client->setApplicationName(APPLICATION_NAME);
    $client->setScopes(SCOPES);
    $client->setAuthConfig(CLIENT_SECRET_PATH);
    $client->setAccessType('offline');
    //$client->setHttpClient(new GuzzleHttp\Client(['verify' => false]));
    $accessToken = null;
    $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
    if (isset($_SESSION["googleToken"])) {
      $accessToken = json_decode($_SESSION["googleToken"], true);
    } else {
        if(!isset($_GET["auth_code"])) {
            $authUrl = $client->createAuthUrl();
            echo '<form>Authentication code : <input type="text" name="auth_code" placeholder="Authentication code" /> <input type="submit" value="Submit" /></form>';    
            echo '<a target="_blank" href="'.$authUrl.'">Click here to generate your authentication code</a>';
            exit(0);
        } else {
            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($_GET["auth_code"]);

            $_SESSION["googleToken"] = json_encode($accessToken);
            // Store the credentials to disk.
//            if(!file_exists(dirname($credentialsPath))) {
//                mkdir(dirname($credentialsPath), 0700, true);
//            }
//            file_put_contents($credentialsPath, json_encode($accessToken));
        }
    }

    if(isset($accessToken)) {      
        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        }
        $service = new Google_Service_Drive($client);

        //// APP USES
        $fbUserFolderName = "facebook_".$_SESSION["fbusernm"]."_albums";
        $fbUserFolderId = userDirCreateOrFind($service, $fbUserFolderName);
        
        // album upload code
        
        $albumID = $_GET['c1'];
        $albumsMoved = "";
        foreach($albumID as $aId) {
            $res = $fb->get('/'.$aId.'', $fbAccessToken);
            $alnm =$res->getDecodedBody();
            $albumsMoved.=$alnm["name"].",";
            $albumFolderId = albumDirCreateOrFind($service, $alnm["name"], $fbUserFolderId);
            $res = $fb->get('/'.$aId.'/photos?fields=picture,name,height,width,images,id', $fbAccessToken);
            foreach($res->getDecodedBody()["data"] as $photo) {
                $photoName = $photo["id"];
                $photoUri = $photo["images"][0]["source"];
                $photoData = file_get_contents($photoUri);
                
                fileCreateOrSkip($service, $photoName, $albumFolderId, $photoData);
            }
        }
           
        //// APP USES END
       header("Location: home.php?msg=".$albumsMoved." albums moved successfully to GDrive.");
    } else {
        echo "Something wrong happened !!";
    }

} catch(Exception $ex) {
    echo $ex->getMessage();
}
