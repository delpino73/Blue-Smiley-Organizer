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

<h3><u>Knowledge</u></h3>

In the knowledge section you can save all kinds of knowledge which you can access any time. You can organize it in categories and assign a value to it. The value will be used for the "knowledge trainer". New entries have the value "100". This will decrease by 1 every time the entry will be shown in the "knowledge trainer" or on any of the Homepages. The higher the value the more likely it is that the entry will be shown. This ensures that new entries will be shown more frequently. To increase the value click on "+5". The number of times a knowledge entry has been shown will be indicated next to "Shown".<p>

Go to the "Knowledge Trainer" by clicking on <strong>Know > Flashcards</strong>. On the top it will show a button saying <strong>Click for Next (1)</strong>. Every time you click it, it will show four new knowledge entries (called flashcards). The number in the brackets will increase by one to indicate your progress.

</td></tr></table></div>';

$html_instance->add_parameter(
array(
'TEXT'=>$help_text
));

$html_instance->process();

?>