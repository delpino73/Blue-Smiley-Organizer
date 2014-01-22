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

		@mysql_connect(_DB_HOST,_DB_USER,_DB_PW) or die('Could not connect to database');
		mysql_select_db(_DB_NAME) or die('Could not find database');

		$sid=isset($_COOKIE['sid']) ? (int)$_COOKIE['sid'] : 0;

		if ($sid!=0) {

			$data=$this->get_data('SELECT * FROM '.$this->entity['SESSION']['MAIN'].' WHERE session_id='.$sid);

			if (!$data) {

				setcookie('sid','','631213200','/'); # delete cookie
				$this->user=0;

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

		$res=mysql_query($statement);

		if (!$res) { $this->error_report($statement); }

		return $res;

	}

	function get_data($statement, $debug='') {

		if ((_DEBUG==1 or $debug==1) && $this->user==_ADMIN_USERID) { echo $statement.'<br>'; }

		$res=mysql_query($statement);

		if (!$res) { $this->error_report($statement); }

		$rows=mysql_num_rows($res);

		for ($index=1; $index <= $rows; $index++) {
			$data[$index]=mysql_fetch_object($res);
		}

		mysql_free_result($res);

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

		$text=preg_replace("/\[http:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!',:]+) ([-:.a-zA-Z0-9 ]+)\]/e","'<a href=\"load-url.php?url_encoded='.base64_encode('\\1').'\" target=\"_blank\" rel=\"nofollow\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>'.substr('\\2', 0,60).'</u></a>'",$text); # with link text

		$text=preg_replace("/\[https:\/\/([a-zA-Z0-9_\-.\/~?=#&+%!',:]+) ([-:.a-zA-Z0-9 ]+)\]/e","'<a href=\"load-url.php?url_encoded='.base64_encode('\\1').'\" target=\"_blank\" rel=\"nofollow\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>'.substr('\\2', 0,60).'</u></a>'",$text); # with link text

		$text=preg_replace("/([\[]*)(http:\/\/[a-zA-Z0-9_\-.\/~?=#&+%!',:@]+[\/=a-zA-Z0-9]{1})([\]]*)/e","'<img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <a href=\"load-url.php?url_encoded='.base64_encode('\\2').'\" target=\"_blank\"><u>'.substr('\\2', 0,60).'</u></a>'",$text);

		$text=preg_replace("/([\[]*)(https:\/\/[a-zA-Z0-9_\-.\/~?=#&+%!',:@]+[\/=a-zA-Z0-9]{1})([\]]*)/e","'<img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <a href=\"load-url.php?url_encoded='.base64_encode('\\2').'\" target=\"_blank\"><u>'.substr('\\2', 0,60).'</u></a>'",$text);

		# internal links

		$text=preg_replace("/\[k([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"edit-knowledge.php?knowledge_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>KNOWLEDGE '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[d([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"add-diary.php?diary_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>DIARY '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[c([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"edit-contact.php?contact_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>CONTACT '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[t([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"edit-to-do.php?to_do_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>TO-DO '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[n([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"edit-note.php?note_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>NOTE '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[b([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"edit-blog.php?blog_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>BLOG '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[f([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"show-file.php?file_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>FILE '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[i([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"show-file.php?file_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>IMAGE '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[l([0-9]+)([a-zA-Z0-9_\-.\/?=#&+%!', ]*)\]/e","'<a href=\"show-live-chat-complete.php?chat_id='.('\\1').'\"><img src=\"pics/link.gif\" border=\"0\" alt=\"\"> <u>LIVE CHAT '.('\\2').'</u></a>'",$text);

		$text=preg_replace("/\[image-([a-zA-Z0-9]+)\]/e","'<img src=\"get-image.php?id='.('\\1').'\" alt=\"\">'",$text);

		$text=preg_replace("/\[newsletterbox-([0-9]+)\]/e","'<table cellspacing=0 cellpadding=10 class=\"pastel\" bgcolor=\"#ffffff\" style=\"border:1px solid #dcdcdc\"><tr><td><strong>Newsletter Subscription:</strong><p><form action=\"add-blog-subscriber.php\" method=\"post\"><input type=\"hidden\" name=\"category_id\" value=\"'.('\\1').'\"><strong>Email:</strong> <input type=\"text\" name=\"email\" size=\"30\"><br><br><input name=\"subscribe\" type=\"radio\" value=\"1\" checked><strong>Subscribe</strong><br><input name=\"subscribe\" type=\"radio\" value=\"2\"><strong>Unsubscribe</strong> &nbsp;&nbsp; <input type=\"submit\" value=\"Send\"></form><br><strong><font size=\"1\">We have a strict No Spam Policy</font></strong></td></tr></table>'",$text);

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
	if (function_exists('mysql_real_escape_string')) { $value=mysql_real_escape_string($value); }
	else { $value=addslashes($value); }

	return $value;

}

function convert_square_bracket($text) {

	$text=str_replace('<','&lt;',$text);
	$text=str_replace('>','&gt;',$text);

	return $text;

}

?>