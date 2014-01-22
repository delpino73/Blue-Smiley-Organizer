<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$data=$base_instance->get_data("SELECT username,user_password FROM organizer_user WHERE ID=$userid");

$username=$data[1]->username;
$password=$data[1]->user_password;

$url=$username.'/'.$password;
$encoded_url=base64_encode($url);

$url=_HOMEPAGE.'/show-to-do-rss.php?code='.$encoded_url;

$base_instance->show_message('RSS To-Do Feed','Copy and paste this encrypted RSS Feed:<p><form><input type="text" name="" size="80" value="'.$url.'" onFocus="this.select()"></form>',1);

?>