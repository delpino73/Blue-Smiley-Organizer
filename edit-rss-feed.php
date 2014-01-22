<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$feed_id=isset($_REQUEST['feed_id']) ? (int)$_REQUEST['feed_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$feed=$_POST['feed'];
	$max_items=$_POST['max_items'];

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['RSS']['MAIN'].' SET title="'.sql_safe($title).'",feed="'.sql_safe($feed).'",max_items="'.sql_safe($max_items).'" WHERE user='.$userid.' AND ID='.$feed_id);

	$base_instance->show_message('RSS Feed updated','<a href="show-rss-feeds.php">[Show RSS Feeds]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['RSS']['MAIN']} WHERE user='$userid' AND ID='$feed_id'");

if (!$data) { $base_instance->show_message('RSS Feed not found'); exit; }

$title=$data[1]->title;
$feed=$data[1]->feed;
$max_items=$data[1]->max_items;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit RSS Feed',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'600',
'BUTTON_TEXT'=>'Update RSS Feed'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'feed_id','VALUE'=>"$feed_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>70,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'feed','VALUE'=>"$feed",'SIZE'=>70,'TEXT'=>'Feed URL'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'max_items','VALUE'=>"$max_items",'SIZE'=>3,'TEXT'=>'Max Items'));

$html_instance->process();

?>