<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$text_search=isset($_REQUEST['text_search']) ? sql_safe($_REQUEST['text_search']) : '';
$whole_words=isset($_POST['whole_words']) ? 1 : '';

if ($text_search && $whole_words) { $query=" AND (text REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])' OR title REGEXP '([[:space:]]|[[:<:]])$text_search([[:>:]]|[[:space:]])') "; $param='text_search='.$text_search.'&amp;'; }

else if ($text_search) { $query=" AND (text LIKE '%$text_search%' OR title LIKE '%$text_search%') "; $param='text_search='.$text_search.'&amp;'; }

else { $query=''; $param=''; }

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'DATABASE',
'SUBENTITY'=>'CATEGORY',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'ORDER_COL'=>'title',
'ORDER_TYPE'=>'ASC',
'HEADER'=>'Database Categories &nbsp;&nbsp; <a href="add-database-category.php">[Add Category]</a>',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

if (!$data) {

if ($text_search) {

$html_instance->add_parameter(
array(
'HEADER'=>'Nothing found (Database)',
'TEXT'=>'<form action="show-database-categories.php" method="post"><center><table cellpadding=10 cellspacing=0 border=0 bgcolor="#ffffff" class="pastel2"><tr><td align="right"><b>Text:</b> &nbsp;<input type="text" name="text_search" size="30" value="'.$text_search.'"></td></tr><tr><td align="center"><input type="SUBMIT" value="Search Database" name="save"></td></tr></table></center></form>'
));

$html_instance->process();

}

else { $base_instance->show_message('No database categories added yet','<a href="add-database-category.php">[Add Database Category]</a>'); }

}

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['DATABASE']['MAIN']} WHERE user='$userid' AND category_id=$ID");
	$number_database=$data2[1]->total;

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td width=140><a href="show-database-data.php?category_id='.$ID.'"><strong>'.$title.'</strong></a></td>
<td align="left"><strong>Total:</strong> '.$number_database.'</td>
<td align="center"><a href="add-database-data.php?category_id='.$ID.'">[Add Data]</a></td>
<td align="center"><a href="show-database-data.php?category_id='.$ID.'">[Show]</a></td>
<td align="center"><a href="show-database-summary.php?category_id='.$ID.'">[Summary]</a></td>
<td align="center"><a href="show-database-by-month.php?category_id='.$ID.'">[By Month]</a></td>
<td align="center"><a href="show-database-fields.php?category_id='.$ID.'">[Edit Fields]</a></td>
<td align="center"><a href="edit-database-category.php?category_id='.$ID.'">[Edit]</a></td>
<td align="center"><a href="javascript:void(window.open(\'delete-database-category.php?category_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>