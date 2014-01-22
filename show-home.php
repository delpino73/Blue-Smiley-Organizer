<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$html_instance->add_parameter(
array('ACTION'=>'show_content',
'ENTITY'=>'HOME',
'MAXHITS'=>40,
'WHERE'=>"WHERE user='$userid'",
'ORDER_COL'=>'ID',
'ORDER_TYPE'=>'ASC',
'HEADER'=>'Homepage Overview',
'TEXT_CENTER'=>'<a href="add-home.php">[Add new Homepage]</a><p>',
'INNER_TABLE_WIDTH'=>'80%'
));

$data=$html_instance->get_items();

if (!$data) { $base_instance->show_message('No homepage added yet','<a href="add-home.php">[Add new Homepage]</a>'); }

else {

$all_text='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel">';

for ($index=1; $index <= sizeof($data); $index++) {

	$ID=$data[$index]->ID;
	$title=$data[$index]->title;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'>
<td width=140><a href="home.php?home_id='.$ID.'"><strong>'.$title.'</strong></a></td><td align="center"><a href="edit-home.php?home_id='.$ID.'">[Edit]</a></td><td align="center"><a href="home.php?home_id='.$ID.'">[Show]</a></td><td align="center"><a href="delete-home.php?home_id='.$ID.'">[Delete]</a></td>
</tr>';

}

$all_text.='</table>';

}

$content_array[1]=array('MAIN'=>$all_text);

$html_instance->content=$content_array;

$html_instance->process();

?>