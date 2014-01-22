<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'RSS',
'ORDER_COL'=>'title',
'ORDER_TYPE'=>'ASC',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid'",
'HEADER'=>'RSS Feeds',
'TEXT_CENTER'=>'<a href="add-rss-feeds.php">[Add RSS Feeds]</a><p>',
'INNER_TABLE_WIDTH'=>'80%',
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No RSS Feeds added yet','<a href="add-rss-feeds.php">[Add RSS Feeds]</a>');

} else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">'; }

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;
	$feed=$data[$index]->feed;

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><strong>'.$title.'</strong></td><td>'.$feed.'</td><td><a href="'.$feed.'" target="_blank">[Show]</a></td><td><a href="edit-rss-feed.php?feed_id='.$ID.'">[Edit]</a></td><td><a href="javascript:void(window.open(\'delete-rss-feed.php?feed_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td></tr>';

}

$all_text.='</table>';

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>