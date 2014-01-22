<?php

require 'class.base.php';
$base_instance=new base();

$token=isset($_GET['token']) ? $_GET['token'] : '';
$usertoken=isset($_GET['usertoken']) ? $_GET['usertoken'] : '';
$email=isset($_GET['email']) ? $_GET['email'] : '';
$name=isset($_GET['name']) ? $_GET['name'] : '';

if (isset($_COOKIE['js'])) { header("Location: chat-advanced-customer.php?email=$email&usertoken=$usertoken&token=$token"); exit; }

$base_instance->query('INSERT INTO '.$base_instance->entity['LIVE_CHAT']['USER'].' (user_token,chat_token) VALUES ("'.sql_safe($usertoken).'","'.sql_safe($token).'")');

echo '<frameset rows="10%,70%,20%" frameborder=0 border=0><frame name="top" src="chat-top.php" marginwidth="15" marginheight="15" scrolling="no" frameborder="0"><frame name="main" src="chat-customer.php?token=',$token,'&usertoken=',$usertoken,'&autoscroll=1" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0"><frame name="input_frame" src="chat-input-customer.php?token=',$token,'&usertoken=',$usertoken,'&name=',$name,'" marginwidth="0" marginheight="0" frameborder="0" scrolling="no"></frameset>';

?>