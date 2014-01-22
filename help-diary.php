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

<h3><u>Diary</u></h3>

With the diary you can keep track of you daily life. It works similar to web blogs, only that other people cannot see it. Every diary entry has a title and main text. It can be directly search from the navigation bar. Click on <strong>Diary > Search</strong> for more options. In the monthly overview you can jump one year or one month back or forth. Also by clicking on the year you will see a yearly overview. Bold entries in the monthly overview have entries. Today\'s date is highlighted with a grey box.

</td></tr></table></div>';

$html_instance->add_parameter(
array(
'TEXT'=>$help_text
));

$html_instance->process();

?>