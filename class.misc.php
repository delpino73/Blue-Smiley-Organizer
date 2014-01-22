<?php

/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

class misc {

function get_link_category($cat_id) {

	global $base_instance;

	$data=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['LINK']['CATEGORY'].' WHERE ID='.$cat_id);

	if ($data) {

	$parent_id=$data[1]->parent_id;
	$title=$data[1]->title;

	$cat_string=' | <a href="show-link-categories.php?category_id='.$cat_id.'"><img src="pics/folder.gif" border="0" title="Show Link Categories"></a>&nbsp;'.$title;

	if (!empty($parent_id)) {

	$cat_string_parent=$this->get_parent_category($parent_id);
	$cat_string=$cat_string_parent.$cat_string;

	}

	$cat_string_short=substr($cat_string,3);

	return $cat_string_short;

	}

}

function get_parent_category($cat_id) {

	global $base_instance;

	$data=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['LINK']['CATEGORY'].' WHERE ID='.$cat_id);

	if ($data) {

	$parent_id=$data[1]->parent_id;
	$title=$data[1]->title;

	$cat_string=' | <a href="show-link-categories.php?category_id='.$cat_id.'" title="Show Link Categories"><img src="pics/folder.gif" border="0" alt="Show Category"></a>&nbsp;<a href="show-links.php?category_id='.$cat_id.'">'.$title.'</a>';

	if ($parent_id>0) {

	$cat_string_parent=$this->get_parent_category($parent_id);
	$cat_string=$cat_string_parent.$cat_string;

	}

	return $cat_string;

	}

}

function build_category_select_box($target_id,$userid,$h,$category_id) {

	global $base_instance; $select_box='';

	$h++; # count hierarchy

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user='$userid' AND parent_id=$target_id ORDER BY title");

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_title=$data[$index]->title;
	$ID=$data[$index]->ID;
	$parent_id=$data[$index]->parent_id;

	$l=str_repeat('&nbsp;&nbsp;', $h-1);
	if ($parent_id>0) { $l.='|'; }

	if ($ID==$category_id) { $select_box.="<option selected value=$ID>$l $category_title "; }
	else { $select_box.="<option value=$ID>$l $category_title"; }

	$select_box.=$this->build_category_select_box($ID,$userid,$h,$category_id);

	}

	return $select_box;

}

function calculate_new_popularity($value) {

	if ($value < 0) { $new_value=25; }

	else if ($value < 50) {

	$value_change=25;
	$new_value=$value+$value_change;

	}

	else if ($value < 1000) {

	$value_change=round(1000/$value);
	$new_value=$value+$value_change;

	}

	else {

	$value_change=1;
	$new_value=$value+$value_change;

	}

	return $new_value;

}

function load_url($url) {

	if (preg_match('/http:\/\//i',$url)) { $url2=$url; }
	else if (preg_match('/ftp:\/\//i',$url)) { $url2=$url; }
	else if (preg_match('/https:\/\//i',$url)) { $url2=$url; }
	else { $url2='http://'.$url; }

	echo '<meta http-equiv="refresh" content="0; URL='.$url2.'">';

}

}

?>