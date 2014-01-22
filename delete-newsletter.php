<?php

require 'class.base.php';
require 'class.user.php';

$base_instance=new base();
$user_instance=new user();

$user_instance->check_for_admin();

$item=isset($_REQUEST['item']) ? (int)$_REQUEST['item'] : exit;

if ($item) {

$base_instance->query("DELETE FROM organizer_newsletter WHERE ID='$item'");

echo 'item',$item,'|';

}

?>