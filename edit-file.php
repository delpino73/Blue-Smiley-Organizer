<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$file_id=isset($_REQUEST['file_id']) ? (int)$_REQUEST['file_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];
	$new_category=$_POST['new_category'];
	$category_id=(int)$_POST['category_id'];
	$public=(int)$_POST['public'];

	if (!$category_id) { $error.='<li> Category cannot be left blank'; }

	if (!empty($title)) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['FILE']['CATEGORY'].' (title,user) VALUES ("'.sql_safe($new_category).'",'.$userid.')');

	$category_id=mysqli_insert_id($base_instance->db_link);

	}

	$base_instance->query('UPDATE '.$base_instance->entity['FILE']['MAIN'].' SET text="'.sql_safe($text).'",title="'.sql_safe($title).'",category='.$category_id.',public='.$public.' WHERE user='.$userid.' AND ID='.$file_id);

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	$base_instance->show_message('File updated','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelFile(item){if(confirm("Delete File?")){http.open(\'get\',\'delete-file.php?item=\'+item); http.send(null);}}</script>

<a href="add-file.php?category_id='.$category_id.'">[Upload more]</a> &nbsp;&nbsp; <a href="edit-file.php?file_id='.$file_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelFile(\''.$file_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?file_id='.$file_id.'">[Send]</a><p><a href="show-file-categories.php">[Show all Categories]</a> &nbsp; <a href="show-file.php?file_id='.$file_id.'">[Show File]</a> &nbsp; <a href="show-files.php">[Show all Files]</a><p><b>Internal Link:</b> [f'.$file_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-files.php?category_id='.$category_id.'">[Show]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE user='$userid' AND ID=$file_id");

if (!$data) { $base_instance->show_message('File not found'); exit; }

$datetime=$data[1]->datetime;
$title=$data[1]->title;
$text=$data[1]->text;
$category_id=$data[1]->category;
$public=$data[1]->public;

$datetime_converted=$base_instance->convert_date($datetime);

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit File',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update File'
));

# build category select box

$select_box='&nbsp;<select name="category_id">';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_box.="<option selected value=$ID>$category_title"; }
else { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="">';

#

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'file_id','VALUE'=>"$file_id"));

if (empty($error)) { $html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Added:','TEXT2'=>"$datetime_converted",'SECTIONS'=>2)); }

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>"$select_box",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>"$public",'TEXT'=>'File is'));

$html_instance->process();

?>