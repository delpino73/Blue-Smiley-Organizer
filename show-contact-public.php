<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

#

if (isset($_GET['order_col'])) {

$order_col=$_GET['order_col'];
setcookie('oc_contact',$_GET['order_col'],time()+2592000);

} else { $order_col=isset($_COOKIE['oc_contact']) ? $_COOKIE['oc_contact'] : 'datetime'; }

#

if (isset($_GET['order_type'])) {

$order_type=$_GET['order_type'];
setcookie('ot_contact',$_GET['order_type'],time()+2592000);

} else { $order_type=isset($_COOKIE['ot_contact']) ? $_COOKIE['ot_contact'] : 'DESC'; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'CONTACT',
'ORDER_COL'=>$order_col,
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>40,
'WHERE'=>"WHERE public='2'",
'SORTBAR'=>1,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date added',
'HEADER'=>'Public Contacts',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

if (!$data) {

$base_instance->show_message('No public contacts added yet');

}

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$firstname=$data[$index]->firstname;
$lastname=$data[$index]->lastname;
$telephone=$data[$index]->telephone;
$fax=$data[$index]->fax;
$mobile=$data[$index]->mobile;
$email=$data[$index]->email;
$address=$data[$index]->address;
$notes=$data[$index]->notes;
$company=$data[$index]->company;
$url=$data[$index]->url;
$category_id=$data[$index]->category;
$datetime=$data[$index]->datetime;
$user_id=$data[$index]->user;

$datetime_converted=$base_instance->convert_date($datetime);

$data2=$base_instance->get_data("SELECT title FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE ID='$category_id'");

$category_text=$data2[1]->title;

$company=convert_square_bracket($company);
$firstname=convert_square_bracket($firstname);
$lastname=convert_square_bracket($lastname);

#

$data3=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$user_id'");

$username=$data3[1]->username;

#

$all_text.='<tr><td valign="top"><div id="item'.$ID.'"><strong>'.$firstname.' '.$lastname;

if ($company && ($firstname || $lastname)) { $all_text.='<br>'; }

$all_text.=$company.'</strong></td><td valign="top">';

if ($email) {

$email=convert_square_bracket($email);
$all_text.='<u>Email:</u> <a href="mailto:'.$email.'">'.$email.'</a><br>';

}

if ($telephone) {

$telephone=convert_square_bracket($telephone);
$all_text.='<u>Phone:</u> '.$telephone.'<br>';

}

if ($fax) {

$fax=convert_square_bracket($fax);
$all_text.='<u>Fax:</u> '.$fax.'<br>';

}

if ($mobile) {

$mobile=convert_square_bracket($mobile);
$all_text.='<u>Mobile:</u> '.$mobile.'<br>';

}

if ($address) {

$address=convert_square_bracket($address);
$all_text.='<u>Address:</u> '.$address.'<br>';

}

if ($notes) {

$notes=convert_square_bracket($notes);
$all_text.='<u>Notes:</u> '.$notes.'<br>';

}

if ($url) {

$url=convert_square_bracket($url);
$url_encoded=base64_encode($url);

$all_text.='<u>Website:</u> <a href="load-url.php?url_encoded='.$url_encoded.'" target="_blank">'.$url.'</a><br>';

}

$all_text.='</td>

<td width="25%" valign="top"><font size="1"><u>'.$category_text.'</u><br>
ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'<p>

User: <a href="show-user.php?username='.$username.'"><font size="1">'.$username.'</font></a>

</font>

</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>