<?php

$flush=1;

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

if (isset($_REQUEST['save'])) {

$months=$_REQUEST['months'];

$today=date('Y-m-d H:i:s');
$period_x=date('Y-m-d H:i:s',mktime(0,0,0,date('m')-$months, date('d'),date('Y')));

$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM {$base_instance->entity['USER']['MAIN']} WHERE lastlogin < '$period_x' AND last_reminded < '$period_x' LIMIT 10");

$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
$fnd_rows=$data2[1]->fnd_rows;

$sizeof=sizeof($data);

for ($index=1; $index <= $sizeof; $index++) {

$ID=$data[$index]->ID;
$username=$data[$index]->username;
$password=$data[$index]->user_password;
$email=$data[$index]->email;
$lastlogin=$data[$index]->lastlogin;
$last_reminded=$data[$index]->last_reminded;

$url=$username.'/'.$password;
$encoded_url=base64_encode($url);

if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/login-'.$encoded_url; }
else { $url=_HOMEPAGE.'/autologin.php?code='.$encoded_url; }

echo 'Sending to <strong><a href="show-user.php?username='.$username.'">'.$username.'</a></strong> ('.$email.')</strong><br>';

$mailheaders='From: '._ADMIN_SENDER_NAME.' <'._ADMIN_EMAIL.'>'."\n";
$mailheaders.='Reply-To: '._ADMIN_EMAIL."\n";

$msg='Hello '.$username.'!'."\n\n";

$mailsubject='Your Account will expire soon';

$msg.='You haven\'t logged in for a long time, your account
could be deleted soon. If you want to keep your account
please login as soon as possible.

Login at '._HOMEPAGE.'

You can directly login with the following URL:

'.$url.'

For the password reminder go here:

'._HOMEPAGE.'/password-reminder.php';

$msg.="\n\n";
$msg.=_SEPARATOR."\n";
$msg.=_EMAIL_ADVERT_TEXT."\n";
$msg.=_SEPARATOR."\n";
$msg.=_SLOGAN."\n";
$msg.=_HOMEPAGE."\n";
$msg.='Email: '._ADMIN_EMAIL."\n";

mail($email, $mailsubject, $msg, $mailheaders);

$base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET last_reminded='$today' WHERE ID=$ID");

}

if ($sizeof > 0) { echo '<head><meta http-equiv="refresh" content="10;URL=send-warning.php?months='.$months.'&save=1"></head><p><strong>Continue to send more reminder emails in a few seconds ('.$fnd_rows.' left) ..</strong>'; }
else { echo 'finished'; }

exit;

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Send Reminder',
'TEXT_CENTER'=>'Send a reminder email to user who have not logged in for a long time.<p>',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Send',
'INNER_TABLE_WIDTH'=>'220'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<strong>Send Email to users who have .. </strong>'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<select name="months">
<option value=3>Not logged in for 3 months
<option selected value=6>Not logged in for 6 months
<option value=12>Not logged in for 1 year
<option value=24>Not logged in for 2 years
</select>'));

$html_instance->process();

?>