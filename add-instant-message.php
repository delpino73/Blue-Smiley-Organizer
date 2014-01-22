<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$receiver=isset($_REQUEST['receiver']) ? $_REQUEST['receiver'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$answer_id=isset($_POST['answer_id']) ? (int)$_POST['answer_id'] : 0;
$close=isset($_POST['close']) ? 1 : 0;

if (isset($_POST['save'])) {

	$error='';

	if (!$receiver) { $error.='<li> No Receiver'; }

	if (!$text) { $error.='<li> Text cannot be blank'; }
	else {

	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

	$datetime=$_POST['datetime'];

	$html_instance->check_for_duplicates('INSTANT_MESSAGE','MAIN',$datetime,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['INSTANT_MESSAGE']['MAIN'].' (datetime,text,user,receiver,answer_id) VALUES ("'.sql_safe($datetime).'","'.sql_safe($text).'",'.$userid.','.$receiver.','.$answer_id.')');

	if ($close==1) { header('Location: close-me.php'); }
	else { $base_instance->show_message('Instant Message sent',''); }

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);

	}

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Send Instant Message',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Send Instant Message'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'receiver','VALUE'=>"$receiver"));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>80,'ROWS'=>4));

$html_instance->process();

?>