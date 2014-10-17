<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$contact_id=isset($_REQUEST['contact_id']) ? (int)$_REQUEST['contact_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$company=$_POST['company'];
	$email=$_POST['email'];
	$telephone=$_POST['telephone'];
	$mobile=$_POST['mobile'];
	$address=$_POST['address'];
	$notes=$_POST['notes'];
	$url=$_POST['url'];
	$fax=$_POST['fax'];
	$category_id=(int)$_POST['category_id'];
	$new_category=$_POST['new_category'];
	$public=(int)$_POST['public'];

	if (!$category_id) { $error.='<li> Category cannot be left blank'; }

	if (!$firstname && !$lastname && !$company) { $error.='<li> First name / Last name / Company  cannot be left blank'; }

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['CONTACT']['CATEGORY'].' (title,user) VALUES ("'.sql_safe($new_category).'",'.$userid.')');

	$category_id=mysqli_insert_id($base_instance->db_link);

	}

	$firstname=str_replace('"','&quot;',$firstname);
	$lastname=str_replace('"','&quot;',$lastname);
	$address=str_replace('"','&quot;',$address);
	$company=str_replace('"','&quot;',$company);

	$base_instance->query('UPDATE '.$base_instance->entity['CONTACT']['MAIN'].' SET firstname="'.sql_safe($firstname).'",lastname="'.sql_safe($lastname).'",email="'.sql_safe($email).'",telephone="'.sql_safe($telephone).'",fax="'.sql_safe($fax).'",mobile="'.sql_safe($mobile).'",address="'.sql_safe($address).'",notes="'.sql_safe($notes).'",company="'.sql_safe($company).'",url="'.sql_safe($url).'",category='.$category_id.',public='.$public.' WHERE user='.$userid.' AND ID='.$contact_id);

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	$base_instance->show_message('Contact updated','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelContact(item){if(confirm("Delete Contact?")){http.open(\'get\',\'delete-contact.php?item=\'+item); http.send(null);}}</script>

<a href="add-contact.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-contact.php?contact_id='.$contact_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelContact(\''.$contact_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?contact_id='.$contact_id.'">[Send]</a><p><a href="show-contact-categories.php">[Show all Categories]</a> &nbsp; <a href="show-contact.php">[Show all Contacts]</a><p><b>Internal Link:</b> [c'.$contact_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-contact.php?category_id='.$category_id.'">[Show]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$company=stripslashes($company);
	$address=stripslashes($address);
	$notes=stripslashes($notes);

	}

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['MAIN']} WHERE user='$userid' AND ID=$contact_id");

	if (!$data) { $base_instance->show_message('Contact not found','',1); }

	$datetime=$data[1]->datetime;
	$firstname=$data[1]->firstname;
	$lastname=$data[1]->lastname;
	$telephone=$data[1]->telephone;
	$fax=$data[1]->fax;
	$mobile=$data[1]->mobile;
	$email=$data[1]->email;
	$address=$data[1]->address;
	$notes=$data[1]->notes;
	$company=$data[1]->company;
	$url=$data[1]->url;
	$category_id=$data[1]->category;
	$public=$data[1]->public;

	$datetime_converted=$base_instance->convert_date($datetime);

}

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Contact',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Save Contact'
));

# build category select box

$select_box='&nbsp;<select name="category_id">';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE user='$userid' ORDER BY title");

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_box.="<option selected value=$ID>$category_title"; }
else { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="">';

#

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'contact_id','VALUE'=>$contact_id));

if (empty($error)) { $html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Added:','TEXT2'=>"$datetime_converted",'SECTIONS'=>2)); }

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>$select_box,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'company','VALUE'=>$company,'SIZE'=>45,'TEXT'=>'Company'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'firstname','VALUE'=>$firstname,'SIZE'=>45,'TEXT'=>'First Name'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'lastname','VALUE'=>$lastname,'SIZE'=>45,'TEXT'=>'Last Name'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'email','VALUE'=>$email,'SIZE'=>45,'TEXT'=>'Email'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'telephone','VALUE'=>$telephone,'SIZE'=>45,'TEXT'=>'Telephone'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'fax','VALUE'=>$fax,'SIZE'=>45,'TEXT'=>'Fax'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'mobile','VALUE'=>$mobile,'SIZE'=>45,'TEXT'=>'Mobile'));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'address','VALUE'=>$address,'COLS'=>60,'ROWS'=>3,'TEXT'=>'Address','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'notes','VALUE'=>$notes,'COLS'=>60,'ROWS'=>6,'TEXT'=>'Notes','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'url','VALUE'=>$url,'SIZE'=>45,'TEXT'=>'Website'));

$html_instance->add_form_field(array('TYPE'=>'radio','NAME'=>'public','FIELD_ARRAY'=>'public_array','VALUE'=>$public,'TEXT'=>'Contact is'));

$html_instance->process();

?>