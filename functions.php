<?php

function csfdId($url){
  if(!$url){ return false; }
  preg_match("#/([0-9]+)-#", $url, $match);
  $id = intval($match[1]);
  return $id;
}

function csfdFilmTvurci($html){

  // typ tvůrců
  $dom = str_get_html($html);
  $title = $dom->find('h4', 0)->innertext;

  switch($title){
    case "Režie:": $typ = 'rezie'; break;
    case "Hudba:": $typ = 'hudba'; break;
    case "Hrají:": $typ = 'hraji'; break;
    case "Scénář:": $typ = 'scenar'; break;
    case "Kamera:": $typ = 'kamera'; break;
    case "Předloha:": $typ = 'predloha'; break;
    default: $typ = ''; break;
  }

  $tvurci['typ'] = $typ;

  // pole tvůrců

  $i = 0;
  foreach($dom->find('a') as $tvurce_html){
    $i++;
    $tvurce_dom = str_get_html($tvurce_html);
    $tvurce[$i]['jmeno'] = $tvurce_dom->plaintext;
    $tvurce[$i]['id'] = csfdId( $tvurce_dom->find('a', 0)->href );
  }
  $tvurci['tvurci'] = $tvurce;

  return $tvurci;

}

function csfdHledatRok($dom){

  $rok = '';

  $other_year = $dom->find('.film-year', 0);
  if(isset($other_year)){
    $rok = $other_year->innertext;
    $rok = csfdRok($rok);
  }else{
    $html = $dom->find('p', 0);
    if($html){ $string = $html->innertext; }
    else{ $string = $dom; }
    $rok = csfdRok($string);
  }

  return $rok;

}

function csfdRok($string){
  $status = preg_match("#([0-9]{4})#", $string, $match);
  return $match[1];
}

function csfdRating($class){
  preg_match("#c([0-9])#", $class, $match);
  $rating = (isset($match[1])) ? $match[1] : false;
  return $rating;
}

function csfdFilmRating($rating){
  preg_match("#([0-9]{1,3})%#", $rating, $match);
  $rating = (isset($match[1])) ? $match[1] : $rating;
  return $rating;
}

function csfdConvertRating($source){
  $percent = csfdFilmRating($source);
  if($percent>70){$rating = 1;}
  elseif($percent>50){$rating = 2;}
  elseif($percent>0){$rating = 3;}
  else{$rating = 0;}
  return $rating;
}

function logAction($message, $file='log'){
  $time = Date("j/m/Y H:i:s", time());
  //$ip = $_SERVER["REMOTE_ADDR"];
  $final_message = "$time > $message"."\n";
  $fp = fopen('log/'.$file.'.txt', 'a');
  fwrite($fp, $final_message);
  fclose($fp);
}

function getUrl($url, $sessionid, $user){
  if($sessionid && $user){
      $html = getCurl($url, "PHPSESSID=$sessionid", $user);
      //echo $html; exit;
  }else{
      $html = file_get_contents($url);
  }
  return $html;
}

function xmlHeader(){
  header ('Content-Type:text/xml');
  echo '<?xml version="1.0" encoding="UTF-8"?'.'>';
}

function getCurl($url, $cookies, $user, $header=0){
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_COOKIE, $cookies);
  $sessionid = $_GET['sessionid'];
  $cookiename = getCookieFile($user);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiename);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiename);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, $header);  // RETURN HTTP HEADERS
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

function getCookieFile($username){
  $cookiename = "./cookies/cookie_$username.txt";
  if( !file_exists($cookiename) ){
    $handle = fopen($cookiename, 'w');
    fclose($handle);
  }
  return $cookiename;
}

function deleteCookieFile($username){
  $cookiename = __DIR__ . "/cookies/cookie_$username.txt";
  unlink($cookiename);
}

function csfdTypFilmografie($typ){
  if($typ == "Herecká filmografie"){ $return = 'herecka'; }
  elseif($typ == "Režijní filmografie"){ $return = 'rezijni'; }
  elseif($typ == "Skladatelská filmografie"){ $return = 'hudebni'; }
  else{$return = '';}
  return $return;
}

function csfdTopStars($src){
  preg_match("#([1-5])\.gif#", $src, $match);
  return $match[1];
}
?>