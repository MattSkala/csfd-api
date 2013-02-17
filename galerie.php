<?php
include 'global.php';


// GALERIE

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$id){ exit; }

logAction('GALERIE: '.$id);
$baseurl = 'http://www.csfd.cz/film/'.$id.'/galerie';

$while = true;
$page = 0;
$i = 0;
$step = 10;
$fotky = '';

while($while){

  $url = '';
  $html = '';
  $galerie_html = '';
  $fotka = '';
  $match = '';
  

  $page++;
  $url = 'http://www.csfd.cz/film/'.$id.'/galerie/strana-'.$page;

  $html = file_get_html($url);
  
  $count = $html->find(".photos .header .count", 0)->plaintext;
  $count = str_replace("(", "", $count);
  $count = str_replace(")", "", $count);
  
  // FOTKY
  $galerie_html = $html->find(".photos .content ul", 0);

  if(  ($count+$step-1)>=($page*$step)  ){
 
      foreach($galerie_html->find("li .photo") as $fotka){ $i++;
        preg_match("#url\('(.+?)'\)#si", $fotka, $match);
        $fotky[$i]['url'] = $match[1];  
      }
  
  }else{
    $while = false;
  }

}

// XML WRITE
xmlHeader();
?>

<galerie>
  <id><?php echo $id; ?></id>
  <fotky>
<?php
foreach($fotky as $fotka){
?>
    <fotka><?php echo $fotka['url']; ?></fotka>
<?php
}
?>
  </fotky>
</galerie>