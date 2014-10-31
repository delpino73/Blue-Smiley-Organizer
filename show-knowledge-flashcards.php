<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$category_id=isset($_GET['category_id']) ? $_GET['category_id'] : 0;
$value=isset($_GET['value']) ? $_GET['value'] : '';
$autoload=isset($_GET['autoload']) ? $_GET['autoload'] : '';
$go_back=isset($_GET['go_back']) ? $_GET['go_back'] : '';

$msg=''; $all_text=''; $now=time();

#$loop_words=$base_instance->loop_words;
$loop_words=30;

if (!empty($_GET['delete_id'])) {

	$delete_id=$_GET['delete_id'];

	$base_instance->query("DELETE FROM {$base_instance->entity['KNOWLEDGE']['MAIN']} WHERE ID='$delete_id' AND user='$userid'");

	$base_instance->query("DELETE FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE word_id='$delete_id' AND user='$userid'");

}

if (!empty($_GET['last_id'])) {

	$last_id=$_GET['last_id'];

	if ($value=='kp') {

		$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET shown=shown+1,last_shown=$now WHERE word_id='$last_id' AND user='$userid' AND category_id='$category_id'");

	}

	else if (!empty($value)) {

		$rand=mt_rand(-100,100);

		if ($value=='5mi') { $new_value=$now+$rand+300; } # 5 mins
		else if ($value=='15mi') { $new_value=$now+$rand+900; } # 15 mins
		else if ($value=='60mi') { $new_value=$now+$rand+3600; } # 60 mins
		else if ($value=='1d') { $new_value=$now+$rand+86400; } # 1 day
		else if ($value=='3d') { $new_value=$now+$rand+259200; } # 3 days
		else if ($value=='7d') { $new_value=$now+$rand+604800; } # 7 days
		else if ($value=='14d') { $new_value=$now+$rand+1209600; } # 14 days
		else if ($value=='30d') { $new_value=$now+$rand+2592000; } # 30 days
		else if ($value=='3mo') { $new_value=$now+$rand+7776000; } # 3 months
		else if ($value=='6mo') { $new_value=$now+$rand+15552000; } # 6 months
		else if ($value=='12mo') { $new_value=$now+$rand+31104000; } # 12 months
		else if ($value=='bl') { $new_value=-1; } # block
		else { echo 'error'; exit; }

		$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET value=$new_value,shown=shown+1,last_shown=$now,word_loop=0 WHERE word_id='$last_id' AND user='$userid' AND category_id='$category_id'");

		$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET word_loop=1 WHERE word_loop=0 AND user=$userid AND category_id=$category_id AND value > -1 AND value < $now ORDER BY value LIMIT 1"); # find a new word from active words that is due and add to loop

		$aff_rows=mysqli_affected_rows($base_instance->db_link);

		if ($aff_rows==0) { increase_loop_words(1); } # if nothing found add a new word to active words and add to loop

	}

} else { $last_id=''; }

$data=$base_instance->get_data("SELECT COUNT(*) AS cnt FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE user='$userid' AND category_id=$category_id AND value>-1 ORDER BY value");

$total_active_words=$data[1]->cnt;

##

$data=$base_instance->get_data("SELECT COUNT(*) AS cnt FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE user='$userid' AND word_loop=1 AND category_id=$category_id AND value>-1 ORDER BY value");

$total_loop_words=$data[1]->cnt;

##

if ($total_loop_words < $loop_words) { # not enough loop words

	$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET word_loop=1 WHERE word_loop=0 AND user=$userid AND category_id='$category_id' AND value > -1 AND value < $now ORDER BY value LIMIT 1"); # find a new word from active words that is due and add to loop

	$aff_rows=mysql_affected_rows();

	if ($aff_rows==0) {

		$diff=$loop_words-$total_loop_words;
		increase_loop_words($diff);

	} # if nothing found add a new word to active words and add to loop

}

if ($total_loop_words > $loop_words) { # too many loop words

	$diff=$total_loop_words-$loop_words;

	$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET word_loop=0 WHERE word_loop=1 AND user='$userid' AND category_id='$category_id' ORDER BY shown DESC LIMIT $diff");

}


if (empty($go_back)) {

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE user=$userid AND category_id=$category_id AND value > -1 AND value < $now AND word_loop=1 ORDER BY last_shown LIMIT 1"); # get next word in loop to show it

#echo 'hereee<br>';

	if (!$data) { # no words in the loop

#echo 'here 2 <br>';

		$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET word_loop=1 WHERE user=$userid AND category_id=$category_id AND value > -1 AND value < $now ORDER BY value LIMIT $loop_words");

		$aff_rows=mysqli_affected_rows($base_instance->db_link);

		if ($aff_rows < $loop_words) { # not found enough active words for loop

			$diff=$loop_words-$aff_rows;
			increase_loop_words($diff);

		}

		$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE user=$userid AND category_id=$category_id AND value > -1 AND word_loop=1 ORDER BY last_shown LIMIT 1"); # get next word in loop to show it

	}

} else { # user has clicked "go back"

	$data=$base_instance->get_data("SELECT * FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE user=$userid AND category_id=$category_id AND word_id=$go_back");

}

# chosen word which will be shown to user

if (!empty($data)) {

	$word_id=$data[1]->word_id;
	$shown=$data[1]->shown;

} else {

	$base_instance->show_message('Finished','No knowledge saved or no knowledge in the queue, check again later.'); exit;

}

if ($data[1]->last_shown==0) { $last_shown_text='never'; }
else {

	$seconds=$now-$data[1]->last_shown;

	if ($seconds < 60) { $last_shown_text=$seconds.' secs'; }
	else if ($seconds < 3600) {

		$mins=round($seconds/60);

		if ($mins==1) { $last_shown_text=$mins.' min'; }
		else { $last_shown_text=$mins.' mins'; }

	}

	else if ($seconds < 86400) {

		$hours=round($seconds/3600);

		if ($hours==1) { $last_shown_text=$hours.' hour'; }
		else { $last_shown_text=$hours.' hours'; }

	}

	else {

		$days=round($seconds/86400);

		if ($days==1) { $last_shown_text=$days.' day'; }
		else { $last_shown_text=$days.' days'; }

	}

	$last_shown_text.=' ago';

}

#

if ($word_id > 0) {

	$data=$base_instance->get_data('SELECT * FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE ID='.$word_id);

#echo 'SELECT * FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE ID='.$word_id;

	$title=$data[1]->title;
	$text=$data[1]->text;
	$word_category_id=$data[1]->category;

#echo "title $title"; exit;

} else { echo 'nothing found'; exit; }

#

$data=$base_instance->get_data("SELECT SUM(shown) AS total_shown FROM {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} WHERE user=$userid AND category_id=$category_id");

$total_shown=$data[1]->total_shown;

if ($total_shown < 10) { $msg.='<font size="4" color="#008000">Click on number below to show next flashcard</font>'; }
else if ($shown==0) { $msg.='<strong>New Flashcard!</strong>'; }

$font_name='Arial';

if (strlen($title) > 7) { $font_size='40'; } else { $font_size='40'; }

#

$all_text.='<table cellspacing=0 cellpadding=5 border=0 width="100%"><tr><td align="center" height="300" valign="middle"><font size="'.$font_size.'">'.$title.'</font><br><br><br>'.$msg.'</td>

<td rowspan="2" width="30%" height="500" valign="top"><p>';

if (!empty($text)) {

	$text=convert_square_bracket($text);
	$text=$base_instance->insert_links($text);
	$text=nl2br($text);

	$all_text.=$text.'<p>';

}

$data=$base_instance->get_data("SELECT title FROM {$base_instance->entity['KNOWLEDGE']['CATEGORY']} WHERE ID=$word_category_id");
$knowledge_category_text=$data[1]->title;

$encoded=urlencode($title);

$all_text.='
<u>Category:</u> '.$knowledge_category_text.'<p>
<u>Shown:</u> '.$shown.'  (Total: '.$total_shown.')<p>
<u>Last Shown:</u> '.$last_shown_text.'<p>
<u>Active Flashcards:</u> '.$total_active_words.'<p>

<a href="http://images.google.com/images?client=pub-1841153363764743&amp;channel=0220874538&amp;safe=active&amp;q='.$encoded.'&amp;ie=UTF-8&amp;oe=UTF-8&amp;um=1&amp;sa=N&amp;tab=wi" target="_blank">[Google Images]</a> &nbsp;

<a target="_blank" title="Google Search" href="http://www.google.com/custom?client=pub-1841153363764743&amp;channel=0929371547&amp;ie=UTF-8&amp;oe=UTF-8&amp;safe=active&amp;q='.$encoded.'">[Google]</a><p>

<a href="edit-knowledge.php?knowledge_id='.$word_id.'">[Edit Knowledge]</a><p>

<a href="'.$_SERVER['PHP_SELF'].'?delete_id='.$word_id.'">[Delete Knowledge]</a><p>


';

#

$all_text.='<p>';

if (!empty($autoload)) { $autoload_param='&amp;autoload='.$autoload; }
else { $autoload_param=''; }

$all_text.='

</td></tr>

<tr><td align="center" valign="top">

<div class="flashcard">

<table cellpadding="0" cellspacing="0">

<tr><td colspan="5" align="center"><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=kp'.$autoload_param.'">Keep in the Loop ('.$total_loop_words.' words in the Loop)</a></td></tr>

<tr><td colspan="5" align="center"><br>Or remove from loop and show not again for at least:<p></td></tr>

<tr>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=5mi'.$autoload_param.'">5 Mins</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=15mi'.$autoload_param.'">15 Mins</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=60mi'.$autoload_param.'">60 Mins</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=1d'.$autoload_param.'">1 Day</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=3d'.$autoload_param.'">3 Days</a></td>

</tr><tr>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=7d'.$autoload_param.'">7 Days</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=30d'.$autoload_param.'">30 Days</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=3mo'.$autoload_param.'">3 Months</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=12mo'.$autoload_param.'">1 Year</a></td>

<td><a href="'.$_SERVER['PHP_SELF'].'?last_id='.$word_id.'&amp;category_id='.$category_id.'&amp;value=bl'.$autoload_param.'">Block</a></td>

</tr>';

if ($autoload==10) { $tag10='<u>10s</u>'; } else { $tag10='10s'; }
if ($autoload==15) { $tag15='<u>15s</u>'; } else { $tag15='15s'; }
if ($autoload==30) { $tag30='<u>30s</u>'; } else { $tag30='30s'; }
if ($autoload==60) { $tag60='<u>1m</u>'; } else { $tag60='1m'; }
if ($autoload==180) { $tag180='<u>3m</u>'; } else { $tag180='3m'; }
if ($autoload==300) { $tag300='<u>5m</u>'; } else { $tag300='5m'; }

$all_text.='</table></div>

<p>Auto Load the loop every: <a href="'.$_SERVER['PHP_SELF'].'?autoload=10&amp;category_id='.$category_id.'">'.$tag10.'</a> &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?autoload=15&amp;category_id='.$category_id.'">'.$tag15.'</a> &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?autoload=30&amp;category_id='.$category_id.'">'.$tag30.'</a> &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?autoload=60&amp;category_id='.$category_id.'">'.$tag60.'</a> &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?autoload=180&amp;category_id='.$category_id.'">'.$tag180.'</a> &nbsp;&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?autoload=300&amp;category_id='.$category_id.'">'.$tag300.'</a>';

if (!empty($last_id)) { $all_text.='<p><a href="'.$_SERVER['PHP_SELF'].'?go_back='.$last_id.'&amp;category_id='.$category_id.'">[Go Back]</a>'; }

$all_text.='</td></tr></table>';

if (!empty($autoload)) { $head='<meta http-equiv="Refresh" content="'.$autoload.';url='.$_SERVER['PHP_SELF'].'?autoload='.$autoload.'&amp;last_id='.$word_id.'&amp;value=kp&amp;category_id='.$category_id.'">'; } else { $head=''; }

$stylesheet='<style type="text/css">
.flashcard a {
background:#fbfbfb;
float:left;
padding:0.8em 0.8em;
width: 100%;
border:1px solid #ececec;
}

.flashcard a:hover {
text-decoration:underline;
}
</style>';

$html_instance->add_parameter(
	array(
		'TEXT_CENTER'=>$all_text,
		'HEAD'=>"$stylesheet $head"
	));

$html_instance->process();

#

function increase_loop_words($how_many) {

	global $base_instance,$html_instance,$category_id;

	$how_many--;

	$userid=$base_instance->user;

	$added_new_words=0;

	if ($category_id > 0) {

		$sql='SELECT ID FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE user='.$userid.' AND category='.$category_id.' ORDER BY RAND()';

	} else {

		$sql='SELECT ID FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE user='.$userid.' ORDER BY RAND()';

	}

	$data=$base_instance->get_data($sql);

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;

		$data2=$base_instance->get_data('SELECT word_id FROM '.$base_instance->entity['KNOWLEDGE']['FLASHCARDS'].' WHERE word_id='.$ID.' AND user='.$userid.' AND category_id='.$category_id);

		if ($data2) { unset($data2); } else {

			$base_instance->query('INSERT INTO '.$base_instance->entity['KNOWLEDGE']['FLASHCARDS'].' (word_id, value, shown, user, word_loop, category_id) VALUES ('.$ID.',0,0,'.$userid.',1,'.$category_id.')');

			$added_new_words++;

		}

		if ($added_new_words > $how_many) { return; }

	}

	return;

}

?>