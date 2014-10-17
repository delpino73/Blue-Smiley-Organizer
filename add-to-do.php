<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';
$new_category=isset($_POST['new_category']) ? $_POST['new_category'] : '';
$priority=isset($_POST['priority']) ? (int)$_POST['priority'] : '';
$public=isset($_POST['public']) ? (int)$_POST['public'] : 1;
$status=isset($_POST['status']) ? (int)$_POST['status'] : 0;

if (isset($_POST['save'])) {

	$error='';

	if (!$category_id && !$new_category) { $error.='<li> Category cannot be left blank'; }

	if ($new_category) {

	$duplicate=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['TO_DO']['CATEGORY'].' WHERE title="'.sql_safe($new_category).'" AND user='.$userid);

	if ($duplicate) { $error.='<li> Category with this name already exists'; }

	$new_category=str_replace('"','&quot;',$new_category);

	if (strlen($new_category)>50) { $error.='<li> Category title is too long (Max. 50 Characters)'; }

	}

	if (!$title && !$text) { $error.='<li> Both Title and Text cannot be blank'; }

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if ($text) {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$priority) { $priority=5; }

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['TO_DO']['CATEGORY'].' (title,user) VALUES ("'.sql_safe($new_category).'",'.$userid.')');

	$category_id=mysqli_insert_id($base_instance->db_link);

	}

	$datetime=$_POST['datetime'];

	$html_instance->check_for_duplicates('TO_DO','MAIN',$datetime,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['TO_DO']['MAIN'].' (datetime,text,title,user,priority,category,public,status) VALUES ("'.sql_safe($datetime).'","'.sql_safe($text).'","'.sql_safe($title).'",'.$userid.','.$priority.','.$category_id.','.$public.','.$status.')');

	$to_do_id=mysqli_insert_id($base_instance->db_link);

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	$base_instance->show_message('To-Do saved','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelToDo(item){if(confirm("Delete To-Do?")){http.open(\'get\',\'delete-to-do.php?item=\'+item); http.send(null);}}</script>

<a href="add-to-do.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-to-do.php?to_do_id='.$to_do_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelToDo(\''.$to_do_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?to_do_id='.$to_do_id.'">[Send]</a><p><a href="show-to-do-categories.php">[Show all Categories]</a> &nbsp;&nbsp; <a href="show-to-do.php">[Show all To-Do]</a><p><b>Internal Link:</b> [t'.$to_do_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-to-do.php?category_id='.$category_id.'">[Show]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

# default category

if (!$category_id) {

$data=$base_instance->get_data("SELECT default_todo_category FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$category_id=$data[1]->default_todo_category;

}

# default priority

if (empty($priority)) {

$data=$base_instance->get_data("SELECT default_todo_priority FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$priority=$data[1]->default_todo_priority;

}

# build category section

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $cat_title='New Category:'; $select_box='&nbsp;<input type="text" name="new_category" value="'.$new_category.'">'; }

else {

$cat_title='Category:';

$select_box='&nbsp;<select name="category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_box.="<option selected value=$ID>$category_title"; }
else { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="'.$new_category.'" size="12">';

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
'HEADER'=>'Add To-Do &nbsp;&nbsp; <a href="help-to-do.php">[Help]</a>',
'HEAD'=>$js,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.text.focus()"',
'BUTTON_TEXT'=>'Save To-Do'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>$cat_title,'TEXT2'=>$select_box,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'priority','VALUE'=>$priority,'OPTION'=>'priority_array','TEXT'=>'Priority'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'status','VALUE'=>$status,'OPTION'=>'todo_status_array','TEXT'=>'Status','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>120,'ROWS'=>12));

$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>$public,'TEXT'=>'To-Do is'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<span class="fakelink" onclick="bigger(document.form1.text,100);">[+]</span>
<span class="fakelink" onclick="smaller(document.form1.text,100);">[-]</span>'));

$html_instance->process();

?>