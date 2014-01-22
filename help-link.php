<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$data=$base_instance->get_data("SELECT username,user_password FROM organizer_user WHERE ID=$userid");
$username=$data[1]->username;
$password=$data[1]->user_password;

$url=$username.'/'.$password;
$encoded_url=base64_encode($url);

$help_text='<br><div align="center"><table width="95%" cellspacing=5 cellpadding=5 bgcolor="#ffffff" class="pastel2"><tr><td>

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

<h3><u>Adding a Link</u></h3>

When you add a new link the following fields are shown:<p>

<strong>URL:</strong> Address of the link, like http://news.bbc.co.uk/<p>

<strong>Category:</strong> Category of the link. You can browse and search links by categories.<p>

<strong>Title:</strong> By entering a title you will hide the URL and show the title instead. This is especially recommended to hide long and unsightly URLs.<p>

<strong>Subtitle:</strong> The subtitle is shown in small fonts below title in the link list. It is an optional field.<p>

<strong>Ascent Speed:</strong> With the "Ascent Speed" you can determine how quickly the link will move to the top of the list if you sort by "Speed Ranking".<p>

<strong>Sequence ID:</strong> With the "Sequence" value you can sort links in a specific sequence. Just enter a number here and sort by "Sequence" in the link list. This field is optional.<p>

<strong>Link is:</strong> If the Link is public, other users can view it in your profile. The default is "private" to protect your privacy. If a link is "public" a "P" under "Actions" in the link list will indicate this.<p>

<strong>Show Link in Bluebox:</strong> A special feature of the Blue Smiley Organizer is the Bluebox. The idea is to visit links in specific intervals, like every day or every 30 days, etc. The Bluebox shows those links which are due to be visited. If you want to visit a website every 30 days enter "30" into the "Days" field. For a news website you could enter "20" in the hours field.<p>

<strong>Keywords:</strong> If you want to find links by specific keywords add these here. Separate keywords with a space, do not use commas.<p>

<strong>Notes:</strong> This field is for longer comments which won\'t be shown in the link list. Notes will be included in the link search function. If a link has notes a <strong>N</strong> will appear next to the URL in the link list.<p>

<h3><u>Misc</u></h3>

<strong>Popularity Value:</strong> The "Popularity Value" indicates how popular a link is in terms of being visited. The difference to the total number of visits is that the value will decrease if the link becomes less popular over time.<p>

<a name="import"></a>
<h3><u>Importing & Exporting Bookmarks</u></h3><p>

You can easily export your existing bookmark file. In the Internet Explorer go to <strong>File > Import and Export</strong> and choose Export Favorites.<p>

In Mozilla Firefox go to <strong>Bookmarks > Manage Bookmarks</strong>, then to <strong>File > Export</strong>.

This file needs to be uploaded to the this website at <strong>Misc > Bookmark Import</strong>.<p>

It is recommended to export your bookmarks from this site from time to time for backup reasons. To do this go to <strong>Misc > Export</strong>.<p>

<h3><u>Quickly Adding Links</u></h3><p>

To add bookmarks to your organizer with a single click, drag and drop "Add Link" onto your Browser Toolbar:<p></p>

<a href="javascript:void(window.open(\''._HOMEPAGE.'/autolink.php?code='.$encoded_url.'&url=\'+location.href,\'_blank\',\'width=550,height=700,status=no,resizable=yes,scrollbars=auto\'))"><u>Add Link</u></a><p>

By doing so, you just need to click on "Add Link" on the toolbar to bookmark a site you are browsing at the moment.

</td></tr></table></div>';

$html_instance->add_parameter(
	array(
		'TEXT'=>$help_text
	));

$html_instance->process();

?>