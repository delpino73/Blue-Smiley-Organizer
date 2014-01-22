<?php

session_start();

require 'class.base.php';
$base_instance=new base();

$main='';

$country=isset($_POST['country']) ? (int)$_POST['country'] : '';
$password=isset($_POST['password']) ? $_POST['password'] : '';
$username=isset($_POST['username']) ? $_POST['username'] : '';
$email=isset($_POST['email']) ? $_POST['email'] : '';
$firstname=isset($_POST['firstname']) ? $_POST['firstname'] : '';
$lastname=isset($_POST['lastname']) ? $_POST['lastname'] : '';
$newsletter=isset($_POST['newsletter']) ? $_POST['newsletter'] : '';

if (isset($_POST['save'])) {

	if (!$country) { $main.='<li> Country cannot be left blank'; $error=1; }

	if ($password) {

	if (strlen($password)<4) { $main.='<li> Password too short, at least 4 characters'; $error=1; }

	} else { $main.='<li> Password cannot be left blank'; $error=1; }

	if ($username) {

	$data=$base_instance->get_data('SELECT username FROM '.$base_instance->entity['USER']['MAIN'].' WHERE username="'.sql_safe($username).'"');

	if ($data) { $main.='<li> Username already taken'; $error=1; }

	if (strstr($username,' ')) { $main.='<li> No spaces allowed in username'; $error=1; }
	else if ($username && !preg_match('/^[0-9a-zA-ZäöüßÖÜÄ]+$/',$username)) { $main.='<li> Only numbers and letters in username allowed'; $error=1; }

	if (strlen($username)>16) { $main.='<li> Username is too long, only 16 characters allowed'; $error=1; }

	if (strlen($username)<3) { $main.='<li> Username is too short, it needs to be at least 3 characters long'; $error=1; }

	if (preg_match('/^[0-9]+$/',$username)) { $main.='<li> Username cannot only consist of numbers'; $error=1; }

	} else { $main.='<li> Username cannot be left blank'; $error=1; }

	if ($email) {

	$data=$base_instance->get_data('SELECT email FROM '.$base_instance->entity['USER']['MAIN'].' WHERE email="'.sql_safe($email).'"');

	if ($data) {

	$main='The entered email address <strong>'.$email.'</strong> has already been registered.<p>If you\'ve forgotten your password please click <a href="password-reminder.php">here</a> to reset your password.<p>';

	require 'template.html'; exit;

	}

	if (!stristr($email,'@')) { $main.='<li> Email Address is not valid'; $error=1; }

	} else { $main.='<li> Email address cannot be left blank'; $error=1; }

	if (empty($error)) {

	$IP=$base_instance->get_ip();

	$datetime=date('Y-m-d H:i:s');

	if ($country==1 or $country==4) { # Germany or Luxembourg

	$e1=3; # Amazon Deutschland
	$e2=46; # eBay Deutschland
	$e3=15; # IMDB deutsch
	$e4=18; # Leo Dict
	$e5=21; # Wiki (de)
	$e6=25; # Google Germany
	$e7=44; # Google Product Search Germany
	$e8=22; # Organizer Search

	}

	else if ($country==2) { # Austria

	$e1=3; # Amazon Deutschland
	$e2=53; # eBay Austria
	$e3=15; # IMDB deutsch
	$e4=18; # Leo Dict
	$e5=21; # Wiki (de)
	$e6=28; # Google Austria
	$e7=44; # Google Product Search Germany
	$e8=22; # Organizer Search

	}

	else if ($country==3) { # Switzerland

	$e1=3; # Amazon Deutschland
	$e2=52; # eBay Schweiz
	$e3=15; # IMDB deutsch
	$e4=18; # Leo Dict
	$e5=21; # Wiki (de)
	$e6=29; # Google Switzerland
	$e7=44; # Google Product Search Germany
	$e8=22; # Organizer Search

	}

	else if ($country==5) { # USA

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=23; # Google USA
	$e6=43; # Google Product Search USA
	$e7=22; # Organizer Search

	}

	else if ($country==7) { # Italy

	$e1=1; # Amazon USA
	$e2=50; # eBay Italy
	$e3=13; # IMDB (US)
	$e4=40; # Wikipedia (it)
	$e5=51; # Google Italy
	$e6=22; # Organizer Search

	}

	else if ($country==9) { # France

	$e1=4; # Amazon France
	$e2=9; # eBay France
	$e3=16; # IMDB (French)
	$e4=35; # Wikipedia (fr)
	$e5=48; # Google France
	$e6=22; # Organizer Search

	}

	else if ($country==14) { # Netherlands

	$e1=3; # Amazon Deutschland
	$e2=45; # eBay Netherlands
	$e3=13; # IMDB (US)
	$e4=39; # Wikipedia (Dutch)
	$e5=26; # Google Netherlands
	$e6=22; # Organizer Search

	}

	else if ($country==17) { # UK

	$e1=2; # Amazon UK
	$e2=8; # eBay UK
	$e3=14; # IMDB (UK)
	$e4=20; # Wikipedia (en)
	$e5=24; # Google UK
	$e6=42; # Google Product Search UK
	$e7=22; # Organizer Search

	}

	else if ($country==19) { # Australia

	$e1=1; # Amazon USA
	$e2=10; # eBay Australia
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=47; # Google Australia
	$e6=22; # Organizer Search

	}

	else if ($country==26) { # Canada

	$e1=6; # Amazon Canada
	$e2=12; # eBay Canada
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=30; # Google Canada
	$e6=22; # Organizer Search

	}

	else if ($country==28) { # India

	$e1=1; # Amazon USA
	$e2=11; # eBay India
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=66; # Google India
	$e6=22; # Organizer Search

	}

	else if ($country==30) { # Japan

	$e1=5; # Amazon Japan
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=36; # Wikipedia (ja)
	$e5=49; # Google Japan
	$e6=22; # Organizer Search

	}

	else if ($country==51) { # Ireland

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=57; # Google Ireland
	$e6=22; # Organizer Search

	}

	else if ($country==21) { # Taiwan

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=41; # Wikipedia (Chinese)
	$e5=69; # Google Taiwan
	$e6=22; # Organizer Search

	}

	else if ($country==16) { # Korea

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=70; # Google Korea
	$e6=22; # Organizer Search

	}

	else if ($country==43) { # Russia

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=80; # Wikipedia (ru)
	$e5=71; # Google Russia
	$e6=22; # Organizer Search

	}

	else if ($country==22) { # Greece

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=72; # Google Greece
	$e6=22; # Organizer Search

	}

	else if ($country==29) { # Israel

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=73; # Google Israel
	$e6=22; # Organizer Search

	}

	else if ($country==10) { # Singapore

	$e1=1; # Amazon USA
	$e2=60; # eBay Singapore
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=74; # Google Singapore
	$e6=22; # Organizer Search

	}

	else if ($country==57) { # Hong Kong

	$e1=1; # Amazon USA
	$e2=61; # eBay Hong Kong
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=67; # Google Hong Kong
	$e6=22; # Organizer Search

	}

	else if ($country==32) { # Sweden

	$e1=1; # Amazon USA
	$e2=62; # Tradera
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=65; # Google Sweden
	$e6=22; # Organizer Search

	}

	else if ($country==58) { # Thailand

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=63; # Wikipedia (th)
	$e5=63; # Google Thailand
	$e6=22; # Organizer Search

	}

	else if ($country==36) { # Argentina

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=33; # Wikipedia (es)
	$e5=68; # Google Argentina
	$e6=22; # Organizer Search

	}

	else if ($country==11) { # Spain

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=33; # Wikipedia (es)
	$e5=27; # Google Spain
	$e6=22; # Organizer Search

	}

	else if ($country==12) { # Poland

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=76; # Wikipedia (pl)
	$e5=75; # Google Poland
	$e6=22; # Organizer Search

	}

	else if ($country==35) { # Romania

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=78; # Wikipedia (ro)
	$e5=77; # Google Romania
	$e6=22; # Organizer Search

	}

	else { # other countries

	$e1=1; # Amazon USA
	$e2=7; # eBay USA
	$e3=13; # IMDB (US)
	$e4=20; # Wikipedia (en)
	$e5=23; # Google USA
	$e6=22; # Organizer Search

	}

	if (_ALLOW_FILE_UPLOAD==0) { $allow_file_upload=2; } else { $allow_file_upload=1; }

	$secure_password=sha1($password);

	$base_instance->query('INSERT INTO '.$base_instance->entity['USER']['MAIN'].' (datetime, email, username, firstname, lastname, user_password, IP, logins, country, lastlogin, font_face_main, font_face_navigation, font_size, background, color_main, color_navigation, newsletter_opt_in, dateformat, allow_file_upload, about_me, online_status) VALUES ("'.$datetime.'","'.$email.'","'.$username.'","'.$firstname.'","'.$lastname.'","'.$secure_password.'","'.$IP.'",0,"'.$country.'","'.$datetime.'",1,1,2,14,1,1,"'.$newsletter.'",1,"'.$allow_file_upload.'","",1)');

	$userid=mysql_insert_id();

	if (empty($e1)) { $e1=0; }
	if (empty($e2)) { $e2=0; }
	if (empty($e3)) { $e3=0; }
	if (empty($e4)) { $e4=0; }
	if (empty($e5)) { $e5=0; }
	if (empty($e6)) { $e6=0; }
	if (empty($e7)) { $e7=0; }
	if (empty($e8)) { $e8=0; }
	if (empty($e9)) { $e9=0; }
	if (empty($e10)) { $e10=0; }

	$base_instance->query('INSERT INTO '.$base_instance->entity['SEARCH']['MAIN'].' (user,element1,element2,element3,element4,element5,element6,element7,element8,element9,element10) VALUES ('.$userid.','.$e1.','.$e2.','.$e3.','.$e4.','.$e5.','.$e6.','.$e7.','.$e8.','.$e9.','.$e10.')');

	$base_instance->query('INSERT INTO '.$base_instance->entity['HOME']['MAIN'].' (user,title,element1,element2,element3,element4,element5,element6,element7,element8,element9,element10,element11,element12) VALUES ('.$userid.',"Home 1",5,13,10,38,6,36,28,40,0,0,0,0)');

	$url=$username.'/'.$secure_password;
	$encoded_url=base64_encode($url);

	#if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/login-'.$encoded_url; }	else { $url=_HOMEPAGE.'/autologin.php?code='.$encoded_url; }
	#if (_SHORT_URLS==1) { $url2=_HOMEPAGE.'/user-'.$username; }	else { $url2=_HOMEPAGE.'/show-about-me.php?username='.$username; }

	$msg='Hello '.$username.'!'."\n\n";
	$msg.='You have successfully created an account.'."\n\n";
	$msg.='You can efficiently organize links, contacts, diary entries, reminder, to-do lists with the Blue Smiley Organizer.'."\n\n";
	#$msg.='Please bookmark the following link to log into your account:'."\n\n".$url."\n\n";
	#$msg.='Your personal space with your blog and "About Me" page can be found here:'."\n\n".$url2."\n\n";
	$msg.='We are interested in your feedback. Please send us your comments or questions.'."\n\n";
	$msg.='For Live Help click here '._HOMEPAGE.'/live-support.php'."\n\n";

	$base_instance->send_email_from_admin('Welcome Email',$msg,$email);

	if (_NEW_USER_NOTIFY==1) {

	$mailheaders='From: '._ADMIN_SENDER_NAME.' <'._ADMIN_EMAIL.'>'."\n";
	$mailheaders.='Reply-To: '._ADMIN_EMAIL."\n";
	$mailheaders.='Content-Type: text/html; charset=utf-8'."\n";

	$country_name=$base_instance->country_array[$country];

	$text='Username: '.$username.'<br>';
	$text.='Firstname: '.$firstname.'<br>';
	$text.='Lastname: '.$lastname.'<br>';
	$text.='Email: '.$email.'<br>';
	$text.='Country: '.$country_name.'<br>';
	$text.='IP: '.$IP.'<p>';
	$text.=_SEPARATOR.'<br>';
	$text.=_SLOGAN.'<br>';
	$text.=_HOMEPAGE.'<br>';
	$text.='Email: '._ADMIN_EMAIL.'<br>';

	$ret=mail(_ADMIN_EMAIL,'Sign Up Notification (Blue Smiley Organizer)',$text,$mailheaders);

	}

	#

	if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/login-'.$encoded_url; }	else { $url=_HOMEPAGE.'/autologin.php?code='.$encoded_url; }

	$http_user_agent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

	if (stristr($http_user_agent,'firefox')) { $ff=1; }
	else if (stristr($http_user_agent,'opera')) { $opera=1; }

	if (isset($ff) or isset($opera)) {

	$text='Autologin enables you to log into the organizer with just one click. Drag and drop the following link onto your toolbar to create your autologin link.';

	} else {

	$text='Autologin enables you to log into the organizer with just one click. To do this bookmark the following link. (Right Mouse Click and choose "Add to Favorites". Then save into "Links" Folder.)';

	}

	$main='<strong>Your account has been created!</strong><p>

<table cellpadding="5" cellspacing="0" border=1 class="pastel" bgcolor="#ffffff">
<tr><td>'.$text.'<p>

<strong><a href="'.$url.'"><u>Autologin</u></a></strong><p>

Or copy and paste the following link:<p>

<small>'.$url.'</small>

</td></tr><tr><td>

To quickly add links, drag and drop the following link onto your toolbar.<p>

<a href="javascript:void(window.open(\''._HOMEPAGE.'/autolink.php?code='.$encoded_url.'&url=\'+location.href,\'_blank\',\'width=550,height=525,status=no,resizable=yes,scrollbars=auto\'))"><u>Add Link</u></a><p>

If you want to bookmark the website you are currently on just click this link.<br>

</td></tr>

<tr><td align="center"><br>

<form action="'._HOMEPAGE.'/autologin.php?code='.$encoded_url.'" method="post">
<input type="SUBMIT" value="CLICK HERE TO LOG IN" name="save">
</form>

</td></tr></table>';

	require 'template.html'; exit;

	#

	}

} else { $username=''; $firstname=''; $lastname=''; $email=''; $password=''; $country=''; }

$header='Sign up';

# country

$country_array=$base_instance->country_array;

asort($country_array); reset($country_array);

$select_box='&nbsp;<select name="country"><option>';

while (list($id,$country_name)=each($country_array)) {

if ($id==$country) { $select_box.="<option selected value=$id>$country_name"; }
else { $select_box.="<option value=$id>$country_name"; }

}

$select_box.='</select>';

# newsletter

$select_box2='&nbsp;<select name="newsletter">';

if ($newsletter==2) { $select_box2.='<option value=1>Yes, please
<option selected value=2>No, thanks'; }

else { $select_box2.='<option selected value=1>Yes, please
<option value=2>No, thanks'; }

$select_box2.='</select>';

#

$main.='<p>

<form action="sign-up.php" method="post">

<table cellpadding="5" cellspacing="0" class="pastel">

<tr><td align="right"><b>Username:</b></td><td align="left">&nbsp;<input type="text" name="username" size="35" value="'.$username.'"></td></tr>

<tr><td align="right"><b>Firstname:</b></td><td align="left">&nbsp;<input type="text" name="firstname" size="35" value="'.$firstname.'"></td></tr>

<tr><td align="right"><b>Lastname:</b></td><td align="left">&nbsp;<input type="text" name="lastname" size="35" value="'.$lastname.'"></td></tr>

<tr><td align="right"><b>Email:</b></td><td align="left">&nbsp;<input type="text" name="email" size="35" value="'.$email.'"></td></tr>

<tr><td align="right"><b>Password:</b></td><td align="left">&nbsp;<input type="password" name="password" size="35" value="'.$password.'"></td></tr>

<tr><td align="right"><b>Country:</b></td><td>'.$select_box.'</td></tr>

<tr><td align="right"><b>Newsletter:</b></td><td>'.$select_box2.'</td></tr>

<tr><td colspan=2 align="center"><input type="SUBMIT" value="Create Account" name="save"></td></tr></form></td></tr></table>

<br><br>';

$title='Sign Up';

require 'template.html';

?>