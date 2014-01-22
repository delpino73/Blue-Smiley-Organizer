/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

------- Introduction -------

This script enables you to organize many aspects online, like bookmark
management, diaries, to-do lists, live support, blogs and more.

Development started in 2002. The script has been made available to the
public in July 2006.

The Freecode URL of this project is http://freecode.com/projects/bluesmiley

------- Software License -------

The script is free to use as long as the copyright and adverts are not
removed or become less readable. If you want to remove the copyright
or adverts please buy a commercial license here:

http://www.bookmark-manager.com/commercial-version.php

------- Disclaimer -------

This software is provided 'as-is', without any express or implied
warranty. In no event will the author be held liable for any damages
arising from the use of this software.

------- Installation -------

- This script has been developed under the LAMP Configuration, but
should also work with other operating systems. However PHP and MySQL
is required.

- Create a subdirectory called "organizer" and upload all files into
that folder. Then run the file setup.php from your browser (for
instance http://www.yoursite.com/organizer/setup.php) which will
create all necessary mysql tables and the file config.php.

- If setup.php cannot create config.php use config.sample.php to do it
manually. Change settings in config.sample.php, rename it to config.php
and upload.

- It is recommended (but not really necessary) to run this script with
register_globals disabled. If this isn't the default setting of your
webhost you can achieve this by adding "php_flag register_globals 0"
to the .htaccess file. Some webhosts require you to add
"register_globals=off" to the file php.ini.

- If you want to use the function to email reminders you need to set
up a cronjob for the file cron-email-reminder.php. It has to run once
every 24 hours.

- The script also allows you to upload files to the server through the
web interface. In the file config.php you can determine which file
types are allowed for upload (in the variable $allowed_file_ext). By
default images and zipped files are allowed. If you run PHP as an
Apache module you need to chmod the UPLOAD directory to 777. Unless
you run the script on a dedicated server changing the upload dir to
777 makes you more vulnerable to hackers. If you run PHP as an Apache
module on a shared webhost consult your web host on how to minimize
the risks. If you run PHP as CGI no changes are necessary. If you are
looking for a web host which runs PHP as CGI see link under "Support"
below.

If you want to change the maximum file size which can be uploaded
you need to add (or change) the parameter upload_max_filesize in
your php.ini. Put this line into your php.ini to change the maximum
file size to 10 Megabyte (if you run PHP as CGI):

upload_max_filesize=10M

If you run PHP as an Apache module put this line into your .htaccess:

php_value upload_max_filesize 10M

- If you want to allow importing / exporting of bookmarks and contacts
and you run PHP as an Apache module set the directories "import" and
"export" to 777.

- The script comes with a php.ini which switches register globals and
safe mode off. Delete if you do not want these settings or you already
have a phi.ini.

- If you want to find out how PHP runs on your server check with the
php command phpinfo(). Under "Server API" it will say "CGI" (runs as
CGI) or "Apache" (runs as Apache module).

- After installing the script make sure to immediately create an
account, as the first user will have admin rights. The second user
will be the demo account, every account after that will be a normal
user account. If you want to change who admin or demo is, do this by
changing "_ADMIN_USERID" or "_GUEST_USERID" in config.php.

------- Updating -------

If you update your script from a previous installations you need to
update 3 things:

1. PHP FILES: First upload all the new files (php, javascript) to your
server. It is a good practice to copy the old files into a backup
folder but not necessary. Do not upload the folders "export", "import"
or "upload". In some updates you also will have to upload new pictures
in the "pics" folder.

2. DATABASE FIELDS: Run the file setup.php to make necessary changes
to the database, like adding new fields or tables.

3. CONFIG.PHP FILE: From time to time parameters are changed or added
to config.php. Compare the new config.sample.php to your current
config.php and make necessary changes. Alternatively rename your
config.php to something like config.old and run setup.php which will
generate a new config.php file or manually by using config.sample.php.

(If your texts don't display correctly after updating and you updated
from version 4.xx to a 5.xx version, you might have to run etc-convert-to-utf.php
to convert your characters to the utf8 standard. Make sure to backup
your mysql database before doing so.)

------- Support -------

If you want to support the development of this script you can make
donations through Paypal (Pay to Email: antosch@gmail.com) or buy
webspace from this affiliate link:

http://www.bookmark-manager.com/webspace

The more support I get from users for this script the more time I can
invest improving it.

If you have problems installing the script or you find bugs contact me
through live help at http://www.bookmark-manager.com/help or by Email
at antosch@gmail.com. I'm also looking forward to suggestions on how
to improve the script.

------- Changes -------

For version information please have a look at the file CHANGES.TXT.

------- Newsletter -------

If you use the script make sure to subscribe to the newsletter at
http://www.bookmark-manager.com/download-script.php