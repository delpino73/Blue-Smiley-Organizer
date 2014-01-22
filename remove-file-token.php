<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_GET['token'])) { $token=$_GET['token']; } else { exit; }

$base_instance->query('UPDATE '.$base_instance->entity['FILE']['MAIN'].' SET token="",public=1 WHERE token="'.sql_safe($token).'"');

$base_instance->show_message('File private again','',1);

?>