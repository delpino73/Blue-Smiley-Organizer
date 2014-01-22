<?php

set_time_limit(0);

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if ($userid!=_ADMIN_USERID) { exit; }

if (isset($_POST['save'])) {

	$error='';

	if (!$error) {

	$res=is_dir('./upload/');

	if (!$res) { system('mkdir ./upload/'); }

	$uploadpath='./upload/';
	$source=$_FILES['file1']['tmp_name'];
	$errormsg=$_FILES['userfile']['error'];

	if ($source) {

	$filename=basename($_FILES['file1']['name']);
	$path=pathinfo($_FILES['file1']['name']);
	$ext=strtolower($path['extension']);

	if ($ext=='gz') {

	$dest=$uploadpath.$filename;

	if (!@move_uploaded_file($source, $dest)) { $error.='<li> File could not be uploaded'; }

	} else { $error.='<li> Only gz extension allowed'; }

	} else { $error.='<li> Upload unsuccessful. File probably too big. Restore backup from shell'.$errormsg; }

	}

	if (!$error) {

	$data=$base_instance->get_data('SHOW TABLE STATUS');

	for ($index=1; $index <= sizeof($data); $index++) {

	$table_name=$data[$index]->Name;

	$first_part=substr($table_name,0,10);

	if ($first_part=='organizer_') { $base_instance->query("DROP TABLE $table_name"); }
	else { echo 'Table "',$table_name,'" not dropped<br>'; }

	}

	$userid=_ADMIN_USERID;

	$filename2=substr($filename,0,-3);

	`gunzip ./upload/$filename`;

	$loginname=_DB_USER;
	$password=_DB_PW;
	$db_name=_DB_NAME;

	`mysql -u $loginname -p$password $db_name < ./upload/$filename2`;

	unlink("./upload/$filename2");

	echo 'Finished. Please <a href="index.php" target="_blank">login</a> here.'; exit;

	}

	else { $html_instance->error_message=$error; }

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Restore Backup',
'TEXT_CENTER'=>'Upload Backup File here (only gz extension). <font color="#FF0000">Be aware that this will delete all organizer tables of your current database!</font><br>If the backup file is very big you might be unable to successfully upload it from here. In this case you will have to restore it manually from shell.<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'FORM_ATTRIB'=>'enctype="multipart/form-data"',
'BUTTON_TEXT'=>'Upload Backup'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'MAX_FILE_SIZE','VALUE'=>'10000000'));

$html_instance->add_form_field(array('TYPE'=>'file','NAME'=>'file1','SIZE'=>45,'TEXT'=>'File'));

$html_instance->process();

?>