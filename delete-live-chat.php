<?php

require 'class.base.php';
$base_instance=new base();

if (isset($_REQUEST['item'])) { $item=sql_safe($_REQUEST['item']); } else { $item=''; }
if (isset($_REQUEST['token'])) { $token=sql_safe($_REQUEST['token']); } else { $token=''; }

if ($item or $token) {

if (!$token) {

	$data=$base_instance->get_data("SELECT token FROM {$base_instance->entity['LIVE_CHAT']['REQUEST']} WHERE ID='$item'");

	if ($data) { $token=$data[1]->token; }
	else { echo 'item',$item,'|<table cellspacing=0 cellpadding=4 class="pastel" bgcolor="#ffffff"><tr><td><strong>Already deleted</strong></td></tr></table>'; exit; }

}

$base_instance->query("DELETE FROM {$base_instance->entity['LIVE_CHAT']['MAIN']} WHERE token='$token'");
$base_instance->query("DELETE FROM {$base_instance->entity['LIVE_CHAT']['REQUEST']} WHERE token='$token'");
$base_instance->query("DELETE FROM {$base_instance->entity['LIVE_CHAT']['USER']} WHERE chat_token='$token'");

if (isset($_GET['back'])) {

header('Location: show-live-chat.php');

} else {

echo 'item',$item,'|<table cellspacing=0 cellpadding=4 class="pastel" bgcolor="#ffffff"><tr><td><strong>Deleted</strong></td></tr></table>';

}

}

?>