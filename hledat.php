<?php
include 'global.php';

// HLEDAT

$q = isset($_GET['q']) ? $_GET['q'] : false;
$q = str_replace(" ", "+", $q);
if(!$q){ logAction('HLEDAT'); exit; }

logAction('HLEDAT: '.$q);
$html = file_get_html('http://www.csfd.cz/hledat/?q='.$q);

$filmy = '';

$i = 0;
foreach( $html->find('#search-films li') as $film_html){
  $i++;
  $film = str_get_html($film_html);
  $filmy[$i]['nazev'] = $film->find('a.film', 0)->innertext;
  $el_type = $film->find('.film-type', 0);
  $filmy[$i]['typ'] = $el_type ? $el_type->innertext : null;
  $filmy[$i]['rating'] = csfdRating( $film->find('a.film', 0)->class );
  $filmy[$i]['id'] = csfdId( $film->find('a.film', 0)->href );
  $filmy[$i]['rok'] = csfdHledatRok( $film );
}

if(!$filmy AND $html->find('#pg-film', 0)){
  $info = $html->find('.info', 0);
  $filmy[1]['nazev'] = trim( $info->find('h1', 0)->innertext );
  //echo $info->find('.origin', 0)->innertext;
  $filmy[1]['rok'] = csfdHledatRok( $info->find('.origin', 0) );
  $filmy[1]['id'] = csfdId( $html->find('.trivia a', 0)->href );
  $filmy[1]['typ'] = '';
  $filmy[1]['rating'] = csfdConvertRating( $html->find('#rating .average', 0)->innertext );
}



// XML WRITE
xmlHeader();
?>

<hledat>
  <q><?php echo $q; ?></q>
  <filmy>
<?php
    foreach($filmy as $film){
?>
    <film>
      <id><?php echo $film['id'] ?></id>
      <nazev><?php echo ($film['typ']) ? $film['nazev'].' '.$film['typ'] : $film['nazev']; ?></nazev>
      <rok><?php echo $film['rok'] ?></rok>
      <rating><?php echo $film['rating'] ?></rating>
    </film>
<?php
    }
?>
  </filmy>
</hledat>