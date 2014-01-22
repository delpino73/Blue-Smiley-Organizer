<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

?>

<html>
<head>
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<?php echo _CSS_NAV ?>

<script language="JavaScript" type="text/javascript">

/***********************************************
* Dynamic Countdown script- © Dynamic Drive (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

// DD Tab Menu :
// http://www.dynamicdrive.com/dynamicindex1/ddtabmenu.htm for full source code

//Set tab to intially be selected when page loads:
//[which tab (1=first tab), ID of tab content to display]:
var initialtab=[1,"home"]

var previoustab=""

function expandcontent(cid, aobject) {
if (document.getElementById) {
highlighttab(aobject)
if (previoustab!="")
document.getElementById(previoustab).style.display="none"
document.getElementById(cid).style.display="block"
previoustab=cid
}
}

function highlighttab(aobject) {
if (typeof tabobjlinks=="undefined") collecttablinks()
for (i=0; i<tabobjlinks.length; i++)
tabobjlinks[i].className=""
aobject.className="current"
}

function collecttablinks() {
var tabobj=document.getElementById("tablist")
tabobjlinks=tabobj.getElementsByTagName("A")
}

function do_onload() {
collecttablinks()
expandcontent(initialtab[1], tabobjlinks[initialtab[0]-1])
}

if (window.addEventListener) window.addEventListener("load", do_onload, false)
else if (window.attachEvent) window.attachEvent("onload", do_onload)
else if (document.getElementById) window.onload=do_onload

</script>
</head>

<body topmargin="0">
<table border=0 width="100%">
<tr>
<td valign="top"><ul id="tablist">
<li><a href="home.php" onMouseover="expandcontent('home',this)" target="main">Home</a></li>
<li><a href="show-links.php" onMouseover="expandcontent('link',this)" target="main">Links</a></li>
<li><a href="show-knowledge.php" onMouseover="expandcontent('knowledge',this)" target="main">Know</a></li>
<li><a href="show-reminder-date.php" onMouseover="expandcontent('remind',this)" target="main">Remind</a></li>
<li><a href="show-to-do.php" onMouseover="expandcontent('todo',this)" target="main">To-Do</a></li>
<li><a href="show-contact.php" onMouseover="expandcontent('contact',this)" target="main">Contact</a></li>
<li><a href="show-diary.php" onMouseover="expandcontent('diary',this)" target="main">Diary</a></li>
<li><a href="show-note.php" onMouseover="expandcontent('notes',this)" target="main">Notes</a></li>
<li><a href="show-files.php" onMouseover="expandcontent('file',this)" target="main">Files</a></li>
<li><a href="show-database-categories.php" onMouseover="expandcontent('database', this)" target="main">Database</a></li>
<li><a href="show-blog.php" onMouseover="expandcontent('blog',this)" target="main">Blog</a></li>
<li><a href="edit-search.php" onMouseover="expandcontent('search', this)" target="main">Search</a></li>
<li><a href="navigation.php" onMouseover="expandcontent('misc',this)" target="_self">Misc</a></li>
<li><a href="logout.php" onMouseover="expandcontent('logout',this)" target="_top">Exit</a></li>
<?php if ($userid==_GUEST_USERID) { ?>
<li><a href="logout.php?signup=1" onMouseover="expandcontent('logout', this)" target="_top"><font color="#2f2f2f">SIGN UP</font></a></li>
<?php } if ($userid==_ADMIN_USERID) { ?>
<li><a href="navigation.php" onMouseover="expandcontent('admin', this)" target="_self">Admin</a></li>
<?php } ?>
</ul>
</td>
</tr>
</table>

<div id="tabcontentcontainer">

<div id="home" class="tabcontent">
<table border=0><tr>
<td width="0" height="40"></td>
<td width="800" valign="top"><br>

<?php

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['HOME']['MAIN']} WHERE user=$userid");

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;

echo '<a href="home.php?home_id='.$ID.'" target="main">'.$title.'</a> | ';

}

?>

<a href="show-home.php" target="main">Homepages</a> |
<a href="edit-theme.php" target="main">Edit Theme</a> |
<a href="edit-search.php" target="main">Edit Search</a> |
<a href="show-rss-feeds.php" target="main">RSS Feeds</a> - <a href="add-rss-feeds.php" target="main">Add</a> |
<a href="show-settings.php" target="main">Settings</a>
</td>
</tr>
</table>
</div>

<div id="link" class="tabcontent">
<table border=0><tr>
<td width="0" height="40">&nbsp;</td>
<td valign="top" width="430"><br>
<a href="add-link.php" target="main">Add</a> |
<a href="show-links.php?bluebox=1" target="main">Bluebox</a> |
<a href="show-link-categories.php" target="main">Categories</a> |
<a href="visit-link.php?random=1" target="_blank">Random</a> |
<a href="show-links-public.php" target="main">Public</a> |
<a href="search-links.php" target="main">Search</a>
</td>
<td valign="bottom" width="210"><form method="post" action="url-search.php" target="_blank"><font size="1">Link Search:</font><br>
<input type="text" name="text_search" size="8" onFocus="this.select()">
<input type="submit" value="Search & Go"></td></form>
<td valign="bottom" width="180"><form action="show-links.php" method="post" target="main"><font size="1">Link Search:</font><br>
<input type="hidden" name="show_all" value="1">
<input type="text" name="text_search" size="8" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="knowledge" class="tabcontent">
<table border=0><tr>
<td width="115" height="40">&nbsp;</td>
<td valign="top" width="300"><br>
<a href="add-knowledge.php" target="main">Add</a> |
<a href="show-knowledge-categories.php" target="main">Categories</a> |
<a href="show-knowledge-flashcards.php" target="_blank">Flashcards</a> |
<a href="show-knowledge-public.php" target="main">Public</a> |
<a href="search-knowledge.php" target="main">Search</a>
</td>
<td valign="bottom" width="200">
<form action="show-knowledge.php" method="post" target="main"><font size="1">Knowledge Search:</font><br>
<input type="text" name="text_search" size="12" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="diary" class="tabcontent">
<table border=0><tr>
<td width="330" height="40">&nbsp;</td>
<td valign="top" width="130"><br>
<a href="add-diary.php" target="main">Add</a> | 
<a href="search-diary.php" target="main">Search</a></td>
<td valign="bottom" width="200"><form action="show-diary.php" method="post" target="main"><font size="1">Diary Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="todo" class="tabcontent">
<table border=0><tr>
<td width="220">&nbsp;</td>
<td valign="top" width="200"><br>
<a href="add-to-do.php" target="main">Add</a> | 
<a href="show-to-do-categories.php" target="main">Categories</a> | 
<a href="show-to-do-public.php" target="main">Public</a> |
<a href="search-to-do.php" target="main">Search</a>
</td>
<td valign="bottom" width="200"><form action="show-to-do.php" method="post" target="main"><font size="1">To-Do Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="contact" class="tabcontent">
<table border=0><tr>
<td width="270">&nbsp;</td>
<td valign="top" width="260"><br>
<a href="add-contact.php" target="main">Add</a> | 
<a href="show-contact-categories.php" target="main">Categories</a> |
<a href="export-contacts-start.php" target="main">Export</a> |
<a href="show-contact-public.php" target="main">Public</a> |
<a href="search-contact.php" target="main">Search</a>
</td>
<td valign="bottom" width="200"><form action="show-contact.php" method="post" target="main"><font size="1">Contact Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="remind" class="tabcontent">
<table border=0><tr>
<td width="0" height="40">&nbsp;</td>
<td width="500" valign="top"><br>
<a href="show-reminder-hours.php" target="main">Hours</a> - <strong><a href="add-reminder-hours.php" target="main">Add</a></strong> |
<a href="show-reminder-days.php" target="main">Days</a> - <strong><a href="add-reminder-days.php" target="main">Add</a></strong> | <a href="show-reminder-date.php" target="main">Date</a> - <strong><a href="add-reminder-date.php" target="main">Add</a></strong> | <a href="show-reminder-weekday.php" target="main" target="main">Weekday</a> - <strong><a href="add-reminder-weekday.php" target="main">Add</a></strong> |
<a href="show-reminder-monthly-overview.php" target="main">Overview</a> |
<a href="search-reminder.php" target="main">Search</a>
</td>
<td valign="bottom" width="200">
<form action="show-reminder-all.php" method="post" target="main">
<font size="1">Reminder Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="notes" class="tabcontent">
<table border=0><tr>
<td width="380" height="40">&nbsp;</td>
<td width="200" valign="top"><br>
<a href="add-note.php" target="main">Add</a> |
<a href="show-note-categories.php" target="main">Categories</a> |
<a href="show-note-public.php" target="main">Public</a> |
<a href="search-note.php" target="main">Search</a>
</td>
<td valign="bottom" width="200"><form action="show-note.php" method="post" target="main"><font size="1">Notes Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="database" class="tabcontent">
<table border=0><tr>
<td width="450" height="40">&nbsp;</td>
<td width="400" valign="top"><br>
<a href="add-database-category.php" target="main">Add Category</a> |
<a href="show-database-categories.php" target="main">Categories</a> |
<a href="search-database.php" target="main">Search</a>
</td>
<td valign="bottom" width="200">
<form action="show-database-search.php" method="post" target="main">
<font size="1">DB Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="misc" class="tabcontent">
<table border=0><tr>
<td width="0" height="40">&nbsp;</td>
<td width="840" valign="top"><br>
<a href="help-intro.php" target="main">Help</a> |
<a href="show-settings.php" target="main">Settings</a> |
<a href="show-account-stats.php" target="main">Account Stats</a> |
<a href="show-news.php" target="main">News</a> |
<a href="show-forum.php" target="main">Forum</a> |
<a href="feedback.php" target="main">Feedback</a> |
<a href="show-live-chat.php" target="main">Live Chats</a> - <a href="edit-online-status.php" target="main">Status</a> - <a href="help-live-help.php" target="main">Help</a> |
<a href="show-instant-messages.php" target="main">Instant Messages</a> |
<a href="import-bookmarks-start.php" target="main">Bookmark Import</a> -
<a href="export-bookmarks-start.php" target="main">Export</a>
</td>
</tr>
</table>
</div>

<div id="search" class="tabcontent">
<table border=0><tr>

<?php

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['SEARCH']['MAIN']} WHERE user='$userid'");

require 'inc.search.php';

if ($data) {

if ($data[1]->element1 > 0) { echo $search[$data[1]->element1]; }
if ($data[1]->element2 > 0) { echo $search[$data[1]->element2]; }
if ($data[1]->element3 > 0) { echo $search[$data[1]->element3]; }
if ($data[1]->element4 > 0) { echo $search[$data[1]->element4]; }
if ($data[1]->element5 > 0) { echo $search[$data[1]->element5]; }
if ($data[1]->element6 > 0) { echo $search[$data[1]->element6]; }
if ($data[1]->element7 > 0) { echo $search[$data[1]->element7]; }
if ($data[1]->element8 > 0) { echo $search[$data[1]->element8]; }
if ($data[1]->element9 > 0) { echo $search[$data[1]->element9]; }
if ($data[1]->element10 > 0) { echo $search[$data[1]->element10]; }
if ($data[1]->element11 > 0) { echo $search[$data[1]->element11]; }
if ($data[1]->element12 > 0) { echo $search[$data[1]->element12]; }
if ($data[1]->element13 > 0) { echo $search[$data[1]->element13]; }
if ($data[1]->element14 > 0) { echo $search[$data[1]->element14]; }
if ($data[1]->element15 > 0) { echo $search[$data[1]->element15]; }
if ($data[1]->element16 > 0) { echo $search[$data[1]->element16]; }
if ($data[1]->element17 > 0) { echo $search[$data[1]->element17]; }
if ($data[1]->element18 > 0) { echo $search[$data[1]->element18]; }
if ($data[1]->element19 > 0) { echo $search[$data[1]->element19]; }
if ($data[1]->element20 > 0) { echo $search[$data[1]->element20]; }

} else { echo $search[1].$search[7].$search[13].$search[20].$search[23].$search[22]; }

?>

</tr>
</table>
</div>

<div id="logout" class="tabcontent">
</div>

<?php

$username=$base_instance->get_username($userid);

if (_SHORT_URLS==1) { $url_blog=_HOMEPAGE.'/blog-'.$username; }
else { $url_blog=_HOMEPAGE.'/show-blog-public.php?username='.$username; }

?>

<div id="blog" class="tabcontent">
<table border=0><tr>
<td width="450" height="40">&nbsp;</td>
<td width="430" valign="top"><br>
<a href="add-blog.php" target="main">Add</a> |
<a href="show-blog-categories.php" target="main">Categories</a> |
<a href="show-blog-comments.php" target="main">Comments</a> |
<a href="<?php echo $url_blog?>" target="_blank">View Blog</a> |
<a href="search-blog.php" target="main">Search</a>
</td>
<td valign="bottom" width="200">
<form action="show-blog.php" method="post" target="main">
<font size="1">Blog Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<div id="file" class="tabcontent">
<table border=0><tr>
<td width="490" height="40">&nbsp;</td>
<td width="380" valign="top"><br>
<a href="add-file.php" target="main">Upload</a> |
<a href="add-file-by-url.php" target="main">Upload (URL)</a> |
<a href="show-file-categories.php" target="main">Categories</a> |
<a href="show-files-public.php" target="main">Public</a> |
<a href="search-file.php" target="main">Search</a>
</td>
<td valign="bottom" width="200">
<form action="show-files.php" method="post" target="main">
<font size="1">File Search:</font><br>
<input type="text" name="text_search" size="10" onFocus="this.select()">
<input type="submit" value="Search"></td></form>
</tr>
</table>
</div>

<?php if ($userid==_ADMIN_USERID) { ?>

<div id="admin" class="tabcontent">
<table border=0><tr>
<td width="0">&nbsp;</td>
<td width="800" align="right">
<a href="show-stats.php" target="main">Show Stats</a> |
<a href="http://www.bookmark-manager.com/version-check.php?version=<?php echo _VERSION?>" target="_blank">Check for Updates</a> |
<a href="add-database-backup.php" target="main">Backup Database</a> -
<a href="add-database-restore.php" target="main">Restore</a> |
<a href="delete-old-accounts.php" target="main">Delete Old Accounts</a> |
<a href="show-log-summary.php" target="main">Activity Log</a> |
<a href="send-warning.php" target="main">Send Warning</a><br>
<a href="add-newsletter.php" target="main">Add Newsletter</a> -
<a href="show-newsletter.php" target="main">Show all</a> |
<a href="add-news.php" target="main">Add News</a> - <a href="show-news.php" target="main">Show all</a> |
<a href="show-user-list.php" target="main">Show all User</a> - <a href="search-user.php" target="main">Search</a> |
<a href="http://www.bookmark-manager.com/blog" target="_blank">Blue Smiley Blog</a> |
<a href="http://www.bookmark-manager.com/contact-us.php" target="_blank">Feedback</a>
</td>
</tr>
</table>
</div>

<?php } ?>

</div>

</body>
</html>
