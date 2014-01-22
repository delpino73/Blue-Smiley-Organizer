<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'KNOWLEDGE',
'SUBENTITY'=>'CATEGORY',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid'",
'ORDER_COL'=>'title',
'ORDER_TYPE'=>'ASC',
'HEADER'=>'Knowledge Categories &nbsp;&nbsp; <a href="add-knowledge-category.php">[Add Category]</a>',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No knowledge categories added yet','<a href="add-knowledge-category.php">[Add new Category]</a>'); }

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	$title=convert_square_bracket($title);

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['KNOWLEDGE']['MAIN']} WHERE user='$userid' AND category=$ID");

	$number_knowledge=$data2[1]->total;

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td width=140><a href="show-knowledge.php?category_id='.$ID.'"><strong>'.$title.'</strong></a></td>
<td align="left"><strong>Total:</strong> '.$number_knowledge.'</td>
<td align="center"><a href="add-knowledge.php?category_id='.$ID.'">[Add]</a></td>
<td align="center"><a href="show-knowledge.php?category_id='.$ID.'">[Show]</a></td>
<td align="center"><a href="show-knowledge-print.php?category_id='.$ID.'" target="_blank">[Print]</a></td>
<td align="center"><a href="search-knowledge.php?category_id='.$ID.'">[Search]</a></td>
<td align="center"><a href="show-knowledge-flashcards.php?category_id='.$ID.'">[Flashcards]</a></td>
<td align="center"><a href="edit-knowledge-category.php?category_id='.$ID.'">[Edit]</a></td>
<td align="center"><a href="merge-knowledge-category.php?category_id='.$ID.'">[Merge]</a></td>
<td align="center"><a href="javascript:void(window.open(\'delete-knowledge-category.php?category_id='.$ID.'\',\'\',\'width=450,height=200,top=100,left=100\'))">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>