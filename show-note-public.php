<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if (isset($_GET['order_col'])) {

$order_col=$_GET['order_col'];
setcookie('oc_note',$_GET['order_col'],time()+2592000);

} else { $order_col=isset($_COOKIE['oc_note']) ? $_COOKIE['oc_note'] : 'datetime'; }

#

if (isset($_GET['order_type'])) {

$order_type=$_GET['order_type'];
setcookie('ot_note',$_GET['order_type'],time()+2592000);

} else { $order_type=isset($_COOKIE['ot_note']) ? $_COOKIE['ot_note'] : 'DESC'; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'NOTE',
'ORDER_COL'=>$order_col,
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>40,
'WHERE'=>"WHERE public='2'",
'SORTBAR'=>1,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'HEADER'=>'Public Notes',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No public notes added yet');

}

else {

$all_text='';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$title=$data[$index]->title;
$text=$data[$index]->text;
$category_id=$data[$index]->category;
$datetime=$data[$index]->datetime;
$user_id=$data[$index]->user;

$datetime_converted=$base_instance->convert_date($datetime);

$text=convert_square_bracket($text);
$text=$base_instance->insert_links($text);
$text=nl2br($text);

if (!empty($title)) {

$title=convert_square_bracket($title);
$title2='<strong>'.$title.'</strong>';

} else { $title2=''; }

$data2=$base_instance->get_data("SELECT title FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE ID='$category_id'");

$category_text=$data2[1]->title;

#

$data3=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$user_id'");

$username=$data3[1]->username;

#

if ($index%2==1) { $bg='#fbfbfb'; } else { $bg='#ffffff'; }

$all_text.='

<table width="100%" cellspacing=1 cellpadding=0 bgcolor="#e9e9e9"><tr><td></td></tr></table>

<table width="100%" cellspacing=0 cellpadding=5><tr bgcolor="'.$bg.'">

<td valign="top">'.$title2.'<p>'.$text.'</td>

<td width="25%" valign="top"><font size="1"><u>'.$category_text.'</u><br>
ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'<p>

User: <a href="show-user.php?username='.$username.'"><font size="1">'.$username.'</font></a>

</font>

</td>
</tr></table>';

}

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>