<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Export Bookmarks &nbsp;&nbsp; <a href="help-link.php#export">[Help]</a>',
'FORM_ACTION'=>'export-bookmarks.php',
'BUTTON_TEXT'=>'Export Bookmarks'
));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'Here you can download your saved bookmarks in Netscape / Mozilla Firefox / IE format.<p>To export your bookmarks click "Export Bookmarks". Depending on the number of bookmarks, this process might take a while. Please click only once.<p>'));

$html_instance->process();

?>