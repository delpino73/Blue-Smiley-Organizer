<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_POST['save'])) {

	$error='';

	$text=$_POST['text'];
	$receiver=$_POST['receiver'];
	$subject=$_POST['subject'];

	if (!$text) { $error.='<li> Text cannot be left blank'; } else { $text=trim($text); }

	if (!$receiver) { $error.='<li> Receiver cannot be left blank'; }

	if (!$error) {

	# who is the receiver

	$data=$base_instance->get_data("SELECT email,firstname,lastname,company FROM {$base_instance->entity['CONTACT']['MAIN']} WHERE ID=$receiver AND user='$userid'");

	$email_receiver=$data[1]->email;
	$firstname_receiver=$data[1]->firstname;
	$lastname_receiver=$data[1]->lastname;
	$company_receiver=$data[1]->company;

	# who is the sender

	$data=$base_instance->get_data("SELECT email,firstname,lastname FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$userid");

	$email_sender=$data[1]->email;
	$firstname_sender=$data[1]->firstname;
	$lastname_sender=$data[1]->lastname;

	}

	if (!$error && $email_receiver && $email_sender) {

	$mailheaders='From: '.$firstname_sender.' '.$lastname_sender.' <'.$email_sender.'>'."\n";
	$mailheaders.='Reply-To: '.$email_sender."\n";
	$mailheaders.='Content-Type: text/html; charset=utf-8'."\n";

	$text=nl2br($text);

	$text.='<p>';
	$text.=_SEPARATOR.'<br>';
	$text.=_EMAIL_ADVERT_TEXT.'<br>';
	$text.=_SEPARATOR.'<br>';
	$text.=_SLOGAN.'<br>';
	$text.=_HOMEPAGE.'<br>';
	$text.='Email: '._ADMIN_EMAIL.'<br>';

	$text=stripslashes($text);

	$ret=mail($email_receiver, $subject, $text, $mailheaders);

	if ($ret==1) { $base_instance->show_message('Email sent','<table border=1 cellspacing=0 cellpadding=5 class="pastel" bgcolor="#ffffff"><tr><td><u>Receiver:</u><p><strong>'.$firstname_receiver.' '.$lastname_receiver.' '.$company_receiver.'</strong><br>'.$email_receiver.'</td><td><u>Sender:</u><p><strong>'.$firstname_sender.' '.$lastname_sender.'</strong><br>'.$email_sender.'</td></tr></table><p>'); }

	else { $base_instance->show_message('Email could not be sent',''); }

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);

	}

}

if (isset($_GET['diary_id'])) {

$diary_id=(int)$_GET['diary_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DIARY']['MAIN']} WHERE ID=$diary_id AND user='$userid'");

if (!$data) { $base_instance->show_message('Diary entry not found'); exit; }

$date_diary=$data[1]->date;
$title_diary=$data[1]->title;
$text_diary=$data[1]->text;

$text=$date_diary.' '.$title_diary."\n\n".$text_diary;
$subject='Diary '.$date_diary;

}

else if (isset($_GET['link_id'])) {

$link_id=(int)$_GET['link_id'];

$data=$base_instance->get_data("SELECT url,title,subtitle FROM {$base_instance->entity['LINK']['MAIN']} WHERE ID=$link_id AND user='$userid'");

if (!$data) { $base_instance->show_message('Link not found'); exit; }

$url_link=$data[1]->url;
$title_link=$data[1]->title;
$subtitle_link=$data[1]->subtitle;

$text='http://'.$url_link."\n".$title_link."\n".$subtitle_link;
$subject='Link';

}

else if (isset($_GET['knowledge_id'])) {

$knowledge_id=(int)$_GET['knowledge_id'];

$data=$base_instance->get_data("SELECT title,text FROM {$base_instance->entity['KNOWLEDGE']['MAIN']} WHERE ID=$knowledge_id AND user='$userid'");

if (!$data) { $base_instance->show_message('Knowledge not found'); exit; }

$title_knowledge=$data[1]->title;
$text_knowledge=$data[1]->text;

$text=$title_knowledge."\n\n".$text_knowledge;
$subject='Knowledge';

}

else if (isset($_GET['to_do_id'])) {

$to_do_id=(int)$_GET['to_do_id'];

$data=$base_instance->get_data("SELECT title,text FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE ID=$to_do_id AND user='$userid'");

if (!$data) { $base_instance->show_message('To-Do not found'); exit; }

$title_to_do=$data[1]->title;
$text_to_do=$data[1]->text;

$text=$title_to_do."\n\n".$text_to_do;
$subject='To-Do';

}

else if (isset($_GET['note_id'])) {

$note_id=(int)$_GET['note_id'];

$data=$base_instance->get_data("SELECT title,text FROM {$base_instance->entity['NOTE']['MAIN']} WHERE ID=$note_id AND user='$userid'");

if (!$data) { $base_instance->show_message('Note not found'); exit; }

$title_note=$data[1]->title;
$text_note=$data[1]->text;

$text=$title_note."\n\n".$text_note;
$subject='Note';

}

else if (isset($_GET['contact_id'])) {

$contact_id=(int)$_GET['contact_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['MAIN']} WHERE ID=$contact_id AND user='$userid'");

if (!$data) { $base_instance->show_message('Contact not found'); exit; }

$firstname=$data[1]->firstname;
$lastname=$data[1]->lastname;
$email=$data[1]->email;
$telephone=$data[1]->telephone;
$mobile=$data[1]->mobile;
$url=$data[1]->url;
$address=$data[1]->address;
$company=$data[1]->company;
$notes=$data[1]->notes;

$text='';

if ($company) { $text.=$company."\n"; }
if ($firstname || $lastname) { $text.=$firstname.' '.$lastname."\n"; }
if ($email) { $text.=$email."\n"; }
if ($telephone) { $text.='Telephone: '.$telephone."\n"; }
if ($mobile) { $text.='Mobile Phone: '.$mobile."\n"; }
if ($address) { $text.='Address: '.$address."\n"; }
if ($notes) { $text.='Notes: '.$notes."\n"; }

$subject='Contact';

}

else if (isset($_GET['file_id'])) {

$file_id=(int)$_GET['file_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID=$file_id AND user='$userid'");

if (!$data) { $base_instance->show_message('File not found'); exit; }

$token=$data[1]->token;

if (!$token) {

	$token=md5(uniqid(rand(),true));

	$base_instance->query("UPDATE {$base_instance->entity['FILE']['MAIN']} SET token='$token' WHERE ID={$_GET['file_id']}");

}

$title_file=$data[1]->title;
$text_file=$data[1]->text;

if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/file-'.$token; }
else { $url=_HOMEPAGE.'/show-file-public.php?token='.$token; }

$text='';

if ($title_file) { $text.=$title_file."\n\n"; }
if ($text_file) { $text.=$text_file."\n\n"; }

$text.=$url;
$subject='File';

}

else if (isset($_GET['blog_id'])) {

$blog_id=(int)$_GET['blog_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['MAIN']} WHERE ID=$blog_id AND user='$userid'");

if (!$data) { $base_instance->show_message('Blog not found'); exit; }

$title_blog=$data[1]->title;
$text_blog=$data[1]->text;

$text=$title_blog."\n\n".$text_blog;
$subject='Blog';

}

else if (isset($_GET['blog_comment_id'])) {

$blog_comment_id=(int)$_GET['blog_comment_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['COMMENTS']} WHERE ID=$blog_comment_id");

if (!$data) { $base_instance->show_message('Blog Comment not found'); exit; }

$title_blog=$data[1]->title;
$text_blog=$data[1]->text;

$text=$title_blog."\n\n".$text_blog;
$subject='Blog Comment';

}

else { $base_instance->show_message('Error','Select receiver and text',1); exit; }

# build category select box

$select_box='&nbsp;<select name="receiver">';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['MAIN']} WHERE email<>'' AND user='$userid' ORDER BY firstname");

if (!$data) { $base_instance->show_message('You need to add a contact first','<a href="add-contact.php">[Add new Contact]</a>'); }

for ($index=1; $index <= sizeof($data); $index++) {

$contact_id=$data[$index]->ID;
$firstname=$data[$index]->firstname;
$lastname=$data[$index]->lastname;
$company=$data[$index]->company;

$select_box.='<option value='.$contact_id.'>'.$firstname.' '.$lastname.' '.$company;

}

$select_box.='</select>';

# who is the sender

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$userid");

$email_sender=$data[1]->email;
$firstname_sender=$data[1]->firstname;
$lastname_sender=$data[1]->lastname;

if (!$firstname_sender && !$lastname_sender) { $warning='<font color="#FF0000">You haven\'t entered your firstname and lastname.</font> It is recommended to <a href="edit-about-me.php">enter your details</a> before sending an email.<p>'; } else { $warning=''; }

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Send by Email',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'TEXT_CENTER'=>$warning,
'BUTTON_TEXT'=>'Send Email'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'From','TEXT2'=>"$firstname_sender $lastname_sender &lt;$email_sender&gt;",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'subject','VALUE'=>"$subject",'SIZE'=>50,'TEXT'=>'Email Subject'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Receiver:','TEXT2'=>"$select_box",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>90,'ROWS'=>12));

$html_instance->process();

?>