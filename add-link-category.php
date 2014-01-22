<?php

require 'class.base.php';
require 'class.html.php';
require 'class.misc.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];

	if (!$title) { $error.='<li> Category cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>50) { $error.='<li> Title is too long (Max. 50 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['LINK']['CATEGORY'].' (title,user,parent_id) VALUES ("'.sql_safe($title).'",'.$userid.','.$category_id.')');

	$cat_id=mysql_insert_id();

	$base_instance->show_message('Link Category saved','<a href="add-link.php?category_id='.$cat_id.'">[Add Link]</a> &nbsp;&nbsp; <a href="add-link-category.php">[Add Category]</a> &nbsp;&nbsp; <a href="edit-link-category.php?category_id='.$cat_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-link-category.php?category_id='.$cat_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a><p><a href="show-link-categories.php">[Show Link Categories]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);

	}

}

$select_box='&nbsp;<select name="category_id">';
if (empty($category_id)) { $select_box.="<option selected value=0>-- MAIN CATEGORY --"; }
else { $select_box.='<option value=0>-- MAIN CATEGORY --'; }

$select_box.=$misc_instance->build_category_select_box(0,$userid,0,$category_id);

$select_box.='</select>';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'New Link Category',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title.focus()"',
'INNER_TABLE_WIDTH'=>'400',
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Save Category'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>'','SIZE'=>35,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Subcategory of','TEXT2'=>"$select_box",'SECTIONS'=>2));

$html_instance->process();

?>