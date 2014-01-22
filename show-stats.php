<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$user_instance->check_for_admin();

# total user

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['USER']['MAIN'].' WHERE ID<>'._ADMIN_USERID);

$total_user=$data[1]->total;

# total of knowledge entries

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_knowledge=$data[1]->total;

# total of diary entries

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['DIARY']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_diary=$data[1]->total;

# total of contact entries

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['CONTACT']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_contact=$data[1]->total;

# total of to-do entries

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['TO_DO']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_to_do=$data[1]->total;

# total of notes entries

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['NOTE']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_notes=$data[1]->total;

# total of files

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['FILE']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_files=$data[1]->total;

# total of blog entries

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['BLOG']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_blog=$data[1]->total;

# total of links

$data=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['LINK']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_link=$data[1]->total;

# total of clicks

$data=$base_instance->get_data('SELECT SUM(visits) AS total FROM '.$base_instance->entity['LINK']['MAIN'].' WHERE user<>'._ADMIN_USERID);

$total_clicks=$data[1]->total;

$text='These statistics exclude the admin account.<p>

<table border=1 cellspacing=0 cellpadding=2 bgcolor="#ffffff" class="pastel">

<tr><td bgcolor="#dedede"><b>User Accounts:</b></td><td>'.$total_user.'</td></tr>

<tr><td bgcolor="#dedede"><b>Knowledge Items:</b></td><td>'.$total_knowledge.'</td></tr>

<tr><td bgcolor="#dedede"><b>Diary Items:</b></td><td>'.$total_diary.'</td></tr>

<tr><td bgcolor="#dedede"><b>Contact Items:</b></td><td>'.$total_contact.'</td></tr>

<tr><td bgcolor="#dedede"><b>To-Do Items:</b></td><td>'.$total_to_do.'</td></tr>

<tr><td bgcolor="#dedede"><b>Notes Items:</b></td><td>'.$total_notes.'</td></tr>

<tr><td bgcolor="#dedede"><b>Files:</b></td><td>'.$total_files.'</td></tr>

<tr><td bgcolor="#dedede"><b>Blog Entries:</b></td><td>'.$total_blog.'</td></tr>

<tr><td bgcolor="#dedede"><b>Link Items:</b></td><td>'.$total_link.'</td></tr>

<tr><td bgcolor="#dedede"><b>Total Link Clicks:</b></td><td>'.$total_clicks.'</td></tr>

</table><br>';

$html_instance->add_parameter(
array(
'HEADER'=>'Statistics of all User',
'TEXT_CENTER'=>"$text"
));

$html_instance->process();

?>