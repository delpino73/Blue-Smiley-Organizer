<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$item=isset($_REQUEST['item']) ? (int)$_REQUEST['item'] : exit;

if ($item) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['MAIN']} WHERE user='$userid' AND ID='$item'");

if (!$data) { echo 'item',$item,'|<table cellspacing=0 cellpadding=4 class="pastel" bgcolor="#ffffff"><tr><td><strong>File not found</strong></td></tr></table>'; exit; }

$filename=$data[1]->filename;

$base_instance->query("DELETE FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID='$item' AND user='$userid'");

if (file_exists('./upload/'.$userid.'/'.$filename)) {

unlink('./upload/'.$userid.'/'.$filename);

}

echo 'item',$item,'|<table cellspacing=0 cellpadding=4 class="pastel" bgcolor="#ffffff"><tr><td><strong>Deleted</strong></td></tr></table>';

}

?>