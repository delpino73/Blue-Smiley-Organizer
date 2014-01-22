<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (!empty($_GET['days_reminder_id'])) {

$days_reminder_id=(int)$_GET['days_reminder_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DAYS']} WHERE ID=$days_reminder_id AND user=$userid");

$title=$data[1]->title;
$text=$data[1]->text;

$text=convert_square_bracket($text);
$text=nl2br($text);

$all_text="<strong>$title</strong><p>$text";

}

else if (!empty($_GET['weekday_reminder_id'])) {

$weekday_reminder_id=(int)$_GET['weekday_reminder_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['WEEKDAY']} WHERE ID=$weekday_reminder_id AND user=$userid");

$title=$data[1]->title;
$text=$data[1]->text;

$text=convert_square_bracket($text);
$text=nl2br($text);

$all_text="<strong>$title</strong><p>$text";

}

else if (!empty($_GET['hours_reminder_id'])) {

$hours_reminder_id=(int)$_GET['hours_reminder_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['HOURS']} WHERE ID=$hours_reminder_id AND user=$userid");

$title=$data[1]->title;
$text=$data[1]->text;

$text=convert_square_bracket($text);
$text=nl2br($text);

$all_text="<strong>$title</strong><p>$text";

}

else if (!empty($_GET['date_reminder_id'])) {

$date_reminder_id=(int)$_GET['date_reminder_id'];

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DATE']} WHERE ID=$date_reminder_id AND user=$userid");

$title=$data[1]->title;
$text=$data[1]->text;

$text=convert_square_bracket($text);
$text=nl2br($text);

$all_text="<strong>$title</strong><p>$text";

}

$html_instance->add_parameter(
array(
'TEXT'=>$all_text
));

$html_instance->process();

?>