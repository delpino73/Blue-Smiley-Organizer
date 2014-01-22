<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$email=isset($_GET['email']) ? $_GET['email'] : '';
$username=isset($_GET['username']) ? $_GET['username'] : '';
$userid=isset($_GET['userid']) ? (int)$_GET['userid'] : '';

$html_instance->add_parameter(
array('ACTION'=>'show_user',
'ENTITY'=>'USER',
'HEADER'=>'User '.$username,
'USERNAME'=>$username,
'USERID'=>$userid,
'SINGLE'=>1
));

$html_instance->process();

?>