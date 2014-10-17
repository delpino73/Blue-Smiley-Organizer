<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$title=isset($_POST['title']) ? $_POST['title'] : '';
$text=isset($_POST['text']) ? $_POST['text'] : '';
$category_id=isset($_REQUEST['category_id']) ? (int)$_REQUEST['category_id'] : '';
$new_category=isset($_POST['new_category']) ? $_POST['new_category'] : '';

if (isset($_POST['save'])) {

	$error='';

	if (!$category_id && !$new_category) { $error.='<li> Category cannot be left blank'; }

	if ($new_category) {

	$duplicate=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['BLOG']['CATEGORY'].' WHERE title="'.sql_safe($new_category).'" AND user='.$userid);

	if ($duplicate) { $error.='<li> Category with this name already exists'; }

	$new_category=str_replace('"','&quot;',$new_category);

	if (strlen($new_category)>50) { $error.='<li> Category title is too long (Max. 50 Characters)'; }

	}

	# check title

	if (!$title) { $error.='<li> Title cannot be left blank'; }
	else {

	$title=trim($title);
	if (strlen($title)>100) { $error.='<li> Title is too long (Max. 100 Characters)'; }
	$title=str_replace('"','&quot;',$title);

	}

	# check text

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

	$datetime=$_POST['datetime'];

	$html_instance->check_for_duplicates('BLOG','MAIN',$datetime,$userid);

	$base_instance->query('INSERT INTO '.$base_instance->entity['BLOG']['MAIN'].' (datetime,text,title,user,category) VALUES ("'.sql_safe($datetime).'","'.sql_safe($text).'","'.sql_safe($title).'",'.$userid.','.$category_id.')');

	$blog_id=mysqli_insert_id($base_instance->db_link);

	$title=stripslashes($title);

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user='$userid' AND ID='$category_id'");
	$cat_title=$data[1]->title;

	if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/permalink-'.$blog_id; }
	else { $url=_HOMEPAGE.'/show-blog-public-permalink.php?blog_id='.$blog_id; }

	$base_instance->show_message('Blog Post saved','<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelBlog(item){if(confirm("Delete Blog Post?")){http.open(\'get\',\'delete-blog.php?item=\'+item); http.send(null);}}</script>

<a href="add-blog.php?category_id='.$category_id.'">[Add more]</a> &nbsp;&nbsp; <a href="edit-blog.php?blog_id='.$blog_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelBlog(\''.$blog_id.'\')">[Delete]</a> &nbsp;&nbsp; <a href="send-content.php?blog_id='.$blog_id.'">[Send]</a><p><a href="show-blog-categories.php">[Show all Categories]</a> &nbsp; <a href="show-blog.php">[Show all Blog Posts]</a> &nbsp; <a href="'.$url.'" target="_blank">[View Blog Post]</a><p><b>Internal Link:</b> [b'.$blog_id.'] &nbsp;&nbsp; <b>Category:</b> '.$cat_title.' <a href="show-blog.php?category_id='.$category_id.'">[Show]</a><p><a href="ping-server.php?blog_id='.$blog_id.'">[Ping Technorati]</a>');

	}

	else {

	$html_instance->error_message=$error;
	$text=stripslashes($text);
	$title=stripslashes($title);

	}

}

# default category

if (!$category_id) {

$data=$base_instance->get_data("SELECT default_blog_category FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$category_id=$data[1]->default_blog_category;

}

# build category section

$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user='$userid' ORDER BY title");

if (!$data) { $cat_title='New Category:'; $select_category='&nbsp;<input type="text" name="new_category" value="'.$new_category.'">'; }

else {

$cat_title='Category:';

$select_category='&nbsp;<select name="category_id">';

for ($index=1; $index <= sizeof($data); $index++) {

$category_title=$data[$index]->title;
$ID=$data[$index]->ID;

if ($ID==$category_id) { $select_category.="<option selected value=$ID>$category_title"; }
else { $select_category.="<option value=$ID>$category_title"; }

}

$select_category.='</select> or <b>New Category:</b> <input type="text" name="new_category" value="'.$new_category.'" size="12">';

}

#

$data=$base_instance->get_data("SELECT username FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$userid'");

$username=$data[1]->username;

#

if (_SHORT_URLS==1) { $url_blog=_HOMEPAGE.'/blog-'.$username; }
else { $url_blog=_HOMEPAGE.'/show-blog-public.php?username='.$username; }

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
'HEADER'=>'Add Blog Post',
'HEAD'=>$js,
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'BODY'=>'onLoad="javascript:document.form1.title.focus()"',
'BUTTON_TEXT'=>'Save Blog Post'
));

$html_instance->add_form_field(array('TYPE'=>'text','NAME'=>'title','VALUE'=>$title,'SIZE'=>50,'TEXT'=>'Title'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>$cat_title,'TEXT2'=>$select_category,'SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'textarea','NAME'=>'text','VALUE'=>$text,'COLS'=>120,'ROWS'=>9));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT'=>'<span class="fakelink" onclick="bigger(document.form1.text,100);">[+]</span>
<span class="fakelink" onclick="smaller(document.form1.text,100);">[-]</span> &nbsp;&nbsp;&nbsp; <a href="javascript:make_link()"><u>Create Link</u></a>'));

$html_instance->process();

?>