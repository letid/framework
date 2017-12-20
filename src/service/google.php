<?php
namespace letId\service
{
  class google
  {
    const sessionToken = 'session_token';
    static $client;
    public $authUrl, $authData;
    public function __construct($Id=null)
    {
      if ($Id) $this->Id = $Id;
    }
    protected function connectClient($Client=\Google_Client::class)
    {
      if (class_exists($Client)) {
        return self::$client = new $Client();
        // $this->auth->setAuthConfig($json);
        // $this->auth->setClientId();
        // $this->auth->setClientSecret();
        // $this->auth->setRedirectUri();
      }
    }
    // private function setAuthConfig($json=null)
    // {
    //   if (self::$client && $json) self::$client->setAuthConfig($json);
    // }
    // public function setAccessType($s='web')
    // {
    //   self::$client->setAccessType($s);
    // }
    // public function setIncludeGrantedScopes($json=true)
    // {
    //   self::$client->setIncludeGrantedScopes($json);
    // }
    // public function setRedirectUri($url=null)
    // {
    //   self::$client->setRedirectUri($url);
    // }
    // public function setScopes($scopes=null)
    // {
    //   self::$client->setScopes($scopes);
    // }
    // public function createAuthUrl()
    // {
    //   return self::$client->createAuthUrl();
    // }
    public function accessObserve()
    {
      $this->accessCode();
      if ($this->accessToken()) {
        return $this->authData = self::$client->verifyIdToken();
      }
      // $this->authUrl = $this->createAuthUrl();
    }
    public function accessCode()
    {
      if (isset($_GET['code'])) {
        self::$client->authenticate($_GET['code']);
        $_SESSION[self::sessionToken] = self::$client->getAccessToken();
      }
    }
    public function accessToken()
    {
      if (isset($_SESSION[self::sessionToken])) {
        self::$client->setAccessToken($_SESSION[self::sessionToken]);
        return self::$client->getAccessToken();
      }
    }
    public function accessRemove($access=false)
    {
      if ($access) unset($_SESSION[self::sessionToken]);
    }
  }
}
/*
php composer.phar require "google/apiclient:~2.0@dev"

$client = new \Google_Client();
$client->setAuthConfig('client_secret.json');
// $client->setAccessType("offline");        // offline, online
$client->setIncludeGrantedScopes(true);   // incremental auth
$client->setRedirectUri('http://localhost/google/?oauth2callback');
// $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '?oauth2callback');
// $client->setScopes('email');
$client->setScopes(
  array(
    // "https://www.googleapis.com/auth/plus.login",
    "https://www.googleapis.com/auth/userinfo.email",
    "https://www.googleapis.com/auth/userinfo.profile",
    // "https://www.googleapis.com/auth/plus.me"
  )
);
// $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
// $client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
// $this->googleAuthUrl= $client->createAuthUrl();

if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
}

if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
}

if (isset($_SESSION['token'])) { // extract token from session and configure client
    $token = $_SESSION['token'];
    $client->setAccessToken($token);
}

if ($client->getAccessToken()) {
  $this->issignin='Yes';
  $payload = $client->verifyIdToken();
  $this->googleUserProfilePicture =$payload['picture'];
  // print_r($payload);
  // $payload = $client->verifyIdToken()->getAttributes();
} else {
  $this->issignin='No';
  $this->googleAuthUrl= $client->createAuthUrl();
}
*/