<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

$knowledge_id=isset($_GET['knowledge_id']) ? (int)$_GET['knowledge_id'] : exit;
$value=isset($_GET['value']) ? $_GET['value'] : '';

if ($knowledge_id) {

	$rand=mt_rand(-100,100); $now=time();

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

	$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET value=$new_value,shown=shown+1,last_shown=$now,word_loop=0 WHERE word_id='$knowledge_id' AND user='$userid' AND category_id=0");

	$base_instance->query("UPDATE {$base_instance->entity['KNOWLEDGE']['FLASHCARDS']} SET word_loop=1 WHERE word_loop=0 AND user=$userid AND category_id=0 AND value > -1 AND value < $now ORDER BY value LIMIT 1"); # find a new word from active words that is due and add to loop

	$aff_rows=mysqli_affected_rows($base_instance->db_link);

	if ($aff_rows==0) { increase_loop_words(1); } # if nothing found add a new word to active words and add to loop

	echo '<head>',_CSS_NAV,'<meta http-equiv="refresh" content="10;url=status.php"></head><font size="2">Removed from Loop</font>';

}

#

function increase_loop_words($how_many) {

	global $base_instance,$html_instance;

	$how_many--;

	$userid=$base_instance->user;

	$added_new_words=0;

	$sql='SELECT ID FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE user='.$userid.' ORDER BY RAND()';

	$data=$base_instance->get_data($sql);

	for ($index=1; $index <= sizeof($data); $index++) {

		$ID=$data[$index]->ID;

		$data2=$base_instance->get_data('SELECT word_id FROM '.$base_instance->entity['KNOWLEDGE']['FLASHCARDS'].' WHERE word_id='.$ID.' AND user='.$userid.' AND category_id=0');

		if ($data2) { unset($data2); } else {

			$base_instance->query('INSERT INTO '.$base_instance->entity['KNOWLEDGE']['FLASHCARDS'].' (word_id, value, shown, user, word_loop, category_id) VALUES ('.$ID.',0,0,'.$userid.',1,0)');

			$added_new_words++;

		}

		if ($added_new_words > $how_many) { return; }

	}

	return;

}

?>