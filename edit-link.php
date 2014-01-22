<?php

require 'class.base.php';
require 'class.html.php';
require 'class.misc.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$link_id=isset($_REQUEST['link_id']) ? (int)$_REQUEST['link_id'] : exit;
$new_category=isset($_POST['new_category']) ? $_POST['new_category'] : '';

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$subtitle=$_POST['subtitle'];
	$sequence=(int)$_POST['sequence'];
	$speed=(int)$_POST['speed'];
	$category_id=(int)$_POST['category_id'];
	$url=$_POST['url'];
	$number_of_days=(int)$_POST['number_of_days'];
	$number_of_hours=(int)$_POST['number_of_hours'];
	$number_of_mins=(int)$_POST['number_of_mins'];
	#$public=(int)$_POST['public'];
	$mode=(int)$_POST['mode'];
	$keywords=$_POST['keywords'];
	$notes=$_POST['notes'];

	if ($speed==0) { $speed=50; }

	if (!$category_id && !$new_category) { $error.='<li> Category cannot be left blank'; }

	# check title

	if (!$title) { $title=''; $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>70) { $error.='<li> Title is too long (Max. 70 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if ($notes) {

	$notes=trim($notes);
	if (strlen($notes)>65535) { $error.='<li> Notes are too long (Max. 65535 Characters)'; }

	}

	# check subtitle

	if ($subtitle) {

	$subtitle=trim($subtitle);
	$subtitle=str_replace('"','&quot;',$subtitle);
	if (strlen($subtitle)>75) { $error.='<li> Subtitle is too long (Max. 75 Characters)'; }

	}

	# check URL

	if (!$url or $url=='http://') { $error.='<li> URL cannot be left blank'; }
	else {

	$url=trim($url);
	if (strlen($url)>400) { $error.='<li> URL (Too long)'; }

	}

	# URL duplicate check

	if ($url && $url!='http://') {

	if (preg_match("/^http:\/\//i",$url)) { $url=substr($url,7); }

	if (preg_match('/\/$/',$url)) { $url2=substr($url,0,-1); } else { $url2=$url; }

	$data=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['LINK']['MAIN'].' WHERE (url="'.sql_safe($url2).'" OR url="'.sql_safe($url2).'/") AND user='.$userid);

	if ($data) {

	$link_id2=$data[1]->ID;

	if ($data && $link_id!=$link_id2) { $base_instance->show_message('Link already saved','',1); }

	}

	}

	#

	$freq_total=0;

	if ($number_of_days > 0) { $freq_total+=$number_of_days*86400; }
	if ($number_of_hours > 0) { $freq_total+=$number_of_hours*3600; }
	if ($number_of_mins > 0) { $freq_total+=$number_of_mins*60; }

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['LINK']['CATEGORY'].' (title,user,parent_id) VALUES ("'.sql_safe($new_category).'",'.$userid.','.$category_id.')');

	$category_id=mysql_insert_id();

	}

	#$base_instance->query('UPDATE '.$base_instance->entity['LINK']['MAIN'].' SET subtitle="'.sql_safe($subtitle).'",url="'.sql_safe($url).'",category='.$category_id.',public='.$public.',title="'.sql_safe($title).'",frequency='.$freq_total.',frequency_mode='.$mode.',notes="'.sql_safe($notes).'",keywords="'.sql_safe($keywords).'",speed="'.$speed.'",sequence="'.$sequence.'" WHERE user='.$userid.' AND ID='.$link_id);

	$base_instance->query('UPDATE '.$base_instance->entity['LINK']['MAIN'].' SET subtitle="'.sql_safe($subtitle).'",url="'.sql_safe($url).'",category='.$category_id.',title="'.sql_safe($title).'",frequency='.$freq_total.',frequency_mode='.$mode.',notes="'.sql_safe($notes).'",keywords="'.sql_safe($keywords).'",speed="'.$speed.'",sequence="'.$sequence.'" WHERE user='.$userid.' AND ID='.$link_id);

	header('Location: close-me.php'); exit;

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);
	$subtitle=stripslashes($subtitle);
	$keywords=stripslashes($keywords);
	$notes=stripslashes($notes);

	}

}

else {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user='$userid' AND ID=$link_id");

if (!$data) { $base_instance->show_message('Link not found'); exit; }

$datetime=$data[1]->datetime;
$subtitle=$data[1]->subtitle;
$url=$data[1]->url;
$category_id=$data[1]->category;
#$public=$data[1]->public;
$title=$data[1]->title;
$frequency=$data[1]->frequency;
$mode=$data[1]->frequency_mode;
$notes=$data[1]->notes;
$keywords=$data[1]->keywords;
$speed=$data[1]->speed;
$sequence=$data[1]->sequence;

$datetime_converted=$base_instance->convert_date($datetime);

$number_of_days=floor($frequency / 86400);
$days_in_second=$number_of_days * 86400;

$frequency=$frequency-$days_in_second;

$number_of_hours=floor($frequency / 3600);
$hours_in_second=$number_of_hours * 3600;

$frequency=$frequency-$hours_in_second;

$number_of_mins=floor($frequency / 60);
$mins_in_second=$number_of_mins * 60;

}

$select_box='&nbsp;<select name="category_id"><option selected value=0>-- Choose Category --';

$select_box.=$misc_instance->build_category_select_box(0,$userid,0,$category_id);

$select_box.='</select> or';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Link &nbsp;&nbsp; <a href="help-link.php" target="_blank">[Help]</a>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'INNER_TABLE_WIDTH'=>'500',
'TD_WIDTH'=>'25%',
'BUTTON_TEXT'=>'Update Link'
));

if (stristr($url,'://')) { $url2=$url; } else { $url2='http://'.$url; }

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'link_id','VALUE'=>$link_id));

if (empty($error)) {
$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Added:','TEXT2'=>$datetime_converted,'SECTIONS'=>'2')); }

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'url','VALUE'=>$url2,'SIZE'=>50,'TEXT'=>'URL'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>$select_box,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'new_category','VALUE'=>$new_category,'SIZE'=>50,'TEXT'=>'New Category'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'subtitle','VALUE'=>$subtitle,'SIZE'=>50,'TEXT'=>'Subtitle'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'speed','VALUE'=>$speed,'OPTION'=>'speed_array','TEXT'=>'Ascent Speed','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'sequence','VALUE'=>$sequence,'SIZE'=>10,'TEXT'=>'Sequence ID'));

#$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>"$public",'TEXT'=>'Link is'));

if ($mode==1) { $m1_checked=' CHECKED'; } else { $m1_checked=''; }
if ($mode==2) { $m2_checked=' CHECKED'; } else { $m2_checked=''; }
if ($mode==3) { $m3_checked=' CHECKED'; } else { $m3_checked=''; }

$freq_text='<table cellpadding="5"><tr><td><input type="Radio" name="mode" value="1" id="m1"'.$m1_checked.'><label for="m1">Always</label></td><td><input type="Radio" name="mode" value="2" id="m2"'.$m2_checked.'><label for="m2">Never</label></td><td><input type="Radio" name="mode" value="3" id="m3"'.$m3_checked.'><label for="m3">Every ..</label></td></table>';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Show Link in Bluebox:','TEXT2'=>$freq_text,'SECTIONS'=>2));

$freq_text2='<p>Days: <input type="text" name="number_of_days" size="2" value="'.$number_of_days.'"> &nbsp; Hours: <input type="text" name="number_of_hours" size="2" value="'.$number_of_hours.'"> &nbsp; Minutes: <input type="text" name="number_of_mins" size="2" value="'.$number_of_mins.'">';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>$freq_text2,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'keywords','VALUE'=>$keywords,'SIZE'=>50,'TEXT'=>'Keywords'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'notes','VALUE'=>$notes,'COLS'=>50,'ROWS'=>2,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->process();

?>