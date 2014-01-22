<?php

require 'class.misc.php';
$misc_instance=new misc();

if (isset($_GET['url_encoded'])) {

$url_encoded=$_GET['url_encoded'];
$url=base64_decode($url_encoded);
$misc_instance->load_url($url);

}

?>