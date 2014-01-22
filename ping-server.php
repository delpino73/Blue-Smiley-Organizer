<?php

require 'class.base.php';
$base_instance=new base();

$blog_id=isset($_GET['blog_id']) ? $_GET['blog_id'] : exit;

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['BLOG']['MAIN']} WHERE ID=$blog_id");

$title=$data[1]->title;

if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/permalink-'.$blog_id; }
else { $url=_HOMEPAGE.'/show-blog-public-permalink.php?blog_id='.$blog_id; }

#

$result1=ping_server('http://rpc.technorati.com/rpc/ping','rpc.technorati.com',$title,$url);
$result2=ping_server('http://rpc.icerocket.com:10080','rpc.icerocket.com',$title,$url);

preg_match("/<string>([\x{1}-\x{99999}]+)<\/string>/ui",$result1,$ll);
if (!empty($ll[1])) { $string1=$ll[1]; } else { $string1='(Error)'; }

preg_match("/<string>([\x{1}-\x{99999}]+)<\/string>/ui",$result2,$ll);
if (!empty($ll[1])) { $string2=$ll[1]; } else { $string2='(Error)'; }

$base_instance->show_message('Server Pinged','<u>Technorati Result:</u> '.$string1.'<p><u>Icerocket Result:</u> '.$string2);

#

function ping_server($ping_server,$rpc,$title,$url) {

$request=xmlrpc_encode_request('weblogUpdates.ping',array($title,$url));

$header[]='Host: '.$rpc;
$header[]='Content-type: text/xml';
$header[]='Content-length: '.strlen($request)."\r\n";
$header[]=$request;

$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$ping_server);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'POST');
$result=curl_exec($ch);
curl_close($ch);

return $result;

}

?>