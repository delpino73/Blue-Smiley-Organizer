<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$home_id=isset($_REQUEST['home_id']) ? (int)$_REQUEST['home_id'] : exit;

if (isset($_POST['save'])) {

	$base_instance->query("DELETE FROM {$base_instance->entity['HOME']['MAIN']} WHERE ID='$home_id' AND user='$userid'");

	$base_instance->show_message('Homepage deleted','<a href="add-home.php">[Add Homepage]</a> &nbsp;&nbsp; <a href="show-home.php">[Show all Homepages]</a>

<script language="JavaScript" type="text/javascript">
top.top_frame.navigation.location.reload(true);
</script>');

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['HOME']['MAIN']} WHERE ID='$home_id' AND user='$userid'");

if (!$data) { $base_instance->show_message('Homepage not found'); exit; }

$title=$data[1]->title;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete this Homepage?</font>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete Homepage'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'home_id','VALUE'=>"$home_id"));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"<div align=\"center\">$title</div>"));

$html_instance->process();

?>