<?php
session_start();
require_once __DIR__ . '\vendor\autoload.php';

function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

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
if (file_exists($credentialsPath)) {
  $accessToken = json_decode(file_get_contents($credentialsPath), true);
} else {
    if(!isset($_GET["auth_code"])) {
        $authUrl = $client->createAuthUrl();
        echo '<form>Authentication code : <input type="text" name="auth_code" placeholder="Authentication code" /> <input type="submit" value="Submit" /></form>';    
        echo '<a target="_blank" href="'.$authUrl.'">Click here to generate your authentication code</a>';
        exit(0);
    } else {
        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET["auth_code"]);

        // Store the credentials to disk.
        if(!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
    }
}

if(isset($accessToken)) {      
    $client->setAccessToken($accessToken);

    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    }
    $service = new Google_Service_Drive($client);
    
    //// APP USES
    
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => "facebook_".$_SESSION["fbusernm"]."_albums",
        'mimeType' => 'application/vnd.google-apps.folder'));
    $fbUserFolder = $driveService->files->create($fileMetadata, array(
        'fields' => 'id'));

    
    $file = new Google_Service_Drive_DriveFile();
    
    $file->setName('album.php');
    $file->setDescription('A test document');
    $file->setMimeType('text/plain');
    $file->setParents(array($fbUserFolder->id));

    $data = file_get_contents('album.php');

    $createdFile = $service->files->create($file, array(
          'data' => $data,
          'mimeType' => 'text/plain',
          'uploadType' => 'multipart'
        ));

    print_r($createdFile);
    
    //// APP USES END
    
} else {
    echo "Something wrong happened !!";
}