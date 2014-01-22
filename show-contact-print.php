<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$where='';

if (isset($_GET['text_search'])) {

$text_search=sql_safe($_GET['text_search']);
$where=" AND (firstname LIKE '%$text_search%' OR lastname LIKE '%$text_search%' OR company LIKE '%$text_search%' OR notes LIKE '%$text_search%' OR email LIKE '%$text_search%' OR email LIKE '%$text_search%' OR address LIKE '%$text_search%') "; 

}

if (isset($_GET['category_id'])) {

$category_id=(int)$_GET['category_id'];
$where.=' AND category='.$category_id;

}

else if (isset($_GET['contact_id'])) {

$contact_id=(int)$_GET['contact_id'];
$where=' AND ID='.$contact_id;

}

#

$order_col=isset($_COOKIE['oc_contact']) ? $_COOKIE['oc_contact'] : 'datetime';
$order_type=isset($_COOKIE['ot_contact']) ? $_COOKIE['ot_contact'] : 'DESC';

#

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['MAIN']} WHERE user='$userid'$where ORDER BY $order_col $order_type");

echo '<head><meta http-equiv="content-type" content="text/html;charset=utf-8">
<style type="text/css">
td {font-family:Arial; font-size:10pt}
table.pastel,table.pastel td {border:1px solid #c5c5c5; border-collapse:collapse}
</style>
</head>

<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

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

$company=convert_square_bracket($company);
$firstname=convert_square_bracket($firstname);
$lastname=convert_square_bracket($lastname);

echo '<tr><td valign="top"><strong>'.$firstname.' '.$lastname;

if ($company && ($firstname || $lastname)) { echo '<br>'; }

echo $company,'</strong></td><td valign="top">';

if ($email) {

$email=convert_square_bracket($email);
echo '<u>Email:</u> ',$email,'<br>';

}

if ($telephone) {

$telephone=convert_square_bracket($telephone);
echo '<u>Phone:</u> ',$telephone,'<br>';

}

if ($fax) {

$fax=convert_square_bracket($fax);
echo '<u>Fax:</u> ',$fax,'<br>';

}

if ($mobile) {

$mobile=convert_square_bracket($mobile);
echo '<u>Mobile:</u> ',$mobile,'<br>';

}

if ($address) {

$address=convert_square_bracket($address);
echo '<u>Address:</u> ',$address,'<br>';

}

if ($notes) {

$notes=convert_square_bracket($notes);
echo '<u>Notes:</u> ',$notes,'<br>';

}

if ($url) {

$url=convert_square_bracket($url);

echo '<u>Website:</u> '.$url.'<br>';

}

}

echo '</table>';

?>