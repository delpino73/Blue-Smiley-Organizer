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

<h3><u>Home</u></h3>
The first thing you see when you log in is Homepage 1. There are up to 3 Homepages you can customise. You can customise it by clicking on <strong>Home > Settings > Home 1</strong> to <strong>Home > Settings > Home 3</strong>. To see the main Homepage you can either click on <strong>Home > Home 1</strong> or on the <strong>Home</strong> navigation button. For Homepage 2 or Homepage 3 click on <strong>Home > Home 2</strong> and <strong>Home > Home 3</strong>.<p>

</td></tr></table>
</div>';

$html_instance->add_parameter(
array(
'TEXT'=>$help_text
));

$html_instance->process();

?>