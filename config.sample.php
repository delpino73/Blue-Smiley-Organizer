<?php

/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

/******************** Required Values ***********************/

define('_DB_HOST','localhost');
define('_DB_NAME',''); # name of the database
define('_DB_USER',''); # database username
define('_DB_PW',''); # database password

define('_HOMEPAGE','http://'); # URL of your website, you can copy files into a subdir. (e.g. http://www.mysite.com or http://www.mysite.com/organizer). Do not use a trailing slash

define('_ADMIN_EMAIL',''); # email of admin
define('_ADMIN_SENDER_NAME','Admin'); # will be used as the admin sender name of emails to users

/********************* Optional Values ***********************/

$allowed_file_ext=array('jpg','jpeg','gif','zip','gz'); # which file extensions are allowed to upload

define('_MAX_FILE_SIZE','10000000'); # max file size which can be uploaded (in byte)

define('_FRONTPAGE_TEXT','Welcome to the website of the Blue Smiley Organizer. This site offers the following:<p>

<ul>

<li><strong>Bookmark Manager:</strong> Store your favorite bookmarks online and access them from any computer with internet access.</li>

<li>The <strong>Online Calendar</strong> reminds you of important appointments. Add reminder for specific dates, weekly or recurring events.</li>

<li>Manage your <strong>prioritized To-Do List</strong> and save email addresses and telephone numbers with the <strong>Contact Manager</strong>.</li>

<li>More features include Sticky Notes, <strong>RSS Feeds</strong>, integrated Live Support System, Web Blog, Flashcards, Bookmark Sharing, <strong>Knowledge Management</strong>, File Upload, customizable Themes and a discussion forum.</li>

</ul>'); # text to show on the frontpage

define('_BANNER_AD_MAIN','');

define('_BANNER_AD_FORUM','<a href="http://www.bookmark-manager.com/advert" target="_blank"><img src="http://www.bookmark-manager.com/pics/advert.gif" border="0"></a>');

define('_BANNER_AD_BLOG','<a href="http://www.bookmark-manager.com/advert" target="_blank"><img src="http://www.bookmark-manager.com/pics/advert.gif" border="0"></a>');

define('_BANNER_AD_DOWNLOAD','<a href="http://www.bookmark-manager.com/advert" target="_blank"><img src="http://www.bookmark-manager.com/pics/advert.gif" border="0"></a>');

define('_ALLOW_FILE_UPLOAD',0); # indicate if user may upload files (0 = by default user can not upload files, 1 = any user can upload). This does not affect admin who can always upload. You can allow specific user to upload files anytime by clicking on "edit user" from the admin account.

define('_NO_FILE_UPLOAD_MSG','You are not authorized to upload files, please contact admin to activate file upload'); # user will see this message if no file upload possible

define('_ADMIN_USERID',1);
define('_GUEST_USERID',2);

define('_SHORT_URLS',0); # makes url look shorter, .htaccess needs to work for this (0 = do not use short urls, 1 = use short urls)

define('_FORUM_NOTIFY',0); # send a notification email to admin if someone posts a new forum message (0 = do not notify, 1 = notify)

define('_NEW_USER_NOTIFY',1); # send a notification email to admin if someone signs up (0 = do not notify, 1 = notify)

define('_ASK_FEEDBACK',0); # will ask user for feedback every once in a while (0 = do not ask for feedback, 1 = ask for feedback)

define('_EMAIL_ERROR_REPORT',1); # send a notification email to admin if SQL statement causes an error (0 = do not notify, 1 = notify)

define('_LOG_ACTIVITY',0); # keep a log of all user activity (0 = do not log, 1 = log)

define('_GZIP',0); # (0 = do not gzip compress, 1 = do gzip compress)

define('_GOOGLE_ADSENSE_ID','pub-1841153363764743'); # Google Search Adsense ID

define('_GOOGLE_ADSENSE_CHANNEL','3430673040'); # Google Adsense Search Channel

define('_DEBUG',0); # show SQL statements to admin user (0 = do not show, 1 = show)

define('_SEPARATOR','--------------------------------------------------------------');

define('_SLOGAN','Blue Smiley Organizer');

define('_EMAIL_ADVERT_TEXT','Learn a Language Online

http://www.learnwitholiver.com/');

?>