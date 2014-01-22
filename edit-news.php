<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

$news_id=isset($_REQUEST['news_id']) ? (int)$_REQUEST['news_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if ($text) {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

	$base_instance->query('UPDATE '.$base_instance->entity['NEWS']['MAIN'].' SET text="'.sql_safe($text).'",title="'.sql_safe($title).'" WHERE ID='.$news_id);

	$base_instance->show_message('News updated','<a href="edit-news.php?news_id='.$news_id.'">[Edit]</a> &nbsp;&nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-news.php?news_id='.$news_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NEWS']['MAIN']} WHERE ID=$news_id");

if (!$data) { $base_instance->show_message('News not found','',1); }

$datetime=$data[1]->datetime;
$title=$data[1]->title;
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
'HEADER'=>'Edit News',
'HEAD'=>$js,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update News'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'news_id','VALUE'=>"$news_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>90,'ROWS'=>9));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<span class="fakelink" onclick="bigger(document.form1.text,100);">[+]</span>
<span class="fakelink" onclick="smaller(document.form1.text,100);">[-]</span>'));

$html_instance->process();

?>