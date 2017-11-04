<?php

/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

class html {

var $number_form=0;

function process() {

	global $base_instance;

	if (isset($this->para['HEAD'])) { $head=$this->para['HEAD']; } else { $head=''; }

	if (isset($this->para['TITLE'])) { $title_tag='<title>'.$this->para['TITLE'].' - Blue Smiley Organizer</title>'; } else { $title_tag='<title>Blue Smiley Organizer</title>'; }

	if (isset($this->para['BODY'])) { $body=' '.$this->para['BODY']; } else { $body=''; }

	header('Cache-Control: no-cache, must-revalidate');
	header('Pragma: no-cache');

	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><head><meta http-equiv="content-type" content="text/html;charset=utf-8"><meta http-equiv="cache-control" content="no-cache">',_CSS,$head,$title_tag,'</head>
<body ',_BACKGROUND,$body,'>';

	if (isset($this->error_message)) {

	echo '<div align="center"><table><tr><td align="left"><font color="#ff0000"><ul>',$this->error_message,'</ul></font></td></tr></table></div>';

	}

	else {

	if (isset($this->para['HEADER'])) { echo '<br><div align="center" class="header">',$this->para['HEADER'],'</div><p>'; }
	if (isset($this->para['TEXT_CENTER'])) { echo '<center>',$this->para['TEXT_CENTER'],'</center>'; }
	if (isset($this->para['TEXT'])) { echo $this->para['TEXT']; }

	}

	if (isset($this->para['ACTION'])) {

	if ($this->para['ACTION']=='show_form') { $this->show_form(); }
	else if ($this->para['ACTION']=='show_content') { $this->show_content_items(); }
	else if ($this->para['ACTION']=='show_user') { $this->show_user(); }

	}

	if (isset($this->para['BACK'])) { echo '<p><div align="center"><a href="javascript:history.go(-1)" onMouseOver="window.status=\'\'; return true">[Back]</a></div>'; }

	echo '<br><div align="center">',_BANNER_AD_MAIN,'</div><p><div align="center"><font size="1" face="Verdana">Powered by <a href="http://www.bookmark-manager.com/" target="_blank"><u><font size="1" face="Verdana">Blue Smiley Organizer v'._VERSION.'</font></u></a></font></div></body>'; exit;

}

function add_parameter($data) {

	$get_vars=$GLOBALS['_GET'];
	$post_vars=$GLOBALS['_POST'];

	$this->para=$data;

	if ($get_vars) {

	while(list($name,$value)=each($get_vars)) {

	$name=strtoupper($name);
	$this->para[$name]=$value;

	}

	}

	if ($post_vars) {

	while(list($name,$value)=each($post_vars)) {

	$name=strtoupper($name);
	$this->para[$name]=$value;

	}

	}

}

function add_form_field($data) {

	$this->number_form++;
	$this->element_form[$this->number_form]=$data;

}

function show_form() {

	global $base_instance;

	$form_action=isset($this->para['FORM_ACTION']) ? $this->para['FORM_ACTION'] : '';
	$form_attrib=isset($this->para['FORM_ATTRIB']) ? ' '.$this->para['FORM_ATTRIB'] : '';

	echo '<form action="',$form_action,'" method="post" name="form1"',$form_attrib,'>';

	$datetime=date('Y-m-d H:i:s');

	echo '<input type="Hidden" name="datetime" value="',$datetime,'">';

	if (isset($this->para['INNER_TABLE_WIDTH'])) { $inner_table_width=$this->para['INNER_TABLE_WIDTH']; }
	else { $inner_table_width='50%'; }

	echo '<center><table ',_INNER_TABLE_PROPERTY,' width="',$inner_table_width,'" class="pastel2">';

	if (isset($this->para['TD_WIDTH'])) { $td_width=$this->para['TD_WIDTH']; }
	else { $td_width='20%'; }

	echo '<tr><td width="',$td_width,'"></td><td></td></tr>';

	#

	while (list($number,$subarray)=each($this->element_form)) {

	if (empty($subarray['ERROR'])) { $error=''; } else { $error=' bgcolor="#fbcf7d"'; }

	if ($subarray['TYPE']=='hidden') { echo '<input type="Hidden" name="',$subarray['NAME'],'" value="'.$subarray['VALUE'].'">'; }

	# labels

	if ($subarray['TYPE']=='label') {

	if (isset($subarray['SECTIONS'])) { $number_sections=$subarray['SECTIONS']; } else { $number_sections=0; }

	if ($number_sections==2) { echo '<tr',$error,'><td align="right"><b>',$subarray['TEXT1'],'</b></td><td>',$subarray['TEXT2'],'</td></tr>'; }

	else { echo '<tr',$error,'><td colspan=2>',$subarray['TEXT'],'</td></tr>'; }

	}

	# text

	if ($subarray['TYPE']=='text') {

	if (isset($subarray['ATTRIB'])) { $text_attrib=' '.$subarray['ATTRIB']; } else { $text_attrib=''; }

	echo '<tr',$error,'><td align="right"><b>',$subarray['TEXT'],':</b></td><td align="left">&nbsp;<input type="text" name="',$subarray['NAME'],'" size="',$subarray['SIZE'],'" value="',$subarray['VALUE'],'"',$text_attrib,'></td></tr>'; }

	# password

	if ($subarray['TYPE']=='password') { echo '<tr',$error,'><td align="right"><b>',$subarray['TEXT'],':</b></td><td align=left>&nbsp;<input type="password" name="',$subarray['NAME'],'" size="',$subarray['SIZE'],'" value="',$subarray['VALUE'],'"></td></tr>'; }

	# file

	if ($subarray['TYPE']=='file') { echo '<tr',$error,'><td align="right"><b>',$subarray['TEXT'],':</b></td><td align=left>&nbsp;<input type="file" name="',$subarray['NAME'],'" size="',$subarray['SIZE'],'""></td></tr>'; }

	# textarea

	if ($subarray['TYPE']=='textarea') {

	if (isset($subarray['SECTIONS'])) { $number_sections=$subarray['SECTIONS']; } else { $number_sections=0; }

	if ($number_sections==2) { echo '<tr',$error,'><td align="right" valign="top"><b>',$subarray['TEXT'],':</b></td><td><textarea rows=',$subarray['ROWS'],' cols=',$subarray['COLS'],' name=',$subarray['NAME'],'>',$subarray['VALUE'],'</textarea></td></tr>'; }

	else {

	echo '<tr',$error,'><td colspan=2 align="center">';

	if (isset($subarray['TEXT'])) { echo '<b>',$subarray['TEXT'],':</b><br>'; }

	echo '<textarea rows=',$subarray['ROWS'],' cols=',$subarray['COLS'],' name=',$subarray['NAME'],'>',$subarray['VALUE'],'</textarea></td></tr>';

	}

	}

	# radio

	if ($subarray['TYPE']=='radio') {

	echo '<tr',$error,'><td align="right"><b>',$subarray['TEXT'],':</b></td><td>';

	$this->build_radio_field($subarray['FIELD_ARRAY'], $subarray['NAME'], $subarray['VALUE']);

	echo '</td></tr>';

	}

	# checkbox

	if ($subarray['TYPE']=='checkbox') {

	if (empty($subarray['SECTIONS'])) {

	echo '<tr',$error,'><td align="center" colspan=2><b>',$subarray['TEXT'],':</b><br>';

	$this->build_checkbox_field($subarray['FIELD_ARRAY'], $subarray['NAME'], $subarray['VALUE']);

	echo '</td></tr>';

	}

	else if ($subarray['SECTIONS']==2) {

	echo '<tr',$error,'><td align="right"><b>',$subarray['TEXT'],':</b></td><td>';

	$this->build_checkbox_field($subarray['FIELD_ARRAY'], $subarray['NAME'], $subarray['VALUE']);

	echo '</td></tr>';

	}

	}

	# select

	if ($subarray['TYPE']=='select') {

	if (isset($subarray['ATTRIB'])) { $text_attrib=' '.$subarray['ATTRIB']; } else { $text_attrib=''; }

	$array_unsorted=$subarray['OPTION'];

	echo '<tr',$error,'><td align="right"><b>',$subarray['TEXT'],':</b></td><td align="left">&nbsp;';

	if (empty($subarray['DO_NO_SORT_ARRAY'])) { asort($base_instance->$array_unsorted); }

	reset($base_instance->$array_unsorted);

	echo '<select name="',$subarray['NAME'],'"',$text_attrib,'><option>';

	while (list($key,$value)=each($base_instance->$array_unsorted)) {

	if ($key==$subarray['VALUE']) { echo '<option value=',$key,' selected>',$value; }
	else { echo '<option value=',$key,'>',$value; }

	}

	echo '</select></td></tr>';

	}

	}

	if (isset($this->para['BUTTON_TEXT'])) { $button_text=$this->para['BUTTON_TEXT']; } else { $button_text='Save'; }

	echo '<tr',$error,'><td colspan=2 align="center"><input type="SUBMIT" value="',$button_text,'" name="save"></td></tr></table></center></form>';

}

function build_checkbox_field($check_array, $name, $values) {

	global $base_instance;

	$i=0;

	echo '<table cellpadding="5">';

	while (list($key,$value)=each($base_instance->$check_array)) {

	if (@in_array($key, $values)) { $checked=' checked'; } else { $checked=''; }

	$i++; $mod=$i % 4;

	if ($mod==1) { echo '<tr>'; }

	echo '<td><input type="Checkbox" name="',$name,'[]" value="',$key,'" id="',$i,'"',$checked,'><label for="',$i,'">',$value,'</label></td>';

	if ($mod==0) { echo '</tr>'; }

	}

	echo '</table>';

}

function build_radio_field($check_array, $name, $values) {

	global $base_instance;

	$i=0;

	echo '<table cellpadding="5">';

	while (list($key,$value)=each($base_instance->$check_array)) {

	if ($key==$values) { $checked=' checked'; } else { $checked=''; }

	$i++; $mod=$i % 4;

	if ($mod==1) { echo '<tr>'; }

	echo '<td><input type="Radio" name="',$name,'" value="',$key,'" id="',$i,'"',$checked,'><label for="',$i,'">',$value,'</label></td>';

	if ($mod==0) { echo '</tr>'; }

	}

	echo '</table>';

}

function show_user() {

	global $base_instance;

	$query='';

	if (!empty($this->para['USERNAME'])) {

	$username=sql_safe($this->para['USERNAME']);
	if (!$this->para['SINGLE']) { $query.=" (username LIKE '%$username%') AND"; $param="username=$username"; }
	else { $query.=" (username='$username') AND"; }

	}

	if (!empty($this->para['EMAIL'])) {

	$email=sql_safe($this->para['EMAIL']);
	$query.=' (email="'.$email.'") AND';

	}

	if (!empty($this->para['USERID'])) {

	$userid=sql_safe($this->para['USERID']);
	$query.=" (ID='$userid') AND";

	}

	$year_today=date('Y'); $month_today=date('m'); $day_today=date('j');

	$query=substr($query,0,-3);

	if (empty($query) && empty($order_col) && empty($order_type)) { echo '<center>No search criteria!</center>'; }

	else {

	echo '<div align="center">';

	if ($query) { $this->para['WHERE']='WHERE '.$query; }

	$this->para['TABLE']=$base_instance->entity['USER']['MAIN'];

	$data=$this->get_items();

	$no_items=sizeof($data);

	if ($no_items==0) { echo 'User not found'; }

	for ($index=1; $index <= $no_items; $index++) {
	$this->show_user_entry($data[$index]);
	}

	if (!$this->para['SINGLE']) { $this->build_scrollbar(); }

	echo '</div>';

	}

}

function show_user_entry($data) {

	global $base_instance;

	$ID=$data->ID;
	$username=$data->username;
	$email=$data->email;
	$firstname=$data->firstname;
	$lastname=$data->lastname;
	$logins=$data->logins;
	$datetime=$data->datetime;
	$lastlogin=$data->lastlogin;
	$country=$data->country;
	$about_me=$data->about_me;

	$country_name=$base_instance->country_array[$country];

	preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$datetime,$ed);
	$datetime="$ed[3].$ed[2].$ed[1]";

	preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$lastlogin,$ll);
	$lastlogin="$ll[3].$ll[2].$ll[1] ($ll[4]:$ll[5]:$ll[6])";

	# public links

	$data_links=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['LINK']['MAIN'].' WHERE user='.$ID.' AND public=2');

	$number_of_links=$data_links[1]->total;

	# blog

	$data_blogs=$base_instance->get_data('SELECT COUNT(*) AS total FROM '.$base_instance->entity['BLOG']['MAIN'].' WHERE user='.$ID);

	$number_of_blogs=$data_blogs[1]->total;

	# online status

	$data_online=$base_instance->get_data('SELECT ID FROM '.$base_instance->entity['SESSION']['MAIN'].' WHERE user='.$ID);

	if ($data_online) {	$is_online=1; } else { $is_online=0; }

	$instant='<img src="pics/instant-online.gif" border=0>';
	$instant2='<img src="pics/instant-offline.gif" border=0>';

	echo '<table border=0 cellpadding=4 width=700 cellspacing=1 bgcolor="#f5f5f5"><tr><td width=20"><b>Username:&nbsp;&nbsp;&nbsp;</td><td width=550>',$username,' &nbsp;';

	if ($is_online==1) { echo '&nbsp;&nbsp;&nbsp;&nbsp;',$instant; } else { echo '&nbsp;&nbsp;&nbsp;&nbsp;',$instant2; }

	if (isset($is_online)) { $status=' &nbsp;&nbsp; <a href="live-support.php?userid='.$ID.'" target="_blank"><u>Live Chat</u></a>'; }

	echo ' &nbsp;&nbsp; <u>Country:</u> ',$country_name,'</td></tr><tr><td><b>Login:</b></td><td colspan=2><u>Total</u>: ',$logins,' &nbsp;&nbsp;&nbsp;<u>Last Login</u>: ',$lastlogin,' &nbsp;&nbsp;&nbsp;<u>Account created</u>: ',$datetime,'</td></tr><tr><td><b>Misc:</b></td><td colspan=2><a href="show-links-of-user.php?user=',$ID,'"><u>Public links (',$number_of_links,')</u></a> &nbsp;&nbsp; <a href="show-blog-public.php?username=',$username,'&page=1" target="_blank"><u>Blog (',$number_of_blogs,')</u></a> &nbsp;&nbsp; <a href="add-instant-message.php?receiver=',$ID,'"><u>Instant Message</u></a>',$status,'</td></tr>';

	if ($about_me) { echo '<tr><td><b>About me:</b></td><td>',$about_me,'</td></tr>'; }

	if ($base_instance->user==_ADMIN_USERID) {

	$IP=$data->IP;

	echo '<tr><td><b>IP:</td><td width=400 colspan=2>',$IP,'</td></tr><tr><td><b>Name:</td><td width=400 colspan=2>',$firstname,' ',$lastname,' (',$email,')</td></tr><tr><td><b>Actions:</b></td><td> <a href="edit-user.php?userid=',$ID,'">[Edit User]</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="delete-account-admin.php?userid=',$ID,'"><font color="#FF0000">[Delete User]</font></a></td></tr>';

	}

	if ($base_instance->user==$ID) {

	echo '<tr><td colspan="2" align="center"><a href="edit-about-me.php">[Edit your Profile]</a></td></tr>';

	}

	echo '</table>';

}

function show_content_items() {

	$data=$this->content;

	if (isset($this->para['SORTBAR'])) { $this->build_sortbar(); }

	if (isset($this->para['INNER_TABLE_WIDTH'])) { $inner_table_width=$this->para['INNER_TABLE_WIDTH']; }
	else { $inner_table_width='65%'; }

	echo '<div align="center">';

	for ($index=1; $index <= sizeof($data); $index++) {

	$one_array=$data[$index];
	$main=$one_array['MAIN'];

	echo '<table width="',$inner_table_width,'" ',_INNER_TABLE_PROPERTY,'>';

	if (isset($one_array['TOP'])) { echo '<tr><td align=center bgcolor="#dedede">',$one_array['TOP'],'</td></tr>'; }

	echo '<tr><td><p>',$main,'</td></tr></table><p></div>';

	}

	if (isset($this->para['NO_SCROLLBAR'])==FALSE) { $this->build_scrollbar(); }

}

function get_number_of_items() {

	global $base_instance;

	$entity=$this->para['ENTITY'];
	if (isset($this->para['SUBENTITY'])) { $subentity=$this->para['SUBENTITY']; }
	if (empty($subentity)) { $subentity='MAIN'; }
	$table=$base_instance->entity[$entity][$subentity];

	if (isset($this->para['WHERE'])) { $where=$this->para['WHERE']; } else { $where=''; }
	$data=$base_instance->get_data("SELECT COUNT(*) AS number_of_records FROM $table $where");

	$number_records=$data[1]->number_of_records;

	return $number_records;

}

function get_items() {

	global $base_instance;

	if (empty($this->para['MAXHITS'])) { $maxhits=20; } else { $maxhits=$this->para['MAXHITS']; }

	if (isset($this->para['PAGE'])) {

	if (!preg_match('/^[0-9]+$/',$this->para['PAGE'])) { header('HTTP/1.1 404 Not Found'); echo 'Page Number Error'; exit; }

	$this->para['OFFSET']=$maxhits*($this->para['PAGE']-1);

	}

	else { $this->para['OFFSET']=0; }

	$entity=$this->para['ENTITY'];
	if (isset($this->para['SUBENTITY'])) { $subentity=$this->para['SUBENTITY']; }
	if (empty($subentity)) { $subentity='MAIN'; }
	$table=$base_instance->entity[$entity][$subentity];

	$offset=$this->para['OFFSET'];

	if (!empty($this->para['WHERE'])) { $where=$this->para['WHERE']; } else { $where=''; }

	if (!empty($this->para['ORDER_COL'])) {

		if (!preg_match("/^[A-Za-z_,]+$/",$this->para['ORDER_COL'])) { echo 'Order Col Error'; exit; }
		$order_col=$this->para['ORDER_COL'];

	} else { $order_col='datetime'; }

	if (!empty($this->para['ORDER_TYPE'])) {

	$order_type=$this->para['ORDER_TYPE'];

	if ($order_type!='ASC' && $order_type!='DESC') { header('HTTP/1.1 404 Not Found'); echo 'Order Type Error'; exit; }

	} else { $order_type='DESC'; }

	if ($order_col && $order_type) { $orderby="ORDER BY $order_col $order_type"; }
	else { echo "ORDER BY $order_col $order_type"; }

	$data=$base_instance->get_data("SELECT * FROM $table $where $orderby LIMIT $offset, $maxhits");

	return $data;

}

function build_scrollbar() {

	if (isset($this->para['PAGE'])) { $page=$this->para['PAGE']; } else { $page=1; }
	if (empty($this->para['URL_PARAMETER'])) { $url_parameter=''; } else { $url_parameter='&amp;'.$this->para['URL_PARAMETER']; }

	if (isset($this->para['ORDER_COL'])) {

	$order_col=$this->para['ORDER_COL'];
	$url_parameter.='&amp;order_col='.$order_col;

	}

	if (isset($this->para['ORDER_TYPE'])) {

	$order_type=$this->para['ORDER_TYPE'];
	$url_parameter.='&amp;order_type='.$order_type;

	}

	if (isset($this->para['SEARCH_ENGINE_FRIENDLY'])) {

	$search_engine_friendly=$this->para['SEARCH_ENGINE_FRIENDLY'];

	} else { $search_engine_friendly=''; }

	# number of page links

	if (empty($this->para['MAXHITS'])) { $maxhits=20; } else { $maxhits=$this->para['MAXHITS']; }

	$number_items=$this->get_number_of_items();

	$page_links=ceil($number_items/$maxhits);
	if ($page_links < 2) { $page_links=0; return; }

	$scrollbar='<div class="pages" align="center">';

	if ($page!=1) {

	if ($search_engine_friendly) {

	$page2=$page-1;

	eval("\$search_engine_friendly2=\"$search_engine_friendly\";");

	$scrollbar.='<a href="'.$search_engine_friendly2.'.html">&laquo; Previous</a>';

	}

	else { $new_page=$page-1; $scrollbar.='<a href="'.$_SERVER['PHP_SELF'].'?page='.$new_page.$url_parameter.'">&laquo; Previous</a>'; }

	}

	for ($index=1; $index <= $page_links; $index++) {

	if ($index==$page) { $scrollbar.='<span class="current">'.$index.'</span>'; }
	else {

	$off=abs($index-$page);

	if ($search_engine_friendly and ($off < 5 or $index==$page_links or $index==($page_links-1) or $index==1 or $index==2)) {

	$page2=$index;
	eval("\$search_engine_friendly2=\"$search_engine_friendly\";");
	$scrollbar.='<a href="'.$search_engine_friendly2.'.html">'.$index.'</a>';

	$dots=0;

	}

	else if ($off < 5 or $index==$page_links or $index==($page_links-1) or $index==1 or $index==2) { $scrollbar.='<a href="'.$_SERVER['PHP_SELF'].'?page='.$index.$url_parameter.'">'.$index.'</a>'; $dots=0; }

	else if ($dots==0) { $scrollbar.='<span class="dots">...</span>'; $dots=1; }

	}

	}

	if ($page < $page_links) {

	if ($search_engine_friendly) {

	$page2=$page+1;
	eval("\$search_engine_friendly=\"$search_engine_friendly\";");
	$scrollbar.='<a href="'.$search_engine_friendly.'.html">Next &raquo;</a>';

	}

	else { $new_page=$page+1; $scrollbar.='<a href="'.$_SERVER['PHP_SELF'].'?page='.$new_page.$url_parameter.'">Next &raquo;</a>'; }

	}

	$scrollbar.='</div><br>';

	echo $scrollbar;

}

function build_sortbar() {

	if (isset($this->para['ORDER_COL'])) { $order_col=$this->para['ORDER_COL']; }
	else { $order_col='datetime'; }

	if (isset($this->para['ORDER_TYPE'])) { $order_type=$this->para['ORDER_TYPE']; }
	else { $order_type='DESC'; }

	if (empty($this->para['URL_PARAMETER'])) { $url_parameter=''; } else { $url_parameter='&amp;'.$this->para['URL_PARAMETER']; }

	$sort_up='<img src="pics/sortup.gif" border=0 alt="Sort up">';
	$sort_down='<img src="pics/sortdown.gif" border=0 alt="Sort down">';

	$sort_up2='<img src="pics/sortup2.gif" border=0 alt="Sort up">';
	$sort_down2='<img src="pics/sortdown2.gif" border=0 alt="Sort down">';

	echo '<div align=center><table border=1 cellspacing=0 cellpadding=2 class="pastel" bgcolor="#ffffff"><tr>';

	$number_fields=$this->para['SORTBAR'];

	for ($index=1; $index <= $number_fields; $index++) {

	$sb_array_field='SORTBAR_FIELD'.$index;
	$sb_array_name='SORTBAR_NAME'.$index;

	$sb_name=$this->para[$sb_array_name];
	$sb_field=$this->para[$sb_array_field];

	echo '<td width="88" align="center">',$sb_name,'<br>';

	if ($order_col==$sb_field && $order_type=='DESC') { echo '<a href="',$_SERVER['PHP_SELF'],'?order_col=',$sb_field,'&amp;order_type=DESC',$url_parameter,'" rel="nofollow">',$sort_down2,'</a>&nbsp;'; }
	else { echo '<a href="',$_SERVER['PHP_SELF'],'?order_col=',$sb_field,'&amp;order_type=DESC',$url_parameter,'" rel="nofollow">',$sort_down,'</a>&nbsp;'; }

	echo '&nbsp;';

	if ($order_col==$sb_field && $order_type=='ASC') { echo '<a href="',$_SERVER['PHP_SELF'],'?order_col=',$sb_field,'&amp;order_type=ASC',$url_parameter,'" rel="nofollow">',$sort_up2,'</a>'; }
	else { echo '<a href="',$_SERVER['PHP_SELF'],'?order_col=',$sb_field,'&amp;order_type=ASC',$url_parameter,'" rel="nofollow">',$sort_up,'</a>'; }

	echo '</td>';

	}

	echo '</tr></table></div><p>';

}

function check_for_duplicates($entity, $subentity, $datetime, $userid) {

	global $base_instance;

	if (!$subentity) { $subentity='MAIN'; }
	$table=$base_instance->entity[$entity][$subentity];

	$data=$base_instance->get_data("SELECT * FROM $table WHERE datetime='$datetime' AND user='$userid'");

	if ($data) { $base_instance->show_message('Already saved','',1); }

}

function check_for_duplicates_by_title($entity, $subentity, $title, $userid) {

	global $base_instance;

	if (!$subentity) { $subentity='MAIN'; }
	$table=$base_instance->entity[$entity][$subentity];

	$data=$base_instance->get_data("SELECT * FROM $table WHERE title='$title' AND user='$userid'");

	if ($data) { $base_instance->show_message('Already saved','',1); }

}

}

?>