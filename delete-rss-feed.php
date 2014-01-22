<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$feed_id=isset($_REQUEST['feed_id']) ? (int)$_REQUEST['feed_id'] : exit;

if (isset($_POST['save'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['RSS']['MAIN']} WHERE ID='$feed_id' AND user='$userid'");

	header('Location: close-me.php'); exit;

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['RSS']['MAIN']} WHERE ID='$feed_id' AND user='$userid'");

if (!$data) { $base_instance->show_message('RSS Feed not found'); exit; }

$title=$data[1]->title;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this RSS Feed?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete RSS Feed'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'feed_id','VALUE'=>"$feed_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<div align="center"><strong>'.$title.'</strong></div>'));

$html_instance->process();

?>