<?php

$flush=1;

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if ($userid==_GUEST_USERID) { $base_instance->show_message('Import not possible','Importing bookmarks is deactivated for the guest account. Please click <a href="logout.php?signup=1" target="_top">here</a> to sign up.'); exit; }

if (isset($_POST['save'])) {

	$error='';

	$source=$_FILES['file1']['tmp_name'];

	if ($source) {

	echo '<html><head>',_CSS,'</head><body ',_BACKGROUND,'><br><center><table width=400 bgcolor="#ffffff" cellpadding=4 cellspacing=0 border=0><tr><td><center><h1>Importing Bookmarks, please wait</h1><img src="pics/progress.gif"></center><br></td></tr></table><br></body>';

	$token=md5(uniqid(rand(),true));

	$dest='./import/bookmarks'.$token.'.html';

	if ($dest) { if (!@move_uploaded_file($source,$dest)) { $error.='<li> File could not be stored'; } }

	} else { $error.='<li> File not supplied or too big'; }

	if (empty($error)) { import_bookmarks($userid,'./import/bookmarks'.$token.'.html'); }
	else { $html_instance->error_message=$error; }

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Import Bookmarks &nbsp;&nbsp; <a href="help-link.php#import">[Help]</a>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'ENCTYPE'=>'multipart/form-data',
'BUTTON_TEXT'=>'Upload Bookmarks',
'FORM_ATTRIB'=>'enctype="multipart/form-data"'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'MAX_FILE_SIZE','VALUE'=>'800000'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"Here you can upload your existing bookmark file (export it from your browser), duplicate URLs will be filtered. All your bookmarks are set to private.<p>Uploading your bookmarks might take a few seconds, please wait after clicking 'Upload Bookmarks'.<p>"));

$html_instance->add_form_field(array('TYPE'=>'file','NAME'=>'file1','SIZE'=>40,'TEXT'=>'File'));

$html_instance->process();

#

function import_bookmarks($userid, $userfile) {

	global $base_instance;

	$bookmark_count=0; $duplicate_count=0;

	$fp=fopen($userfile,'r');

	$first_line=fgets($fp,4096);
	$mozilla_line='<!DOCTYPE NETSCAPE-Bookmark-file-1>';

	$first_line=trim($first_line);

	if ($mozilla_line!=$first_line) {

	echo '<meta http-equiv="refresh" content="1; URL=import-bookmarks-result.php?error=1">'; exit;

	}

	$stack=array();

	while (!feof($fp)) {

	$current_line=fgets($fp,4096);

	if ($bookmark_count % 100==0) { echo ' '; } # make sure browser doesn't timeout

	# start a folder, only used for indentation

	#if (eregi("^[ ]*<DL",$current_line)) {}

	if (preg_match('/^[ ]*<DL/i',$current_line)) {}

	# ends a (sub)folder

	#else if (eregi("^[ ]*<\/DL",$current_line))	{

	else if (preg_match('/^[ ]*<\/DL/i',$current_line)) {

	array_pop($stack);
	$current_folder=end($stack);

	}

	# starts a bookmark

#	else if (eregi("^[ ]*<DT",$current_line)) {

	else if (preg_match('/^[ ]*<DT/i',$current_line)) {

	#if (eregi("<A",$current_line)) {

	if (preg_match('/<A/i',$current_line)) {

	#eregi(">([^\"]*)</A>",$current_line,$tt);

	preg_match("/>([^\"]*)<\/A>/i",$current_line,$tt);

	#$title=$tt[1];
	if (!empty($tt[1])) { $title=$tt[1]; } else { $title='No title'; }

	#eregi("HREF=\"http([^\"]*)\"",$current_line,$uu);
	preg_match("/HREF=\"http([^\"]*)\"/i",$current_line,$uu);

	if (!empty($uu[1])) { $url='http'.$uu[1]; } else { continue; }

	#$url='http'.$uu[1];

	#echo '['.$current_line.'/'.$url.']<br />';

	#eregi("ADD_DATE=\"([0-9]+)\"",$current_line,$ll);
	preg_match("/ADD_DATE=\"([0-9]+)\"/i",$current_line,$ll);

	if (!empty($ll[1])) { $added=date('Y-m-d H:i:s',$ll[1]); } else { $added='2000-01-01 12:00:00'; }

	#eregi("LAST_VISIT=\"([0-9]+)\"",$current_line,$vv);
	preg_match("/LAST_VISIT=\"([0-9]+)\"/i",$current_line,$vv);

	if (!empty($vv)) { $last_visit=date('Y-m-d H:i:s',$vv[1]); } else { $last_visit=''; }

	$current_folder=end($stack);

	$bookmark_count++;

	$title=substr($title,0,35);

	$title=sql_safe($title);
	$url=sql_safe($url);

	if (preg_match("/^http:\/\//i",$url)) { $url=substr($url,7); }

	# check for duplicates

	if (!empty($url)) {

	if (preg_match('/\/$/',$url)) { $url2=substr($url,0,-1); } else { $url2=$url; }

	$data_duplicate=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE (url='$url2' OR url='$url2/') AND user='$userid'");

	if ($data_duplicate) { $duplicate_count++; }

	}

	if (!$data_duplicate) {

	if ($current_folder) {
	$base_instance->query("INSERT INTO organizer_link (datetime,url,user,category,public,title,frequency,last_visit) VALUES ('$added','$url',$userid,'$current_folder',1,'$title','2592000','$last_visit')");
	}

	else if (isset($main_folder)) {
	$base_instance->query("INSERT INTO organizer_link (datetime,url,user,category,public,title,frequency,last_visit) VALUES ('$added','$url',$userid,'$main_folder',1,'$title','2592000','$last_visit')");
	}

	else { # create new folder, as link has no folder

	$base_instance->query("INSERT INTO organizer_link_category (title,user,parent_id) VALUES ('main',$userid,0)");
	$main_folder=mysqli_insert_id($base_instance->db_link);

	$base_instance->query("INSERT INTO organizer_link (datetime,url,user,category,public,title,frequency,last_visit) VALUES ('$added','$url',$userid,'$main_folder',1,'$title','2592000','$last_visit')");

	}

	}

	unset($data_duplicate);

	}

	# start a folder

	else {

	#$name=eregi_replace("^( *<DT><[^>]*>)([^<]*)(.*)","\\2",$current_line);
	$name=preg_replace("/^( *<DT><[^>]*>)([^<]*)(.*)/","\\2",$current_line);

	if (!$stack) { # empty stack

	$name=sql_safe($name);

	$base_instance->query("INSERT INTO organizer_link_category (title,user,parent_id) VALUES ('$name',$userid,0)");
	$category_id=mysqli_insert_id($base_instance->db_link);

	array_push($stack, $category_id);

	}

	else {

	$parent_id=end($stack);

	$name=sql_safe($name);

	$base_instance->query("INSERT INTO organizer_link_category (title,user,parent_id) VALUES ('$name',$userid,'$parent_id')");

	$category_id=mysqli_insert_id($base_instance->db_link);

	array_push($stack, $category_id);

	}

	}

	}

	}

	fclose($fp);

	echo '<meta http-equiv="refresh" content="2; URL=import-bookmarks-result.php?bookmarks=',$bookmark_count,'&duplicates=',$duplicate_count,'">'; exit;

}

?>