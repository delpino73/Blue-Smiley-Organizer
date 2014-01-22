<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Export Contacts',
'FORM_ACTION'=>'export-contacts.php',
'BUTTON_TEXT'=>'EXPORT'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'Here you can download your contacts in tab-delimited format. Please click only once.<p>'));

$html_instance->process();

?>