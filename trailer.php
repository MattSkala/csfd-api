<?php
include 'global.php';


// TRAILER

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if(!$id){ exit; }

logAction('TRAILER: '.$id);
$html = file_get_html('http://www.csfd.cz/film/'.$id.'/videa');


// VIDEO
$video_html = $html->find(".ui-video-player video source", 0);
$video = $video_html->src;


// XML WRITE
xmlHeader();
?>

<trailer>
  <id><?php echo $id; ?></id>
  <url><?php echo $video; ?></url>
</trailer>