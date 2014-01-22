<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_POST['save'])) {

	$error='';

	$about_me=$_POST['about_me'];
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$country=(int)$_POST['country'];

	if (!empty($about_me)) {

	$about_me=trim($about_me);
	if (strlen($about_me)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['USER']['MAIN'].' SET about_me="'.sql_safe($about_me).'",firstname="'.sql_safe($firstname).'",lastname="'.sql_safe($lastname).'",country='.$country.' WHERE ID='.$userid);

	$base_instance->show_message('About Me page updated','<a href="show-user.php?userid='.$userid.'">[View Profile Page]</a>');

	}

	else { $html_instance->error_message=$error; }

}

else {

	$data=$base_instance->get_data("SELECT about_me,firstname,lastname,country FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

	$about_me=$data[1]->about_me;
	$firstname=$data[1]->firstname;
	$lastname=$data[1]->lastname;
	$country=$data[1]->country;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'About Me',
'TEXT_CENTER'=>'The About Me text is a public text which appears in your profile.<br>Firstname and lastname is not public, it will only be used for sending emails within the Organizer.<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.about_me.focus()"',
'BUTTON_TEXT'=>'Update'
));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'about_me','VALUE'=>"$about_me",'TEXT'=>'About me','COLS'=>80,'ROWS'=>4));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'firstname','VALUE'=>"$firstname",'SIZE'=>35,'TEXT'=>'Firstname'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'lastname','VALUE'=>"$lastname",'SIZE'=>35,'TEXT'=>'Lastname'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'country','VALUE'=>"$country",'OPTION'=>'country_array','TEXT'=>'Country'));

$html_instance->process();

?>