<?php

require 'class.base.php';
require 'class.user.php';
require 'class.html.php';

$base_instance=new base();
$user_instance=new user();
$html_instance=new html();

$userid=$base_instance->user;

$text1=isset($_POST['text1']) ? $_POST['text1'] : '';
$text2=isset($_POST['text2']) ? $_POST['text2'] : '';
$text3=isset($_POST['text3']) ? $_POST['text3'] : '';
$continue=isset($_POST['continue']) ? $_POST['continue'] : '';

if (isset($_POST['save'])) {

	$user_data=$user_instance->get_userinfo($userid);

	if (!empty($user_data)) {

	$user_name=$user_data[1]->username;
	$user_email=$user_data[1]->email;

	} else { $user_name=''; $user_email=''; }

	if ($text1 or $text2 or $text3) {

	$message="Username: $user_name ($user_email)\nLike: $text1\nDislike: $text2\nMisc: $text3";

	$header="From: User <$user_email>\n";
	$header.="Reply-To: $user_email\n";

	mail(_ADMIN_EMAIL,'Feedback (Blue Smiley Organizer)',$message,$header);

	$base_instance->show_message('Thank you for your feedback','<a href="start.php" target="_top">[Continue]</a>');

	} else { $base_instance->show_message('Feedback','<a href="start.php" target="_top">[Continue]</a>'); }

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Feedback',
'TEXT_CENTER'=>'To help improve this website we need your feedback from time to time.<br>You can influence future changes by submitting your suggestions and ideas.<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.text1.focus()"',
'BUTTON_TEXT'=>'Send Feedback'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'continue','VALUE'=>$continue));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text1','VALUE'=>$text1,'COLS'=>50,'ROWS'=>4,'TEXT'=>'What do you like'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text2','VALUE'=>$text2,'COLS'=>50,'ROWS'=>4,'TEXT'=>'What you don\'t like'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text3','VALUE'=>$text3,'COLS'=>50,'ROWS'=>4,'TEXT'=>'Improvements, Suggestions and other comments'));

$html_instance->process();

?>