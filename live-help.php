<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text='<br><div align="center"><table width="95%" border cellspacing=0 cellpadding=5 class="pastel" bgcolor="#ffffff"><tr><td>

<h3><u>Live Help</u></h3>

Click on the button below to start talking to the Admin of this website:<br><br>

<a href="'._HOMEPAGE.'/live-support.php?userid=1" target="_blank"><img src="'._HOMEPAGE.'/status-image.php?userid=1" border="0"></a><br>
<a href="'._HOMEPAGE.'/live-support.php?userid=1" target="_blank">Free Live Support</a>

<br><br><br><br>

Do you also want to offer Live Support to your website visitors? Then include this HTML code in your homepage:<p>

<strong>&lt;a href="'._HOMEPAGE.'/live-support.php?userid='.$userid.'" target="_blank"&gt;&lt;img src="'._HOMEPAGE.'/status-image.php?userid='.$userid.'" border="0"&gt;&lt;/a&gt;&lt;br&gt;&lt;a href="'._HOMEPAGE.'/live-support.php?userid='.$userid.'" target="_blank"&gt;Free Live Support&lt;/a&gt;</strong>

<br><br>

When you are logged into this website it will indicate that you are "Online". When somebody clicks on the button you will get a pop-up message here and you can start chatting to your site visitor.

</td></tr></table></div>';

$html_instance->add_parameter(
array(
'TEXT'=>"$text"
));

$html_instance->process();

?>