<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

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

<h3><u>Introduction</u></h3>

The Development of the Blue Smiley Organizer started in the year 2002. You can efficiently organize your bookmarks, to-do lists, contacts, images, etc with it. You are invited to send suggestions, bugs, etc to me at <strong><a href="mailto:antosch@gmail.com">antosch@gmail.com</a></strong>. To get help for a specific topic click on a link at the top.<p>

</td></tr></table>
</div>';

$html_instance->add_parameter(
array(
'TEXT'=>$help_text
));

$html_instance->process();

?>