<?php

/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

require 'class.rss.php';

class home {

function home() {

	$this->user=$GLOBALS['base_instance']->get_userid();

	$data=$GLOBALS['base_instance']->get_data("SELECT * FROM {$GLOBALS['base_instance']->entity['USER']['MAIN']} WHERE ID=$this->user");

	$this->user_logins=$data[1]->logins;
	$this->user_lastlogin=$data[1]->lastlogin;

}

function get_element($element_id) {

	global $base_instance;

	if ($element_id==1) {

	$number_of_elements=count($base_instance->home_array);
	$element_id=mt_rand(2,$number_of_elements);

	}

	if ($element_id==2) { $textblock=$this->random_links(10); }
	else if ($element_id==3) { $textblock=$this->longest_in_bluebox(5); }
	else if ($element_id==4) { $textblock=$this->last_visited_links(10); }
	else if ($element_id==5) { $textblock=$this->flashcards(); }
	else if ($element_id==6) { $textblock=$this->random_diary(); }
	else if ($element_id==7) { $textblock=$this->random_links(5); }
	else if ($element_id==8) { $textblock=$this->last_visited_links(5); }
	else if ($element_id==9) { $textblock=$this->links_by_popularity(10); }
	else if ($element_id==10) { $textblock=$this->reminder(); }
	else if ($element_id==11) { $textblock=$this->show_logins(); }
	else if ($element_id==12) { $textblock=$this->random_to_do(); }
	else if ($element_id==13) { $textblock=$this->bluebox_by_popularity(10); }
	else if ($element_id==14) { $textblock=$this->random_public_link(5); }
	else if ($element_id==15) { $textblock=$this->last_added_links(5); }
	else if ($element_id==16) { $textblock=$this->bluebox_by_popularity(20); }
	else if ($element_id==17) { $textblock=$this->newest_in_bluebox(20); }
	else if ($element_id==18) { $textblock=$this->longest_in_bluebox(20); }
	else if ($element_id==19) { $textblock=$this->links_by_sequence(10); }
	else if ($element_id==20) { $textblock=$this->links_by_popularity(20); }
	else if ($element_id==21) { $textblock=$this->last_added_links(10); }
	else if ($element_id==22) { $textblock=$this->bluebox_by_speed_ranking(20); }
	else if ($element_id==23) { $textblock=$this->to_do_by_priority(10); }
	else if ($element_id==24) { $textblock=$this->random_bluebox_links(5); }
	else if ($element_id==25) { $textblock=$this->search(); }
	else if ($element_id==26) { $textblock=$this->rss_feed_word_of_the_day(); }
	else if ($element_id==27) { $textblock=$this->rss_feed_bbc_news(); }
	else if ($element_id==28) { $textblock=$this->ted_goff(); }
	else if ($element_id==29) { $textblock=$this->rss_feed_cnn_latest(); }
	else if ($element_id==30) { $textblock=$this->rss_feed_new_york_times(); }
	else if ($element_id==31) { $textblock=$this->sticky_note(1); }
	else if ($element_id==32) { $textblock=$this->sticky_note(2); }
	else if ($element_id==33) { $textblock=$this->sticky_note(3); }
	else if ($element_id==34) { $textblock=$this->longest_in_bluebox(10); }
	else if ($element_id==35) { $textblock=$this->newest_in_bluebox(10); }
	else if ($element_id==36) { $textblock=$this->bluebox_by_speed_ranking(10); }
	else if ($element_id==37) { $textblock=$this->random_links(20); }
	else if ($element_id==38) { $textblock=$this->random_bluebox_links(10); }
	else if ($element_id==39) { $textblock=$this->random_bluebox_links(20); }
	else if ($element_id==40) { $textblock=$this->random_rss_feed(); }
	else if ($element_id==41) { $textblock=$this->random_notes(); }
	else if ($element_id==42) { $textblock=$this->bluebox_by_sequence(10); }
	else if ($element_id==43) { $textblock=$this->bluebox_by_sequence(20); }
	else if ($element_id==44) { $textblock=$this->links_by_sequence(20); }
	else if ($element_id==45) { $textblock=$this->link_category(); }
	else if ($element_id==46) { $textblock=$this->knowledge_category(); }
	else if ($element_id==47) { $textblock=$this->to_do_category(); }
	else if ($element_id==48) { $textblock=$this->contact_category(); }
	else if ($element_id==49) { $textblock=$this->notes_category(); }
	else if ($element_id==50) { $textblock=$this->files_category(); }
	else if ($element_id==51) { $textblock=$this->database_category(); }
	else if ($element_id==52) { $textblock=$this->blog_category(); }
	else if ($element_id==53) { $textblock=$this->random_knowledge(); }
	else if ($element_id > 99)  { $textblock=$this->rss_feed($element_id); }
	else { $textblock=''; }

	return $textblock;

}

function show_logins() {

	preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$this->user_lastlogin,$ll);
	$lastlogin="$ll[3].$ll[2].$ll[1] ($ll[4]:$ll[5]:$ll[6])";

	$all_text='<table bgcolor="#ffffff" cellpadding="8" width="100%" cellspacing=0 class="pastel2"><tr><td align="center"><b>Last Login</b>: '.$lastlogin.' &nbsp;&nbsp; <b>Logins</b>: '.$this->user_logins.'</td></tr></table>';

	return $all_text;

}

function last_visited_links($links) {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user={$this->user} ORDER BY last_visit DESC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (Last visited)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (Last visited)</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td width="250" onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?order_col=last_visit\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function links_by_popularity($links) {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user={$this->user} ORDER BY popularity DESC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (by Popularity)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="6" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (by Popularity)</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?order_col=popularity\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function random_bluebox_links($links) {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ((DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime' AND frequency_mode=3) OR frequency_mode=1) AND user={$this->user} ORDER BY RAND() LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (Randomly)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (Randomly) &nbsp;&nbsp; <a href="visit-link.php?random_bluebox=1" target="_blank">[Lucky Dip]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';" width="250"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='</table>';

	return $all_text;

}

function random_links($links) {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user={$this->user} ORDER BY RAND() LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (Randomly)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (Randomly) &nbsp;&nbsp; <a href="visit-link.php?random=1" target="_blank">[Lucky Dip]</a></b></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';" width="250"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='</table>';

	return $all_text;

}

function random_public_link($links) {

	global $base_instance;

	$datetime=date('Y-m-d');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE public=2 AND user<>{$this->user} ORDER BY RAND() LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Public Link</b></td></tr><tr><td width="100%" style="padding:10">No public links saved</td></tr></table>';

	return $all_text;

	}

	$link_id=$data[1]->ID;
	$link_title=$data[1]->title;
	$link_url=$data[1]->url;
	$link_user=$data[1]->user;

	$username=$base_instance->get_username($link_user);

	if ($link_title) { $link_url_short=$link_title; }
	else { $link_url_short='http://'.substr($link_url,0,30); }

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Public Link</b> - from <a href="show-user.php?username='.$username.'">'.$username.'</a></td></tr>';

	$link_url2=base64_encode($link_url);

	if ($link_url) { $all_text.='<tr><td><a href="add-link.php?url=http://'.$link_url.'"><b>Add Link</b></a> <a href="load-url.php?url_encoded='.$link_url2.'" target="_blank" class="link">'.$link_url_short.'</a></b></td></tr>'; }

	$all_text.='</table>';

	return $all_text;

}

function random_diary() {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DIARY']['MAIN']} WHERE user={$this->user} ORDER BY last_shown ASC LIMIT 1");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Diary</b></td></tr><tr><td width="100%" style="padding:10">No diary saved</td></tr></table>';

	return $all_text;

	}

	$diary_id=$data[1]->ID;

	$date=date('Y-m-d');

	$base_instance->query("UPDATE {$base_instance->entity['DIARY']['MAIN']} SET last_shown='$date',shown=shown+1 WHERE ID='$diary_id'");

	$diary_title=$data[1]->title;
	$diary_text=$data[1]->text;
	$diary_date=$data[1]->date;
	$diary_shown=$data[1]->shown;

	$diary_shown++;

	$diary_date_converted=$base_instance->convert_date($diary_date.' 12:00:00');

	$diary_text=convert_square_bracket($diary_text);
	$diary_text=$base_instance->insert_links($diary_text);
	$diary_text=nl2br($diary_text);

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Diary</b> - '.$diary_date_converted.' - Shown: '.$diary_shown.' - <a href="add-diary.php?diary_id='.$diary_id.'">[Edit]</a></td></tr><tr><td style="padding:10"><b>'.$diary_title.'</b><br>'.$diary_text.'</td></tr></table>';

	return $all_text;

}

function random_knowledge() {

	global $base_instance;

	$now=time();

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['KNOWLEDGE']['MAIN']} WHERE user={$this->user} ORDER BY RAND() LIMIT 1");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Knowledge</b></td></tr><tr><td width="100%" style="padding:10">No knowledge saved</td></tr></table>';

	return $all_text;

	}

	$knowledge_id=$data[1]->ID;
	$knowledge_title=$data[1]->title;
	$knowledge_text=$data[1]->text;
	$knowledge_category_id=$data[1]->category;
	$knowledge_shown=($data[1]->shown)+1;

	$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['MAIN']} SET shown=$knowledge_shown,last_shown=$now WHERE ID=$knowledge_id");

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE ID=$knowledge_category_id");
	$knowledge_category_text=$data[1]->title;

	$knowledge_text=convert_square_bracket($knowledge_text);

	$knowledge_text=nl2br($knowledge_text);

	if (!empty($knowledge_title)) {

	$knowledge_title=convert_square_bracket($knowledge_title);
	$knowledge_title='<strong>'.$knowledge_title.':</strong> ';

	}

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'"><font size="2">'.$knowledge_category_text.'</font> &nbsp; <font size="1">Shown: '.$knowledge_shown.' &nbsp; <a href="increase-value.php?knowledge_id='.$knowledge_id.'" target="status">[+5]</a> &nbsp; <a href="edit-knowledge.php?knowledge_id='.$knowledge_id.'">[E]</a> &nbsp; <a href="javascript:DelKnow(\''.$knowledge_id.'\')">[D]</a></font></td></tr><tr><td width="100%" style="padding:10"><div id="item'.$knowledge_id.'">'.$knowledge_title.$knowledge_text.'</div></td></tr></table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelKnow(item){if(confirm("Delete Knowledge?")){http.open(\'get\',\'delete-knowledge.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

	return $all_text;

}

function flashcards() {

	global $base_instance;

	$now=time();

	$data=$base_instance->get_data("SELECT word_id,shown FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE user=$this->user AND category_id=0 AND value > -1 AND value < $now AND word_loop=1 ORDER BY last_shown LIMIT 1");

	if (!$data) {

	$data3=$base_instance->get_data("SELECT COUNT(*) AS cnt FROM {$base_instance->entity['KNOWLEDGE']['MAIN']} WHERE user=$this->user");

	if (empty($data3[1]->cnt)) { $msg='No knowledge added yet.'; }
	else { $msg='No words in the queue or loop or loop has not been created yet. Click <a href="show-knowledge-flashcards.php" target="_blank">here to initialize</a>'; }

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Knowledge (Loop)</b></td></tr><tr><td width="100%" style="padding:10">'.$msg.'</td></tr></table>';

	return $all_text;

	}

	$knowledge_id=$data[1]->word_id;
	$knowledge_shown=$data[1]->shown;

	#

	$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET shown=shown+1,last_shown=$now WHERE word_id='$knowledge_id' AND user='$this->user' AND category_id=0");

	#

	$data2=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE ID='.$knowledge_id);

	if (!empty($data2)) {

		$knowledge_title=$data2[1]->title;

		$encoded=urlencode($knowledge_title);

		$knowledge_text=$data2[1]->text;
		$knowledge_category_id=$data2[1]->category;

		$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE ID=$knowledge_category_id");
		$knowledge_category_text=$data[1]->title;

		$knowledge_text=convert_square_bracket($knowledge_text);

		$knowledge_text=nl2br($knowledge_text);

		$knowledge_title=convert_square_bracket($knowledge_title);

		if (!empty($knowledge_title)) { $knowledge_title='<strong>'.$knowledge_title.':</strong> '; }
		else { $knowledge_title=''; }

		$google_image='<a href="http://images.google.com/images?client=pub-1841153363764743&amp;channel=0220874538&amp;safe=active&amp;q='.$encoded.'&amp;ie=UTF-8&amp;oe=UTF-8&amp;um=1&amp;sa=N&amp;tab=wi" target="_blank">[Google Images]</a>';

		$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'"><font size="2">'.$knowledge_category_text.'</font> &nbsp; <font size="1">Shown: '.$knowledge_shown.' - <a href="edit-knowledge.php?knowledge_id='.$knowledge_id.'">[E]</a> &nbsp; <a href="javascript:DelKnow(\''.$knowledge_id.'\')">[D]</a> &nbsp; <a href="show-knowledge-flashcards.php">[Flashcards]</a> &nbsp; '.$google_image.'</font></td></tr><tr><td width="100%" style="padding:10"><div id="item'.$knowledge_id.'">'.$knowledge_title.$knowledge_text.'

<p><div align="center"><table border="0" cellpadding="5" cellspacing="0" bgcolor="#f0f8ff" class="pastel2">
<tr><td align="center"><strong>Remove from Loop for:</strong><p></td></tr>
<tr><td align="center">

<a href="remove-from-loop.php?value=1d&knowledge_id='.$knowledge_id.'" target="status">[1 Day]</a>
<a href="remove-from-loop.php?value=7d&knowledge_id='.$knowledge_id.'" target="status">[7 Days]</a>
<a href="remove-from-loop.php?value=30d&knowledge_id='.$knowledge_id.'" target="status">[30 Days]</a>
<a href="remove-from-loop.php?value=3mo&knowledge_id='.$knowledge_id.'" target="status">[3 Months]</a>
<a href="remove-from-loop.php?value=6mo&knowledge_id='.$knowledge_id.'" target="status">[6 Months]</a>
<a href="remove-from-loop.php?value=12mo&knowledge_id='.$knowledge_id.'" target="status">[1 Year]</a>
<a href="remove-from-loop.php?value=bl&knowledge_id='.$knowledge_id.'" target="status">[Block]</a>

</td></tr>
</table></div>

</div></td></tr></table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelKnow(item){if(confirm("Delete Knowledge?")){http.open(\'get\',\'delete-knowledge.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

	} else { $all_text=''; }

	return $all_text;

}

function calculate_date($reminder_id, $year, $month, $day, $warning, $title, $notes, $time) {

	if ($day==0) { $display_day='* '; } else { $display_day=$day; }
	if ($month==0) { $display_month='* '; } else { $display_month=$month; }
	if ($year==0) { $display_year='* '; } else { $display_year=$year; }

	$display_date=$time.' - '.$display_day.'.'.$display_month.'.'.$display_year;

	$target_date=$year.'-'.$month.'-'.$day;

	$now_date=date('Y-m-d'); $bold=0; $not_bold=0;

	for ($index=0; $index <= $warning; $index++) {

	$y=date('Y',mktime(0,0,0,date('m'),date('d')+$index,date('Y')));
	$m=date('m',mktime(0,0,0,date('m'),date('d')+$index,date('Y')));
	$d=date('d',mktime(0,0,0,date('m'),date('d')+$index,date('Y')));

	if (($y==$year or $year==0) && ($m==$month or $month==0) && ($d==$day or $day==0)) {

	if ($index==0) { $bold=1; } else { $not_bold=1; }

	}

	}

	if ($notes) { $notes_link='<a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$reminder_id.'\',\'\',\'width=600,height=620,top=100,left=100\'))">N</a>'; }
	else { $notes_link='&nbsp;'; }

	#

	if ($bold==1) { return '<tr onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';"><td align=left colspan="2"><strong>'.$display_date.' - '.$title.'</strong></td><td width=10>'.$notes_link.'</td><td width=10><a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$reminder_id.'\',\'\',\'width=600,height=620,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-reminder-date.php?reminder_id='.$reminder_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">D</a></td></tr>'; }

	else if ($not_bold==1) { return '<tr onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';"><td align=left colspan="2">'.$display_date.' - '.$title.'</td><td width=10>'.$notes_link.'</td><td width=10><a href="javascript:void(window.open(\'edit-reminder-date.php?reminder_id='.$reminder_id.'\',\'\',\'width=600,height=620,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-reminder-date.php?reminder_id='.$reminder_id.'\',\'\',\'width=450,height=200,top=100,left=100\'))">D</a></td></tr>'; }

}

function reminder() {

	global $base_instance;

	$day_0=date('j',mktime(0,0,0,date('m'),date('d'),date('Y')));
	$month_0=date('n',mktime(0,0,0,date('m'),date('d'),date('Y')));
	$year_0=date('Y',mktime(0,0,0,date('m'),date('d'),date('Y')));

	$day_1=date('j',mktime(0,0,0,date('m'),date('d')+1,date('Y')));
	$month_1=date('n',mktime(0,0,0,date('m'),date('d')+1,date('Y')));
	$year_1=date('Y',mktime(0,0,0,date('m'),date('d')+1,date('Y')));

	$day_2=date('j',mktime(0,0,0,date('m'),date('d')+2,date('Y')));
	$month_2=date('n',mktime(0,0,0,date('m'),date('d')+2,date('Y')));
	$year_2=date('Y',mktime(0,0,0,date('m'),date('d')+2,date('Y')));

	$day_3=date('j',mktime(0,0,0,date('m'),date('d')+3,date('Y')));
	$month_3=date('n',mktime(0,0,0,date('m'),date('d')+3,date('Y')));
	$year_3=date('Y',mktime(0,0,0,date('m'),date('d')+3,date('Y')));

	$day_4=date('j',mktime(0,0,0,date('m'),date('d')+4,date('Y')));
	$month_4=date('n',mktime(0,0,0,date('m'),date('d')+4,date('Y')));
	$year_4=date('Y',mktime(0,0,0,date('m'),date('d')+4,date('Y')));

	$day_5=date('j',mktime(0,0,0,date('m'),date('d')+5,date('Y')));
	$month_5=date('n',mktime(0,0,0,date('m'),date('d')+5,date('Y')));
	$year_5=date('Y',mktime(0,0,0,date('m'),date('d')+5,date('Y')));

	$day_6=date('j',mktime(0,0,0,date('m'),date('d')+6,date('Y')));
	$month_6=date('n',mktime(0,0,0,date('m'),date('d')+6,date('Y')));
	$year_6=date('Y',mktime(0,0,0,date('m'),date('d')+6,date('Y')));

	$day_7=date('j',mktime(0,0,0,date('m'),date('d')+7,date('Y')));
	$month_7=date('n',mktime(0,0,0,date('m'),date('d')+7,date('Y')));
	$year_7=date('Y',mktime(0,0,0,date('m'),date('d')+7,date('Y')));

	$day_8=date('j',mktime(0,0,0,date('m'),date('d')+8,date('Y')));
	$month_8=date('n',mktime(0,0,0,date('m'),date('d')+8,date('Y')));
	$year_8=date('Y',mktime(0,0,0,date('m'),date('d')+8,date('Y')));

	$day_9=date('j',mktime(0,0,0,date('m'),date('d')+9,date('Y')));
	$month_9=date('n',mktime(0,0,0,date('m'),date('d')+9,date('Y')));
	$year_9=date('Y',mktime(0,0,0,date('m'),date('d')+9,date('Y')));

	$day_10=date('j',mktime(0,0,0,date('m'),date('d')+10,date('Y')));
	$month_10=date('n',mktime(0,0,0,date('m'),date('d')+10,date('Y')));
	$year_10=date('Y',mktime(0,0,0,date('m'),date('d')+10,date('Y')));

	$day_11=date('j',mktime(0,0,0,date('m'),date('d')+11,date('Y')));
	$month_11=date('n',mktime(0,0,0,date('m'),date('d')+11,date('Y')));
	$year_11=date('Y',mktime(0,0,0,date('m'),date('d')+11,date('Y')));

	$day_12=date('j',mktime(0,0,0,date('m'),date('d')+12,date('Y')));
	$month_12=date('n',mktime(0,0,0,date('m'),date('d')+12,date('Y')));
	$year_12=date('Y',mktime(0,0,0,date('m'),date('d')+12,date('Y')));

	$day_13=date('j',mktime(0,0,0,date('m'),date('d')+13,date('Y')));
	$month_13=date('n',mktime(0,0,0,date('m'),date('d')+13,date('Y')));
	$year_13=date('Y',mktime(0,0,0,date('m'),date('d')+13,date('Y')));

	$day_14=date('j',mktime(0,0,0,date('m'),date('d')+14,date('Y')));
	$month_14=date('n',mktime(0,0,0,date('m'),date('d')+14,date('Y')));
	$year_14=date('Y',mktime(0,0,0,date('m'),date('d')+14,date('Y')));

	$day_15=date('j',mktime(0,0,0,date('m'),date('d')+15,date('Y')));
	$month_15=date('n',mktime(0,0,0,date('m'),date('d')+15,date('Y')));
	$year_15=date('Y',mktime(0,0,0,date('m'),date('d')+15,date('Y')));

	$day_16=date('j',mktime(0,0,0,date('m'),date('d')+16,date('Y')));
	$month_16=date('n',mktime(0,0,0,date('m'),date('d')+16,date('Y')));
	$year_16=date('Y',mktime(0,0,0,date('m'),date('d')+16,date('Y')));

	$day_17=date('j',mktime(0,0,0,date('m'),date('d')+17,date('Y')));
	$month_17=date('n',mktime(0,0,0,date('m'),date('d')+17,date('Y')));
	$year_17=date('Y',mktime(0,0,0,date('m'),date('d')+17,date('Y')));

	$day_18=date('j',mktime(0,0,0,date('m'),date('d')+18,date('Y')));
	$month_18=date('n',mktime(0,0,0,date('m'),date('d')+18,date('Y')));
	$year_18=date('Y',mktime(0,0,0,date('m'),date('d')+18,date('Y')));

	$day_19=date('j',mktime(0,0,0,date('m'),date('d')+19,date('Y')));
	$month_19=date('n',mktime(0,0,0,date('m'),date('d')+19,date('Y')));
	$year_19=date('Y',mktime(0,0,0,date('m'),date('d')+19,date('Y')));

	$day_20=date('j',mktime(0,0,0,date('m'),date('d')+20,date('Y')));
	$month_20=date('n',mktime(0,0,0,date('m'),date('d')+20,date('Y')));
	$year_20=date('Y',mktime(0,0,0,date('m'),date('d')+20,date('Y')));

	$day_20=date('j',mktime(0,0,0,date('m'),date('d')+20,date('Y')));
	$month_20=date('n',mktime(0,0,0,date('m'),date('d')+20,date('Y')));
	$year_20=date('Y',mktime(0,0,0,date('m'),date('d')+20,date('Y')));

	$day_21=date('j',mktime(0,0,0,date('m'),date('d')+21,date('Y')));
	$month_21=date('n',mktime(0,0,0,date('m'),date('d')+21,date('Y')));
	$year_21=date('Y',mktime(0,0,0,date('m'),date('d')+21,date('Y')));

	$day_22=date('j',mktime(0,0,0,date('m'),date('d')+22,date('Y')));
	$month_22=date('n',mktime(0,0,0,date('m'),date('d')+22,date('Y')));
	$year_22=date('Y',mktime(0,0,0,date('m'),date('d')+22,date('Y')));

	$day_23=date('j',mktime(0,0,0,date('m'),date('d')+23,date('Y')));
	$month_23=date('n',mktime(0,0,0,date('m'),date('d')+23,date('Y')));
	$year_23=date('Y',mktime(0,0,0,date('m'),date('d')+23,date('Y')));

	$day_24=date('j',mktime(0,0,0,date('m'),date('d')+24,date('Y')));
	$month_24=date('n',mktime(0,0,0,date('m'),date('d')+24,date('Y')));
	$year_24=date('Y',mktime(0,0,0,date('m'),date('d')+24,date('Y')));

	$day_25=date('j',mktime(0,0,0,date('m'),date('d')+25,date('Y')));
	$month_25=date('n',mktime(0,0,0,date('m'),date('d')+25,date('Y')));
	$year_25=date('Y',mktime(0,0,0,date('m'),date('d')+25,date('Y')));

	$day_26=date('j',mktime(0,0,0,date('m'),date('d')+26,date('Y')));
	$month_26=date('n',mktime(0,0,0,date('m'),date('d')+26,date('Y')));
	$year_26=date('Y',mktime(0,0,0,date('m'),date('d')+26,date('Y')));

	$day_27=date('j',mktime(0,0,0,date('m'),date('d')+27,date('Y')));
	$month_27=date('n',mktime(0,0,0,date('m'),date('d')+27,date('Y')));
	$year_27=date('Y',mktime(0,0,0,date('m'),date('d')+27,date('Y')));

	$day_28=date('j',mktime(0,0,0,date('m'),date('d')+28,date('Y')));
	$month_28=date('n',mktime(0,0,0,date('m'),date('d')+28,date('Y')));
	$year_28=date('Y',mktime(0,0,0,date('m'),date('d')+28,date('Y')));

	$day_29=date('j',mktime(0,0,0,date('m'),date('d')+29,date('Y')));
	$month_29=date('n',mktime(0,0,0,date('m'),date('d')+29,date('Y')));
	$year_29=date('Y',mktime(0,0,0,date('m'),date('d')+29,date('Y')));

	$day_30=date('j',mktime(0,0,0,date('m'),date('d')+30,date('Y')));
	$month_30=date('n',mktime(0,0,0,date('m'),date('d')+30,date('Y')));
	$year_30=date('Y',mktime(0,0,0,date('m'),date('d')+30,date('Y')));

	$sql="SELECT * FROM {$base_instance->entity['REMINDER']['DATE']} WHERE (
((day=$day_0 OR day=0) AND (month=$month_0 OR month=0) AND (year=$year_0 OR year=0)) OR
((day=$day_1 OR day=0) AND (month=$month_1 OR month=0) AND (year=$year_1 OR year=0)) OR
((day=$day_2 OR day=0) AND (month=$month_2 OR month=0) AND (year=$year_2 OR year=0)) OR
((day=$day_3 OR day=0) AND (month=$month_3 OR month=0) AND (year=$year_3 OR year=0)) OR
((day=$day_4 OR day=0) AND (month=$month_4 OR month=0) AND (year=$year_4 OR year=0)) OR
((day=$day_5 OR day=0) AND (month=$month_5 OR month=0) AND (year=$year_5 OR year=0)) OR
((day=$day_6 OR day=0) AND (month=$month_6 OR month=0) AND (year=$year_6 OR year=0)) OR
((day=$day_7 OR day=0) AND (month=$month_7 OR month=0) AND (year=$year_7 OR year=0)) OR
((day=$day_8 OR day=0) AND (month=$month_8 OR month=0) AND (year=$year_8 OR year=0)) OR
((day=$day_9 OR day=0) AND (month=$month_9 OR month=0) AND (year=$year_9 OR year=0)) OR
((day=$day_10 OR day=0) AND (month=$month_10 OR month=0) AND (year=$year_10 OR year=0)) OR
((day=$day_11 OR day=0) AND (month=$month_11 OR month=0) AND (year=$year_11 OR year=0)) OR
((day=$day_12 OR day=0) AND (month=$month_12 OR month=0) AND (year=$year_12 OR year=0)) OR
((day=$day_13 OR day=0) AND (month=$month_13 OR month=0) AND (year=$year_13 OR year=0)) OR
((day=$day_14 OR day=0) AND (month=$month_14 OR month=0) AND (year=$year_14 OR year=0)) OR
((day=$day_15 OR day=0) AND (month=$month_15 OR month=0) AND (year=$year_15 OR year=0)) OR
((day=$day_16 OR day=0) AND (month=$month_16 OR month=0) AND (year=$year_16 OR year=0)) OR
((day=$day_17 OR day=0) AND (month=$month_17 OR month=0) AND (year=$year_17 OR year=0)) OR
((day=$day_18 OR day=0) AND (month=$month_18 OR month=0) AND (year=$year_18 OR year=0)) OR
((day=$day_19 OR day=0) AND (month=$month_19 OR month=0) AND (year=$year_19 OR year=0)) OR
((day=$day_20 OR day=0) AND (month=$month_20 OR month=0) AND (year=$year_20 OR year=0)) OR
((day=$day_21 OR day=0) AND (month=$month_21 OR month=0) AND (year=$year_21 OR year=0)) OR
((day=$day_22 OR day=0) AND (month=$month_22 OR month=0) AND (year=$year_22 OR year=0)) OR
((day=$day_23 OR day=0) AND (month=$month_23 OR month=0) AND (year=$year_23 OR year=0)) OR
((day=$day_24 OR day=0) AND (month=$month_24 OR month=0) AND (year=$year_24 OR year=0)) OR
((day=$day_25 OR day=0) AND (month=$month_25 OR month=0) AND (year=$year_25 OR year=0)) OR
((day=$day_26 OR day=0) AND (month=$month_26 OR month=0) AND (year=$year_26 OR year=0)) OR
((day=$day_27 OR day=0) AND (month=$month_27 OR month=0) AND (year=$year_27 OR year=0)) OR
((day=$day_28 OR day=0) AND (month=$month_28 OR month=0) AND (year=$year_28 OR year=0)) OR
((day=$day_29 OR day=0) AND (month=$month_29 OR month=0) AND (year=$year_29 OR year=0)) OR
((day=$day_30 OR day=0) AND (month=$month_30 OR month=0) AND (year=$year_30 OR year=0)))
AND user=$this->user AND homepage=1 ORDER BY day ASC LIMIT 100";

	$data=$base_instance->get_data($sql);

	$reminder_total=sizeof($data);

	for ($index=1; $index <= $reminder_total; $index++) {

	$reminder_id[$index]=$data[$index]->ID;
	$reminder_title[$index]=$data[$index]->title;
	$reminder_text[$index]=$data[$index]->text;
	$reminder_day[$index]=$data[$index]->day;
	$reminder_month[$index]=$data[$index]->month;
	$reminder_year[$index]=$data[$index]->year;

	$reminder_time[$index]=$data[$index]->what_time;

	$reminder_warning[$index]=$data[$index]->warning_homepage;

	}

	# week of the day

	$day_of_the_week=date('w')+1;

	$data2=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['WEEKDAY']} WHERE day_of_the_week LIKE '%$day_of_the_week%' AND homepage=1 AND user='$this->user' ORDER BY what_time");

	$dotw_total=sizeof($data2);

	for ($index=1; $index <= $dotw_total; $index++) {

	$dotw_id[$index]=$data2[$index]->ID;
	$dotw_title[$index]=$data2[$index]->title;
	$dotw_text[$index]=$data2[$index]->text;
	$dotw_time[$index]=$data2[$index]->what_time;

	}

	# reminder by days

	$datetime=date('Y-m-d H:i:s');

	$data3=$base_instance->get_data("SELECT * FROM {$base_instance->entity['REMINDER']['DAYS']} WHERE DATE_ADD(last_reminded, INTERVAL frequency DAY)<'$datetime' AND user={$this->user} AND homepage=1 ORDER BY (UNIX_TIMESTAMP('$datetime')-UNIX_TIMESTAMP(last_reminded)-(frequency*86400)) DESC");

	$reg_total=sizeof($data3);

	$timestamp=time();

	for ($index=1; $index <= $reg_total; $index++) {

	$reg_id[$index]=$data3[$index]->ID;
	$reg_title[$index]=$data3[$index]->title;
	$reg_text[$index]=$data3[$index]->text;
	$reg_last_reminded[$index]=$data3[$index]->last_reminded;
	$reg_frequency[$index]=$data3[$index]->frequency;

	preg_match("/([0-9]+)-([0-9]+)-([0-9]+)/",$reg_last_reminded[$index],$dd);

	$temp=mktime(0,0,0,$dd[2],$dd[3]+$reg_frequency[$index],$dd[1]);
	$days_rounded[$index]=round(($timestamp-$temp)/86400);

	}

	#

	$all_date='';

	if (empty($reminder_id) && empty($dotw_id) && empty($reg_id)) { $no_data=1; }

	$today_converted=$base_instance->convert_date($datetime);

	$day_today=date('j');

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'"><b>Reminder</b> - Today: '.$today_converted.' &nbsp; <a href="show-reminder-monthly-overview.php#'.$day_today.'">[Monthly Overview]</a></td></tr>';

	if (empty($no_data)) {

	for ($index=1; $index <= $reminder_total; $index++) {

	if ($reminder_text[$index]) { $notes=1; } else { $notes=''; }

	$text=$this->calculate_date($reminder_id[$index], $reminder_year[$index], $reminder_month[$index], $reminder_day[$index], $reminder_warning[$index], $reminder_title[$index], $notes, $reminder_time[$index]);

	if ($text) { $all_date.=$text; }

	}

	for ($index=1; $index <= $dotw_total; $index++) {

	if ($dotw_text[$index]) { $notes_link='<a href="javascript:void(window.open(\'show-notes-text.php?weekday_reminder_id='.$dotw_id[$index].'\',\'\',\'width=600,height=500,top=100,left=100\'))">N</a>'; }
	else { $notes_link='&nbsp;'; }

	$all_date.='<tr onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';"><td align=left colspan=2>'.$dotw_time[$index].' - '.$dotw_title[$index].'</td><td width=10>'.$notes_link.'</td><td width=10><a href="javascript:void(window.open(\'edit-reminder-weekday.php?reminder_id='.$dotw_id[$index].'\',\'\',\'width=600,height=500,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-reminder-weekday.php?reminder_id='.$dotw_id[$index].'\',\'\',\'width=450,height=200,top=100,left=100\'))">D</a></td></tr>';

	}

	for ($index=1; $index <= $reg_total; $index++) {

	if ($reg_text[$index]) { $notes_link='<a href="javascript:void(window.open(\'show-notes-text.php?days_reminder_id='.$reg_id[$index].'\',\'\',\'width=600,height=500,top=100,left=100\'))">N</a>'; }
	else { $notes_link='&nbsp;'; }

	$all_date.='<tr onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';"><td width=10>'.$days_rounded[$index].'</td><td onClick="window.open(\'count-reminder.php?reload=1&amp;reminder_id='.$reg_id[$index].'\',\'status\')"><span class="fakelink">'.$reg_title[$index].'</span></td><td width=10>'.$notes_link.'</td><td width=10><a href="javascript:void(window.open(\'edit-reminder-days.php?reminder_id='.$reg_id[$index].'\',\'\',\'width=600,height=500,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-reminder-days.php?reminder_id='.$reg_id[$index].'\',\'\',\'width=450,height=200,top=100,left=100\'))">D</a></td></tr>';

	}

	}

	if (!$all_date) { $all_text.='<tr><td align=left>Nothing today</td></tr>'; } else { $all_text.=$all_date; }

	$all_text.='<tr><td colspan="5" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-reminder-days-overview.php?order_col=bluebox&amp;order_type=DESC\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function random_to_do() {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE user={$this->user} ORDER BY RAND() LIMIT 1");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random To-Do</b></td></tr><tr><td width="100%" style="padding:10">No to-do saved</td></tr></table>';

	return $all_text;

	}

	$to_do_id=$data[1]->ID;
	$to_do_title=$data[1]->title;
	$to_do_text=$data[1]->text;
	$to_do_datetime=$data[1]->datetime;

	$to_do_title=convert_square_bracket($to_do_title);
	$to_do_text=convert_square_bracket($to_do_text);

	$to_do_text=$base_instance->insert_links($to_do_text);

	$to_do_text=nl2br($to_do_text);

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">

&nbsp;<b>Random To-Do</b> &nbsp; <a href="edit-to-do.php?to_do_id='.$to_do_id.'">[Edit]</a> &nbsp;&nbsp; <a href="javascript:DelToDo(\''.$to_do_id.'\')">[Delete]</a>

</td></tr><tr><td width="100%" style="padding:10"><div id="item'.$to_do_id.'">';

	if ($to_do_title) { $all_text.='<strong>'.$to_do_title.'</strong><br>'; }

	$all_text.=$to_do_text.'</div></td></tr></table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelToDo(item){if(confirm("Delete To-Do?")){http.open(\'get\',\'delete-to-do.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

	return $all_text;

}

function bluebox_by_popularity($links) {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ((DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime' AND frequency_mode=3) OR frequency_mode=1) AND user={$this->user} ORDER BY popularity DESC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (by Popularity)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2">

<tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (by Popularity)</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?bluebox=1&amp;order_col=popularity\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function newest_in_bluebox($links) {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ((DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime' AND frequency_mode=3) OR frequency_mode=1) AND user={$this->user} ORDER BY (UNIX_TIMESTAMP('$datetime')-UNIX_TIMESTAMP(last_visit)-frequency) ASC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Newest in Bluebox</b></td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</a></td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Newest in Bluebox</b></td></tr>';

	$now=time();

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_frequency=$data[$index]->frequency;
	$link_title=$data[$index]->title;
	$link_last_visit=$data[$index]->last_visit;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	# calculate time in Bluebox

	$last_active_converted=strtotime($link_last_visit);

	if ($link_frequency==0) { $time_in_BB='-'; }

	else {

	$seconds_since_last_active=$now-$last_active_converted;
	$seconds_in_BB=$seconds_since_last_active-$link_frequency;

	if ($seconds_in_BB < 0) { $minus=1; $seconds_in_BB=abs($seconds_in_BB); }

	if ($seconds_in_BB < 60) { $time_in_BB=$seconds_in_BB.' secs'; }
	else if ($seconds_in_BB < 3600) { $time_in_BB=round($seconds_in_BB/60).' mins'; }
	else if ($seconds_in_BB < 86400) { $time_in_BB=round($seconds_in_BB/3600).' hours'; }
	else { $time_in_BB=round($seconds_in_BB/86400).' days'; }

	if (isset($minus)) { $time_in_BB='-'.$time_in_BB; }

	unset($minus);

	}

#

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td align="center" width=50><font size="1">'.$time_in_BB.'</font></td><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?bluebox=1&link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?bluebox=1&order_col=bluebox&order_type=ASC\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function longest_in_bluebox($links) {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ((DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime' AND frequency_mode=3) OR frequency_mode=1) AND user={$this->user} ORDER BY (UNIX_TIMESTAMP('$datetime')-UNIX_TIMESTAMP(last_visit)-frequency) DESC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Longest in Bluebox</b></td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</a></td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Longest in Bluebox</b></td></tr>';

	$now=time();

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_visits=$data[$index]->visits;
	$link_frequency=$data[$index]->frequency;
	$link_title=$data[$index]->title;
	$link_last_visit=$data[$index]->last_visit;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	# calculate time in Bluebox

	$last_active_converted=strtotime($link_last_visit);

	if ($link_frequency==0) { $time_in_BB='-'; }

	else {

	$seconds_since_last_active=$now-$last_active_converted;
	$seconds_in_BB=$seconds_since_last_active-$link_frequency;

	if ($seconds_in_BB < 0) { $minus=1; $seconds_in_BB=abs($seconds_in_BB); }

	if ($seconds_in_BB < 60) { $time_in_BB=$seconds_in_BB.' secs'; }
	else if ($seconds_in_BB < 3600) { $time_in_BB=round($seconds_in_BB/60).' mins'; }
	else if ($seconds_in_BB < 86400) { $time_in_BB=round($seconds_in_BB/3600).' hours'; }
	else { $time_in_BB=round($seconds_in_BB/86400).' days'; }

	if (isset($minus)) { $time_in_BB='-'.$time_in_BB; }

	unset($minus);

	}

#

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td align="center" width=50><font size="1">'.$time_in_BB.'</font></td><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?bluebox=1&order_col=bluebox&order_type=DESC\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function last_added_links($links) {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user={$this->user} ORDER BY datetime DESC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (Last added)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</a></td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (Last added)</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?order_col=datetime\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function links_by_sequence($links) {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE user={$this->user} ORDER BY sequence DESC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (by Sequence)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</a></td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Links</b> (by Sequence)</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';"><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?order_col=sequence&order_type=DESC\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function bluebox_by_speed_ranking($links) {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ((DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime' AND frequency_mode=3) OR frequency_mode=1) AND user={$this->user} ORDER BY (UNIX_TIMESTAMP('$datetime')-UNIX_TIMESTAMP(last_visit)-frequency)*(speed/100000) DESC LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (by Speed Ranking)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</a></td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="4" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (by Speed Ranking)</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';"><td width="250" onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="4" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?bluebox=1&amp;order_col=ttv&amp;order_type=DESC\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function to_do_by_priority($to_do) {

	global $base_instance;

	$data=$base_instance->get_data("SELECT SQL_CALC_FOUND_ROWS * FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE user={$this->user} ORDER BY priority ASC LIMIT $to_do");

	$data2=$base_instance->get_data("SELECT FOUND_ROWS() as fnd_rows");
	$fnd_rows=$data2[1]->fnd_rows;

	#

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>To-Do List (by Priority)</b></b></td></tr><tr><td width="100%" style="padding:10">No to-do saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>To-Do List (by Priority)</b></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$to_do_id=$data[$index]->ID;
	$to_do_priority=$data[$index]->priority;
	$to_do_title=$data[$index]->title;
	$to_do_text=$data[$index]->text;

	if (!$to_do_title) { $title=substr($to_do_text,0,45); } else { $title=$to_do_title; }

	if ($to_do_id) { $all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td align=right width=30>'.$to_do_priority.'</td><td><span class="fakelink">'.$title.'</span></td><td width=10><a href="javascript:void(window.open(\'edit-to-do.php?to_do_id='.$to_do_id.'\',\'\',\'width=700,height=450,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:DelToDo(\''.$to_do_id.'\')">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-to-do.php?order_col=priority&order_type=ASC\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try{var requester=new XMLHttpRequest();}catch(error){try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}} return requester;}var http=createRequestObject();function DelToDo(item){if(confirm("Delete To-Do?")){http.open(\'get\',\'delete-to-do.php?item=\'+item); http.send(null);}}</script>';

	return $all_text;

}

function rss_feed_word_of_the_day() {

	$rss_instance=new rss('http://www.dictionary.com/wordoftheday/wotd.rss');

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Word of the Day</b></td></tr><tr><td width="100%" style="padding:10">'.$rss_instance->content.'</td></tr></table>';

	return $all_text;

}

function rss_feed_bbc_news() {

	$rss_instance=new rss('http://news.bbc.co.uk/rss/newsonline_uk_edition/front_page/rss.xml');

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>BBC News</b></td></tr><tr><td>'.$rss_instance->content.'</td></tr></table>';

	return $all_text;

}

function rss_feed_cnn_latest() {

	$rss_instance=new rss('http://rss.cnn.com/rss/cnn_latest.rss');

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>CNN Latest</b></td></tr><tr><td>'.$rss_instance->content.'</td></tr></table>';

	return $all_text;

}

function rss_feed_new_york_times() {

	$rss_instance=new rss('http://www.nytimes.com/services/xml/rss/nyt/HomePage.xml');

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>New York Times</b></td></tr><tr><td>'.$rss_instance->content.'</td></tr></table>';

	return $all_text;

}

function rss_feed($feed_id) {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['RSS']['MAIN']} WHERE ID='$feed_id' AND user={$this->user}");

	if ($data) {

	$feed_url=$data[1]->feed;
	$title=$data[1]->title;
	$max_items=$data[1]->max_items;

	}

	if (empty($title)) { $title='Feed '.$feed_id; }

	if (isset($feed_url)) { $rss_instance=new rss($feed_url,$max_items);

	$content=$rss_instance->content;

	} else { $content='<a href="add-rss-feeds.php">[Add RSS Feeds]</a>'; }

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>'.$title.'</b> &nbsp; <a href="edit-rss-feed.php?feed_id='.$feed_id.'">[Edit]</a></td></tr><tr><td width="100%" style="padding:10">'.$content.'</td></tr></table>';

	return $all_text;

}

function ted_goff() {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Ted Goff Cartoon</b></td></tr><tr><td align="center"><a href="http://www.tedgoff.com/" target="_blank"><img src="http://www.tedgoff.com/mb/images/today.gif" alt="Ted Goff\'s Cartoon" border=0 align=bottom></a></td></tr></table>';

	return $all_text;

}

function sticky_note($note_id) {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['STICKY_NOTE']['MAIN']} WHERE user={$this->user} AND note_id=$note_id");

	if ($data) {

	$text=$data[1]->text;
	$text=convert_square_bracket($text);
	$text=$base_instance->insert_links($text);
	$text=nl2br($text);

	} else { $text='Empty'; }

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Sticky Note '.$note_id.'</b> &nbsp; <a href="edit-sticky-note.php?note_id='.$note_id.'">[Edit]</a></td></tr><tr><td width="100%" style="padding:10">'.$text.'</td></tr></table>';

	return $all_text;

}

function search() {

	require 'inc.search.php';

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['SEARCH']['MAIN']} WHERE user={$this->user}");

	if ($data) {

	$search_forms='<table cellpadding=5>';

	$element1=$data[1]->element1;
	$element2=$data[1]->element2;
	$element3=$data[1]->element3;
	$element4=$data[1]->element4;
	$element5=$data[1]->element5;
	$element6=$data[1]->element6;
	$element7=$data[1]->element7;
	$element8=$data[1]->element8;
	$element9=$data[1]->element9;
	$element10=$data[1]->element10;

	$search_forms.='<tr><td>';

	if ($element1) { $search_forms.=$search[$element1]; }
	if ($element2) { $search_forms.=$search[$element2]; }
	if ($element3) { $search_forms.=$search[$element3]; }

	$search_forms.='</td></tr><tr><td>';

	if ($element4) { $search_forms.=$search[$element4]; }
	if ($element5) { $search_forms.=$search[$element5]; }
	if ($element6) { $search_forms.=$search[$element6]; }

	$search_forms.='</td></tr><tr><td>';

	if ($element7) { $search_forms.=$search[$element7]; }
	if ($element8) { $search_forms.=$search[$element8]; }
	if ($element9) { $search_forms.=$search[$element9]; }

	$search_forms.='</td></tr><tr><td>';

	if ($element10) { $search_forms.=$search[$element10]; }

	$search_forms.='</td></tr></table>';

	} else { $search_forms='<table cellpadding=5><tr><td><a href="edit-search.php">Click here to set up your search</a></td></tr></table>'; }

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Search</b> &nbsp;&nbsp; <a href="edit-search.php">[Edit]</a></td></tr><tr><td>'.$search_forms.'</td></tr></table>';

	return $all_text;

}

function random_rss_feed() {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['RSS']['MAIN']} WHERE user={$this->user} ORDER BY RAND() LIMIT 1");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random RSS Feed</b></td></tr><tr><td width="100%" style="padding:10">No RSS Feeds saved <a href="add-rss-feeds.php">[Add RSS Feeds]</a></td></tr></table>';

	return $all_text;

	}

	$feed_id=$data[1]->ID;
	$feed_url=$data[1]->feed;
	$title=$data[1]->title;

	if (empty($title)) { $title='RSS Feed '.$feed_id; }

	if (!empty($feed_url)) {

	$rss_instance=new rss($feed_url);
	$content=$rss_instance->content;

	} else { $content=''; }

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>'.$title.'</b> &nbsp; <a href="edit-rss-feed.php?feed_id='.$feed_id.'">[Edit]</a></td></tr><tr><td>'.$content.'</td></tr></table>';

	return $all_text;

}

function random_notes() {

	global $base_instance;

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NOTE']['MAIN']} WHERE user={$this->user} ORDER BY RAND() LIMIT 1");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Note</b></td></tr><tr><td width="100%" style="padding:10">No notes saved</td></tr></table>';

	return $all_text;

	}

	$note_id=$data[1]->ID;
	$note_title=$data[1]->title;
	$note_text=$data[1]->text;
	$note_category_id=$data[1]->category;

	$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE ID=$note_category_id");
	$note_category_text=$data[1]->title;

	$note_text=convert_square_bracket($note_text);
	$note_text=$base_instance->insert_links($note_text);
	$note_text=nl2br($note_text);

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Random Note</b> - '.$note_category_text.' - <a href="edit-note.php?note_id='.$note_id.'">[Edit]</a> &nbsp; <a href="javascript:DelNote(\''.$note_id.'\')">[Delete]</a></td></tr><tr><td style="padding:10"><div id="item'.$note_id.'"><b>'.$note_title.'</b><br>'.$note_text.'</div></td></tr></table>

<script language="JavaScript" type="text/javascript">function createRequestObject(){try {var requester=new XMLHttpRequest();}catch (error) {try{var requester=new ActiveXObject("Microsoft.XMLHTTP");}catch(error){return false;}}return requester;}var http=createRequestObject();function DelNote(item){if(confirm("Delete Note?")){http.open(\'get\',\'delete-note.php?item=\'+item);http.onreadystatechange=handleResponse;http.send(null);}}function handleResponse(){if(http.readyState==4){var response=http.responseText;var update=new Array();if(response.indexOf(\'|\'!=-1)){res=response.split(\'|\');document.getElementById(res[0]).innerHTML=res[1];}}}</script>';

	return $all_text;

}

function bluebox_by_sequence($links) {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['MAIN']} WHERE ((DATE_ADD(last_visit, INTERVAL frequency SECOND)<'$datetime' AND frequency_mode=3) OR frequency_mode=1) AND user={$this->user} ORDER BY sequence LIMIT $links");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (by Sequence)</td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</a></td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Bluebox</b> (by Sequence)</td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$link_id=$data[$index]->ID;
	$link_url=$data[$index]->url;
	$link_title=$data[$index]->title;

	if ($link_title) { $link_url=$link_title; }
	else if ($link_id) { $link_url='http://'.substr($link_url,0,30); }

	if ($link_url) { $all_text.='<tr onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';"><td onClick="window.open(\'visit-link.php?link_id='.$link_id.'\',\'_blank\'); this.style.fontWeight=\'bold\';"><span class="fakelink">'.$link_url.'</span></td><td width=10><a href="visit-link.php?link_id='.$link_id.'" target="_blank">V</a></td><td width=10><a href="javascript:void(window.open(\'edit-link.php?link_id='.$link_id.'\',\'\',\'width=550,height=650,top=100,left=100\'))">E</a></td><td width=10><a href="javascript:void(window.open(\'delete-link.php?link_id='.$link_id.'\',\'\',\'width=450,height=300,top=100,left=100\'))">D</a></td></tr>'; }

	}

	$all_text.='<tr><td colspan="7" align="center" onMouseOver="this.style.backgroundColor=\'#e9e9e9\';" onMouseOut="this.style.backgroundColor=\'#ffffff\';" onClick="javascript:window.open(\'show-links.php?order_col=sequence&order_type=DESC&bluebox=1\',\'_self\')"><span class="fakelink">more ...</span></td></tr></table>';

	return $all_text;

}

function link_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['LINK']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Link Categories</b></td></tr><tr><td width="100%" style="padding:10">No links saved <a href="import-bookmarks-start.php">[Upload Bookmarks]</a></td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Link Categories</b> &nbsp;&nbsp; <a href="show-link-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['LINK']['MAIN']} WHERE user={$this->user} AND category=$category_id");

	$number_links=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-links.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_links.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

function knowledge_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Knowledge Categories</b></td></tr><tr><td width="100%" style="padding:10">No knowledge saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Knowledge Categories</b> &nbsp;&nbsp; <a href="show-knowledge-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['KNOWLEDGE']['MAIN']} WHERE user={$this->user} AND category=$category_id");

	$number_knowledge=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-knowledge.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_knowledge.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

function to_do_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['TO_DO']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>To-Do Categories</b></td></tr><tr><td width="100%" style="padding:10">No to-do saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>To-Do Categories</b> &nbsp;&nbsp; <a href="show-to-do-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['TO_DO']['MAIN']} WHERE user={$this->user} AND category=$category_id");

	$number_to_do=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-to-do.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_to_do.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

function contact_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['CONTACT']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Contact Categories</b></td></tr><tr><td width="100%" style="padding:10">No contacts saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Contact Categories</b> &nbsp;&nbsp; <a href="show-contact-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['CONTACT']['MAIN']} WHERE user={$this->user} AND category=$category_id");

	$number_contact=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-contact.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_contact.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

function notes_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['NOTE']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Notes Categories</b></td></tr><tr><td width="100%" style="padding:10">No notes saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Notes Categories</b> &nbsp;&nbsp; <a href="show-note-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['NOTE']['MAIN']} WHERE user={$this->user} AND category=$category_id");

	$number_note=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-note.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_note.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

function files_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['FILE']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Files Categories</b></td></tr><tr><td width="100%" style="padding:10">No files saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Files Categories</b> &nbsp;&nbsp; <a href="show-file-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['FILE']['MAIN']} WHERE user={$this->user} AND category=$category_id");

	$number_files=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-files.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_files.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

function database_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['DATABASE']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Database Categories</b></td></tr><tr><td width="100%" style="padding:10">No database saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Database Categories</b> &nbsp;&nbsp; <a href="show-database-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['DATABASE']['MAIN']} WHERE user={$this->user} AND category_id=$category_id");

	$number_database=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-database-data.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_database.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

function blog_category() {

	global $base_instance;

	$datetime=date('Y-m-d H:i:s');

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['BLOG']['CATEGORY']} WHERE user={$this->user} ORDER BY title");

	if (!$data) {

	$all_text='<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td width="100%" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Blog Categories</b></td></tr><tr><td width="100%" style="padding:10">No blog saved</td></tr></table>';

	return $all_text;

	}

	$all_text='<table width="100%" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="pastel2"><tr><td colspan="5" style="background-image: url(\'pics/gradient.jpg\');border-bottom:1px solid '._BLOCK_LINE_COLOR.'">&nbsp;<b>Blog Categories</b> &nbsp;&nbsp; <a href="show-blog-categories.php">[Detailed View]</a></td></tr>';

	for ($index=1; $index <= sizeof($data); $index++) {

	$category_id=$data[$index]->ID;
	$category_title=$data[$index]->title;

	#

	$data2=$base_instance->get_data("SELECT COUNT(*) AS total FROM {$base_instance->entity['BLOG']['MAIN']} WHERE user={$this->user} AND category=$category_id");

	$number_blog=$data2[1]->total;

	#

	$all_text.='<tr onMouseOver=\'this.style.background="#e9e9e9"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-blog.php?category_id='.$category_id.'"><strong>'.$category_title.'</strong></a></td><td align="left"><strong>Total:</strong> '.$number_blog.'</td></tr>';

	}

	$all_text.='</table>';

	return $all_text;

}

}

?>