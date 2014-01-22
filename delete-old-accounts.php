<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

if (isset($_POST['delete'])) {

$user=$_POST['user'];

for ($i=0; $i < count($user); $i++) { $user_instance->delete_user($user[$i]); }

$base_instance->show_message('User deleted');

}

if (isset($_POST['save'])) {

$years=$_POST['years'];

$period=date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d'),date('Y')-$years));

$data=$base_instance->get_data("SELECT ID,username,datetime,lastlogin,logins FROM {$base_instance->entity['USER']['MAIN']} WHERE lastlogin < '$period'");

if (!$data) { $base_instance->show_message('No User need to be deleted'); }

$text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td><strong>Delete?</strong></td><td><strong>Username</strong></td><td><strong>Sign Up</strong></td><td><strong>Last Login</strong></td><td><strong>Logins</strong></td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$username=$data[$index]->username;
$datetime=$data[$index]->datetime;
$lastlogin=$data[$index]->lastlogin;
$logins=$data[$index]->logins;

$text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><input type="Checkbox" name="user[]" value="'.$ID.'" checked></td><td><a href="show-user.php?username='.$username.'" onMouseOver="window.status=\'\'; return true">'.$username.'</a></td><td>'.$datetime.'</td><td>'.$lastlogin.'</td><td>'.$logins.'</td></tr>';

}

$text.='</table>';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete old Accounts</font>',
'TEXT_CENTER'=>'Choose which users you want to delete.<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Permanently Delete Selected User'
));

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'delete','VALUE'=>1));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>"$text"));

$html_instance->process(); exit;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'<font color="#ff0000">Delete Old Accounts</font>',
'TEXT_CENTER'=>'Choose which user accounts you want to delete.<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Delete Accounts',
'INNER_TABLE_WIDTH'=>'190'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<strong>Delete users who have .. </strong>'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<select name="years">
<option value=1>Not logged in for 1 year
<option selected value=2>Not logged in for 2 years
<option value=3>Not logged in for 3 years
<option value=4>Not logged in for 4 years
<option value=5>Not logged in for 5 years
</select>'));

$html_instance->process();

?>