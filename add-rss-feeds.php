<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$number_of_fields=isset($_POST['number_of_fields']) ? (int)$_POST['number_of_fields'] : '';

if (isset($_POST['save']) && isset($_POST['more_fields'])==FALSE) {

	$error='';

	for ($index=1; $index <= $number_of_fields; $index++) {

	$title=$_POST['title'.$index];
	$feed=$_POST['feed'.$index];
	$max_items=$_POST['max_items'.$index];

	if (!empty($feed) && substr($feed,0,4)!='http') { $error.='<li> Feed URL '.$index.' has wrong format (use http:// at the beginning)'; }

	if (strlen($title)>50) { $error.='<li> Feed Title '.$index.' is too long (Max. 50 Characters)'; }

	if (!empty($title) && !$feed) { $error.='<li> Feed URL '.$index.' cannot be left empty'; }

	if (!empty($feed) && !$title) { $error.='<li> Feed Title '.$index.' cannot be left empty'; }

	$data=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['RSS']['MAIN'].' WHERE feed="'.sql_safe($feed).'" AND user='.$userid);

	if ($data) { $error.='<li> RSS Feed '.$index.' already saved'; }

	}

	if (!$error) {

	for ($index=1; $index <= $number_of_fields; $index++) {

	if (!empty($_POST['title'.$index])) {

	$title=$_POST['title'.$index];
	$feed=$_POST['feed'.$index];
	$max_items=$_POST['max_items'.$index];

	$base_instance->query('INSERT INTO '.$base_instance->entity['RSS']['MAIN'].' (user,feed,title,max_items) VALUES ('.$userid.',"'.sql_safe($feed).'","'.sql_safe($title).'","'.sql_safe($max_items).'")');

	}

	}

	$base_instance->show_message('RSS Feeds saved','<a href="add-rss-feeds.php">[Add RSS Feeds]</a> &nbsp;&nbsp; <a href="show-rss-feeds.php">[Show RSS Feeds]</a><p><a href="show-home.php">[Edit Homepages]</a>');

	}

	else { $html_instance->error_message=$error; }

}

if (isset($_POST['more_fields'])) {

$number_of_fields+=3;

$text='<table>';

for ($index=1; $index <= $number_of_fields; $index++) {

if (isset($_POST['title'.$index])) { $title=stripslashes($_POST['title'.$index]); } else { $title=''; }
if (isset($_POST['feed'.$index])) { $feed=$_POST['feed'.$index]; } else { $feed=''; }
if (isset($_POST['max_items'.$index])) { $max_items=$_POST['max_items'.$index]; } else { $max_items=''; }

$text.='<tr><td align="right"><b>Feed Title '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="title'.$index.'" size="65" value="'.$title.'"></td></tr><tr><td align="right"><b>Feed URL '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="feed'.$index.'" size="65" value="'.$feed.'"></td></tr><tr><td align="right"><b>Max Items '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="max_items'.$index.'" size="5" value="'.$max_items.'"></td></tr>';

}

$text.='<tr><td></td><td><input type="SUBMIT" value="More Fields" name="more_fields"></td></tr></table>

<input type="Hidden" name="number_of_fields" value="'.$number_of_fields.'">';

}

else if (isset($_POST['save'])) {

$text='<table>';

for ($index=1; $index <= $number_of_fields; $index++) {

if (isset($_POST['title'.$index])) { $title=stripslashes($_POST['title'.$index]); } else { $title=''; }
if (isset($_POST['feed'.$index])) { $feed=$_POST['feed'.$index]; } else { $feed=''; }
if (isset($_POST['max_items'.$index])) { $max_items=$_POST['max_items'.$index]; } else { $max_items=''; }

$text.='<tr><td align="right"><b>Feed Title '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="title'.$index.'" size="65" value="'.$title.'"></td></tr><tr><td align="right"><b>Feed URL '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="feed'.$index.'" size="65" value="'.$feed.'"></td></tr><tr><td align="right"><b>Max Items '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="max_items'.$index.'" size="5" value="'.$max_items.'"></td></tr>';

}

$text.='<tr><td></td><td><input type="SUBMIT" value="More Fields" name="more_fields"></td></tr></table>

<input type="Hidden" name="number_of_fields" value="'.$number_of_fields.'">';

}

else {

$text='<table>';

$number_of_fields=5;

for ($index=1; $index < $number_of_fields+1; $index++) {

$text.='<tr><td align="right"><b>Feed Title '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="title'.$index.'" size="65" value=""></td></tr>

<tr><td align="right"><b>Feed URL '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="feed'.$index.'" size="65" value=""></td></tr>

<tr><td align="right"><b>Max Items '.$index.':</b></td><td align="left">&nbsp;<input type="text" name="max_items'.$index.'" size="5" value=""></td></tr>';

}

$text.='<tr><td></td><td><input type="SUBMIT" value="More Feeds" name="more_fields"></td></tr></table>

<input type="Hidden" name="number_of_fields" value="'.$number_of_fields.'">';

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add RSS Feeds',
'INNER_TABLE_WIDTH'=>'60%',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title1.focus()"',
'BUTTON_TEXT'=>'Save RSS Feeds'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>$text));

$html_instance->process();

?>