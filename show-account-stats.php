<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

# total of knowledge entries

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['KNOWLEDGE']['MAIN']} WHERE user='$userid'");

$total_knowledge=$data[1]->total;

# total of todo entries

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE user='$userid'");

$total_todo=$data[1]->total;

# total of blog entries

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['BLOG']['MAIN']} WHERE user='$userid'");

$total_blog=$data[1]->total;

# total of files

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['FILE']['MAIN']} WHERE user='$userid'");

$total_files=$data[1]->total;

# total of notes entries

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['NOTE']['MAIN']} WHERE user='$userid'");

$total_notes=$data[1]->total;

# total of contact entries

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['CONTACT']['MAIN']} WHERE user='$userid'");

$total_contact=$data[1]->total;

# total of diary entries

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['DIARY']['MAIN']} WHERE user='$userid'");

$total_diary=$data[1]->total;

# total of links

$data=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['LINK']['MAIN']} WHERE user='$userid'");

$total_link=$data[1]->total;

# total of clicks

$data=$base_instance->get_data("SELECT SUM(visits) AS total FROM {$base_instance->entity['LINK']['MAIN']} WHERE user='$userid'");

$total_clicks=$data[1]->total;

# logins & sign up date

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$userid");

$logins=$data[1]->logins;
$sign_up_date=$data[1]->datetime;

#

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$base_instance->lastlogin,$ll);
$lastlogin="$ll[3].$ll[2].$ll[1] ($ll[4]:$ll[5]:$ll[6])";

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$sign_up_date,$ll);
$sign_up_date2="$ll[3].$ll[2].$ll[1] ($ll[4]:$ll[5]:$ll[6])";

$seconds_signup=strtotime($sign_up_date);
$seconds_now=time();
$days_member=round(($seconds_now-$seconds_signup)/86400);

$diary_per_day=@round($total_diary/$days_member,2);
$knowledge_per_day=@round($total_knowledge/$days_member,2);
$logins_per_day=@round($logins/$days_member,2);
$link_visits_per_day=@round($total_clicks/$days_member,2);
$links_per_day=@round($total_link/$days_member,2);

$text='<table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel">

<tr><td bgcolor="#dedede"><b>User since:</b></td><td>'.$sign_up_date2.' (Days: '.$days_member.')</td></tr>

<tr><td bgcolor="#dedede"><b>Last Login:</b></td><td>'.$lastlogin.'</td></tr>

<tr><td bgcolor="#dedede"><b>Logins:</b></td><td>'.$logins.' ('.$logins_per_day.' per day)</td></tr>

<tr><td bgcolor="#dedede"><b>Diary Entries:</b></td><td>'.$total_diary.' ('.$diary_per_day.' per day)&nbsp;&nbsp;&nbsp;&nbsp;<a href="show-added-diary-monthly.php">[By Month]</a> &nbsp;&nbsp;
<a href="show-added-diary-yearly.php">[By Year]</td></tr>

<tr><td bgcolor="#dedede"><b>Knowledge Entries:</b></td><td>'.$total_knowledge.' ('.$knowledge_per_day.' per day)&nbsp;&nbsp;&nbsp;&nbsp;<a href="show-added-knowledge-monthly.php">[By Month]</a> &nbsp;&nbsp;
<a href="show-added-knowledge-yearly.php">[By Year]</a></td></tr>

<tr><td bgcolor="#dedede"><b>Links:</b></td><td>'.$total_link.' ('.$links_per_day.' per day)&nbsp;&nbsp;&nbsp;&nbsp;<a href="show-added-links-monthly.php">[By Month]</a> &nbsp;&nbsp;
<a href="show-added-links-yearly.php">[By Year]</td></tr>

<tr><td bgcolor="#dedede"><b>To-Do Entries:</b></td><td>'.$total_todo.'</td></tr>

<tr><td bgcolor="#dedede"><b>Contact Entries:</b></td><td>'.$total_contact.'</td></tr>

<tr><td bgcolor="#dedede"><b>Notes Entries:</b></td><td>'.$total_notes.'</td></tr>

<tr><td bgcolor="#dedede"><b>Files:</b></td><td>'.$total_files.'</td></tr>

<tr><td bgcolor="#dedede"><b>Blog Entries:</b></td><td>'.$total_blog.'</td></tr>

<tr><td bgcolor="#dedede"><b>Total Link Visits:</b></td><td>'.$total_clicks.' ('.$link_visits_per_day.' per day)</td></tr></table><br>';

$html_instance->add_parameter(
array(
'HEADER'=>'Account Statistics',
'TEXT_CENTER'=>"$text"
));

$html_instance->process();

?>