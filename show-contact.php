<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_search=isset($_REQUEST['text_search']) ? sql_safe($_REQUEST['text_search']) : '';
$whole_words=isset($_POST['whole_words']) ? 1 : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';

if ($text_search && $whole_words) { $query=" AND (firstname REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR lastname REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR company REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR notes REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR email REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR address REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR telephone REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR mobile REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR fax REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') "; $param='text_search='.$text_search.'&amp;'; }

else if ($text_search) { $query=" AND (firstname LIKE '%$text_search%' OR lastname LIKE '%$text_search%' OR company LIKE '%$text_search%' OR notes LIKE '%$text_search%' OR email LIKE '%$text_search%' OR email LIKE '%$text_search%' OR address LIKE '%$text_search%' OR telephone LIKE '%$text_search%' OR mobile LIKE '%$text_search%' OR fax LIKE '%$text_search%') "; $param='text_search='.$text_search.'&amp;'; }

else { $query=''; $param=''; }

#

if ($category_id) {

$query.=" AND (category=$category_id) "; $param.='category_id='.$category_id.'&amp;';

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE ID=$category_id");
$title=$data[1]->title;

$category_name='(Category '.$title.')';

} else { $category_name=''; }

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

#

if (isset($_GET['view_mode'])) {

$view_mode=$_GET['view_mode'];
setcookie('vm_contact',$view_mode,time()+2592000);

} else { $view_mode=isset($_COOKIE['vm_contact']) ? $_COOKIE['vm_contact'] : ''; }

#

if ($view_mode==1) { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=0">[Complete View]</a>'; }
else { $link='<a href="'.$_SERVER['PHP_SELF'].'?'.$param.'view_mode=1">[List View]</a>'; }

#

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'CONTACT',
'ORDER_COL'=>$order_col,
'ORDER_TYPE'=>$order_type,
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'SORTBAR'=>4,
'SORTBAR_FIELD1'=>'datetime','SORTBAR_NAME1'=>'Date Added',
'SORTBAR_FIELD2'=>'firstname','SORTBAR_NAME2'=>'First Name',
'SORTBAR_FIELD3'=>'lastname','SORTBAR_NAME3'=>'Last Name',
'SORTBAR_FIELD4'=>'email','SORTBAR_NAME4'=>'Email',
'HEADER'=>'Contact '.$category_name.'&nbsp;&nbsp; '.$link.' &nbsp;&nbsp; <a href="show-contact-print.php?'.$param.'" target="_blank">[Print]</a>',
'INNER_TABLE_WIDTH'=>'80%',
'URL_PARAMETER'=>$param,
'HEAD'=>'<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelContact(item){if(confirm("Delete Contact?")){http.open(\'get\',\'delete-contact.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>'
));

$data=$html_instance->get_items();

if (!$data) {

if ($text_search) { $base_instance->show_message('Search Result','Nothing found for the entered search terms.<p><a href="javascript:history.go(-1)">[Go Back]</a>'); }

else { $base_instance->show_message('No contacts added yet','<a href="add-contact.php">[Add Contact]</a>'); }

}

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

$ID=$data[$index]->ID;
$datetime=$data[$index]->datetime;
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

$company=convert_square_bracket($company);
$firstname=convert_square_bracket($firstname);
$lastname=convert_square_bracket($lastname);

$all_text.='<tr><td valign="top"><div id="item'.$ID.'"><strong>'.$firstname.' '.$lastname;

if ($company && ($firstname || $lastname)) { $all_text.='<br>'; }

$all_text.=$company.'</strong></td><td valign="top">';

if ($email && empty($view_mode)) {

$email=convert_square_bracket($email);
$all_text.='<u>Email:</u> <a href="mailto:'.$email.'">'.$email.'</a><br>';

}

if ($telephone) {

$telephone=convert_square_bracket($telephone);
$all_text.='<u>Phone:</u> '.$telephone.'<br>';

}

if ($fax && empty($view_mode)) {

$fax=convert_square_bracket($fax);
$all_text.='<u>Fax:</u> '.$fax.'<br>';

}

if ($mobile && empty($view_mode)) {

$mobile=convert_square_bracket($mobile);
$all_text.='<u>Mobile:</u> '.$mobile.'<br>';

}

if ($address && empty($view_mode)) {

$address=convert_square_bracket($address);
$all_text.='<u>Address:</u> '.$address.'<br>';

}

if ($notes && empty($view_mode)) {

$notes=convert_square_bracket($notes);
$all_text.='<u>Notes:</u> '.nl2br($notes).'<br>';

}

if ($url && empty($view_mode)) {

$url=convert_square_bracket($url);
$url_encoded=base64_encode($url);

$all_text.='<u>Website:</u> <a href="load-url.php?url_encoded='.$url_encoded.'" target="_blank">'.$url.'</a><br>';

}

$all_text.='</div></td><td width="25%" valign="top" bgcolor="#fbfbfb">

<a href="javascript:DelContact(\''.$ID.'\')">[Del]</a> &nbsp; <a href="edit-contact.php?contact_id='.$ID.'">[Edit]</a> &nbsp; <a href="send-content.php?contact_id='.$ID.'">[Send]</a> &nbsp; <a href="show-contact-print.php?contact_id='.$ID.'" target="_blank">[Print]</a><p>';

if (empty($view_mode)) {

$data2=$base_instance->get_data('SELECT title FROM '.$base_instance->entity['CONTACT']['CATEGORY'].' WHERE ID='.$category_id);

$category_text=$data2[1]->title;

$datetime_converted=$base_instance->convert_date($datetime);

$all_text.='<font size="1"><u>'.$category_text.'</u><br>
ID:'.$ID.' &nbsp;&nbsp;&nbsp; Added: '.$datetime_converted.'</font>';

}

$all_text.='</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>