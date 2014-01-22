<?php

require 'class.base.php';
require 'class.misc.php';

$base_instance=new base();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

$link_id=isset($_GET['link_id']) ? (int)$_GET['link_id'] : '';

$datetime=date('Y-m-d H:i:s');

if ($link_id > 0) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ID='$link_id' AND user='$userid'");

}

else if (isset($_GET['random'])) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user='$userid' ORDER BY RAND() LIMIT 1");

}

else if (isset($_GET['random_bluebox'])) {

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE (DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime') AND frequency>0 AND user='$userid' ORDER BY RAND() LIMIT 1");

}

else { echo 'Error'; exit; }

if (!$data) { $base_instance->show_message('Link not found'); exit; }

$link_id=$data[1]->ID;
$added=$data[1]->datetime;
$url=$data[1]->url;
$user_of_link=$data[1]->user;
$popularity=$data[1]->popularity;
$frequency=$data[1]->frequency;
$last_visit=$data[1]->last_visit;
$visits=$data[1]->visits;
$speed=$data[1]->speed;

if (!$link_id) { echo 'Link ID Error. Please log in again.'; exit; }

$new_popularity=$misc_instance->calculate_new_popularity($popularity);

$base_instance->query("UPDATE {$base_instance->entity['LINK']['MAIN']} SET visits=visits+1,popularity=$new_popularity,last_visit='$datetime' WHERE ID='$link_id'");

if (preg_match('/https:\/\//',$url)) { $url2=$url; } elseif (preg_match('/ftp:\/\//',$url)) { $url2=$url; } else { $url2='http://'.$url; }

echo '<meta http-equiv="refresh" content="0; URL='.$url2.'">';

?>