<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$note_id=isset($_REQUEST['note_id']) ? (int)$_REQUEST['note_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];
	$new_category=$_POST['new_category'];
	$category_id=(int)$_POST['category_id'];
	$public=(int)$_POST['public'];

	if (!$category_id) { $error.='<li> Category cannot be left blank'; }

	if (!$title && !$text) { $error.='<li> Both Title and Text cannot be blank'; }

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }

	}

	if ($text) {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['NOTE']['CATEGORY'].' (title,user) VALUES ("'.sql_safe($new_category).'",'.$userid.')');

	$category_id=mysql_insert_id();

	}

	$base_instance->query('UPDATE '.$base_instance->entity['NOTE']['MAIN'].' SET text="'.sql_safe($text).'",title="'.sql_safe($title).'",category='.$category_id.',public='.$public.' WHERE user='.$userid.' AND ID='.$note_id);

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	$base_instance->show_message('Note updated','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelNote(item){if(confirm("Delete Note?")){http.open(\'get\',\'delete-note.php?item=\'+item); http.send(null);}}</script>

<a href="add-note.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-note.php?note_id='.$note_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelNote(\''.$note_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?note_id='.$note_id.'">[Send]</a><p><a href="show-note-categories.php">[Show all Categories]</a> &nbsp;&nbsp; <a href="show-note.php">[Show all Notes]</a><p><b>Internal Link:</b> [n'.$note_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-note.php?category_id='.$category_id.'">[Show]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NOTE']['MAIN']} WHERE user='$userid' AND ID='$note_id'");

if (!$data) { $base_instance->show_message('Note not found','',1); }

$datetime=$data[1]->datetime;
$title=$data[1]->title;
$text=$data[1]->text;
$category_id=$data[1]->category;
$public=$data[1]->public;

$title=str_replace('"','&quot;',$title);
$datetime_converted=$base_instance->convert_date($datetime);

}

#

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
'HEADER'=>'Edit Note',
'HEAD'=>$js,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update Note'
));

# build category select box

$select_box='&nbsp;<select name="category_id">';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_box.="<option selected value=$ID>$category_title"; }
else { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="">';

#

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'note_id','VALUE'=>$note_id));

if (empty($error)) { $html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Added:','TEXT2'=>$datetime_converted,'SECTIONS'=>2)); }

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>45,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>$select_box,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>120,'ROWS'=>12));

$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>$public,'TEXT'=>'Note is'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<span class="fakelink" onclick="bigger(document.form1.text,100);">[+]</span>
<span class="fakelink" onclick="smaller(document.form1.text,100);">[-]</span>'));

$html_instance->process();

?>