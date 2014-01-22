<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$help_text='<br><div align="center">
<table width="95%" cellspacing=5 cellpadding=5 bgcolor="#ffffff" class="pastel2">
<tr><td>

<br><div class="pages">
<a href="help-intro.php">Intro</a>
<a href="help-home.php">Home</a>
<a href="help-link.php">Link</a>
<a href="help-knowledge.php">Knowledge</a>
<a href="help-diary.php">Diary</a>
<a href="help-to-do.php">To-Do</a>
<a href="help-contact.php">Contact</a>
<a href="help-reminder.php">Reminder</a>
<a href="help-database.php">Database</a>
<a href="help-live-help.php">Live Help</a>
<a href="help-misc.php">Misc</a>
</div><br>

<h3><u>Live Help</u></h3>

The Blue Smiley Organizer also offers a Live Help feature which enables you to chat with your web site visitors. Put the following HTML code where you want the Live Help Button to appear:<p>

<strong>&lt;a href="'._HOMEPAGE.'/live-support.php?userid='.$userid.'" target="_blank"&gt;&lt;img src="'._HOMEPAGE.'/status-image.php?userid='.$userid.'" border="0"&gt;&lt;/a&gt;&lt;br&gt;&lt;a href="'._HOMEPAGE.'/live-support.php?userid='.$userid.'" target="_blank"&gt;Free Live Support&lt;/a&gt;</strong><p>

As soon as you log into the Blue Smiley Organizer the Live Support Button will indicate that
you are online. If someone clicks on the image a pop-up window with a ring sound will open and you can start chatting with your website visitor. Make sure to unblock this website from a pop-up blocker you might have installed.<p>

Log out the organizer to indicate that you are not available for Live Chat. The graphic will show "offline" in this case.<p>

If you want to change your status to "offline" without logging out go to <strong>Misc > Status</strong> and change the status to either "Offline (Permanently)" or "Offline (Until next Login)".<p>

Under <strong>Misc > Live Chats</strong> you can see the logs of your live support chats and delete them.

</td></tr></table></div>';

$html_instance->add_parameter(
array(
'TEXT'=>$help_text
));

$html_instance->process();

?>