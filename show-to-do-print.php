<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$where='';

if (isset($_GET['text_search'])) {

$text_search=sql_safe($_GET['text_search']);
$where=" AND (text LIKE '%$text_search%' OR title LIKE '%$text_search%') ";

}

if (isset($_GET['category_id'])) {

$category_id=(int)$_GET['category_id'];
$where.=' AND category='.$category_id;

}

else if (isset($_GET['to_do_id'])) {

$to_do_id=(int)$_GET['to_do_id'];
$where=' AND ID='.$to_do_id;

}

#

$order_col=isset($_COOKIE['oc_todo']) ? $_COOKIE['oc_todo'] : 'datetime';
$order_type=isset($_COOKIE['ot_todo']) ? $_COOKIE['ot_todo'] : 'DESC';

#

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE user='$userid'$where ORDER BY $order_col $order_type");

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

$title=convert_square_bracket($title);
$text=convert_square_bracket($text);

$text=nl2br($text);

echo '<tr><td><strong>',$title,'</strong><br>',$text,'</td></tr>';

}

echo '</table>';

?>