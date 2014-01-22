<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if ($userid!=_ADMIN_USERID && $base_instance->allow_file_upload==2) { $base_instance->show_message(_NO_FILE_UPLOAD_MSG,''); }

$new_category=isset($_POST['new_category']) ? $_POST['new_category'] : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';
$public=isset($_POST['public']) ? (int)$_POST['public'] : 1;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];
	$source=$_POST['source'];

	if (!$category_id && !$new_category) { $error.='<li> Category cannot be left blank'; }

	if ($new_category) {

	$duplicate=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['FILE']['CATEGORY'].' WHERE title="'.sql_safe($new_category).'" AND user='.$userid);

	if ($duplicate) { $error.='<li> Category with this name already exists'; }

	$new_category=str_replace('"','&quot;',$new_category);

	if (strlen($new_category)>50) { $error.='<li> Category title is too long (Max. 50 Characters)'; }

	}

	if ($title) {

	$title=trim($title);
	$title=str_replace('"','&quot;',$title);

	if (strlen($title)>100) { $error.='<li> Title too long (Max. 100 Characters)'; }

	}

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['FILE']['CATEGORY'].' (title,user) VALUES ("'.sql_safe($new_category).'",'.$userid.')');

	$category_id=mysql_insert_id();
	$new_category='';

	}

	$res=is_dir('./upload/'.$userid.'/');

	if (!$res) { system('mkdir ./upload/'.$userid.'/'); }

	$uploadpath='./upload/'.$userid.'/';

	if ($source) {

	$imagesize=@getimagesize($source);

	switch ($imagesize[2]) {

	case 0: $error.='<li> Not an image file (only gif, jpg or png allowed)'; break;
	case 1: $filename=uniqid('img').'.gif'; $dest=$uploadpath.$filename; break;
	case 2: $filename=uniqid('img').'.jpg'; $dest=$uploadpath.$filename; break;
	case 3: $filename=uniqid('img').'.png'; $dest=$uploadpath.$filename; break;

	}

	if (isset($dest)) { if (!copy($source,$dest)) { $error.='<li> File could not be stored'; } }

	} else { $error.='<li> File not supplied or too big'; }

	}

	if (!$error) {

	$datetime=$_POST['datetime'];

	if ($public==2) { $token='t'.md5(uniqid(rand(),true)); } else { $token=''; }

	$base_instance->query('INSERT INTO '.$base_instance->entity['FILE']['MAIN'].' (datetime,text,title,filename,user,category,public,token) VALUES ("'.sql_safe($datetime).'","'.sql_safe($text).'","'.sql_safe($title).'","'.sql_safe($filename).'",'.$userid.','.$category_id.','.$public.',"'.$token.'")');

	$file_id=mysql_insert_id();

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	#

	$path=pathinfo($filename);

	if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

	if ($ext=='gif' or $ext=='png' or $ext=='jpg' or $ext=='jpeg') {

	$display_image='<p><b>Display Image:</b> [image-'.$file_id.']';

	} else { $display_image=''; }

	#

	$base_instance->show_message('File uploaded','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelFile(item){if(confirm("Delete File?")){http.open(\'get\',\'delete-file.php?item=\'+item); http.send(null);}}</script>

<a href="add-file-by-url.php?category_id='.$category_id.'">[Upload more]</a> &nbsp;&nbsp; <a href="edit-file.php?file_id='.$file_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelFile(\''.$file_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?file_id='.$file_id.'">[Send]</a><p><a href="show-file-categories.php">[Show all Categories]</a> &nbsp; <a href="show-file.php?file_id='.$file_id.'">[Show File]</a> &nbsp; <a href="show-files.php">[Show all Files]</a><p><b>Internal Link:</b> [i'.$file_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-files.php?category_id='.$category_id.'">[Show]</a>'.$display_image);

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

$category_id=isset($_GET['category_id']) ? $_GET['category_id'] : '';

# build category section

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $cat_title='New Category:'; $select_category='&nbsp;<input type="text" name="new_category" value="'.$new_category.'">'; }

else {

$cat_title='Category:';

$select_category='&nbsp;<select name="category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_category.="<option selected value=$ID>$category_title"; }
else { $select_category.="<option value=$ID>$category_title"; }

}

$select_category.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="'.$new_category.'" size="12">';

}

#

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$source=isset($_POST['source']) ? $_POST['source'] : '';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Upload Image by URL',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.source.focus()"',
'BUTTON_TEXT'=>'Upload File'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'source','VALUE'=>"$source",'SIZE'=>55,'TEXT'=>'Image URL'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>55,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>"$select_category",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>"$public",'TEXT'=>'File is'));

$html_instance->process();

?>