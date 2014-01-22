<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$datetime=date('Y-m-d H:i:s');

$base_instance->query("DELETE FROM {$base_instance->entity['SESSION']['MAIN']} WHERE user='$userid'");

setcookie('sid','','631213200','/'); # delete cookie

if (isset($_GET['signup'])) { header('Location: '._HOMEPAGE.'/sign-up.php'); exit; }

else {

# if you want to remove these adverts you need to get a commercial license, to get a commercial license please check README.TXT for contact details

$main='<table bgcolor="#ffffff" cellpadding=5 cellspacing=5><tr><td align="center">

<iframe width="400" height="400" scrolling="no" frameborder=0 marginheight="0" marginwidth="0" src="http://www.bookmark-manager.com/logout-adverts.php"></iframe>

</td></tr></table>';

}

$title='Logged Out';

require 'template.html';

?>