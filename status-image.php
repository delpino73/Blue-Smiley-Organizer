<?php

$userid=isset($_GET['userid']) ? (int)$_GET['userid'] : exit;

require 'class.base.php';
$base_instance=new base();

$data=$base_instance->get_data('SELECT last_active,online_status FROM organizer_session WHERE user='.$userid.' ORDER BY ID DESC LIMIT 1');

if ($data) {

$last_active=$data[1]->last_active;
$online_status=$data[1]->online_status;

$now=time();

if ($now-$last_active < 30 && $online_status==1) { $online=1; }

}

header('Content-type: image/jpeg');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

if (isset($online)) { echo implode('',file('pics/online.gif')); }
else { echo implode('',file('pics/offline.gif')); }

?>