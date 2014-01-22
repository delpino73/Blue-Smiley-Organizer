<?php

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

$style='<style type="text/css">
form {margin:0}
A:visited.link {font-weight:700}
body,td {font-family:Arial; font-size:10pt}
A:link,A:visited {font-family:Arial; font-size:10pt; color:#4070A0; text-decoration: none}
.header,h1 {font-family:Arial; font-size:13pt; font-weight:700}
A:hover {color:#646464}
input,select,textarea {font:10pt Arial;border-color:#cfcdcb;border-width:1px;background:#fcfcfc}
table.pastel {border-top:1px solid #ececec; border-left:1px solid #ececec; border-right:1px solid #ececec; border-bottom:1px solid #ececec}
table.pastel {border:1px solid #ececec; border-collapse:collapse}
table.pastel td {border:1px solid #ececec}
table.no_border td {border:0}
input[type="submit"] {background:#efefef}
input[type="submit"]:hover {border:1px solid #4070A0}
</style>
<body bgcolor="#fbfbfb">';

$confirm=isset($_POST['confirm']) ? $_POST['confirm'] : '';
$db_host=isset($_POST['db_host']) ? $_POST['db_host'] : '';
$db_name=isset($_POST['db_name']) ? $_POST['db_name'] : '';
$db_user=isset($_POST['db_user']) ? $_POST['db_user'] : '';
$db_pw=isset($_POST['db_pw']) ? $_POST['db_pw'] : '';
$admin_email=isset($_POST['admin_email']) ? $_POST['admin_email'] : '';
$admin_sender_name=isset($_POST['admin_sender_name']) ? $_POST['admin_sender_name'] : 'Admin';
$homepage=isset($_POST['homepage']) ? $_POST['homepage'] : '';
$forum_notify=isset($_POST['forum_notify']) ? $_POST['forum_notify'] : 0;
$new_user_notify=isset($_POST['new_user_notify']) ? $_POST['new_user_notify'] : 0;
$ask_feedback=isset($_POST['ask_feedback']) ? $_POST['ask_feedback'] : 0;
$allow_file_upload=isset($_POST['allow_file_upload']) ? $_POST['allow_file_upload'] : 0;
$short_urls=isset($_POST['short_urls']) ? $_POST['short_urls'] : 0;
$email_error_report=isset($_POST['email_error_report']) ? $_POST['email_error_report'] : 0;
$log_activity=isset($_POST['log_activity']) ? $_POST['log_activity'] : 0;
$gzip_compress=isset($_POST['gzip_compress']) ? $_POST['gzip_compress'] : 0;

if (isset($_POST['save'])) {

echo $style;

if (!$confirm) { echo 'Tick the box to confirm that you understand the license of the script <p><a href="javascript:history.go(-1)">[Go Back and try again]</a>'; exit; }
if (!$db_host) { echo 'No host entered!<p><a href="javascript:history.go(-1)">Go Back</a> and try again'; exit; }
if (!$db_name) { echo 'No database name entered!<p><a href="javascript:history.go(-1)">Go Back</a> and try again'; exit; }
if (!$db_user) { echo 'No user name entered!<p><a href="javascript:history.go(-1)">Go Back</a> and try again'; exit; }
if (!$db_pw) { echo 'No database password entered!<p><a href="javascript:history.go(-1)">Go Back</a> and try again'; exit; }
if (!$admin_email) { echo 'No admin email entered!<p><a href="javascript:history.go(-1)">Go Back</a> and try again'; exit; }
if (!$admin_sender_name) { $admin_sender_name='Admin'; }
if (!$homepage) { echo 'No Website URL entered!<p><a href="javascript:history.go(-1)">Go Back</a> and try again'; exit; }

@mysql_connect($db_host,$db_user,$db_pw) or die('Could not connect to database!<p><a href="javascript:history.go(-1)">[Go Back and check settings]</a>');
mysql_select_db($db_name) or die('Could not find database!<p><a href="javascript:history.go(-1)">[Go Back and check settings]</a>');

# strip if trailing slash found

$len=strlen($homepage);
$last_char=substr($homepage,$len-1);

if ($last_char=='/') { $homepage=substr($homepage,0,$len-1); }

#

$text="<?php

/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

/******************** Required Values ***********************/

define('_DB_HOST','".$db_host."');
define('_DB_NAME','".$db_name."'); # name of the database
define('_DB_USER','".$db_user."'); # database username
define('_DB_PW','".$db_pw."'); # database password

define('_HOMEPAGE','".$homepage."'); # URL of your website, you can copy files into a subdir. (e.g. http://www.mysite.com or http://www.mysite.com/organizer). Do not use a trailing slash

define('_ADMIN_EMAIL','".$admin_email."'); # email of admin
define('_ADMIN_SENDER_NAME','".$admin_sender_name."'); # will be used as the admin sender name of emails to users

/********************* Optional Values ***********************/

\$allowed_file_ext=array('jpg','jpeg','gif','zip','gz'); # which file extensions are allowed to upload

define('_FRONTPAGE_TEXT','Welcome to the website of the Blue Smiley Organizer. This site offers the following:<p>

<ul>

<li><strong>Bookmark Manager:</strong> Store your favorite bookmarks online and access them from any computer with internet access.</li>

<li>The <strong>Online Calendar</strong> reminds you of important appointments. Add reminder for specific dates, weekly or recurring events.</li>

<li>Manage your <strong>prioritized To-Do List</strong> and save email addresses and telephone numbers with the <strong>Contact Manager</strong>.</li>

<li>More features include Sticky Notes, <strong>RSS Feeds</strong>, integrated Live Support System, Web Blog, Flashcards, Groupware functionality, <strong>Knowledge Management</strong>, File Upload, customizable Themes and a discussion forum.</li>

</ul>'); # text to show on the frontpage

define('_BANNER_AD_MAIN','');

define('_BANNER_AD_FORUM','<a href=\"http://www.bookmark-manager.com/advert\" target=\"_blank\"><img src=\"http://www.bookmark-manager.com/pics/advert.gif\" border=\"0\"></a>');

define('_BANNER_AD_BLOG','<a href=\"http://www.bookmark-manager.com/advert\" target=\"_blank\"><img src=\"http://www.bookmark-manager.com/pics/advert.gif\" border=\"0\"></a>');

define('_BANNER_AD_DOWNLOAD','<a href=\"http://www.bookmark-manager.com/advert\" target=\"_blank\"><img src=\"http://www.bookmark-manager.com/pics/advert.gif\" border=\"0\"></a>');

define('_ALLOW_FILE_UPLOAD',".$allow_file_upload."); # indicate if user may upload files (0 = by default user can not upload files, 1 = any user can upload). This does not affect admin who can always upload. You can allow specific user to upload files anytime by clicking on \"edit user\" from the admin account.

define('_NO_FILE_UPLOAD_MSG','You are not authorized to upload files, please contact Admin to activate file upload'); # user will see this message if no file upload possible

define('_ADMIN_USERID',1);
define('_GUEST_USERID',2);

define('_SHORT_URLS',".$short_urls."); # makes url look shorter, .htaccess needs to work for this (0 = do not use short urls, 1 = use short urls)

define('_FORUM_NOTIFY',".$forum_notify."); # send a notification email to admin if someone posts a new forum message (0 = do not notify, 1 = notify)

define('_NEW_USER_NOTIFY',".$new_user_notify."); # send a notification email to admin if someone signs up (0 = do not notify, 1 = notify)

define('_ASK_FEEDBACK',".$ask_feedback."); # will ask user for feedback every once in a while (0 = do not ask for feedback, 1 = ask for feedback)

define('_EMAIL_ERROR_REPORT',".$email_error_report."); # send a notification email to admin if SQL statement causes an error (0 = do not notify, 1 = notify)

define('_LOG_ACTIVITY',".$log_activity."); # keep a log of all user activity (0 = do not log, 1 = log)

define('_GZIP',".$gzip_compress."); # (0 = do not gzip compress, 1 = do gzip compress)

define('_GOOGLE_ADSENSE_ID','pub-1841153363764743'); # Google Search Adsense ID

define('_GOOGLE_ADSENSE_CHANNEL','3430673040'); # Google Adsense Search Channel

define('_DEBUG',0); # show SQL statements to admin user (0 = do not show, 1 = show)

define('_SEPARATOR','--------------------------------------------------------------');

define('_SLOGAN','Blue Smiley Organizer');

define('_EMAIL_ADVERT_TEXT','Web Hosting for Professionals

http://www.bookmark-manager.com/webspace');

?>";

$f=@fopen('config.php','w');

if (!$f) { echo 'Copy and paste the following text into a new file called <strong>config.php</strong> and upload to server:<p>

<textarea rows=17 cols=90 name=text onFocus="this.select()">'.$text.'</textarea><p>

<a href="setup-frame1.php">[Click here after uploading config.php]</a><p>'; exit;

} else {

fwrite($f,$text);
fclose($f);

echo '<h3>config.php created</h3><a href="setup-frame1.php">[Click here to create tables]</a>'; exit;

}

}

#

if (file_exists('config.php')) { require 'config.php'; } else {

$admin_email=$_SERVER['SERVER_ADMIN'];
$server_name=$_SERVER['SERVER_NAME'];
$request_uri=$_SERVER['REQUEST_URI'];

$homepage='http://'.$server_name.$request_uri;

$homepage=substr($homepage,0,-16);

echo $style.'

<h3>Blue Smiley Organizer Setup</h3>

<table cellpadding=4 cellspacing=0 border=0 bgcolor="#ffffff" width="75%" class="pastel">

<tr><td>

<form action="setup-frame1.php" method="post">

<strong>Host:</strong><br>
<input type="text" name="db_host" value="localhost" size="50"><p>

<strong>Database Name:</strong><br>
<input type="text" name="db_name" size="50"><p>

<strong>Database User:</strong><br>
<input type="text" name="db_user" size="50"><p>

<strong>Database Password:</strong><br>
<input type="Password" name="db_pw" size="50"><p>

<strong>Website URL:</strong><br>
<input type="text" name="homepage" value="',$homepage,'" size="50"><p>

<strong>Admin Email:</strong><br>
<input type="text" name="admin_email" value="',$admin_email,'" size="50"><p>

<strong>Admin Sender Name:</strong><br>
<input type="text" name="admin_sender_name" size="50"><p>

<strong>Forum Notification:</strong><br>

<select name="forum_notify">
<option value=1> Yes
<option value=0 selected> No
</select> (Send a notification email to admin if new forum message is posted)<p>

<strong>New User Notification:</strong><br>

<select name="new_user_notify">
<option value=1 selected> Yes
<option value=0> No
</select> (Send a notification email to admin if new user signs up)<p>

<strong>User Feedback:</strong><br>

<select name="ask_feedback">
<option value=1> Yes
<option value=0 selected> No
</select> (Once in a while ask user for feedback when they login)<p>

<strong>Allow File Upload:</strong><br>

<select name="allow_file_upload">
<option value=1> Yes
<option value=0 selected> No
</select> (Allow all user to upload files. This does not affect admin who can always upload. You can allow specific user to upload files anytime by clicking on "edit user" from the admin account.)<p>

<strong>Use Short URLs:</strong><br>

<select name="short_urls">
<option value=1> Yes
<option value=0 selected> No
</select> (This makes URLs look more beautiful, .htaccess needs to work for this)<p>

<strong>Email SQL Error Report:</strong><br>

<select name="email_error_report">
<option value=1 selected> Yes
<option value=0> No
</select> (Send a notification email to admin if a SQL statement causes an error)<p>

<strong>Log Activity:</strong><br>

<select name="log_activity">
<option value=1> Yes
<option value=0 selected> No
</select> (Keep a log of all user activity)<p>

<strong>Gzip Compress:</strong><br>

<select name="gzip_compress">
<option value=1 selected> Yes
<option value=0> No
</select> (If gzip is already activated by your server choose no)<p>

<input type="Checkbox" name="confirm" value="1"> Tick box to agree to the following Terms and Conditions: A <a href="http://www.bookmark-manager.com/commercial-version.php" target="_blank">commercial license</a> is necessary if you want to remove the adverts or copyright messages, otherwise the script is free to use. Also this software is provided "as-is", without any express or implied warranty. In no event will the author be held liable for any damages arising from the use of this software.<p>

<input type="SUBMIT" value="Continue Setup" name="save">

</form>

</td></tr>

</table>'; exit;

}

@mysql_connect(_DB_HOST,_DB_USER,_DB_PW) or die('Could not connect to database, check if values in config.php are correct!');
mysql_select_db(_DB_NAME) or die('Could not find database, check if values in config.php are correct!');

# change value field to enable negative values for DB number fields

$res=mysql_query("ALTER TABLE organizer_database_number_fields_values CHANGE `value` `value` FLOAT NOT NULL DEFAULT '0'");

# change urgency field in to-do table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_to_do');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='urgency') { $field_exists=1; }

}

if (isset($field_exists)) {

mysql_query("ALTER TABLE organizer_to_do CHANGE `urgency` `priority` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'");

}

}

# change default_urgency_category field in user table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='default_todo_urgency') { $field_exists=1; }

}

if (isset($field_exists)) {

mysql_query("ALTER TABLE organizer_user CHANGE `default_todo_urgency` `default_todo_priority` INT(10) UNSIGNED NOT NULL DEFAULT '0'");

}

}

# change default_external_category field in user table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='default_external_category') { $field_exists=1; }

}

if (isset($field_exists)) {

mysql_query("ALTER TABLE organizer_user CHANGE `default_external_category` `default_note_category` INT(10) UNSIGNED NOT NULL DEFAULT '0'");

}

}

# add default_file_category field to user table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='default_file_category') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_user ADD default_file_category INT UNSIGNED NOT NULL');

}

}

# add online_status field to user table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='online_status') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_user ADD online_status TINYINT UNSIGNED NOT NULL');

}

}

# add online_status field to session table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_session');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='online_status') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_session ADD online_status TINYINT UNSIGNED NOT NULL');

}

}

# add name2 field to chat request table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_chat_request');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='name2') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_chat_request ADD name2 VARCHAR(255) NOT NULL');

}

}

# add dateformat field to user table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='dateformat') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_user ADD dateformat TINYINT UNSIGNED NOT NULL');

}

}

# add user field to blog comments table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_blog_comments');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='user') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_blog_comments ADD user MEDIUMINT UNSIGNED NOT NULL');
mysql_query('ALTER TABLE organizer_blog_comments ADD INDEX (`user`)');

}

}

# add public field to blog comments table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_blog_comments');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='public') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_blog_comments ADD public TINYINT UNSIGNED NOT NULL');

}

}

# add dateformat field to session table

unset($field_exists);

$res=mysql_query("SHOW FIELDS FROM organizer_session");

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='dateformat') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_session ADD dateformat TINYINT UNSIGNED NOT NULL");

}

}

# add email_alert field

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_reminder_date');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='email_alert') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_reminder_date ADD email_alert TINYINT UNSIGNED NOT NULL");
mysql_query("ALTER TABLE organizer_reminder_date ADD INDEX (`email_alert`)");

}

}

# add allow_file_upload field

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='allow_file_upload') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_user ADD allow_file_upload TINYINT UNSIGNED NOT NULL");
mysql_query("ALTER TABLE organizer_session ADD allow_file_upload TINYINT UNSIGNED NOT NULL");

}

}

# add token field

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_file');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='token') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_file ADD token varchar(255) NOT NULL");

}

}

# add answer_id field

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_instant_message');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='answer_id') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_instant_message ADD answer_id INT UNSIGNED NOT NULL');

}

}

# add last_reminded field

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='last_reminded') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_user ADD last_reminded datetime NOT NULL');

}

}

# add font_size field to user table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='font_size') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_user ADD font_size INT UNSIGNED NOT NULL');

}

}

# add font_size field to session table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_session');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='font_size') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_session ADD font_size INT UNSIGNED NOT NULL');

}

}

# add frequency_mode field to link table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_link');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='frequency_mode') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_link ADD frequency_mode TINYINT UNSIGNED NOT NULL DEFAULT 3');

}

}

# add email field to blog comment table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_blog_comments');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='email') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_blog_comments ADD email varchar(255) NOT NULL');

}

}

# add additional fields to search table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_search');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='element20') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_search ADD element11 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element12 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element13 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element14 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element15 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element16 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element17 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element18 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element19 tinyint(3) unsigned NOT NULL default '0'");
mysql_query("ALTER TABLE organizer_search ADD element20 tinyint(3) unsigned NOT NULL default '0'");

}

}

# rename priority field in link table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_link');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='priority') { $field_exists=1; }

}

if (isset($field_exists)) {

mysql_query("ALTER TABLE organizer_link CHANGE `priority` `sequence` INT(10) UNSIGNED NOT NULL DEFAULT '0'");

}

}

# add speed field to link table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_link');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='speed') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_link ADD speed INT UNSIGNED NOT NULL');

$need_to_reorganize_links=1;

}

}

# add public field to todo table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_to_do');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='public') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_to_do ADD public TINYINT UNSIGNED NOT NULL DEFAULT '1'");

}

}

# add public field to contact table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_contact');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='public') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_contact ADD public TINYINT UNSIGNED NOT NULL DEFAULT '1'");

}

}

# add public field to note table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_note');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='public') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_note ADD public TINYINT UNSIGNED NOT NULL DEFAULT '1'");

}

}

# add public field to knowledge table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_knowledge');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='public') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_knowledge ADD public TINYINT UNSIGNED NOT NULL DEFAULT '1'");

}

}

# add public field to file table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_file');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='public') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_file ADD public TINYINT UNSIGNED NOT NULL DEFAULT '1'");

}

}

# add max_items field to rss table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_rss_feed');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='max_items') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_rss_feed ADD max_items INT UNSIGNED NOT NULL DEFAULT '0'");

}

}

# add user_password field to user table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_user');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='user_password') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query('ALTER TABLE organizer_user ADD user_password varchar(255) NOT NULL');

$need_to_convert_passwords=1;

}

}

# add status field to todo table

unset($field_exists);

$res=mysql_query('SHOW FIELDS FROM organizer_to_do');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$field=$data[$index]->Field;
if ($field=='status') { $field_exists=1; }

}

if (empty($field_exists)) {

mysql_query("ALTER TABLE organizer_to_do ADD status TINYINT UNSIGNED NOT NULL DEFAULT '0'");

}

}

# convert passwords

if (isset($need_to_convert_passwords)) {

$res=mysql_query("SELECT ID,password FROM organizer_user");

if (!$res) { echo 'convert password error'; }

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);

$ID=$data[$index]->ID;
$password=$data[$index]->password;

$secure_password=sha1($password);

mysql_query("UPDATE organizer_user SET user_password='$secure_password' WHERE ID=$ID");

}

}

# reorganize ascent speed

if (isset($need_to_reorganize_links)) {

$res=mysql_query("SELECT ID,ascent_speed,url FROM organizer_link");

if (!$res) { echo 'ascent speed error'; }

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);

$ID=$data[$index]->ID;
$ascent_speed=$data[$index]->ascent_speed;
$url=$data[$index]->url;

if ($ascent_speed < 4) { $new_ascent_speed=1; }
else if ($ascent_speed > 3 and $ascent_speed < 30) { $new_ascent_speed=10; }
else if ($ascent_speed > 29 and $ascent_speed < 75) { $new_ascent_speed=50; }
else if ($ascent_speed > 74 and $ascent_speed < 550) { $new_ascent_speed=100; }
else { $new_ascent_speed=1000; }

mysql_query("UPDATE organizer_link SET speed='$new_ascent_speed' WHERE ID=$ID");

}

}

# rename tables

$res=mysql_query('SHOW TABLE STATUS');

if (!empty($res)) {

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);
$table_name=$data[$index]->Name;

if ($table_name=='organizer_upload') { $table_upload_exists=1; }
if ($table_name=='organizer_upload_category') { $table_upload_category_exists=1; }
if ($table_name=='organizer_external_text') { $table_external_text_exists=1; }
if ($table_name=='organizer_external_text_category') { $table_external_category_exists=1; }
if ($table_name=='organizer_external_text_category') { $table_external_category_exists=1; }
if ($table_name=='organizer_rss_feeds') { $table_organizer_rss_feeds_exists=1; }
if ($table_name=='organizer_home') { $table_organizer_home_exists=1; }
if ($table_name=='organizer_home_one') { $table_organizer_home_one_exists=1; }

}

if (isset($table_upload_exists)) {

mysql_query('RENAME TABLE organizer_upload TO organizer_file');

}

if (isset($table_upload_category_exists)) {

mysql_query('RENAME TABLE organizer_upload_category TO organizer_file_category');

}

if (isset($table_external_text_exists)) {

mysql_query('RENAME TABLE organizer_external_text TO organizer_note');

}

if (isset($table_external_category_exists)) {

mysql_query('RENAME TABLE organizer_external_text_category TO organizer_note_category');

}

}

# add tables

$mysql_version=mysql_get_server_info();

if ($mysql_version && version_compare($mysql_version,'4','<')) {

echo '<h3>Please upgrade MySQL to at least version 4 before you continue to install the script.</h3><p><b>Your current MySQL version:</b> '.$mysql_version; exit;

}

if ($mysql_version && version_compare($mysql_version,'4.1','>=')) { $utf='DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'; } else { $utf=''; }

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_home` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(255) NOT NULL default '',
`element1` int(10) unsigned NOT NULL default '0',
`element2` int(10) unsigned NOT NULL default '0',
`element3` int(10) unsigned NOT NULL default '0',
`element4` int(10) unsigned NOT NULL default '0',
`element5` int(10) unsigned NOT NULL default '0',
`element6` int(10) unsigned NOT NULL default '0',
`element7` int(10) unsigned NOT NULL default '0',
`element8` int(10) unsigned NOT NULL default '0',
`element9` int(10) unsigned NOT NULL default '0',
`element10` int(10) unsigned NOT NULL default '0',
`element11` int(10) unsigned NOT NULL default '0',
`element12` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

# reorganize home table

if (isset($table_organizer_home_one_exists) && empty($table_organizer_home_exists)) {

$res=mysql_query('SELECT * FROM organizer_home_one');

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);

$user=$data[$index]->user;
$element1=$data[$index]->element1;
$element2=$data[$index]->element2;
$element3=$data[$index]->element3;
$element4=$data[$index]->element4;
$element5=$data[$index]->element5;
$element6=$data[$index]->element6;
$element7=$data[$index]->element7;
$element8=$data[$index]->element8;
$element9=$data[$index]->element9;
$element10=$data[$index]->element10;
$element11=$data[$index]->element11;
$element12=$data[$index]->element12;

$title='Home 1';

mysql_query("INSERT INTO organizer_home (user,title,element1,element2,element3,element4,element5,element6,element7,element8,element9,element10,element11,element12) VALUES ($user,'$title','$element1','$element2','$element3','$element4','$element5','$element6','$element7','$element8','$element9','$element10','$element11','$element12')");

}

#

$res=mysql_query('SELECT * FROM organizer_home_two');

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);

$user=$data[$index]->user;
$element1=$data[$index]->element1;
$element2=$data[$index]->element2;
$element3=$data[$index]->element3;
$element4=$data[$index]->element4;
$element5=$data[$index]->element5;
$element6=$data[$index]->element6;
$element7=$data[$index]->element7;
$element8=$data[$index]->element8;
$element9=$data[$index]->element9;
$element10=$data[$index]->element10;
$element11=$data[$index]->element11;
$element12=$data[$index]->element12;

$title='Home 2';

mysql_query("INSERT INTO organizer_home (user,title,element1,element2,element3,element4,element5,element6,element7,element8,element9,element10,element11,element12) VALUES ($user,'$title','$element1','$element2','$element3','$element4','$element5','$element6','$element7','$element8','$element9','$element10','$element11','$element12')");

}

#

$res=mysql_query('SELECT * FROM organizer_home_three');

$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);

$user=$data[$index]->user;
$element1=$data[$index]->element1;
$element2=$data[$index]->element2;
$element3=$data[$index]->element3;
$element4=$data[$index]->element4;
$element5=$data[$index]->element5;
$element6=$data[$index]->element6;
$element7=$data[$index]->element7;
$element8=$data[$index]->element8;
$element9=$data[$index]->element9;
$element10=$data[$index]->element10;
$element11=$data[$index]->element11;
$element12=$data[$index]->element12;

$title='Home 3';

mysql_query("INSERT INTO organizer_home (user,title,element1,element2,element3,element4,element5,element6,element7,element8,element9,element10,element11,element12) VALUES ($user,'$title','$element1','$element2','$element3','$element4','$element5','$element6','$element7','$element8','$element9','$element10','$element11','$element12')");

}

}

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_rss_feed` (
ID int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
feed varchar(255) NOT NULL default '0',
title varchar(255) NOT NULL default '',
max_items int(10) unsigned NOT NULL default '0',
PRIMARY KEY  (ID),
KEY `user` (`user`)
) AUTO_INCREMENT=100 $utf;
");

# reorganize rss table

if (isset($table_organizer_rss_feeds_exists)) {

	$res=mysql_query('SELECT * FROM organizer_rss_feeds');

	$rows=mysql_num_rows($res);

	for ($index=1; $index <= $rows; $index++) {

	$data[$index]=mysql_fetch_object($res);

	$ID=$data[$index]->ID;
	$user=$data[$index]->user;

	$feed1=$data[$index]->feed1;

	if (!empty($feed1)) { $title1=$data[$index]->title1; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title1.'","'.$feed1.'")');	}

	$feed2=$data[$index]->feed2;

	if (!empty($feed2)) { $title2=$data[$index]->title2; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title2.'","'.$feed2.'")');	}

	$feed3=$data[$index]->feed3;

	if (!empty($feed3)) { $title3=$data[$index]->title3; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title3.'","'.$feed3.'")');	}

	$feed4=$data[$index]->feed4;

	if (!empty($feed4)) { $title4=$data[$index]->title4; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title4.'","'.$feed4.'")');	}

	$feed5=$data[$index]->feed5;

	if (!empty($feed5)) { $title5=$data[$index]->title5; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title5.'","'.$feed5.'")'); }

	$feed6=$data[$index]->feed6;

	if (!empty($feed6)) { $title6=$data[$index]->title6; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title6.'","'.$feed6.'")');	}

	$feed7=$data[$index]->feed7;

	if (!empty($feed7)) { $title7=$data[$index]->title7; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title7.'","'.$feed7.'")');	}

	$feed8=$data[$index]->feed8;

	if (!empty($feed8)) { $title8=$data[$index]->title8; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title8.'","'.$feed8.'")');	}

	$feed9=$data[$index]->feed9;

	if (!empty($feed9)) { $title9=$data[$index]->title9; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title9.'","'.$feed9.'")');	}

	$feed10=$data[$index]->feed10;

	if (!empty($feed10)) { $title10=$data[$index]->title10; mysql_query('INSERT INTO organizer_rss_feed (user,title,feed) VALUES ("'.$user.'","'.$title10.'","'.$feed10.'")'); }

	}

	mysql_query('DROP TABLE IF EXISTS organizer_rss_feeds');

}

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_blog` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`category` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_blog_category`(
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_blog_comments` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`name` varchar(255) NOT NULL default '',
`email` varchar(255) NOT NULL default '',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`blog_id` int(10) unsigned NOT NULL default '0',
`public` tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `blog_id` (`blog_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_chat` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`username` varchar(255) NOT NULL default '',
`message` text NOT NULL,
`token` varchar(255) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `token` (`token`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_chat_request` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`token` varchar(255) NOT NULL default '',
`name` varchar(255) NOT NULL default '',
`name2` varchar(255) NOT NULL default '',
`email` varchar(255) NOT NULL default '',
`question` text NOT NULL,
`accepted` tinyint(3) unsigned NOT NULL default '0',
`popup` tinyint(3) unsigned NOT NULL default '0',
`IP` varchar(255) NOT NULL default '',
`referrer` varchar(255) NOT NULL default '',
`browser` varchar(255) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `token` (`token`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_chat_user` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user_token` varchar(255) NOT NULL default '',
`chat_token` varchar(255) NOT NULL default '',
`last_active` int(10) unsigned NOT NULL default '0',
`chat_ended` tinyint(3) unsigned NOT NULL default '0',
`typing` tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `chat_token` (`chat_token`),
KEY `last_active` (`last_active`),
KEY `user_token` (`user_token`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_contact` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`firstname` varchar(100) NOT NULL default '',
`lastname` varchar(100) NOT NULL default '',
`company` varchar(100) NOT NULL default '',
`email` varchar(100) NOT NULL default '',
`telephone` varchar(100) NOT NULL default '',
`fax` varchar(100) NOT NULL default '',
`mobile` varchar(100) NOT NULL default '',
`address` text NOT NULL,
`notes` text NOT NULL,
`url` varchar(255) NOT NULL default '',
`category` int(10) unsigned NOT NULL default '1',
`public` tinyint(3) unsigned NOT NULL default '1',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_contact_category` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_category` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_checkbox_fields` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_checkbox_fields_items` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`checkbox_field_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `checkbox_field_id` (`checkbox_field_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_checkbox_fields_values` (
`ID` int(10) unsigned NOT NULL auto_increment,
`date` date NOT NULL default '0000-00-00',
`user` mediumint(8) unsigned NOT NULL default '0',
`data_id` int(10) unsigned NOT NULL default '0',
`checkbox_field_id` int(10) unsigned NOT NULL default '0',
`value` int(10) unsigned NOT NULL default '0',
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`),
KEY `data_id` (`data_id`),
KEY `checkbox_field_id` (`checkbox_field_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_main_values` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`day` tinyint(3) unsigned NOT NULL default '0',
`month` tinyint(3) unsigned NOT NULL default '0',
`year` year(4) NOT NULL default '0000',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_number_fields` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_number_fields_values` (
`ID` int(10) unsigned NOT NULL auto_increment,
`date` date NOT NULL default '0000-00-00',
`user` mediumint(8) unsigned NOT NULL default '0',
`data_id` int(10) unsigned NOT NULL default '0',
`number_field_id` int(10) unsigned NOT NULL default '0',
`value` float NOT NULL default '0',
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`),
KEY `number_field_id` (`number_field_id`),
KEY `value` (`value`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_select_fields` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_select_fields_items` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`select_field_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `select_field_id` (`select_field_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_database_select_fields_values` (
`ID` int(10) unsigned NOT NULL auto_increment,
`date` date NOT NULL default '0000-00-00',
`user` mediumint(8) unsigned NOT NULL default '0',
`data_id` int(10) unsigned NOT NULL default '0',
`select_field_id` int(10) unsigned NOT NULL default '0',
`value` int(10) unsigned NOT NULL default '0',
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`),
KEY `data_id` (`data_id`),
KEY `select_field_id` (`select_field_id`)
) $utf");

mysql_query("CREATE TABLE `organizer_database_text_fields` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`)
) $utf");

mysql_query("CREATE TABLE `organizer_database_text_fields_values` (
`ID` int(10) unsigned NOT NULL auto_increment,
`date` date NOT NULL default '0000-00-00',
`user` mediumint(8) unsigned NOT NULL default '0',
`data_id` int(10) unsigned NOT NULL default '0',
`text_field_id` int(10) unsigned NOT NULL default '0',
`value` text NOT NULL,
`category_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category_id` (`category_id`),
KEY `text_field_id` (`text_field_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_diary` (
`ID` int(10) unsigned NOT NULL auto_increment,
`date` date NOT NULL default '0000-00-00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`last_shown` date NOT NULL default '0000-00-00',
`shown` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `date` (`date`),
KEY `last_showed` (`last_shown`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_note` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`category` int(10) unsigned NOT NULL default '0',
`public` tinyint(3) unsigned NOT NULL default '1',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_note_category` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_forum` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`updated` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`followup` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `datetime` (`datetime`),
KEY `followup` (`followup`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_instant_message` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`receiver` mediumint(8) unsigned NOT NULL default '0',
`text` text NOT NULL,
`popup` tinyint(3) unsigned NOT NULL default '0',
`answer_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY (`ID`),
KEY `user` (`user`),
KEY `receiver` (`receiver`),
KEY `popup` (`popup`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_knowledge` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`category` int(10) unsigned NOT NULL default '0',
`value` int(10) unsigned NOT NULL default '100',
`shown` int(10) unsigned NOT NULL default '0',
`last_shown` bigint(20) unsigned NOT NULL default '0',
`public` tinyint(3) unsigned NOT NULL default '1',
PRIMARY KEY(`ID`),
KEY `value` (`value`),
KEY `user` (`user`),
KEY `category` (`category`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_knowledge_category` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE `organizer_knowledge_flashcards` (
`word_id` int(10) unsigned NOT NULL default '0',
`last_shown` bigint(20) unsigned NOT NULL default '0',
`value` int(10) NOT NULL default '0',
`user` int(10) unsigned NOT NULL default '0',
`shown` int(10) unsigned NOT NULL default '0',
`word_loop` tinyint(3) unsigned NOT NULL default '0',
`category_id` int(10) unsigned NOT NULL default '0',
UNIQUE KEY word_id_2 (word_id,category_id,`user`),
KEY word_id (word_id),
KEY `user` (`user`),
KEY `value` (`value`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_link` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`last_visit` datetime NOT NULL default '0000-00-00 00:00:00',
`url` text NOT NULL,
`category` int(10) unsigned NOT NULL default '0',
`popularity` float(10,2) NOT NULL default '0.00',
`visits` int(10) unsigned NOT NULL default '0',
`public` tinyint(3) unsigned NOT NULL default '1',
`title` varchar(100) NOT NULL default '',
`subtitle` varchar(100) NOT NULL default '',
`frequency` int(10) unsigned NOT NULL default '0',
`frequency_mode` tinyint(3) unsigned NOT NULL default '3',
`notes` text NOT NULL,
`keywords` text NOT NULL,
`speed` int(10) unsigned NOT NULL default '50',
`sequence` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `popularity` (`popularity`),
KEY `visits` (`visits`),
KEY `category` (`category`),
KEY `last_visit` (`last_visit`),
KEY `datetime` (`datetime`),
KEY `public` (`public`),
KEY `sequence` (`sequence`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_link_category` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`parent_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `parent_id` (`parent_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_log` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`request_uri` varchar(255) NOT NULL default '',
`user` mediumint(8) unsigned NOT NULL default '0',
`globals_request` text NOT NULL,
`user_agent` varchar(255) NOT NULL default '',
`IP` varchar(255) NOT NULL default '',
`referrer` varchar(255) NOT NULL default '',
PRIMARY KEY(`ID`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_news` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` date NOT NULL default '0000-00-00',
`text` text NOT NULL,
`title` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `datetime` (`datetime`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_newsletter` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` date NOT NULL default '0000-00-00',
`subject` varchar(255) NOT NULL default '',
`text` text NOT NULL,
PRIMARY KEY(`ID`),
KEY `datetime` (`datetime`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_password` (
`ID` int(10) unsigned NOT NULL auto_increment,
`create_time` int(10) unsigned NOT NULL default '0',
`email` varchar(255) NOT NULL default '',
`password` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_reminder_date` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`what_time` time NOT NULL default '00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`day` tinyint(3) unsigned NOT NULL default '0',
`month` tinyint(3) unsigned NOT NULL default '0',
`year` smallint(5) unsigned NOT NULL default '0',
`warning_homepage` tinyint(3) unsigned NOT NULL default '0',
`popup` tinyint(3) unsigned NOT NULL default '0',
`homepage` tinyint(3) unsigned NOT NULL default '0',
`email_alert` tinyint(3) unsigned NOT NULL default '0',
`last_reminded` date NOT NULL default '0000-00-00',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `homepage` (`homepage`),
KEY `email_alert` (`email_alert`),
KEY `popup` (`popup`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_reminder_days` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`last_reminded` date NOT NULL default '0000-00-00',
`what_time` time NOT NULL default '00:00:00',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`done` int(10) unsigned NOT NULL default '0',
`frequency` int(10) unsigned NOT NULL default '0',
`popup` tinyint(3) unsigned NOT NULL default '0',
`homepage` tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `done` (`done`),
KEY `last_reminded` (`last_reminded`),
KEY `datetime` (`datetime`),
KEY `popup` (`popup`),
KEY `homepage` (`homepage`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_reminder_hours` (
`ID` int(11) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`last_reminded` int(10) unsigned NOT NULL default '0',
`frequency` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_reminder_weekday` (
`ID` int(11) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`what_time` time NOT NULL default '00:00:00',
`day_of_the_week` varchar(100) NOT NULL default '0',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`last_reminded` date NOT NULL default '0000-00-00',
`popup` tinyint(3) unsigned NOT NULL default '0',
`homepage` tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `popup` (`popup`),
KEY `homepage` (`homepage`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_search` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`element1` tinyint(3) unsigned NOT NULL default '0',
`element2` tinyint(3) unsigned NOT NULL default '0',
`element3` tinyint(3) unsigned NOT NULL default '0',
`element4` tinyint(3) unsigned NOT NULL default '0',
`element5` tinyint(3) unsigned NOT NULL default '0',
`element6` tinyint(3) unsigned NOT NULL default '0',
`element7` tinyint(3) unsigned NOT NULL default '0',
`element8` tinyint(3) unsigned NOT NULL default '0',
`element9` tinyint(3) unsigned NOT NULL default '0',
`element10` tinyint(3) unsigned NOT NULL default '0',
`element11` tinyint(3) unsigned NOT NULL default '0',
`element12` tinyint(3) unsigned NOT NULL default '0',
`element13` tinyint(3) unsigned NOT NULL default '0',
`element14` tinyint(3) unsigned NOT NULL default '0',
`element15` tinyint(3) unsigned NOT NULL default '0',
`element16` tinyint(3) unsigned NOT NULL default '0',
`element17` tinyint(3) unsigned NOT NULL default '0',
`element18` tinyint(3) unsigned NOT NULL default '0',
`element19` tinyint(3) unsigned NOT NULL default '0',
`element20` tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_session` (
`ID` int(10) unsigned NOT NULL auto_increment,
`session_id` int(10) unsigned NOT NULL default '0',
`create_time` int(10) unsigned NOT NULL default '0',
`last_active` int(10) unsigned NOT NULL default '0',
`user` mediumint(8) unsigned NOT NULL default '0',
`lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
`theme` tinyint(3) unsigned NOT NULL default '1',
`timezone` tinyint(4) NOT NULL default '1',
`online_status` tinyint(3) unsigned NOT NULL default '1',
`dateformat` tinyint(3) unsigned NOT NULL default '1',
`font_face_navigation` int(10) unsigned NOT NULL default '0',
`font_face_main` int(10) unsigned NOT NULL default '0',
`font_size` int(10) unsigned NOT NULL default '0',
`background` int(10) unsigned NOT NULL default '0',
`color_navigation` int(10) unsigned NOT NULL default '0',
`color_main` int(10) unsigned NOT NULL default '0',
`allow_file_upload` tinyint(3) unsigned NOT NULL default '2',
PRIMARY KEY(`ID`),
KEY `session_id` (`session_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_sticky_note` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`note_id` int(10) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `note_id` (`note_id`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_to_do` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`category` int(10) unsigned NOT NULL default '0',
`priority` tinyint(3) unsigned NOT NULL default '0',
`public` tinyint(3) unsigned NOT NULL default '1',
`status` tinyint(3) unsigned NOT NULL default '0',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category` (`category`),
KEY `priority` (`priority`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_to_do_category` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_file` (
`ID` int(10) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
`filename` varchar(100) NOT NULL default '',
`text` text NOT NULL,
`category` int(10) unsigned NOT NULL default '0',
`token` varchar(255) NOT NULL default '',
`public` tinyint(3) unsigned NOT NULL default '1',
PRIMARY KEY(`ID`),
KEY `user` (`user`),
KEY `category` (`category`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_file_category` (
`ID` int(10) unsigned NOT NULL auto_increment,
`user` mediumint(8) unsigned NOT NULL default '0',
`title` varchar(100) NOT NULL default '',
PRIMARY KEY(`ID`),
KEY `user` (`user`)
) $utf");

mysql_query("CREATE TABLE IF NOT EXISTS `organizer_user` (
`ID` mediumint(8) unsigned NOT NULL auto_increment,
`datetime` datetime NOT NULL default '0000-00-00 00:00:00',
`firstname` varchar(50) NOT NULL default '',
`lastname` varchar(50) NOT NULL default '',
`email` varchar(100) NOT NULL default '',
`username` varchar(50) NOT NULL default '',
`user_password` varchar(255) NOT NULL default '',
`logins` int(10) unsigned NOT NULL default '0',
`lastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
`country` tinyint(3) unsigned NOT NULL default '1',
`theme` tinyint(3) unsigned NOT NULL default '1',
`language` tinyint(3) unsigned NOT NULL default '1',
`timezone` tinyint(4) NOT NULL default '1',
`dateformat` tinyint(3) unsigned NOT NULL default '1',
`IP` varchar(200) NOT NULL default '',
`newsletter` int(10) unsigned NOT NULL default '0',
`newsletter_opt_in` int(10) unsigned NOT NULL default '0',
`default_link_category` int(10) unsigned NOT NULL default '0',
`default_know_category` int(10) unsigned NOT NULL default '0',
`default_todo_category` int(10) unsigned NOT NULL default '0',
`default_contact_category` int(10) unsigned NOT NULL default '0',
`default_todo_priority` tinyint(4) unsigned NOT NULL default '0',
`default_note_category` int(10) unsigned NOT NULL default '0',
`default_file_category` int(10) unsigned NOT NULL default '0',
`default_blog_category` int(10) unsigned NOT NULL default '0',
`font_face_navigation` int(10) unsigned NOT NULL default '0',
`font_face_main` int(10) unsigned NOT NULL default '0',
`font_size` int(10) unsigned NOT NULL default '0',
`background` int(10) unsigned NOT NULL default '0',
`color_navigation` int(10) unsigned NOT NULL default '0',
`color_main` int(10) unsigned NOT NULL default '0',
`about_me` text NOT NULL,
`allow_file_upload` tinyint(3) unsigned NOT NULL default '2',
`online_status` tinyint(3) unsigned NOT NULL default '0',
`last_reminded` datetime NOT NULL default '0000-00-00 00:00:00',
PRIMARY KEY(`ID`),
UNIQUE KEY `username` (`username`),
KEY `datetime` (`datetime`),
KEY `user_password` (`user_password`),
KEY `email` (`email`),
KEY `newsletter` (`newsletter`)
) $utf");

setcookie('sid','','631213200','/'); # delete cookie

$res=mysql_query("SELECT COUNT(*) AS cnt FROM organizer_user");
$data=mysql_fetch_object($res);

$number_user=$data->cnt;

echo $style.'<h3>Database changes finished</h3>';

if ($number_user==0) { echo 'The next step is to create two users. The first user has admin rights, the second user will be the demo account.<br>Every user created afterwards will be a regular user.<p><a href="sign-up.php" target="_top">[Click here to sign up the admin user]</a>'; }

else { echo '<p><a href="login.php" target="_top">[Click here to login]</a>'; }

#

$sapi_type=php_sapi_name();

$b=@putenv('TZ=Europe/London');

if ($b!=1) {

echo '<p><table border=1 cellspacing="0" cellpadding="5"><tr><td>';

echo '<font color="#ff0000">Warning: PHP runs with safe mode on. In this case timezones can not be customized in the settings of the organizer.<br>No action is required if the timezone where you live and the server timezone is the same.</font><p>';

if (substr($sapi_type,0,3)=='cgi') { echo 'To switch safe mode off include <strong>safe_mode=Off</strong> in your php.ini'; }

else { echo 'To switch safe mode off include <strong>php_flag safe_mode off</strong> in your .htaccess file'; }

echo '</td></tr></table>';

}

if (ini_get('register_globals')) {

echo '<p><table border=1 cellspacing="0" cellpadding="5"><tr><td>';

echo '<p><font color="#ff0000">Warning: register_globals are on. For security reasons it is recommended to switch it off.</font><p>';

if (substr($sapi_type,0,3)=='cgi') { echo 'To switch them off include <strong>register_globals=Off</strong> in your php.ini'; }
else { echo 'To switch them off include <strong>php_flag register_globals off</strong> in your .htaccess file'; }

echo '</td></tr></table>';

}

if (substr($sapi_type,0,3)!='cgi') {

echo '<p><table border=1 cellspacing="0" cellpadding="5"><tr><td>You are running PHP as an Apache module. Therefore you need to chmod the following directories to 777:<p>

<strong>upload</strong> : for uploading files<br>
<strong>import</strong> : importing bookmark file<br>
<strong>export</strong> : exporting bookmarks and contacts<br>

Be aware that setting directories to 777 has security risks, unless you run the script on a VPS or dedicated server. It is recommended to run this script with PHP in CGI mode where setting directories to 777 is not necessary. Check out <a href="http://www.bookmark-manager.com/webspace" target="_blank"><u>this webhost</u></a> which offers PHP in CGI mode.<p>

For security reasons you can now delete setup-frame1.php from the server.
</td></tr></table>';

}

?>