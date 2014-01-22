<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$data_id=isset($_REQUEST['data_id']) ? (int)$_REQUEST['data_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];
	$year=(int)$_POST['year'];
	$month=(int)$_POST['month'];
	$day=(int)$_POST['day'];
	$category_id=(int)$_POST['category_id'];

	if (!$day) { $error.='<li> Day cannot be left blank'; }
	if (!$month) { $error.='<li> Month cannot be left blank'; }
	if (!$year) { $error.='<li> Year cannot be left blank'; }

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	$date="$year-$month-$day";
	$time=date('H:i:s');

	$base_instance->query('UPDATE '.$base_instance->entity['DATABASE']['MAIN'].' SET datetime="'.$date.' '.$time.'",day='.$day.',month='.$month.',year='.$year.',title="'.sql_safe($title).'",text="'.sql_safe($text).'" WHERE user='.$userid.' AND ID='.$data_id);

	# update checkbox values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$checkbox_field_id=$data[$index]->ID;

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE user='$userid' AND data_id='$data_id' AND checkbox_field_id='$checkbox_field_id'");

	if (isset($_POST['checkbox'.$checkbox_field_id])) {

	$checkbox_array=$_POST['checkbox'.$checkbox_field_id];

	for ($hh=0; $hh < count($checkbox_array); $hh++) {

	$value=(int)$checkbox_array[$hh];

	if ($value) { $base_instance->query('INSERT INTO '.$base_instance->entity['DATABASE']['CHECKBOX_VALUES'].' (date, user, value, data_id,checkbox_field_id,category_id) VALUES ("'.$date.'",'.$userid.','.$value.','.$data_id.','.$checkbox_field_id.','.$category_id.')'); }

	}

	}

	}

	unset($data);

	# update select values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$select_field_id=$data[$index]->ID;
	$value=(int)$_POST['select'.$select_field_id];

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE user='$userid' AND data_id=$data_id AND select_field_id=$select_field_id");

	if ($value) { $base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['SELECT_VALUES']} (date, user, value, data_id, select_field_id, category_id) VALUES ('$date', $userid, $value, $data_id, $select_field_id, $category_id)"); }

	}

	unset($data);

	# update number values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$number_field_id=$data[$index]->ID;
	$value=$_POST['number'.$number_field_id];

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['NUMBER_VALUES']} WHERE user='$userid' AND data_id=$data_id AND number_field_id=$number_field_id");

	$base_instance->query('INSERT INTO '.$base_instance->entity['DATABASE']['NUMBER_VALUES'].' (date, user, value, data_id,number_field_id,category_id) VALUES ("'.$date.'",'.$userid.',"'.sql_safe($value).'",'.$data_id.','.$number_field_id.', '.$category_id.')');

	}

	# update text values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$text_field_id=$data[$index]->ID;
	$value=$_POST['text'.$text_field_id];

	$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['TEXT_VALUES']} WHERE user='$userid' AND data_id=$data_id AND text_field_id=$text_field_id");

	$base_instance->query('INSERT INTO '.$base_instance->entity['DATABASE']['TEXT_VALUES'].' (date, user, value, data_id,text_field_id,category_id) VALUES ("'.$date.'",'.$userid.',"'.sql_safe($value).'",'.$data_id.','.$text_field_id.','.$category_id.')');

	}

	$base_instance->show_message('Data saved','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelData(item){if(confirm("Delete Data?")){http.open(\'get\',\'delete-database-data.php?item=\'+item); http.send(null);}}</script>

<a href="add-database-data.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-database-data.php?data_id='.$data_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelData(\''.$data_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="show-database-data.php?category_id='.$category_id.'">[Show all Data]</a><p>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);

	}

}

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['MAIN']} WHERE user='$userid' AND ID='$data_id'");

if (!$data) { $base_instance->show_message('Data not found'); exit; }

$category_id=$data[1]->category_id;
$text=$data[1]->text;
$title=$data[1]->title;
$day=$data[1]->day;
$month=$data[1]->month;
$year=$data[1]->year;

unset($data);

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Data',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Save Data'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'data_id','VALUE'=>"$data_id"));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>"$category_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

# get number fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$number_field_id=$data[$index]->ID;
	$title=$data[$index]->title;

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['NUMBER_VALUES']} WHERE user='$userid' AND data_id=$data_id AND number_field_id=$number_field_id");

	if ($data2) { $value=$data2[1]->value; } else { $value=0; }

	$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>"number$number_field_id",'VALUE'=>"$value",'SIZE'=>10,'TEXT'=>"$title"));

	}

}

unset($data);

# get text fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$text_field_id=$data[$index]->ID;
	$title=$data[$index]->title;

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_VALUES']} WHERE user='$userid' AND data_id=$data_id AND text_field_id=$text_field_id");

	if ($data2) { $value=$data2[1]->value; } else { $value=''; }

	$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>"text$text_field_id",'VALUE'=>"$value",'COLS'=>50,'ROWS'=>3,'TEXT'=>"$title",'SECTIONS'=>2));

	}

}

unset($data);

# get select fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$select_field_id=$data[$index]->ID;
	$select_title=$data[$index]->title;

	$data3=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE user='$userid' AND data_id='$data_id' AND select_field_id='$select_field_id'");

	if (!empty($data3)) { $selected_value=$data3[1]->value; }
	else { $selected_value=''; }

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE user='$userid' AND select_field_id='$select_field_id' ORDER BY title ASC");

		$dropdown_box='<select name="select'.$select_field_id.'"><option>';

		for ($index2=1; $index2 <= sizeof($data2); $index2++) {

		$title=$data2[$index2]->title;
		$value=$data2[$index2]->ID;

		if ($value==$selected_value) { $dropdown_box.='<option value='.$value.' selected>'.$title; }
		else { $dropdown_box.='<option value='.$value.'>'.$title; }

		}

		$dropdown_box.='</select>';

		$html_instance->add_form_field(array('TYPE'=>'label', 'TEXT1'=>$select_title.':','TEXT2'=>"$dropdown_box",'SECTIONS'=>2));

	}

}

# get checkbox fields

$selected_values=array();

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$checkbox_field_id=$data[$index]->ID;
	$checkbox_title=$data[$index]->title;

	$data3=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE user='$userid' AND data_id='$data_id' AND checkbox_field_id='$checkbox_field_id'");

	for ($index3=1; $index3 <= sizeof($data3); $index3++) {
	$cb_value=$data3[$index3]->value;
	array_push($selected_values,$cb_value);
	}

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE user='$userid' AND checkbox_field_id='$checkbox_field_id' ORDER BY title ASC");

	$check='<hr><table cellpadding="5">';

	$i=0;

	for ($index2=1; $index2 <= sizeof($data2); $index2++) {

	$title=$data2[$index2]->title;
	$value=$data2[$index2]->ID;

		if (@in_array($value, $selected_values)) { $checked=' checked'; } else { $checked=''; }

		$i++; $mod=$i % 4;

		if ($mod==1) { $check.='<tr>'; }

		$check.='<td><input type="Checkbox" name="checkbox'.$checkbox_field_id.'[]" value="'.$value.'" id="'.$index.'_'.$index2.'"'.$checked.'><label for="'.$index.'_'.$index2.'">'.$title.'</label></td>';

		if ($mod==0) { $check.='</tr>'; }

		}

		$check.='</table>';

		#

		$html_instance->add_form_field(array('TYPE'=>'label', 'TEXT1'=>$checkbox_title.':','TEXT2'=>"$check",'SECTIONS'=>2));

	}

}

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'text','VALUE'=>"$text",'SIZE'=>50,'TEXT'=>'Quick Note'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'day','VALUE'=>"$day",'OPTION'=>'day_array_database','TEXT'=>'Day'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'month','VALUE'=>"$month",'OPTION'=>'month_array_database','TEXT'=>'Month','DO_NO_SORT_ARRAY'=>1));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'year','VALUE'=>"$year",'SIZE'=>5,'TEXT'=>'Year'));

$html_instance->process();

?>