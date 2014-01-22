<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$link_id=isset($_REQUEST['link_id']) ? (int)$_REQUEST['link_id'] : exit;

if (isset($_POST['save'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['LINK']['MAIN']} WHERE ID='$link_id' AND user='$userid'");

	header('Location: close-me.php'); exit;

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ID='$link_id' AND user='$userid'");

if (!$data) { $base_instance->show_message('Link not found'); exit; }

$datetime=$data[1]->datetime;
$url=$data[1]->url;
$title=$data[1]->title;

if (preg_match('/:\/\//',$url)) {} else { $url='http://'.$url; }

$url=substr($url,0,60);

$datetime_converted=$base_instance->convert_date($datetime);

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this Link?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'80%',
'BUTTON_TEXT'=>'Delete Link'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'link_id','VALUE'=>"$link_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"
<div align=\"center\"><table><tr><td><strong>Added:</strong></td><td>$datetime_converted</td></tr><tr><td><strong>URL:</strong></td><td>$url</td></tr><tr><td><strong>Title:</strong></td><td>$title</td></tr></table></div>"));

$html_instance->process();

?>