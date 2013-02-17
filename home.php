<?php
include 'global.php';
logAction('HOME');

// HOME PAGE

$html = file_get_html('http://www.csfd.cz/');


// NOVINKA

$e_novinka = $html->find('.news', 0);
$novinka['obrazek'] = $e_novinka->find('img', 0)->src; 
$novinka['nazev'] = $e_novinka->find('h3', 0)->innertext; 
$novinka['obsah'] = $e_novinka->find('p', 0)->plaintext;
$novinka['obsah'] = str_replace(' (více)', '', $novinka['obsah']);


// TELEVIZE

$e_televize = $html->find('.tv .films', 0);

$i = 0;
foreach($e_televize->find('li') as $e_televize_tip_string){ 
  $i++;
  $e_televize_tip = str_get_html($e_televize_tip_string);   
  $televize_tip[$i]['id'] = csfdId( $e_televize_tip->find('a.film', 0)->href );
  $televize_tip[$i]['rating'] = csfdRating( $e_televize_tip->find('a.film', 0)->class );
  $televize_tip[$i]['nazev'] = $e_televize_tip->find('a.film', 0)->innertext;
  $televize_tip[$i]['typ'] = @$e_televize_tip->find('.film-type', 0)->innertext;
  $televize_tip[$i]['rok'] = csfdRok( $e_televize_tip->find('span.film-year', 0)->innertext ); 
  $televize_tip[$i]['info'] = $e_televize_tip->find('div.info', 0)->innertext;
}


// KINO

$e_kino = $html->find('.cinema-releases .films', 0);

$i = 0;
foreach($e_kino->find('li') as $e_kino_tip_string){ 
  $i++;
  $e_kino_tip = str_get_html($e_kino_tip_string);  
  $kino_tip[$i]['id'] = csfdId( $e_kino_tip->find('a.film', 0)->href ); 
  $kino_tip[$i]['rating'] = csfdRating( $e_kino_tip->find('a.film', 0)->class );
  $kino_tip[$i]['nazev'] = $e_kino_tip->find('a.film', 0)->innertext;
  $kino_tip[$i]['typ'] = @$e_kino_tip->find('.film-type', 0)->innertext;
  $kino_tip[$i]['rok'] = csfdRok( $e_kino_tip->find('span.film-year', 0)->innertext ); 
  $kino_tip[$i]['info'] = $e_kino_tip->find('div.info', 0)->innertext;
}


// DVD

$e_dvd = $html->find('.media-visits', 0);

$i = 0;
foreach($e_dvd->find('li') as $e_dvd_string){ 
  $i++;
  $e_dvd_film = str_get_html($e_dvd_string);  
  $dvd[$i]['id'] = csfdId( $e_dvd_film ->find('a.film', 0)->href ); 
  $dvd[$i]['rating'] = csfdRating( $e_dvd_film ->find('a.film', 0)->class );
  $dvd[$i]['nazev'] = $e_dvd_film ->find('a.film', 0)->innertext;
  $dvd[$i]['typ'] = @$e_dvd_film ->find('.film-type', 0)->innertext;
  $dvd[$i]['rok'] = csfdRok( $e_dvd_film ->find('span.film-year', 0)->innertext ); 
  $dvd[$i]['info'] = $e_dvd_film ->find('div.info', 0)->innertext;
}


// TRAILERY

$e_trailery = $html->find('.videos', 0);

$i = 0;
foreach($e_trailery->find('li') as $e_trailer_string){ 
  $i++;
  $e_trailer = str_get_html($e_trailer_string);
  $trailer[$i]['id'] = csfdId( $e_trailer->find('a.film', 0)->href ); 
  $trailer[$i]['rating'] = csfdRating( $e_trailer->find('a.film', 0)->class );   
  $trailer[$i]['nazev'] = $e_trailer->find('a.film', 0)->innertext;
  $trailer[$i]['typ'] = @$e_trailer->find('.film-type', 0)->innertext;
  $trailer[$i]['rok'] = csfdRok( $e_trailer->find('span.film-year', 0)->innertext ); 
  $trailer[$i]['info'] = $e_trailer->find('div.info', 0)->innertext;
}


// NEJNAVSTEVOVANEJSI

$e_nejnavstevovanejsi = $html->find('.profile-access [rel=profile-film]', 0);

$i = 0;
foreach($e_nejnavstevovanejsi->find('li') as $e_nejnavstevovanejsi_string){ 
  $i++;

  $e_film = str_get_html($e_nejnavstevovanejsi_string); 
  $nejnavstevovanejsi[$i]['id'] = csfdId( $e_film->find('a.film', 0)->href );       
  $nejnavstevovanejsi[$i]['rating'] = csfdRating( $e_film->find('a.film', 0)->class ); 
  $nejnavstevovanejsi[$i]['nazev'] = $e_film->find('a.film', 0)->innertext;
  $nejnavstevovanejsi[$i]['typ'] = @$e_film->find('.film-type', 0)->innertext;
  $nejnavstevovanejsi[$i]['rok'] = csfdRok( $e_film->find('span.film-year', 0)->innertext ); 
}

// XML WRITE
xmlHeader();
?>

<home>
  <novinka>
      <nazev><?php echo $novinka['nazev'] ?></nazev>
    <obsah><?php echo $novinka['obsah'] ?></obsah>
    <obrazek><?php echo $novinka['obrazek'] ?></obrazek>
  </novinka>
  <televize>
<?php
    foreach($televize_tip as $film){
?>
    <film>
      <id><?php echo $film['id'] ?></id>
      <nazev><?php echo ($film['typ']) ? $film['nazev'].' '.$film['typ'] : $film['nazev']; ?></nazev>
      <rok><?php echo $film['rok'] ?></rok>
      <info><?php echo $film['info'] ?></info>
      <rating><?php echo $film['rating'] ?></rating>
    </film>
<?php
    }
?>
  </televize>
  <kino>
<?php
    foreach($kino_tip as $film){
?>
    <film>
      <id><?php echo $film['id'] ?></id>
      <nazev><?php echo ($film['typ']) ? $film['nazev'].' '.$film['typ'] : $film['nazev']; ?></nazev>
      <rok><?php echo $film['rok'] ?></rok>
      <info><?php echo $film['info'] ?></info>
      <rating><?php echo $film['rating'] ?></rating>
    </film>
<?php
    }
?>
  </kino>
  <dvd>
<?php
    foreach($dvd as $film){
?>
    <film>
      <id><?php echo $film['id'] ?></id>
      <nazev><?php echo ($film['typ']) ? $film['nazev'].' '.$film['typ'] : $film['nazev']; ?></nazev>
      <rok><?php echo $film['rok'] ?></rok>
      <info><?php echo $film['info'] ?></info>
      <rating><?php echo $film['rating'] ?></rating>
    </film>
<?php
    }
?>
  </dvd>
  <trailery>
<?php
/*
    foreach($trailer as $film){
?>
    <film>
      <id><?php echo $film['id'] ?></id>
      <nazev><?php echo ($film['typ']) ? $film['nazev'].' '.$film['typ'] : $film['nazev']; ?></nazev>
      <rok><?php echo $film['rok'] ?></rok>
      <info><?php echo $film['info'] ?></info>
      <rating><?php echo $film['rating'] ?></rating>
    </film>
<?php
    }
*/
?>
  </trailery>
  <nejnavstevovanejsi>
<?php
    foreach($nejnavstevovanejsi as $film){
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
  </nejnavstevovanejsi>
</home>