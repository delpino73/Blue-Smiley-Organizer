<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$where='';

if (isset($_GET['text_search'])) {

$text_search=sql_safe($_GET['text_search']);
$where=" AND (text LIKE '%$text_search%' OR title LIKE '%$text_search%') ";

}

else if (isset($_GET['diary_id'])) {

$diary_id=(int)$_GET['diary_id'];
$where=' AND ID='.$diary_id;

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DIARY']['MAIN']} WHERE user='$userid'$where ORDER BY date DESC");

echo '<head><meta http-equiv="content-type" content="text/html;charset=utf-8">
<style type="text/css">
td {font-family:Arial; font-size:10pt}
table.pastel,table.pastel td {border:1px solid #c5c5c5; border-collapse:collapse}
</style>
</head>

<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$title=$data[$index]->title;
$text=$data[$index]->text;
$date=$data[$index]->date;

$date_converted=$base_instance->convert_date($date.' 00:00:00');

$title=convert_square_bracket($title);
$text=convert_square_bracket($text);

$text=nl2br($text);

echo '<tr><td><strong>',$title,'</strong> ',$date_converted,'<br>',$text,'</td></tr>';

}

echo '</table>';

?>