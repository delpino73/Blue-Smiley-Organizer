<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

$subject=isset($_POST['subject']) ? $_POST['subject'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';

if (isset($_POST['save'])) {

	$error='';

	if (!$subject) { $error.='<li> Subject cannot be left blank'; }
	else {

	$subject=str_replace('"','&quot;',$subject);

	if (strlen($subject)>100) { $error.='<li> Subject too long (Max. 100 Characters)'; }

	}

	if (!$text) { $error.='<li> Text cannot be left blank'; }

	if (!$error) {

	$datetime=$_POST['datetime'];

	$base_instance->query('INSERT INTO '.$base_instance->entity['NEWSLETTER']['MAIN'].' (datetime,text,subject) VALUES ("'.sql_safe($datetime).'","'.sql_safe($text).'","'.sql_safe($subject).'")');

	$newsletter_id=mysql_insert_id();

	$base_instance->show_message('Newsletter saved','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelNewsletter(item){if(confirm("Delete Newsletter?")){http.open(\'get\',\'delete-newsletter.php?item=\'+item); http.send(null);}}</script>

<a href="edit-newsletter.php?newsletter_id='.$newsletter_id.'">[Edit]</a> &nbsp;&nbsp;&nbsp; <a href="javascript:DelNewsletter(\''.$newsletter_id.'\')">[Delete]</a> &nbsp;&nbsp;&nbsp; <a href="send-newsletter.php?newsletter_id='.$newsletter_id.'&test=1">[Send Test Newsletter to Admin]</a><p><a href="send-newsletter.php?newsletter_id='.$newsletter_id.'&subscribed=1">[Send only to subscribed]</a> &nbsp;&nbsp; <a href="send-newsletter.php?newsletter_id='.$newsletter_id.'&all=1">[Send to all]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$subject=stripslashes($subject);

	}

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
'HEADER'=>'Add Newsletter',
'HEAD'=>$js,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.subject.focus()"',
'BUTTON_TEXT'=>'Save Newsletter'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'subject','VALUE'=>"$subject",'SIZE'=>50,'TEXT'=>'Subject'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>90,'ROWS'=>9));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<span class="fakelink" onclick="bigger(document.form1.text,100);">[+]</span>
<span class="fakelink" onclick="smaller(document.form1.text,100);">[-]</span>'));

$html_instance->process();

?>