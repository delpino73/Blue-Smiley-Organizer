<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

$newsletter_id=isset($_REQUEST['newsletter_id']) ? (int)$_REQUEST['newsletter_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$subject=$_POST['subject'];
	$text=$_POST['text'];

	if ($subject) {

		$subject=trim($subject);
		$subject=str_replace('"','&quot;',$subject);
		if (strlen($subject)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }

	}

	if ($text) {

		$text=trim($text);
		if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

		$base_instance->query('UPDATE '.$base_instance->entity['NEWSLETTER']['MAIN'].' SET text="'.sql_safe($text).'",subject="'.sql_safe($subject).'" WHERE ID='.$newsletter_id);

		$base_instance->show_message('Newsletter updated','<a href="edit-newsletter.php?newsletter_id='.$newsletter_id.'">[Edit]</a> &nbsp;&nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-newsletter.php?newsletter_id='.$newsletter_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp;&nbsp; <a href="send-newsletter.php?newsletter_id='.$newsletter_id.'&test=1">[Send Test Newsletter to Admin]</a> <p> <a href="send-newsletter.php?newsletter_id='.$newsletter_id.'&subscribed=1">[Send only to subscribed]</a> <p> <a href="send-newsletter.php?newsletter_id='.$newsletter_id.'&all=1">[Send to all]</a>');

	}

	else {

		$html_instance->error_message=$error;
		$text=stripslashes($text);
		$title=stripslashes($title);

	}

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NEWSLETTER']['MAIN']} WHERE ID=$newsletter_id");

	if (!$data) { $base_instance->show_message('Newsletter not found','',1); }

	$datetime=$data[1]->datetime;
	$subject=$data[1]->subject;
	$text=$data[1]->text;

}

$js='<script language="JavaScript" type="text/javascript">

function bigger(what,add) {
if (what.style.height==\'\') { what.style.height=\'300px\'; }
newHeight=parseInt(what.style.height)+add;
what.style.height=newHeight+"px";
}

function smaller(what,deduct) {
if ((parseInt(what.style.height)-deduct) > 50) {
newHeight=parseInt(what.style.height)-deduct;
what.style.height=newHeight+"px";
} else { newHeight=50; what.style.height="50px"; }
}

</script>';

$html_instance->add_parameter(
	array('ACTION'=>'show_form',
		'HEADER'=>'Edit Newsletter',
		'HEAD'=>$js,
		'FORM_ACTION'=>$_SERVER['PHP_SELF'],
		'BUTTON_TEXT'=>'Update Newsletter'
	));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'newsletter_id','VALUE'=>$newsletter_id));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'subject','VALUE'=>$subject,'SIZE'=>50,'TEXT'=>'Subject'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>90,'ROWS'=>20));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<span class="fakelink" onclick="bigger(document.form1.text,100);">[+]</span>
<span class="fakelink" onclick="smaller(document.form1.text,100);">[-]</span>'));

$html_instance->process();

?>