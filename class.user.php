<?php

/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

class user {

function get_userinfo($userid) {

	global $base_instance;

	$sql='SELECT * FROM '.$base_instance->entity['USER']['MAIN'].' WHERE ID='.$userid;

	$data=$base_instance->get_data($sql);

	return $data;

}

function check_for_admin() {

	global $base_instance;

	if ($base_instance->user!=_ADMIN_USERID) { exit; }

}

function delete_user($userid) {

	global $base_instance;

	$userid=(int)$userid;

	if (empty($userid)) { exit; }

	if ($userid==_ADMIN_USERID) { $base_instance->show_message('Admin account cannot be deleted'); exit; }

	if ($userid==_GUEST_USERID) { $base_instance->show_message('Guest account cannot be deleted'); exit; }

	ignore_user_abort(true);

	$base_instance->query('DELETE FROM '.$base_instance->entity['USER']['MAIN'].' WHERE ID='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DIARY']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['KNOWLEDGE']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['KNOWLEDGE']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['NOTE']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['NOTE']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['LINK']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['LINK']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['TO_DO']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['TO_DO']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['SESSION']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['HOME']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['CONTACT']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['CONTACT']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['REMINDER']['DAYS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['REMINDER']['DATE'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['REMINDER']['WEEKDAY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['REMINDER']['HOURS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['FILE']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['FILE']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['BLOG']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['BLOG']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['BLOG']['COMMENTS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['SEARCH']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['RSS']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['MAIN'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['CATEGORY'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['CHECKBOX_FIELDS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['CHECKBOX_ITEMS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['CHECKBOX_VALUES'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['SELECT_FIELDS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['SELECT_ITEMS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['SELECT_VALUES'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['NUMBER_FIELDS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['NUMBER_VALUES'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['TEXT_FIELDS'].' WHERE user='.$userid);

	$base_instance->query('DELETE FROM '.$base_instance->entity['DATABASE']['TEXT_VALUES'].' WHERE user='.$userid);

	system('rm -rf ./upload/'.$userid.'/');

}

}

?>