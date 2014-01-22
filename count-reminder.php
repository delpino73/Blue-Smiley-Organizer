<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$datetime=date('Y-m-d H:i:s');

$reminder_id=(int)$_GET['reminder_id'];

$base_instance->query("UPDATE {$base_instance->entity['REMINDER']['DAYS']} SET done=done+1,last_reminded='$datetime' WHERE ID='$reminder_id' AND user='$userid'");

$data=$base_instance->get_data("SELECT done FROM {$base_instance->entity['REMINDER']['DAYS']} WHERE ID='$reminder_id' AND user='$userid'");

if ($data) { $result='Done<p>(Counter: '.$data[1]->done.')'; }
else { $result='Reminder not found'; }

echo '<head>',_CSS_NAV,'<meta http-equiv="refresh" content="2;url=status.php"></head><font size="2">',$result,'</font>';

if (isset($_GET['reload'])) { echo '<body onLoad="parent.parent.frames[\'main\'].location.reload();">'; }

?>