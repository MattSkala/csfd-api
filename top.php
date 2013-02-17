<?php
include 'global.php';


// TOP

$sessionid = isset($_GET['sessionid']) ? $_GET['sessionid'] : "";
$user = isset($_GET['user']) ? $_GET['user'] : "";
$typ = isset($_GET['typ']) ? $_GET['typ'] : "nejlepsi-filmy";
$complete = "?show=complete";
//$complete = "";

//logAction('ZEBRICEK: '.$id);
$film_html = getUrl('http://www.csfd.cz/zebricky/'.$typ.'/'.$complete, $sessionid, $user);
$html = str_get_html($film_html);


// ZEBRICEK

$top = $html->find('#results tr');
$i = 0;
foreach($top as $e){ $i++;
  $nazev = trim( $e->find('.film a', 0)->plaintext );
  if($nazev && $i<=99){
    $filmy[$i]['id'] = csfdId( $e->find('.film a', 0)->href );
    $filmy[$i]['nazev'] = $nazev;
    $filmy[$i]['poradi'] = $e->find('.order', 0)->plaintext;
    $filmy[$i]['poradi'] = trim( str_replace(".", "", $filmy[$i]['poradi']) );
    $filmy[$i]['rating'] = $e->find('.average', 0)->plaintext;
    $filmy[$i]['muj_rating'] = $e->find('.rating img', 0) ? csfdTopStars( $e->find('.rating img', 0)->src ) : null;
  }
}


// LOGIN
$login = @csfdId( @$html->find("#user-menu a", 0).href );

// XML WRITE
xmlHeader();
?>

<zebricek>
  <typ><?php echo $typ ?></typ>
  <logged><?php echo $login ?></logged>
  <filmy>
<?php
foreach($filmy as $film){
?>
    <film>
      <id><?php echo $film['id'] ?></id>
      <nazev><?php echo $film['nazev'] ?></nazev>
      <poradi><?php echo $film['poradi'] ?></poradi>
      <rating><?php echo $film['rating'] ?></rating>
      <muj_rating><?php echo $film['muj_rating'] ?></muj_rating>
    </film>
<?php
}
?>
  </filmy>
</zebricek>