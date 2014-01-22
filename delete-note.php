<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$item=isset($_REQUEST['item']) ? (int)$_REQUEST['item'] : exit;

if ($item) {

$base_instance->query("DELETE FROM organizer_note WHERE ID='$item' AND user='$userid'");

echo 'item',$item,'|<table cellspacing=0 cellpadding=4 class="pastel" bgcolor="#ffffff"><tr><td><strong>Deleted</strong></td></tr></table>';

}

?>