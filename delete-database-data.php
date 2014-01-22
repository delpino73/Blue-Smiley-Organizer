<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$item=isset($_REQUEST['item']) ? (int)$_REQUEST['item'] : exit;

if ($item) {

$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['MAIN']} WHERE user='$userid' AND ID='$item'");

$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['SELECT_VALUES']} WHERE user='$userid' AND data_id='$item'");

$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['CHECKBOX_VALUES']} WHERE user='$userid' AND data_id='$item'");

$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['NUMBER_VALUES']} WHERE user='$userid' AND data_id='$item'");

$base_instance->query("DELETE FROM {$base_instance->entity['DATABASE']['TEXT_VALUES']} WHERE user='$userid' AND data_id='$item'");

echo 'item',$item,'|<b>Deleted</b>';

}

?>