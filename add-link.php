<?php

require 'class.base.php';
require 'class.html.php';
require 'class.misc.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$text='';

$title=isset($_POST['title']) ? $_POST['title'] : '';
$url=isset($_REQUEST['url']) ? $_REQUEST['url'] : '';

if (isset($_GET['e1'])) { $title=base64_decode($_GET['e1']); }
if (isset($_GET['e2'])) {

$url=base64_decode($_GET['e2']);

#

	if ($url && $url!='http://') {

	$url_save=$url;

	if (preg_match("/^http:\/\//i",$url_save)) { $url_save=substr($url_save,7); }

	if (preg_match('/\/$/',$url_save)) { $url_test=substr($url_save,0,-1); } else { $url_test=$url_save; }

	$data=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['LINK']['MAIN'].' WHERE (url="'.sql_safe($url_test).'" OR url="'.sql_safe($url_test).'/") AND user='.$userid);

	if ($data) { $link_id=$data[1]->ID; $text='<font color="#FF0000">You\'ve already saved this link</font> <a href="edit-link.php?link_id='.$link_id.'">[Edit Link]</a><p>'; }

	}

#

}

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : 0;
$subtitle=isset($_POST['subtitle']) ? $_POST['subtitle'] : '';
$sequence=isset($_POST['sequence']) ? (int)$_POST['sequence'] : '';
$number_of_days=isset($_POST['number_of_days']) ? (int)$_POST['number_of_days'] : 0;
$number_of_hours=isset($_POST['number_of_hours']) ? (int)$_POST['number_of_hours'] : 0;
$number_of_mins=isset($_POST['number_of_mins']) ? (int)$_POST['number_of_mins'] : 0;
$public=isset($_POST['public']) ? (int)$_POST['public'] : 1;
$mode=isset($_POST['mode']) ? (int)$_POST['mode'] : 3;
$notes=isset($_POST['notes']) ? $_POST['notes'] : '';
$speed=isset($_POST['speed']) ? (int)$_POST['speed'] : 50;
$keywords=isset($_POST['keywords']) ? $_POST['keywords'] : '';
$new_category=isset($_POST['new_category']) ? $_POST['new_category'] : '';

if (isset($_POST['save'])) {

	$error='';

	if ($speed==0) { $speed=50; }

	if (!$category_id && !$new_category) { $error.='<li> Category cannot be left blank'; }

	if ($new_category) {

	$duplicate=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['LINK']['CATEGORY'].' WHERE title="'.sql_safe($new_category).'" AND user='.$userid);

	if ($duplicate) { $error.='<li> Category with this name already exists'; }

	$new_category=str_replace('"','&quot;',$new_category);

	if (strlen($new_category)>50) { $error.='<li> Category title is too long (Max. 50 Characters)'; }

	}

	# check title

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>70) { $error.='<li> Title is too long (Max. 70 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	# notes

	if ($notes) {

	$notes=trim($notes);
	if (strlen($notes)>65535) { $error.='<li> Notes are too long (Max. 65535 Characters)'; }

	}

	# check subtitle

	if (!$subtitle) {

	$subtitle=trim($subtitle);
	if (strlen($subtitle)>75) { $error.='<li> Subtitle is too long (Max. 75 Characters)'; }

	}

	# check URL

	if (!$url or $url=='http://') { $error.='<li> URL cannot be left blank'; }
	else {

	$url=trim($url);
	if (strlen($url)>400) { $error.='<li> URL is too long (Max. 400 Characters)'; }

	}

	# URL duplicate check

	if ($url && $url!='http://') {

	$url_save=$url;

	if (preg_match("/^http:\/\//i",$url_save)) { $url_save=substr($url_save,7); }

	if (preg_match('/\/$/',$url_save)) { $url_test=substr($url_save,0,-1); } else { $url_test=$url_save; }

	$data=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['LINK']['MAIN'].' WHERE (url="'.sql_safe($url_test).'" OR url="'.sql_safe($url_test).'/") AND user='.$userid);

	if ($data) { $link_id=$data[1]->ID; $base_instance->show_message('Link already saved','<a href="edit-link.php?link_id='.$link_id.'">[Edit Link]</a>'); }

	}

	$freq_total=0;

	if ($number_of_days > 0) { $freq_total+=$number_of_days*86400; }
	if ($number_of_hours > 0) { $freq_total+=$number_of_hours*3600; }
	if ($number_of_mins > 0) { $freq_total+=$number_of_mins*60; }

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['LINK']['CATEGORY'].' (title,user,parent_id) VALUES ("'.sql_safe($new_category).'",'.$userid.','.$category_id.')');

	$category_id=mysql_insert_id();

	}

	$datetime=$_POST['datetime'];

	$base_instance->query('INSERT INTO '.$base_instance->entity['LINK']['MAIN'].' (datetime,subtitle,url,user,category,public,title,frequency,frequency_mode,last_visit,notes,keywords,speed,sequence) VALUES ("'.sql_safe($datetime).'","'.sql_safe($subtitle).'","'.sql_safe($url_save).'",'.$userid.','.$category_id.','.$public.',"'.sql_safe($title).'",'.$freq_total.','.$mode.',"'.$datetime.'","'.sql_safe($notes).'","'.sql_safe($keywords).'","'.$speed.'","'.$sequence.'")');

	$link_id=mysql_insert_id();

	$base_instance->show_message('Link saved','<a href="add-link.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?link_id='.$link_id.'">[Send]</a> &nbsp;&nbsp; <a href="show-links.php">[Show all]</a><p>');

	}

	else {

	$html_instance->error_message=$error;
	$subtitle=stripslashes($subtitle);
	$keywords=stripslashes($keywords);
	$notes=stripslashes($notes);

	}

}

$title=stripslashes($title);

# default category

if (!$category_id) {

$data=$base_instance->get_data("SELECT default_link_category FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$category_id=$data[1]->default_link_category;

}

#

$select_box=$misc_instance->build_category_select_box(0,$userid,0,$category_id);

if (!$select_box) { $cat_title='New Category:'; $select_category='&nbsp;<input type="text" name="new_category" size="50" value="'.$new_category.'">'; }

else {

$cat_title='Category:';

$select_category='&nbsp;<select name="category_id"><option selected value=0>-- Choose Category --'.$select_box.'</select> or

<tr><td align="right"><b>New Category:</b></td><td align="left">&nbsp;<input type="text" name="new_category" size="50" value="'.$new_category.'"></td></tr>';

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Link &nbsp;&nbsp; <a href="help-link.php" target="_blank">[Help]</a>',
'TEXT_CENTER'=>$text,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:self.focus();document.form1.url.focus()"',
'INNER_TABLE_WIDTH'=>'500',
'TD_WIDTH'=>'25%',
'BUTTON_TEXT'=>'Save Link'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'url','VALUE'=>"$url",'SIZE'=>50,'TEXT'=>'URL'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>"$cat_title",'TEXT2'=>"$select_category",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'subtitle','VALUE'=>"$subtitle",'SIZE'=>50,'TEXT'=>'Subtitle'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'speed','VALUE'=>"$speed",'OPTION'=>'speed_array','TEXT'=>'Ascent Speed','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'sequence','VALUE'=>"$sequence",'SIZE'=>10,'TEXT'=>'Sequence ID'));

$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>"$public",'TEXT'=>'Link is'));

if (!$number_of_days && !$number_of_hours && !$number_of_mins) { $number_of_days=30; }

if ($mode==1) { $m1_checked=' CHECKED'; } else { $m1_checked=''; }
if ($mode==2) { $m2_checked=' CHECKED'; } else { $m2_checked=''; }
if ($mode==3) { $m3_checked=' CHECKED'; } else { $m3_checked=''; }

$freq_text='<table cellpadding="5"><tr><td><input type="Radio" name="mode" value="1" id="m1"'.$m1_checked.'><label for="m1">Always</label></td><td><input type="Radio" name="mode" value="2" id="m2"'.$m2_checked.'><label for="m2">Never</label></td><td><input type="Radio" name="mode" value="3" id="m3"'.$m3_checked.'><label for="m3">Every ..</label></td></table>';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Show Link in Bluebox:','TEXT2'=>"$freq_text",'SECTIONS'=>2));

$freq_text2='<p>Days: <input type="text" name="number_of_days" size="3" value="'.$number_of_days.'"> &nbsp; Hours: <input type="text" name="number_of_hours" size="3" value="'.$number_of_hours.'"> &nbsp; Minutes: <input type="text" name="number_of_mins" size="3" value="'.$number_of_mins.'">';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>"$freq_text2",'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'keywords','VALUE'=>"$keywords",'SIZE'=>50,'TEXT'=>'Keywords'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'notes','VALUE'=>"$notes",'COLS'=>50,'ROWS'=>2,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->process();

?>