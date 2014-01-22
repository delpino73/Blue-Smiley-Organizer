<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$userid=$base_instance->get_userid();

if (isset($_POST['save'])) {

	$error='';

	$timezone=(int)$_POST['timezone'];
	$dateformat=(int)$_POST['dateformat'];
	$sid=(int)$_COOKIE['sid'];

	if (!isset($timezone)) { $error.='<li> Timezone cannot be left blank'; }

	if (!$dateformat) { $error.='<li> Date format cannot be left blank'; }

	if (!$error) {

		$base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET timezone='$timezone',dateformat='$dateformat' WHERE ID=$userid");

		$base_instance->query("UPDATE {$base_instance->entity['SESSION']['MAIN']} SET timezone=$timezone,dateformat=$dateformat WHERE session_id=$sid");

		header('Location: start.php'); exit;

	}

	else { $html_instance->error_message=$error; }

}

if (empty($_POST['save'])) {

	$data=$user_instance->get_userinfo($userid);

	$timezone=$data[1]->timezone;
	$dateformat=$data[1]->dateformat;

}

$select_box='&nbsp;<select name="timezone">';

for ($index=12; $index >= -11; $index--) {

	if ($index > 0) { $sign='+'; $gmt_name='-'.$index; } else if ($index==0) { $sign=''; $gmt_name='0'; } else { $sign=''; $gmt_name='+'.abs($index); }

//$string='TZ=Etc/GMT'.$sign.$index;
//@putenv($string);

	$string='Etc/GMT'.$sign.$index;
	date_default_timezone_set($string);

	$time=date('H:i');

	if ($index==$timezone) { $select_box.="<option selected value=$index>$time (GMT $gmt_name)"; }
	else { $select_box.="<option value=$index>$time (GMT $gmt_name)"; }

}

if ($timezone==13) { $select_box.='<option selected value=13>London'; }
else { $select_box.='<option value=13>London'; }

if ($timezone==14) { $select_box.='<option selected value=14>Berlin'; }
else { $select_box.='<option value=14>Berlin'; }

if ($timezone==15) { $select_box.='<option selected value=15>US Pacific'; }
else { $select_box.='<option value=15>US Pacific'; }

if ($timezone==16) { $select_box.='<option selected value=16>US Mountain'; }
else { $select_box.='<option value=16>US Mountain'; }

if ($timezone==17) { $select_box.='<option selected value=17>US Central'; }
else { $select_box.='<option value=17>US Central'; }

if ($timezone==18) { $select_box.='<option selected value=18>US Eastern'; }
else { $select_box.='<option value=18>US Eastern'; }

if ($timezone==19) { $select_box.='<option selected value=19>Asia Jakarta'; }
else { $select_box.='<option value=19>Asia Jakarta'; }

if ($timezone==20) { $select_box.='<option selected value=20>Asia Hong Kong'; }
else { $select_box.='<option value=20>Asia Hong Kong'; }

if ($timezone==21) { $select_box.='<option selected value=21>Asia Japan'; }
else { $select_box.='<option value=21>Asia Japan'; }

if ($timezone==22) { $select_box.='<option selected value=22>Israel'; }
else { $select_box.='<option value=22>Israel'; }

$select_box.='</select>';

$test=@putenv('TZ=Europe/London');
if ($test!=1) { $warning='<font color="#ff0000">Warning: Your server runs with safe mode on. In this case timezones can not be customized here.<br>Please correct this if necessary.</font>'; } else { $warning=''; }

$html_instance->add_parameter(
	array('ACTION'=>'show_form',
		'HEADER'=>'Change Date and Time',
		'INNER_TABLE_WIDTH'=>'60%',
		'FORM_ACTION'=>$_SERVER['PHP_SELF'],
		'FORM_ATTRIB'=>'target="_top"',
		'TEXT_CENTER'=>'Choose the current time of your timezone (24h format).<p>'.$warning,
		'TD_WIDTH'=>'30%',
		'BUTTON_TEXT'=>'Save Settings'
	));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'dateformat','VALUE'=>$dateformat,'OPTION'=>'date_format_array','TEXT'=>'Date format'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Timezone:','TEXT2'=>$select_box,'SECTIONS'=>2));

$html_instance->process();

?>