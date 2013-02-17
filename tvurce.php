<?php
include 'global.php';


// FILM


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$id){ exit; }

logAction('TVURCE: '.$id);
$film_html = getUrl('http://www.csfd.cz/tvurce/'.$id);
$html = str_get_html($film_html);

                                             
// INFO

$jmeno = trim( $html->find('.info', 0)->find('h1', 0)->plaintext );
$obrazek = $html->find('.content .image img', 0)->src;

$info = $html->find('.info', 0)->find('li', 0)->innertext;
preg_match("#(.+)<br />(.+)#", $info, $match);
$narozeni_datum = trim($match[1]);
$narozeni_misto = trim($match[2]);

$biografie = $html->find('#action .content p', 0)->innertext;
$biografie = str_replace("<br /><br />", " ", $biografie);
$biografie = str_replace("<br><br>", " ", $biografie);
$biografie = str_replace("&", "a", $biografie);
//$biografie = str_replace("<", "&lt;", $biografie);
//$biografie = str_replace(">", "&gt;", $biografie);
$biografie = strip_tags( $biografie );
              

// FILMOGRAFIE

$filmografie_array = $html->find('#filmography', 0)->find('.ct-general');
$i = 0;

foreach($filmografie_array as $filmografie_html){ $i++;


  //$e = str_get_html($filmografie_html);  

  $typ = csfdTypFilmografie( trim($filmografie_html->find('h2', 0)->plaintext) );

  $content = $filmografie_html->find('.content tr');
 
  $j = 0;
  foreach($content as $film){ $j++;
    $nazev = trim($film->find('td a', 0)->plaintext);
    if($nazev){
        $actualrok = trim($film->find('th', 0)->innertext);
        $rok = ($actualrok) ? $actualrok : $rok; 
        $filmografie[$typ][$j]['rok'] = $rok;    
        $filmografie[$typ][$j]['nazev'] = $nazev;
        $filmografie[$typ][$j]['id'] = trim( csfdId( $film->find('td a', 0)->href ) );
        $filmografie[$typ][$j]['rating'] = trim( csfdRating( $film->find('td a', 0)->class ) );
    }
  }                                                             
}
                  
// XML WRITE
xmlHeader();
?>

<tvurce>
  <id><?php echo $id; ?></id>
  <jmeno><?php echo $jmeno; ?></jmeno>
  <obrazek><?php echo $obrazek; ?></obrazek>
  <narozeni_datum><?php echo $narozeni_datum; ?></narozeni_datum>
  <narozeni_misto><?php echo $narozeni_misto; ?></narozeni_misto>
  <biografie><?php echo $biografie; ?></biografie>
  <filmografie>
<?php
foreach($filmografie as $key => $filmo1){
  echo "<$key>";
  foreach($filmo1 as $filmo2){
    echo '<film>';
    echo "<nazev>".$filmo2['nazev']."</nazev>"; 
    echo "<id>".$filmo2['id']."</id>"; 
    echo "<rating>".$filmo2['rating']."</rating>"; 
    echo "<rok>".$filmo2['rok']."</rok>"; 
    echo '</film>';
  }
  echo "</$key>";
}
?>
  </filmografie>
</tvurce>