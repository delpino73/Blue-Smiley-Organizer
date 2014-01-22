<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';

if (isset($_POST['save_data'])) {

	$error='';

	$title=$_POST['title'];
	$day=(int)$_POST['day'];
	$month=(int)$_POST['month'];
	$year=(int)$_POST['year'];

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$error) {

	$date="$year-$month-$day";
	$time=date('H:i:s');

	$base_instance->query('INSERT INTO '.$base_instance->entity['DATABASE']['MAIN'].' (datetime,day,month,year,title,text,user,category_id) VALUES ("'.$date.' '.$time.'",'.$day.','.$month.','.$year.',"'.sql_safe($title).'","'.sql_safe($text).'",'.$userid.','.$category_id.')');

	$insert_id=mysql_insert_id();

	# insert checkbox values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;

	if (isset($_POST['checkbox'.$ID])) {

	$checkbox_array=$_POST['checkbox'.$ID];

	for ($hh=0; $hh < count($checkbox_array); $hh++) {

	$value=sql_safe($checkbox_array[$hh]);

	if ($value) { $base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} (date,user,value,data_id,checkbox_field_id,category_id) VALUES ('$date',$userid,$value,$insert_id,$ID,$category_id)"); }

	}

	}

	}

	unset($data);

	# insert select values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$value=sql_safe($_POST['select'.$ID]);

	if ($value) { $base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['SELECT_VALUES']} (date, user, value, data_id,select_field_id,category_id) VALUES ('$date',$userid,$value,$insert_id,$ID,$category_id)"); }

	}

	unset($data);

	# insert number values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$value=sql_safe($_POST['number'.$ID]);

	$base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['NUMBER_VALUES']} (date,user,value,data_id,number_field_id,category_id) VALUES ('$date',$userid,'$value',$insert_id,$ID,$category_id)");

	}

	# insert text values

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

	for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$value=sql_safe($_POST['text'.$ID]);

	$base_instance->query("INSERT INTO {$base_instance->entity['DATABASE']['TEXT_VALUES']} (date,user,value,data_id,text_field_id,category_id) VALUES ('$date',$userid,'$value',$insert_id,$ID,$category_id)");

	}

	$base_instance->show_message('Data saved','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelData(item){if(confirm("Delete Data?")){http.open(\'get\',\'delete-database-data.php?item=\'+item); http.send(null);}}</script>

<a href="add-database-data.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-database-data.php?data_id='.$insert_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelData(\''.$insert_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="show-database-data.php?category_id='.$category_id.'">[Show all Data]</a><p>');

	}

	else {

	$html_instance->error_message=$error;
	$title=stripslashes($title);

	}

}

$day=date('j'); $month=date('n'); $year=date('Y');

$title=isset($_POST['title']) ? $_POST['title'] : '';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Data',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Save Data'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'save_data','VALUE'=>"1"));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'category_id','VALUE'=>"$category_id"));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>"$title",'SIZE'=>50,'TEXT'=>'Title'));

# get number fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['NUMBER_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$number_field_id=$data[$index]->ID;
	$title=$data[$index]->title;

	$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>"number$number_field_id",'VALUE'=>'','SIZE'=>50,'TEXT'=>"$title"));

	}

}

unset($data);

# get text fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['TEXT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$text_field_id=$data[$index]->ID;
	$title=$data[$index]->title;

	$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>"text$text_field_id",'VALUE'=>'','COLS'=>50,'ROWS'=>3,'TEXT'=>"$title",'SECTIONS'=>2));

	}

}

unset($data);

# get select fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$select_field_id=$data[$index]->ID;
	$select_title=$data[$index]->title;

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['SELECT_ITEMS']} WHERE user='$userid' AND select_field_id='$select_field_id' ORDER BY title ASC");

	$dropdown_box='<select name="select'.$select_field_id.'"><option>';

	for ($index2=1; $index2 <= sizeof($data2); $index2++) {

	$title=$data2[$index2]->title;
	$value=$data2[$index2]->ID;

	$dropdown_box.='<option value='.$value.'>'.$title;

	}

	$dropdown_box.='</select>';

	$html_instance->add_form_field(array('TYPE'=>'label', 'TEXT1'=>$select_title.':','TEXT2'=>"$dropdown_box",'SECTIONS'=>2));

	}

}

unset($data);

# get checkbox fields

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_FIELDS']} WHERE user='$userid' AND category_id='$category_id'");

if ($data) {

	for ($index=1; $index <= sizeof($data); $index++) {

	$checkbox_field_id=$data[$index]->ID;
	$checkbox_title=$data[$index]->title;

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CHECKBOX_ITEMS']} WHERE user='$userid' AND checkbox_field_id='$checkbox_field_id' ORDER BY title ASC");

	$check='<hr><table cellpadding="5">';

	$i=0;

	for ($index2=1; $index2 <= sizeof($data2); $index2++) {

	$title=$data2[$index2]->title;
	$value=$data2[$index2]->ID;

	$i++; $mod=$i % 4;

	if ($mod==1) { $check.='<tr>'; }

	$check.='<td><input type="Checkbox" name="checkbox'.$checkbox_field_id.'[]" value="'.$value.'" id="'.$index.'_'.$index2.'"><label for="'.$index.'_'.$index2.'">'.$title.'</label></td>';

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