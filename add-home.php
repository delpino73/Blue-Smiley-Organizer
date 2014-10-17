<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$element1=isset($_POST['element1']) ? (int)$_POST['element1'] : '';
$element2=isset($_POST['element2']) ? (int)$_POST['element2'] : '';
$element3=isset($_POST['element3']) ? (int)$_POST['element3'] : '';
$element4=isset($_POST['element4']) ? (int)$_POST['element4'] : '';
$element5=isset($_POST['element5']) ? (int)$_POST['element5'] : '';
$element6=isset($_POST['element6']) ? (int)$_POST['element6'] : '';
$element7=isset($_POST['element7']) ? (int)$_POST['element7'] : '';
$element8=isset($_POST['element8']) ? (int)$_POST['element8'] : '';
$element9=isset($_POST['element9']) ? (int)$_POST['element9'] : '';
$element10=isset($_POST['element10']) ? (int)$_POST['element10'] : '';
$element11=isset($_POST['element11']) ? (int)$_POST['element11'] : '';
$element12=isset($_POST['element12']) ? (int)$_POST['element12'] : '';

$title=isset($_POST['title']) ? $_POST['title'] : '';

if (isset($_REQUEST['save'])) {

	$error='';

	if ($title) {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	} else { $error.='<li> Title cannot be left blank'; }

	if (!$error) {

	$title=sql_safe($title);

	$base_instance->query("INSERT INTO {$base_instance->entity['HOME']['MAIN']} (user,title,element1,element2,element3,element4,element5,element6,element7,element8,element9,element10,element11,element12) VALUES ($userid,'$title','$element1','$element2','$element3','$element4','$element5','$element6','$element7','$element8','$element9','$element10','$element11','$element12')");

	$home_id=mysqli_insert_id($base_instance->db_link);

	$base_instance->show_message('Homepage saved','<a href="edit-home.php?home_id='.$home_id.'">[Edit Homepage]</a> &nbsp;&nbsp;&nbsp; <a href="add-home.php">[Add Homepage]</a> &nbsp;&nbsp;&nbsp; <a href="home.php?home_id='.$home_id.'">[Show Homepage]</a><p><a href="show-home.php">[Show all Homepages]</a>

<script language="JavaScript" type="text/javascript">
top.top_frame.navigation.location.reload(true);
</script>');

	}

	else {

	$html_instance->error_message=$error;

	}

}

# write RSS Feeds into array

$rss_array=array();

$data=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['RSS']['MAIN'].' WHERE user='.$userid.' ORDER BY title');

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$rss_array[$ID]=$data[$index]->title;

}

#

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Homepage',
'TEXT_CENTER'=>'',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Save Home'
));

$s1=build_select($element1,'element1');
$s2=build_select($element2,'element2');
$s3=build_select($element3,'element3');
$s4=build_select($element4,'element4');
$s5=build_select($element5,'element5');
$s6=build_select($element6,'element6');
$s7=build_select($element7,'element7');
$s8=build_select($element8,'element8');
$s9=build_select($element9,'element9');
$s10=build_select($element10,'element10');
$s11=build_select($element11,'element11');
$s12=build_select($element12,'element12');

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>20,'TEXT'=>'Title'));

$all_select='<table cellpadding=3><tr><td><b>1.&nbsp;Row</b></td><td>'.$s1.'</td><td>'.$s2.'</td></tr>
<tr><td><strong>2. Row</strong></td><td>'.$s3.'</td><td>'.$s4.'</td></tr>
<tr><td><strong>3. Row</strong></td><td>'.$s5.'</td><td>'.$s6.'</td></tr>
<tr><td><strong>4. Row</strong></td><td>'.$s7.'</td><td>'.$s8.'</td></tr>
<tr><td><strong>5. Row</strong></td><td>'.$s9.'</td><td>'.$s10.'</td></tr>
<tr><td><strong>6. Row</strong></td><td>'.$s11.'</td><td>'.$s12.'</td></tr></table>';

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>$all_select));

$html_instance->process();

#

function build_select($current_value,$name) {

global $base_instance, $rss_array;

$userid=$base_instance->get_userid();

$all='';

asort($base_instance->home_array); reset($base_instance->home_array);

$all.='<select name="'.$name.'"><option>';

while (list($key,$value)=each($base_instance->home_array)) {

if ($key==$current_value) { $all.='<option value='.$key.' selected>'.$value; }
else { $all.='<option value='.$key.'>'.$value; }

}

#

reset($rss_array);

while (list($rss_id,$rss_title)=each($rss_array)) {

if ($rss_id==$current_value) { $all.='<option value='.$rss_id.' selected>RSS Feed - '.$rss_title; }
else { $all.='<option value='.$rss_id.'>RSS Feed - '.$rss_title; }

}

#

$all.='</select>';

return $all;

}

?>