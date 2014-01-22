<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$file_id=isset($_GET['file_id']) ? (int)$_GET['file_id'] : exit;

$token='t'.md5(uniqid(rand(),true));

#

$data=$base_instance->get_data("SELECT filename FROM {$base_instance->entity['FILE']['MAIN']} WHERE ID='$file_id'");
$filename=$data[1]->filename;

$path=pathinfo($filename);
if (isset($path['extension'])) { $ext=strtolower($path['extension']); } else { $ext=''; }

if ($ext=='gif' or $ext=='png' or $ext=='jpg' or $ext=='jpeg') { $image_link='<p><form><strong>Display Image:</strong> &nbsp; <input type="text" name="" size="15" value="[image-'.$file_id.']" onFocus="this.select()"></form>'; } else { $image_link=''; }

#

$base_instance->query("UPDATE {$base_instance->entity['FILE']['MAIN']} SET token='$token',public=2 WHERE ID='$file_id'");

$base_instance->show_message('File is public now','<b>Download Link:</b><p><form><input type="text" name="" size="80" value="'._HOMEPAGE.'/file-'.$token.'" onFocus="this.select()"></form>'.$image_link,1);

?>