<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

if ($userid!=_ADMIN_USERID && $base_instance->allow_file_upload==2) { $base_instance->show_message(_NO_FILE_UPLOAD_MSG,''); }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'FILE',
'SUBENTITY'=>'CATEGORY',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid'",
'ORDER_COL'=>'title',
'ORDER_TYPE'=>'ASC',
'HEADER'=>'Files Categories &nbsp;&nbsp; <a href="add-file-category.php">[Add Category]</a>',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No file categories added yet','<a href="add-file-category.php">[Add new Category]</a>'); }

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['FILE']['MAIN']} WHERE user='$userid' AND category=$ID");

	$number_upload=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td width=140><a href="show-files.php?category_id='.$ID.'"><strong>'.$title.'</strong></a></td>
<td align="left"><strong>Total:</strong> '.$number_upload.'</td>
<td align="center"><a href="add-file.php?category_id='.$ID.'">[Upload]</a></td>
<td align="center"><a href="show-files.php?category_id='.$ID.'">[Show]</a></td>
<td align="center"><a href="search-file.php?category_id='.$ID.'">[Search]</a></td>
<td align="center"><a href="edit-file-category.php?category_id='.$ID.'">[Edit]</a></td>
<td align="center"><a href="merge-file-category.php?category_id='.$ID.'">[Merge]</a></td>
<td align="center"><a href="javascript:void(window.open(\'delete-file-category.php?category_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>