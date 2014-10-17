<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$blog_id=isset($_REQUEST['blog_id']) ? (int)$_REQUEST['blog_id'] : exit;

if (isset($_POST['save'])) {

	$error='';

	$title=$_POST['title'];
	$text=$_POST['text'];
	$new_category=$_POST['new_category'];
	$category_id=(int)$_POST['category_id'];

	if (!$category_id) { $error.='<li> Category cannot be left blank'; }

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	if (!$text) { $error.='<li> Text cannot be left blank'; }
	else {

	$text=trim($text);
	if (strlen($text)>65535) { $error.='<li> Text is too long (Max. 65535 Characters)'; }

	}

	if (!$error) {

	if ($new_category) {

	$base_instance->query('INSERT INTO '.$base_instance->entity['BLOG']['CATEGORY'].' (title,user) VALUES ("'.sql_safe($new_category).'",'.$userid.')');

	$category_id=mysqli_insert_id($base_instance->db_link);

	}

	$base_instance->query('UPDATE '.$base_instance->entity['BLOG']['MAIN'].' SET text="'.sql_safe($text).'",title="'.sql_safe($title).'",category='.$category_id.' WHERE user='.$userid.' AND ID='.$blog_id);

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/permalink-'.$blog_id; }
	else { $url=_HOMEPAGE.'/show-blog-public-permalink.php?blog_id='.$blog_id; }

	$base_instance->show_message('Blog Post updated','<a href="add-blog.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-blog.php?blog_id='.$blog_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:void(window.open(\'delete-blog.php?blog_id='.$blog_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?blog_id='.$blog_id.'">[Send]</a><p><a href="show-blog-categories.php">[Show all Categories]</a> &nbsp; <a href="show-blog.php">[Show all Blog Posts]</a> &nbsp; <a href="'.$url.'" target="_blank">[View Blog Post]</a><p><b>Internal Link:</b> [b'.$blog_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-blog.php?category_id='.$category_id.'">[Show]</a><p><a href="ping-server.php?blog_id='.$blog_id.'">[Ping Technorati]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

else {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['MAIN']} WHERE user='$userid' AND ID='$blog_id'");

	if (!$data) { $base_instance->show_message('Blog Post not found','',1); }

	$datetime=$data[1]->datetime;
	$title=$data[1]->title;
	$text=$data[1]->text;
	$category_id=$data[1]->category;

	$datetime_converted=$base_instance->convert_date($datetime);

}

$js='<script language="JavaScript" type="text/javascript">

function bigger(what,add) {
if (what.style.height==\'\') { what.style.height=\'300px\'; }
newHeight=parseInt(what.style.height)+add;
what.style.height=newHeight+"px";
}

function smaller(what,deduct) {
if ((parseInt(what.style.height)-deduct) > 50) {
newHeight=parseInt(what.style.height)-deduct;
what.style.height=newHeight+"px";
} else { newHeight=50; what.style.height="50px"; }
}

function make_link() {
var1=prompt("Link URL:","http://");	if (var1==null) return;
var2=prompt("Anchor Text:","");	if (var2==null) return;
document.form1.text.value+="[url="+var1+"]"+var2+"[/url]";
}

</script>';

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Blog Post',
'HEAD'=>$js,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BUTTON_TEXT'=>'Update Blog Post'
));

# build category select box

$select_box='&nbsp;<select name="category_id">';

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user='$userid' ORDER BY title");

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_box.="<option selected value=$ID>$category_title"; }
else { $select_box.="<option value=$ID>$category_title"; }

}

$select_box.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="">';

#

$html_instance->add_form_field(array('TYPE'=>'hidden','NAME'=>'blog_id','VALUE'=>$blog_id));

if (empty($error)) { $html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Added:','TEXT2'=>$datetime_converted,'SECTIONS'=>2)); }

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'Category:','TEXT2'=>$select_box,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>120,'ROWS'=>10));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<span class="fakelink" onclick="bigger(document.form1.text,100);">[+]</span>
<span class="fakelink" onclick="smaller(document.form1.text,100);">[-]</span> &nbsp;&nbsp;&nbsp; <a href="javascript:make_link()"><u>Create Link</u></a>'));

$html_instance->process();

?>