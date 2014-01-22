<?php

echo 'edit and remove first line in this file to run it'; exit; # remove this line to run this script, and also make sure to make a mysql backup before running script!!

require 'config.php';
require 'inc.vars.php';

@mysql_connect(_DB_HOST,_DB_USER,_DB_PW) or die('Could not connect to database');
mysql_select_db(_DB_NAME) or die('Could not find database');

$day=date('j',mktime(0,0,0,date('m'),date('d'),date('Y')));
$month=date('n',mktime(0,0,0,date('m'),date('d'),date('Y')));
$year=date('Y',mktime(0,0,0,date('m'),date('d'),date('Y')));

$res=mysql_query("SELECT charset,ID,username,password FROM organizer_user");
$rows=mysql_num_rows($res);

for ($index=1; $index <= $rows; $index++) {

$data[$index]=mysql_fetch_object($res);

$userid=$data[$index]->ID;
$charset=$data[$index]->charset;
$username=$data[$index]->username;
$password=$data[$index]->password;

if ($charset==1) { $charset='ISO-8859-1'; }
else if ($charset==2) { $charset='big5'; }
else if ($charset==3) { $charset='euc-kr'; }
else if ($charset==4) { $charset='Shift_JIS'; }
else if ($charset==5) { $charset='ISO-8859-7'; }
else if ($charset==6) { $charset='ISO-8859-10'; }
else if ($charset==7) { $charset='Windows-1255'; }
else if ($charset==8) { $charset='UTF-8'; }
else if ($charset==9) { $charset='Windows-1251'; }
else { $charset='ISO-8859-1'; }

echo '<hr>'.$username.' ['.$password.'] [Charset: '.$charset.']<br>';

# organizer_blog

$table='organizer_blog'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;
$text=$data2[$index2]->text;
$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$text_converted=iconv($charset,'utf-8',$text);

$title_converted=addslashes($title_converted);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_blog_category

$table='organizer_blog_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;
$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_blog_comments

$table='organizer_blog_comments'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;
$text=$data2[$index2]->text;
$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$text_converted=iconv($charset,'utf-8',$text);

$title_converted=addslashes($title_converted);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_contact

$table='organizer_contact'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$firstname=$data2[$index2]->firstname;
$lastname=$data2[$index2]->lastname;
$company=$data2[$index2]->company;
$address=$data2[$index2]->address;
$notes=$data2[$index2]->notes;

$firstname_converted=iconv($charset,'utf-8',$firstname);
$lastname_converted=iconv($charset,'utf-8',$lastname);
$company_converted=iconv($charset,'utf-8',$company);
$address_converted=iconv($charset,'utf-8',$address);
$notes_converted=iconv($charset,'utf-8',$notes);

$firstname_converted=addslashes($firstname_converted);
$lastname_converted=addslashes($lastname_converted);
$company_converted=addslashes($company_converted);
$address_converted=addslashes($address_converted);
$notes_converted=addslashes($notes_converted);

$res3=mysql_query("UPDATE $table SET firstname='$firstname_converted',lastname='$lastname_converted',company='$company_converted',address='$address_converted',notes='$notes_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_contact_category

$table='organizer_contact_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;
$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_database_category

$table='organizer_database_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_database_checkbox_fields

$table='organizer_database_checkbox_fields'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_database_main_values

$table='organizer_database_main_values'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_database_select_fields

$table='organizer_database_select_fields'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_database_select_fields_items

$table='organizer_database_select_fields_items'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_database_text_fields

$table='organizer_database_text_fields'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_diary

$table='organizer_diary'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;
$text=$data2[$index2]->text;
$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$text_converted=iconv($charset,'utf-8',$text);

$title_converted=addslashes($title_converted);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_file

$table='organizer_file'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_file_category

$table='organizer_file_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_file

$table='organizer_file'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_instant_message

$table='organizer_instant_message'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$text=$data2[$index2]->text;

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_knowledge

$table='organizer_knowledge'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_knowledge_category

$table='organizer_knowledge_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_link

$table='organizer_link'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$subtitle=$data2[$index2]->subtitle;
$notes=$data2[$index2]->notes;

$title_converted=iconv($charset,'utf-8',$title);
$subtitle_converted=iconv($charset,'utf-8',$subtitle);
$notes_converted=iconv($charset,'utf-8',$notes);

$title_converted=addslashes($title_converted);
$subtitle_converted=addslashes($subtitle_converted);
$notes_converted=addslashes($notes_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',subtitle='$subtitle_converted',notes='$notes_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_link_category

$table='organizer_link_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_knowledge

$table='organizer_note'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_note_category

$table='organizer_note_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_reminder_date

$table='organizer_reminder_date'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_reminder_days

$table='organizer_reminder_days'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_reminder_hours

$table='organizer_reminder_hours'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_reminder_weekday

$table='organizer_reminder_weekday'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_sticky_note

$table='organizer_sticky_note'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_to_do

$table='organizer_to_do'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;
$text=$data2[$index2]->text;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$text_converted=iconv($charset,'utf-8',$text);
$text_converted=addslashes($text_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted',text='$text_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# organizer_to_do_category

$table='organizer_to_do_category'; echo $table.' ';

$res2=mysql_query("SELECT * FROM $table WHERE user=$userid");
$rows2=mysql_num_rows($res2);

for ($index2=1; $index2 <= $rows2; $index2++) {

$data2[$index2]=mysql_fetch_object($res2);

$item_id=$data2[$index2]->ID;

$title=$data2[$index2]->title;

$title_converted=iconv($charset,'utf-8',$title);
$title_converted=addslashes($title_converted);

$res3=mysql_query("UPDATE $table SET title='$title_converted' WHERE ID='$item_id' AND user=$userid");

echo $item_id.' ';

}

# update organizer_user charset=8

$res4=mysql_query("UPDATE organizer_user SET charset=8 WHERE ID='$userid'");
$res4=mysql_query("UPDATE organizer_session SET charset=8 WHERE user='$userid'");

}

?>