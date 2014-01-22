<?php

require 'class.base.php';
$base_instance=new base();

$title='Bookmark Manager : Manage Bookmarks, Contacts, Diary, To-Do List, Reminder, Knowledge';

$header='Blue Smiley Organizer - Bookmark Manager and more';

# if you want to remove these adverts you need to get a commercial license, to get a commercial license please check README.TXT for contact details

$main=_FRONTPAGE_TEXT.'

<iframe width="100%" height="100" scrolling="no" frameborder=0 marginheight="0" marginwidth="0" src="http://www.bookmark-manager.com/index-adverts.php"></iframe>

<br><font color="#b3b3b3">Powered by <a href="http://www.bookmark-manager.com/" target="_blank"><u><font color="#b3b3b3">Blue Smiley Organizer v'._VERSION.'</font></u></a> - Copyright &copy; 2002-2011 by Oliver Antosch</font>';

require 'template.html';

?>