<?php

set_time_limit(0);

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if ($userid!=_ADMIN_USERID) { exit; }

$overwrite=isset($_POST['overwrite']) ? 1 : 0;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];
	$category_id=(int)$_POST['category_id'];

	if (!$category_id) { $error.='<li> Category cannot be left blank'; }

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	# check if only organizer tables

	$data=$base_instance->get_data('SHOW TABLE STATUS');

	for ($index=1; $index <= sizeof($data); $index++) {

	$table_name=$data[$index]->Name;

	$first_part=substr($table_name,0,10);

	if ($first_part!='organizer_') {

	$base_instance->show_message('Database Backup not possible','Backup is only possible if database only contains organizer tables.');

	}

	}

	#

	$res=is_dir('./upload/'.$userid.'/');

	if (!$res) { system('mkdir ./upload/'.$userid.'/'); }

	$db_password=_DB_PW;
	$db_uid=_DB_USER;
	$db_name_live=_DB_NAME;
	$userid=_ADMIN_USERID;

	$date=date('Y-m-d');
	$filename="mysql_$date.gz";

	`mysqldump -u $db_uid -p$db_password --opt $db_name_live | gzip > ./upload/$userid/$filename`;

	}

	if (!$error) {

	$base_instance->query('DELETE FROM '.$base_instance->entity['FILE']['MAIN'].' WHERE filename="'.sql_safe($filename).'" AND user='.$userid);

	$datetime=$_POST['datetime'];

	$base_instance->query('INSERT INTO '.$base_instance->entity['FILE']['MAIN'].' (datetime,text,title,filename,user,category) VALUES ("'.sql_safe($datetime).'","'.sql_safe($text).'","'.sql_safe($title).'","'.sql_safe($filename).'",'.$userid.','.$category_id.')');

	$title=stripslashes($title);

	$base_instance->show_message('Database Backup saved','<a href="show-files.php">[Show all]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';

# build category select box

$select_box='&nbsp;<select name="category_id">';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $base_instance->show_message('Categories','You need at least one category.<p><a href="add-file-category.php">[New File Category]</a>'); }

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_box.="<option selected value=$ID>$category_title"; }
else { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select>';

#

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Backup Database',
'TEXT_CENTER'=>'Here you can make a complete backup of all MySQL tables of the organizer.<br>It is recommended to make backups regularly and download it to your local computer.<br><strong>This is experimental do not rely on this backup!</strong><p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'FORM_ATTRIB'=>'enctype="multipart/form-data"',
'BUTTON_TEXT'=>'Backup Database'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'MAX_FILE_SIZE','VALUE'=>'800000'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>'','SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>"$select_box",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>'','COLS'=>50,'ROWS'=>3,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->process();

?>