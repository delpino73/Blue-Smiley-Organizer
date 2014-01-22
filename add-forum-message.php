<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$followup=isset($_POST['followup']) ? (int)$_POST['followup'] : '';

if (isset($_POST['save'])) {

	$error='';

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	} else if (!$followup) { $error.='<li> Title cannot be left blank'; }

	if ($text) {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	} else { $error.='<li> Message cannot be left blank'; }

	if (!$error) {

	$datetime=$_POST['datetime'];

	$base_instance->query('INSERT INTO '.$base_instance->entity['FORUM']['MAIN'].' (datetime,updated,text,title,followup,user) VALUES ("'.sql_safe($datetime).'","'.sql_safe($datetime).'","'.sql_safe($text).'","'.sql_safe($title).'",'.$followup.','.$userid.')');

	if (_FORUM_NOTIFY==1 && $userid!=_ADMIN_USERID) {

	$msg="New Forum Message:\n\n".$title."\n\n".$text;

	$base_instance->send_email_from_admin('New Forum Message Notification',$msg,_ADMIN_EMAIL);

	}

	header('Location: show-forum.php'); exit;

	}

	else {

	$html_instance->error_message=$error;

	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'New Forum Message',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title.focus()"',
'BUTTON_TEXT'=>'Post new Message'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'followup','VALUE'=>"$followup"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>90,'ROWS'=>11));

$html_instance->process();

?>