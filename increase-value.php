<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$knowledge_id=isset($_GET['knowledge_id']) ? (int)$_GET['knowledge_id'] : exit;

if ($knowledge_id) {

$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['MAIN']} SET value=value+5 WHERE user='$userid' AND ID=$knowledge_id");

echo '<head>',_CSS_NAV,'<meta http-equiv="refresh" content="10;url=status.php"></head><font size="2">Value increased</font>';

}

?>