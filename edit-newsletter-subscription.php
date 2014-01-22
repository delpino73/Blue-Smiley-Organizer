<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$userid=$base_instance->get_userid();

if (isset($_POST['save'])) {

	$error='';

	$newsletter=(int)$_POST['newsletter'];

	if (!$newsletter) { $error.='<li> Choose Yes or No'; }

	if (!$error) {

	$base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET newsletter_opt_in='$newsletter' WHERE ID='$userid'");

	$base_instance->show_message('Newsletter Settings updated');

	}

	else { $html_instance->error_message=$error; }

}

$data=$user_instance->get_userinfo($userid);
$newsletter=$data[1]->newsletter_opt_in;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Newsletter Settings',
'INNER_TABLE_WIDTH'=>'60%',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Update'
));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'newsletter','VALUE'=>"$newsletter",'OPTION'=>'newsletter_array','TEXT'=>'Newsletter'));

$html_instance->process();

?>