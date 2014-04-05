<?php

$title='Bookmark Manager - Login';

$header='Login';

if (isset($_GET['invalid_password'])) { $text='Password is incorrect. Please try again or go to the password reminder <a href="password-reminder.php">here</a>.<p>'; } else { $text=''; }

$main=$text.'<form action="auth.php" method="post" target="_top">

<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff">

<tr><td align="right"><b>User:</b></td><td align="left">&nbsp;<input type="text" name="username" size="35" value=""></td></tr>

<tr><td align="right"><b>Password:</b></td><td align="left">&nbsp;<input type="Password" name="pw" size="35" value=""></td></tr>

<tr><td colspan=2 align="center"><input type="SUBMIT" value="Log in" name="save"></td></tr></form></td></tr></table>

<br><br>';

require 'template.html';

?>