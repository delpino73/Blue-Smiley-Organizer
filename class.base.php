<?php

/**************************************************************/
/*                    Blue Smiley Organizer                   */
/*       Written by Oliver Antosch - antosch@gmail.com        */
/*                http://www.bookmark-manager.com/            */
/**************************************************************/

if (file_exists('config.php')) { require 'config.php'; } else { header('Location: setup.php'); }

if (isset($flush)) { ob_implicit_flush(); } else if (_GZIP==1) { ob_start('ob_gzhandler'); }

class base {

	var $user, $color_main, $background, $font_face_main, $font_size, $color_navigation, $font_face_navigation;

	function base() {

		require 'inc.vars.php';

		$this->db_link=mysqli_connect(_DB_HOST,_DB_USER,_DB_PW,_DB_NAME) or die('Error '.mysqli_error($link));

		$sid=isset($_COOKIE['sid']) ? (int)$_COOKIE['sid'] : 0;

		if ($sid!=0) {

			$data=$this->get_data('SELECT * FROM '.$this->entity['SESSION']['MAIN'].' WHERE session_id='.$sid);

			if (!$data) {

				setcookie('sid','','631213200','/'); # delete cookie
				$this->user=0;

				date_default_timezone_set('Europe/London');

			} else {

				$this->user=$data[1]->user;
				$this->lastlogin=$data[1]->lastlogin;
				$this->timezone=$data[1]->timezone;
				$this->dateformat=$data[1]->dateformat;
				$this->font_face_navigation=$data[1]->font_face_navigation;
				$this->font_face_main=$data[1]->font_face_main;
				$this->font_size=$data[1]->font_size;
				$this->background=$data[1]->background;
				$this->color_navigation=$data[1]->color_navigation;
				$this->color_main=$data[1]->color_main;
				$this->allow_file_upload=$data[1]->allow_file_upload;

				if ($this->timezone > 0 && $this->timezone < 13) { date_default_timezone_set('Etc/GMT+'.$this->timezone); }
				else if ($this->timezone < 0 or $this->timezone==0) { date_default_timezone_set('Etc/GMT'.$this->timezone); }
				else if ($this->timezone==13) { date_default_timezone_set('Europe/London'); }
				else if ($this->timezone==14) { date_default_timezone_set('Europe/Berlin'); }
				else if ($this->timezone==15) { date_default_timezone_set('US/Pacific'); }
				else if ($this->timezone==16) { date_default_timezone_set('US/Mountain'); }
				else if ($this->timezone==17) { date_default_timezone_set('US/Central'); }
				else if ($this->timezone==18) { date_default_timezone_set('US/Eastern'); }
				else if ($this->timezone==19) { date_default_timezone_set('Asia/Jakarta'); }
				else if ($this->timezone==20) { date_default_timezone_set('Hongkong'); }
				else if ($this->timezone==21) { date_default_timezone_set('Japan'); }
				else if ($this->timezone==22) { date_default_timezone_set('Israel'); }
				else { date_default_timezone_set('Europe/London'); }

			}

		}

		require 'inc.theme.php';

		//if (_LOG_ACTIVITY==1 && $this->user!=_ADMIN_USERID && !eregi('status',$_SERVER['REQUEST_URI'])) { $this->log_activity(); }

	}

	function log_activity() {

		@putenv('TZ=Europe/London');

		$IP=$this->get_ip();

		$datetime=date('Y-m-d H:i:s');

		if (!empty($GLOBALS['_REQUEST'])) {

			$globals_request=print_r($GLOBALS['_REQUEST'],true);
			$globals_request=substr($globals_request,12,-3);

		}

		if (!empty($_SERVER['REQUEST_URI'])) {

			$request_uri=substr($_SERVER['REQUEST_URI'],1);

		}

		$http_user_agent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$referrer=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		$lang=isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? '['.$_SERVER['HTTP_ACCEPT_LANGUAGE'].']' : '';

		$this->query('INSERT INTO '.$this->entity['LOG']['MAIN'].' (datetime,user,request_uri,globals_request,user_agent,IP,referrer) VALUES ("'.$datetime.'","'.$this->user.'","'.sql_safe($request_uri).'","'.sql_safe($globals_request).'","'.sql_safe($http_user_agent).' '.sql_safe($lang).'","'.sql_safe($IP).'","'.sql_safe($referrer).'")');

	}

	function query($statement) {

		if (_DEBUG==1 && $this->user==_ADMIN_USERID) { echo $statement.'<br>'; }

		$res=mysqli_query($this->db_link, $statement);

		if (!$res) { $this->error_report($statement); }

		return $res;

	}

	function get_data($statement, $debug='') {

		if ((_DEBUG==1 or $debug==1) && $this->user==_ADMIN_USERID) { echo $statement.'<br>'; }

		$res=mysqli_query($this->db_link, $statement);

		if (!$res) { $this->error_report($statement); }

		$rows=mysqli_num_rows($res);

		for ($index=1; $index <= $rows; $index++) {
			$data[$index]=mysqli_fetch_object($res);
		}

		mysqli_free_result($res);

		if (isset($data)) { return $data; }

	}

	function error_report($sql_query) {

		$errormsg=mysql_error();

		if ($this->user==_ADMIN_USERID) {

			echo '<table bgcolor="#ffffff" cellpadding="10"><tr><td><b>SQL Query</b></td><td>',$sql_query,'</td></tr><tr><td><b>Errormessage</b></td><td>',$errormsg,'</td></tr><tr><td colspan="2">If you have updated the script recently make sure to run <a href="setup.php" target="_blank">setup.php</a>. If that doesn\'t help try to repair database or <a href="http://www.bookmark-manager.com/contact-us.php" target="_blank">contact developer here</a>.</td></tr></table>'; exit;

		}

		else if (_EMAIL_ERROR_REPORT==1) {

			$datetime=date('Y-m-d H:i:s');
			$request_uri=substr($_SERVER['REQUEST_URI'],1);

			$referrer=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			$http_user_agent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

			$globals_request=print_r($GLOBALS['_REQUEST'],true);
			$globals_request=substr($globals_request,12,-3);

			$IP=$this->get_ip();

			$error_and_query="QUERY: $sql_query\nERRORMESSAGE: $errormsg\nREQUEST URI: $request_uri\nDATETIME: $datetime\nREFERRER: $referrer\nUSER AGENT: $http_user_agent\nREMOTE ADDR: $IP\nVARS: $globals_request\n";

			$this->send_email_from_admin('SQL Error Notification',$error_and_query,_ADMIN_EMAIL);

			header('HTTP/1.1 404 Not Found');

			$this->show_message('System error','Error report has been sent to the admin. Please try again later.');

		} else {

			header('HTTP/1.1 404 Not Found');

			$this->show_message('System error','Please try again later. Notify admin if this is a permanent error.');

		}

	}

	function show_message($header, $text='', $back='') {

		$body=isset($this->para['BODY']) ? ' '.$this->para['BODY'] : '';

		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');

		if ($header) { $header2='<p><span class="header">'.$header.'</span><p>'; } else { $header2=''; }

		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><head><meta http-equiv="content-type" content="text/html;charset=utf-8">',_CSS,'<title>',$header,'</title></head><body ',_BACKGROUND,'',$body,'><br><div align="center"><table width=400 cellpadding=4 cellspacing=0 class="pastel2" bgcolor="#ffffff"><tr><td><div align="center">',$header2,'',$text,'</div><p>';

		if ($back) { echo '<div align="Center"><a href="javascript:history.go(-1)" onMouseOver="window.status=\'\'; return true">[Back]</a></div>'; }

		echo '</td></tr></table></div><br></body>'; exit;

	}

	function get_username($userid) {

		$data=$this->get_data('SELECT username FROM '.$this->entity['USER']['MAIN'].' WHERE ID='.$userid);

		if (!empty($data)) { $username=$data[1]->username; } else { $username=''; }

		return $username;

	}

	function get_userid() {

		$userid=$this->user;

		if (!$userid) { header('Location: login.php'); exit; }

		return $userid;

	}

	function send_email_from_admin($subject, $text, $email_receiver) {

		if ($email_receiver) {

			$mailheaders='From: '._ADMIN_SENDER_NAME.' <'._ADMIN_EMAIL.'>'."\n".'Reply-To: '._ADMIN_EMAIL."\n";

			$msg=$text."\n"._SEPARATOR."\n"._EMAIL_ADVERT_TEXT."\n"._SEPARATOR."\n"._SLOGAN."\n"._HOMEPAGE."\n".'Email: '._ADMIN_EMAIL."\n";

			mail($email_receiver, $subject, $msg, $mailheaders);

		}

	}

	function insert_links($text) {

		$text=preg_replace_callback('/\[https:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!,:]+) ([-:.a-zA-Z0-9 ]+)\]/',function($m) { return '<a href="load-url.php?url_encoded='.base64_encode($m[1]).'" target="_blank"><u>'.substr($m[2],0,60).'</u></a>';},$text);

		$text=preg_replace_callback('/\[http:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!,:]+) ([-:.a-zA-Z0-9 ]+)\]/',function($m) { return '<a href="load-url.php?url_encoded='.base64_encode($m[1]).'" target="_blank"><u>'.substr($m[2],0,60).'</u></a>';},$text);

		$text=preg_replace_callback('/(https|http):\/\/([a-zA-Z0-9_\-.\/~?=#&+%!]+)/',function($m) { return '<a href="load-url.php?url_encoded='.base64_encode($m[0]).'" target="_blank"><u>'.substr($m[0],0,90).'</u></a>';},$text);

		$text=preg_replace_callback('/\[t([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!, ]*)\]/',function($m) { return '<a href="edit-to-do.php?to_do_id='.$m[1].'"><u>TO-DO '.$m[2].'</u></a>';},$text);

		$text=preg_replace_callback('/\[k([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!, ]*)\]/',function($m) { return '<a href="edit-knowledge.php?knowledge_id='.$m[1].'"><u>KNOWLEDGE '.$m[2].'</u></a>';},$text);

		$text=preg_replace_callback('/\[d([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!, ]*)\]/',function($m) { return '<a href="add-diary.php?diary_id='.$m[1].'"><u>DIARY '.$m[2].'</u></a>';},$text);

		$text=preg_replace_callback('/\[c([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!, ]*)\]/',function($m) { return '<a href="edit-contact.php?contact_id='.$m[1].'"><u>CONTACT '.$m[2].'</u></a>';},$text);

		$text=preg_replace_callback('/\[n([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!, ]*)\]/',function($m) { return '<a href="edit-note.php?note_id='.$m[1].'"><u>NOTE '.$m[2].'</u></a>';},$text);

		$text=preg_replace_callback('/\[b([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!, ]*)\]/',function($m) { return '<a href="edit-blog.php?blog_id='.$m[1].'"><u>BLOG '.$m[2].'</u></a>';},$text);

		$text=preg_replace_callback('/\[f([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!, ]*)\]/',function($m) { return '<a href="show-file.php?file_id='.$m[1].'"><u>FILE '.$m[2].'</u></a>';},$text);

		return $text;

}

	function get_ip() {

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{ $IP=$_SERVER['HTTP_X_FORWARDED_FOR']; }
		else if (isset($_SERVER['REMOTE_ADDR']))
		{ $IP=$_SERVER['REMOTE_ADDR']; }

		return $IP;

	}

	function convert_date($datetime) {

		global $base_instance;

		preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$datetime,$dd);

		if (empty($base_instance->dateformat) or $base_instance->dateformat==1) {

			if ($dd[2]==1) { $month='Jan'; }
			else if ($dd[2]==2) { $month='Feb'; }
			else if ($dd[2]==3) { $month='Mar'; }
			else if ($dd[2]==4) { $month='Apr'; }
			else if ($dd[2]==5) { $month='May'; }
			else if ($dd[2]==6) { $month='Jun'; }
			else if ($dd[2]==7) { $month='Jul'; }
			else if ($dd[2]==8) { $month='Aug'; }
			else if ($dd[2]==9) { $month='Sept'; }
			else if ($dd[2]==10) { $month='Oct'; }
			else if ($dd[2]==11) { $month='Nov'; }
			else if ($dd[2]==12) { $month='Dec'; }

			$datetime_converted=$dd[3].'. '.$month.' '.$dd[1];

		}

		else if ($base_instance->dateformat==2) {

			$datetime_converted="$dd[3].$dd[2].$dd[1]"; # European format

		}

		else if ($base_instance->dateformat==3) {

			$datetime_converted=$dd[2].'/'.$dd[3].'/'.$dd[1]; # American format

		}

		else {

			if ($dd[2]==1) { $month='Jan'; }
			else if ($dd[2]==2) { $month='Feb'; }
			else if ($dd[2]==3) { $month='Mar'; }
			else if ($dd[2]==4) { $month='Apr'; }
			else if ($dd[2]==5) { $month='May'; }
			else if ($dd[2]==6) { $month='Jun'; }
			else if ($dd[2]==7) { $month='Jul'; }
			else if ($dd[2]==8) { $month='Aug'; }
			else if ($dd[2]==9) { $month='Sept'; }
			else if ($dd[2]==10) { $month='Oct'; }
			else if ($dd[2]==11) { $month='Nov'; }
			else if ($dd[2]==12) { $month='Dec'; }

			$datetime_converted=$dd[3].'. '.$month.' '.$dd[1];

		}

		return $datetime_converted;

	}

}

#

function sql_safe($value) {

	if (get_magic_quotes_gpc()) { $value=stripslashes($value); }

	$value=addslashes($value);

	return $value;

}

function convert_square_bracket($text) {

	$text=str_replace('<','&lt;',$text);
	$text=str_replace('>','&gt;',$text);

	return $text;

}

?>