<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();
$now=time();

$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';

$textblock1=trainer_block($category_id);
$textblock2=trainer_block($category_id);
$textblock3=trainer_block($category_id);
$textblock4=trainer_block($category_id);

if (isset($_POST['count'])) { $count=$_POST['count']+1; } else { $count=1; }

$all_text='<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelKnow(item){if(confirm("Delete Knowledge?")){http.open(\'get\',\'delete-knowledge.php?item=\'+item); http.send(null);}}</script>

<table cellspacing=5><tr><td colspan=3 align=center>

<form action="'.$_SERVER['PHP_SELF'].'" method="post">
<input type="hidden" name="count" value="'.$count.'">
<input type="hidden" name="category_id" value="'.$category_id.'">
<input type="SUBMIT" value="Click for Next ('.$count.')" style="width:12em;height:4em;color:black;background:#ffffff">
</form>

</td></tr><tr><td valign="top" width=350>'.$textblock1.'<br>'.$textblock2.'<br></td><td width="40">&nbsp;</td><td valign="top" width=350>'.$textblock3.'<br>'.$textblock4.'<br></td></tr></table>';

$html_instance->add_parameter(
array('TEXT_CENTER'=>"$all_text"
));

$html_instance->process();

#

function trainer_block($category_id) {

	global $base_instance,$userid,$now;

	if ($category_id) { $selected_category=' AND category='.$category_id; } else { $selected_category=''; }

	$data=$base_instance->get_data("SELECT *,($now-last_shown)*(value+1) AS abc FROM organizer_knowledge WHERE user=$userid $selected_category ORDER BY abc DESC LIMIT 1");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Knowledge</b></td></tr><tr><td width="100%" style="padding:10">No knowledge saved</td></tr></table>';

	return $all_text;

	}

	$knowledge_id=$data[1]->ID;
	$knowledge_title=$data[1]->title;
	$knowledge_text=$data[1]->text;
	$knowledge_category_id=$data[1]->category;
	$knowledge_value=($data[1]->value)-1;
	$knowledge_shown=($data[1]->shown)+1;

	$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['MAIN']} SET shown=$knowledge_shown,value=$knowledge_value,last_shown=$now WHERE ID=$knowledge_id");

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE ID=$knowledge_category_id");
	$knowledge_category_text=$data[1]->title;

	$knowledge_text=convert_square_bracket($knowledge_text);

	$knowledge_text=nl2br($knowledge_text);

	if (!empty($knowledge_title)) {

	$knowledge_title=convert_square_bracket($knowledge_title);
	$knowledge_title='<strong>'.$knowledge_title.':</strong> ';

	}

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'"><font size="2">'.$knowledge_category_text.'</font> &nbsp; <font size="1">Shown: '.$knowledge_shown.' - Value: '.$knowledge_value.' &nbsp; <a href="increase-value.php?knowledge_id='.$knowledge_id.'" target="status">[+5]</a> &nbsp; <a href="edit-knowledge.php?knowledge_id='.$knowledge_id.'">[E]</a> &nbsp; <a href="javascript:DelKnow(\''.$knowledge_id.'\')">[D]</a></font></td></tr><tr><td width="100%" style="padding:10"><div id="item'.$knowledge_id.'">'.$knowledge_title.$knowledge_text.'</div></td></tr></table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelKnow(item){if(confirm("Delete Knowledge?")){http.open(\'get\',\'delete-knowledge.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

	return $all_text;

}

?>