<?php
include 'global.php';

// LOGIN

if(!isset($_GET['user']) or !$_GET['user'] or !isset($_GET['password']) or !$_GET['password']){ exit; }

$user = $_GET['user'];
$password = $_GET['password'];
$error = 0;

logAction('LOGIN: '.$user);

define('POSTURL', 'http://www.csfd.cz/prihlaseni/prihlaseni/?do=form-submit');
define('POSTVARS', "username=$user&password=$password");

deleteCookieFile($user);
$cookiename = getCookieFile($user);

$ch = curl_init(POSTURL);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, POSTVARS);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiename); 
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiename); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
curl_setopt($ch, CURLOPT_HEADER, 1);  // RETURN HTTP HEADERS 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL

$data = curl_exec($ch);

if (curl_errno($ch)) {
  $error = curl_error($ch);
}

curl_close($ch);


// CHECK ERROR CODE

$sessionid = "";
preg_match("#errorCode=([0-9]+)#si", $data, $errorcode);
if($errorcode){ $error = $errorcode[1]; }else{

  // GET SESSION ID
  
  preg_match_all("#PHPSESSID=([a-z0-9]+);#si", $data, $match);
  $count = count($match[1]) - 1;
  $sessionid = $match[1][$count];

}

// XML
xmlHeader();
?>
<login>
  <jmeno><?php echo $user; ?></jmeno>
  <sessionid><?php echo $sessionid; ?></sessionid>
  <error><?php echo $error; ?></error>
</login>