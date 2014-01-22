<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$text='<table cellpadding=10 cellspacing=0 bgcolor="#ffffff" class="pastel">

<tr><td><a href="show-home.php"><li>Homepages</a></td><td>Configure homepages to your needs</td></tr>

<tr><td><a href="edit-online-status.php"><li>Online Status</a></td><td>Change your online status</td></tr>

<tr><td><a href="edit-search.php"><li>Search</a></td><td>Configure your Search Tab</td></tr>

<tr><td><a href="edit-theme.php"><li>Theme</a></td><td>Change how the design looks</td></tr>

<tr><td><a href="edit-password.php"><li>Password</a></td><td>Change your password here</td></tr>

<tr><td><a href="edit-email-address.php"><li>Email</a></td><td>Update your Email Address</td></tr>

<tr><td><a href="edit-timezone.php"><li>Date & Time</a></td><td>Choose the Date format and Timezone</td></tr>

<tr><td><a href="show-rss-feeds.php"><li>RSS Feeds</a></td><td>Set up RSS Feeds</td></tr>

<tr><td><a href="edit-default-categories.php"><li>Default Categories</a></td><td>Set up your default categories</td></tr>

<tr><td><a href="edit-newsletter-subscription.php"><li>Newsletter</a></td><td>Decide if you want our newsletter</td></tr>

<tr><td><a href="edit-about-me.php"><li>About Me</a></td><td>Edit your "About Me" page & your name</td></tr>

</table>';

$html_instance->add_parameter(
array(
'HEADER'=>'Settings',
'TEXT_CENTER'=>'<p>'.$text.'<p>'
));

$html_instance->process();

?>