<?php

$flush=1;

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$text='firstname'."\t".'lastname'."\t".'company'."\t".'email'."\t".'telephone'."\t".'fax'."\t".'mobile'."\t".'address'."\t".'notes'."\t".'url'."\n";

$data=$base_instance->get_data("SELECT * FROM organizer_contact WHERE user='$userid'");

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$firstname=$data[$index]->firstname;
$lastname=$data[$index]->lastname;
$company=$data[$index]->company;
$email=$data[$index]->email;
$telephone=$data[$index]->telephone;
$fax=$data[$index]->fax;
$mobile=$data[$index]->mobile;
$address=$data[$index]->address;
$notes=$data[$index]->notes;
$url=$data[$index]->url;

$text.=$firstname."\t".$lastname."\t".$company."\t".$email."\t".$telephone."\t".$fax."\t".$mobile."\t".$address."\t".$notes."\t".$url."\n";

}

foreach (glob('./export/contacts*.*') as $filename) { unlink($filename); }

$token=md5(uniqid(rand(),true));

$filepath='./export/contacts'.$token.'.txt';
$filename='contacts'.$token.'.txt';

exec("rm $filepath; touch $filepath; chmod 0600 $filepath");

if (is_writable($filepath)) {

if (!$fp=fopen($filepath,'w')) { echo "Cannot open file ($filename)"; exit; }
if (!fwrite($fp,$text)) { echo "Cannot write to file ($filename)"; exit; }
fclose($fp);

}

else { echo "The file $filename is not writable"; exit; }

#

if (file_exists($filepath)) {

header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename="'.$filename.'";');
#header('Content-Length: '.filesize($filepath));

set_time_limit(0);

@readfile($filepath) or die();

} else { $base_instance->show_message('File not found'); }

?>