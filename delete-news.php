<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

$news_id=isset($_REQUEST['news_id']) ? (int)$_REQUEST['news_id'] : exit;

if (isset($_POST['save'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['NEWS']['MAIN']} WHERE ID='$news_id'");

	header('Location: close-me.php'); exit;

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NEWS']['MAIN']} WHERE ID='$news_id'");

if (!$data) { $base_instance->show_message('News not found'); exit; }

$datetime=$data[1]->datetime;
$text=$data[1]->text;
$title=$data[1]->title;

$text2=substr($text,0,50);

$datetime_converted=$base_instance->convert_date($datetime.' 00:00:00');

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this News?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete News'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'news_id','VALUE'=>"$news_id"));

if ($title) { $text2="<b>$title</b>: $text2"; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"<strong>Added:</strong> $datetime_converted<p>$text2"));

$html_instance->process();

?>