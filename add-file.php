<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if ($userid!=_ADMIN_USERID && $base_instance->allow_file_upload==2) { $base_instance->show_message(_NO_FILE_UPLOAD_MSG,''); }

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';
$new_category=isset($_POST['new_category']) ? $_POST['new_category'] : '';
$overwrite=isset($_POST['overwrite']) ? 1 : 0;
$public=isset($_POST['public']) ? (int)$_POST['public'] : 1;

if (isset($_POST['save'])) {

	$error='';

	$error_code=$_FILES['file1']['error'];

	if ($error_code==1 or $error_code==2) {

	$max_filesize=ini_get('upload_max_filesize');

	if ($userid!=_ADMIN_USERID) {

	$error.='<li> Sorry current maximum file size is '.$max_filesize.'. Contact admin or upload smaller files';

	} else {

	$error.='<li> Current maximum file size is '.$max_filesize.'. Check README.TXT on how to increase maximum file size.';

	}

	}

	if ($error_code==3 or $error_code==4) {

	$error.='<li> File was not or only partly uploaded';

	}

	if (!$category_id && !$new_category) { $error.='<li> Category cannot be left blank'; }

	if ($new_category) {

	$duplicate=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['FILE']['CATEGORY'].' WHERE title="'.sql_safe($new_category).'" AND user='.$userid);

	if ($duplicate) { $error.='<li> Category with this name already exists'; }

	$new_category=str_replace('"','&quot;',$new_category);

	if (strlen($new_category)>50) { $error.='<li> Category title is too long (Max. 50 Characters)'; }

	}

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

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
	$source=$_FILES['file1']['tmp_name'];

	if ($source) {

#	$filename=basename($_FILES['file1']['name']);
	$filename=uniqid('file-').'-'.basename($_FILES['file1']['name']);

	$path=pathinfo($_FILES['file1']['name']);

	if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

	if ($overwrite==0) { $data=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['FILE']['MAIN'].' WHERE filename="'.sql_safe($filename).'" AND user='.$userid); } # do not overwrite

	else { $base_instance->query('DELETE FROM '.$base_instance->entity['FILE']['MAIN'].' WHERE filename="'.sql_safe($filename).'" AND user='.$userid); } # overwrite

	if (isset($data)) { $error.='<li> File with the same name already uploaded, tick box below to overwrite.'; }
	else {

	if (in_array($ext,$allowed_file_ext)) {

	$dest=$uploadpath.$filename;

	if (!@move_uploaded_file($source,$dest)) { $error.='<li> File could not be uploaded'; }

	} else {

	if ($userid==_ADMIN_USERID) { $error.='<li> Not an allowed file type, add extension to $allowed_file_ext in config.php if required or zip file before uploading'; }
	else { $error.='<li> Not an allowed file type, try to zip file before uploading'; }

	}

	}

	} else { $error.='<li> Please specify a file'; }

	}

	if (!$error) {

	chmod('./upload/'.$userid.'/'.$filename,0600);

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

<a href="add-file.php?category_id='.$category_id.'">[Upload more]</a> &nbsp;&nbsp; <a href="edit-file.php?file_id='.$file_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelFile(\''.$file_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?file_id='.$file_id.'">[Send]</a><p><a href="show-file-categories.php">[Show all Categories]</a> &nbsp; <a href="show-file.php?file_id='.$file_id.'">[Show File]</a> &nbsp; <a href="show-files.php">[Show all Files]</a><p><b>Internal Link:</b> [f'.$file_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-files.php?category_id='.$category_id.'">[Show]</a>'.$display_image);

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

# default category

if (empty($category_id)) {

$data=$base_instance->get_data('SELECT default_file_category FROM '.$base_instance->entity['USER']['MAIN'].' WHERE ID='.$userid);

$category_id=$data[1]->default_file_category;

}

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

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Upload File',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'FORM_ATTRIB'=>'enctype="multipart/form-data"',
'BUTTON_TEXT'=>'Upload File'
));

$html_instance->add_form_field(array('TYPE'=>'file','NAME'=>'file1','SIZE'=>45,'TEXT'=>'File'));

if ($overwrite) { $cb='<input type="Checkbox" name="overwrite" value="1" checked id="tick_overwrite">'; }
else { $cb='<input type="Checkbox" name="overwrite" value="1" id="tick_overwrite">'; }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$cb <label for=\"tick_overwrite\">Overwrite if same filename</label>",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>"$cat_title",'TEXT2'=>"$select_category",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>"$text",'COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>"$public",'TEXT'=>'File is'));

$html_instance->process();

?>