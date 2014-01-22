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

<h3>Reminder</h3>

This feature reminds you of specific events. There are three different types:<p>

<u>By Days:</u> If there are regular events, you can type in the number of days. For instance if you want to go to the dentist every 6 months type in 180 days. Also tick "Show on Home Page" or/and "Show Pop-up Window at" depending on how you want to be notified.<p>

<u>By Date:</u> Type in a specific date, for instance 1.1.2002, then you will be reminded on this day. If you want to be reminded regularly for a specific event (like the birthday of a friend) then select a Day and Month and choose "every year" for Year.<p>

<u>By Weekday:</u> Choose one or more weekdays and a time to be reminded of something.<p>

<u>By Hours:</u> Here you will be reminded every few hours. This could be used to remind you to take medicine for example.

</td></tr></table></div>';

$html_instance->add_parameter(
array(
'TEXT'=>$help_text
));

$html_instance->process();

?>