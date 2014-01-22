<?php

$flush=1;

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$text='<!DOCTYPE NETSCAPE-Bookmark-file-1>'."\n";
$text.='<!-- Exported from '._HOMEPAGE.' -->'."\n";
$text.='<title>Bookmarks</title>'."\n";
$text.='<h1>Bookmarks</h1>'."\n\n";
$text.='<dl><p>'."\n";

$text.=export_mozilla_bookmarks($userid,0);

foreach (glob('./export/bookmarks*.*') as $filename) { unlink($filename); }

$token=md5(uniqid(rand(),true));

$filepath='./export/bookmarks'.$token.'.html';
$filename='bookmarks'.$token.'.html';

exec("rm $filepath; touch $filepath; chmod 0600 $filepath");

if (is_writable($filepath)) {

if (!$fp=fopen($filepath,'w')) { echo "Cannot open file ($filename)"; exit; }
if (!fwrite($fp,$text)) { echo "Cannot write to file ($filename)"; exit; }
fclose($fp);

}

else { echo "The file $filename is not writable"; exit; }

if (file_exists($filepath)) {

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename="'.$filename.'";');
#header('Content-Length: '.filesize($filepath));

set_time_limit(0);

@readfile($filepath) or die();

} else { $base_instance->show_message('File not found'); }

#

function export_mozilla_bookmarks($userid, $parent_id) {

global $base_instance, $h;

$text='';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user='$userid' AND parent_id=$parent_id");

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$parent_id=$data[$index]->parent_id;

$h++;

$indent=str_repeat('    ', $h);

$text.=$indent;
$text.='<dt><h3>'.$title.'</h3>'."\n";
$text.=$indent;
$text.='<dl><p>'."\n";

$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user='$userid' AND category=$ID");

for ($index2=1; $index2 <= sizeof($data2); $index2++) {

$url=$data2[$index2]->url;
$link_title=$data2[$index2]->title;
$added=$data2[$index2]->datetime;
$lastvisit=$data2[$index2]->last_visit;

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$added,$ll);
$timestamp_added=mktime($ll[4],$ll[5],$ll[6],$ll[2],$ll[3],$ll[1]);

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$lastvisit,$ll);
$timestamp_lastvisit=mktime($ll[4],$ll[5],$ll[6],$ll[2],$ll[3],$ll[1]);

$text.='<dt><a href="http://'.$url.'" add_date="'.$timestamp_added.'" last_visit="'.$timestamp_lastvisit.'">'.$link_title.'</a>'."\n";
$text.=$indent;

}

$text.=export_mozilla_bookmarks($userid, $ID);

$text.='</dl><p>'."\n";

$h--;

}

return $text;

}

?>