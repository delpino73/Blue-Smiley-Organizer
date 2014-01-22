<?php

require 'class.base.php';
$base_instance=new base();

$token=isset($_REQUEST['token']) ? sql_safe($_REQUEST['token']) : '';
$usertoken=isset($_REQUEST['usertoken']) ? sql_safe($_REQUEST['usertoken']) : '';

$base_instance->query("INSERT INTO {$base_instance->entity['LIVE_CHAT']['USER']} (user_token,chat_token) VALUES ('$usertoken','$token')");

#

$userid=$base_instance->get_userid();

$data=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$userid");
$name=$data[1]->username;

echo '<frameset rows="80%,20%" frameborder=0 border=0><frame name="main" src="chat.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=1" marginwidth="15" marginheight="15" scrolling="auto" frameborder="0"><frame name="input_frame" src="chat-input.php?token=',$token,'&usertoken=',$usertoken,'&name=',$name,'" marginwidth="0" marginheight="0" frameborder="0" scrolling="no"></frameset>';

?>