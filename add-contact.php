<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$company=isset($_POST['company']) ? $_POST['company'] : '';
$firstname=isset($_POST['firstname']) ? $_POST['firstname'] : '';
$lastname=isset($_POST['lastname']) ? $_POST['lastname'] : '';
$email=isset($_POST['email']) ? $_POST['email'] : '';
$telephone=isset($_POST['telephone']) ? $_POST['telephone'] : '';
$fax=isset($_POST['fax']) ? $_POST['fax'] : '';
$mobile=isset($_POST['mobile']) ? $_POST['mobile'] : '';
$address=isset($_POST['address']) ? $_POST['address'] : '';
$notes=isset($_POST['notes']) ? $_POST['notes'] : '';
$url=isset($_POST['url']) ? $_POST['url'] : '';
$new_category=isset($_POST['new_category']) ? $_POST['new_category'] : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';
$public=isset($_POST['public']) ? (int)$_POST['public'] : 1;

if (isset($_POST['save'])) {

	$error='';

	if (!$firstname && !$lastname && !$company) { $error.='<li> First name / Last name / Company cannot be left blank'; }

	if (!$category_id && !$new_category) { $error.='<li> Category cannot be left blank'; }

	if ($new_category) {

	$duplicate=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['CONTACT']['CATEGORY'].' WHERE title="'.sql_safe($new_category).'" AND user='.$userid);

	if ($duplicate) { $error.='<li> Category with this name already exists'; }

	$new_category=str_replace('"','&quot;',$new_category);

	if (strlen($new_category)>50) { $error.='<li> Category title is too long (Max. 50 Characters)'; }

	}

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['CONTACT']['CATEGORY'].' (title,user) VALUES ("'.sql_safe($new_category).'",'.$userid.')');

	$category_id=mysqli_insert_id($base_instance->db_link);

	}

	$datetime=$_POST['datetime'];

	$html_instance->check_for_duplicates('CONTACT','MAIN',$datetime,$userid);

	$firstname=str_replace('"','&quot;',$firstname);
	$lastname=str_replace('"','&quot;',$lastname);
	$address=str_replace('"','&quot;',$address);
	$company=str_replace('"','&quot;',$company);

	$base_instance->query('INSERT INTO '.$base_instance->entity['CONTACT']['MAIN'].' (datetime,user,firstname,lastname,email,telephone,fax,mobile,address,notes,company,url,category,public) VALUES ("'.sql_safe($datetime).'",'.$userid.',"'.sql_safe($firstname).'","'.sql_safe($lastname).'","'.sql_safe($email).'","'.sql_safe($telephone).'","'.sql_safe($fax).'","'.sql_safe($mobile).'","'.sql_safe($address).'","'.sql_safe($notes).'","'.sql_safe($company).'","'.sql_safe($url).'",'.$category_id.','.$public.')');

	$contact_id=mysqli_insert_id($base_instance->db_link);

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	$base_instance->show_message('Contact saved','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelContact(item){if(confirm("Delete Contact?")){http.open(\'get\',\'delete-contact.php?item=\'+item); http.send(null);}}</script>

<a href="add-contact.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-contact.php?contact_id='.$contact_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelContact(\''.$contact_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?contact_id='.$contact_id.'">[Send]</a><p><a href="show-contact-categories.php">[Show all Categories]</a> &nbsp; <a href="show-contact.php">[Show all Contacts]</a><p><b>Internal Link:</b> [c'.$contact_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-contact.php?category_id='.$category_id.'">[Show]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$company=stripslashes($company);
	$address=stripslashes($address);
	$notes=stripslashes($notes);

	}

}

# default category

if (!$category_id) {

$data=$base_instance->get_data("SELECT default_contact_category FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$category_id=$data[1]->default_contact_category;

}

# build category section

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $cat_title='New Category:'; $select_category='&nbsp;<input type="text" name="new_category" value="'.$new_category.'">'; }

else {

$cat_title='Category:';

$select_category='&nbsp;<select name="category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_category.="<option selected value=$ID>$category_title"; }
else { $select_category.="<option value=$ID>$category_title"; }

}

$select_category.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="'.$new_category.'" size="12">';

}

#

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Add Contact',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.firstname.focus()"',
'BUTTON_TEXT'=>'Save Contact'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>"$cat_title",'TEXT2'=>$select_category,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'firstname','VALUE'=>$firstname,'SIZE'=>45,'TEXT'=>'First Name'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'lastname','VALUE'=>$lastname,'SIZE'=>45,'TEXT'=>'Last Name'));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'company','VALUE'=>$company,'SIZE'=>45,'TEXT'=>'Company'));

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