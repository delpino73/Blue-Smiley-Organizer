<?php

require 'class.base.php';
require 'class.html.php';
require 'class.misc.php';

$base_instance=new base();
$html_instance=new html();
$misc_instance=new misc();

$userid=$base_instance->get_userid();

if (isset($_GET['category_id'])) {

$category_id=(int)$_GET['category_id'];

$query='AND parent_id='.$category_id;
$cat_name=$misc_instance->get_link_category($category_id);

}

else {

$query='AND parent_id=0';
$cat_name='';
$category_id='';

}

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'LINK',
'SUBENTITY'=>'CATEGORY',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid' $query",
'ORDER_COL'=>'title',
'ORDER_TYPE'=>'ASC',
'HEADER'=>'Link Categories '.$cat_name,
'TEXT_CENTER'=>'<a href="add-link-category.php?category_id='.$category_id.'">[Add new Category]</a><p>',
'INNER_TABLE_WIDTH'=>'90%'
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No link categories added yet','<a href="add-link-category.php">[Add new Category]</a>'); }

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['LINK']['MAIN']} WHERE user='$userid' AND category=$ID");

	$number_links=$data2[1]->total;

	#

	$data3=$base_instance->get_data("SELECT COUNT(*) AS total_subcats FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user='$userid' AND parent_id=$ID");

	$number_subcats=$data3[1]->total_subcats;

	if ($number_subcats > 0) { $subcats='<a href="show-link-categories.php?category_id='.$ID.'">[Show Subcats: '.$number_subcats.']</a>'; }
	else { $subcats='No Subcat'; }

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td width=140><a href="show-links.php?category_id='.$ID.'"><strong>'.$title.'</strong></a></td>
<td align="left"><strong>Total:</strong> '.$number_links.'</td>
<td align="center">'.$subcats.'</td>
<td align="center"><a href="add-link.php?category_id='.$ID.'">[Add]</a></td>
<td align="center"><a href="show-links.php?category_id='.$ID.'">[Show]</a></td>
<td align="center"><a href="search-links.php?category_id='.$ID.'">[Search]</a></td>
<td align="center"><a href="edit-link-category.php?category_id='.$ID.'">[Edit]</a></td>
<td align="center">';

if ($number_subcats==0) { $all_text.='<a href="merge-link-category.php?category_id='.$ID.'">[Merge]</a>'; } else { $all_text.='-'; }

$all_text.='</td>

<td align="center">';

if ($number_subcats==0) { $all_text.='<a href="javascript:void(window.open(\'delete-link-category.php?category_id='.$ID.'\',\'\',\'width=450,height=300,top=100,left=100\'))">[Delete]</a>'; } else { $all_text.='-'; }

$all_text.='</td></tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>