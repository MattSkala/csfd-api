<?php
include 'global.php';

// RATE

$user = @$_GET['user'];
$sessionid = @$_GET['sessionid'];
$rating = @$_GET['rating'];
$finalrating = $rating*20;
$id = @$_GET['id'];
$token = @$_GET['token'];

if(!$user or !$sessionid or !$id){ exit; }

logAction('RATE: '.$rating);
$error = 0;


if($rating=="-1"){

    // DELETE RATING

    // refresh DELETE TOKEN
    $filmurl = $dirpath."/film.php?user=$user&id=$id&sessionid=$sessionid";
     $filmtext = file_get_contents($filmurl);
      $filmxml = new SimpleXMLElement($filmtext);

      $delete_token = (string) $filmxml->delete_token;

    $url = "http://www.csfd.cz/film/$id/?token=$delete_token&do=myRatingDelete";
    var_dump($url);
    define('POSTURL', $url);

    $cookiename = getCookieFile($user);

    $ch = curl_init(POSTURL);

    //curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, POSTVARS);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiename);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiename);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);  // RETURN HTTP HEADERS
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL

    $data = curl_exec($ch);

    if (curl_errno($ch)) {
      echo 'cURL error: ' . curl_error($ch);
    }

    curl_close($ch);

}else{

    // RATE

    define('POSTURL', "http://www.csfd.cz/film/$id/?do=ratingForm-submit");
    define('POSTVARS', "rating=$finalrating&_token_=$token");


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
      echo 'cURL error: ' . curl_error($ch);
    }

    curl_close($ch);

}

// XML
xmlHeader();
?>
<rate>
  <error><?php echo $error; ?></error>
</rate>