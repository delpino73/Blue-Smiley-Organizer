<?php

require 'class.base.php';
require 'class.user.php';
require 'class.html.php';

$base_instance=new base();
$user_instance=new user();
$html_instance=new html();

$user_instance->check_for_admin();

if (isset($_GET['delete_log'])) { $base_instance->query('DELETE FROM '.$base_instance->entity['LOG']['MAIN']); }

if (isset($_GET['user'])) {

$query1='WHERE user="'.$_GET['user'].'"';
$query2='AND user="'.$_GET['user'].'"';
$title='Summary (User)';
$refresh='<a href="show-log-summary.php?user='.$_GET['user'].'">[Refresh]</a> &nbsp;&nbsp; <a href="show-log-summary.php">[Show Summary]</a>';

} else if (isset($_GET['IP'])) {

$query1='WHERE IP="'.$_GET['IP'].'"';
$query2='AND IP="'.$_GET['IP'].'"';
$title='Summary (User)';
$refresh='<a href="show-log-summary.php?IP='.$_GET['IP'].'">[Refresh]</a> &nbsp;&nbsp; <a href="show-log-summary.php">[Show Summary]</a>';

}

else {

$query1=''; $query2=''; $title='Summary';
$refresh='<a href="show-log-summary.php">[Show Summary]</a>';

}

$data=$base_instance->get_data('SELECT IP,user,COUNT(*) AS cnt FROM '.$base_instance->entity['LOG']['MAIN'].' '.$query1.' GROUP BY user ORDER BY cnt DESC');

$text='<table border cellspacing=0 cellpadding=5 class="pastel"><tr><td align="center"><strong>Username</strong></td><td><strong>Actions</strong></td><td align="center"><strong>Last Reminded</strong></td><td><strong>Logins</strong></td><td align="center"><strong>IP Address</strong></td><td colspan="3">&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

$cnt=$data[$index]->cnt;
$user=$data[$index]->user;
$IP=$data[$index]->IP;

$data2=$base_instance->get_data("SELECT username,logins,last_reminded FROM {$base_instance->entity['USER']['MAIN']} WHERE ID='$user'");

if ($data2) {

$username=$data2[1]->username;
$logins=$data2[1]->logins;
$last_reminded=$data2[1]->last_reminded;

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$last_reminded,$dd);
$last_reminded=$dd[3].'.'.$dd[2].'.'.$dd[1];
if ($last_reminded=='00.00.0000') { $last_reminded=''; }

$text.='<tr bgcolor="#ffffff" onMouseOver=\'this.style.background="#f2f2f2"\' onMouseOut=\'this.style.background="#ffffff"\'><td><a href="show-user.php?username='.$username.'" onMouseOver="window.status=\'\'; return true">'.$username.'</a></td><td>'.$cnt.'</td><td>'.$last_reminded.'</td><td>'.$logins.'</td><td><a href="show-log-summary.php?IP='.$IP.'">['.$IP.']</a></td><td><a href="show-log.php?user='.$user.'">[Page Loads]</a></td><td><a href="show-log-summary.php?user='.$user.'">[Summary]</a></td></tr>';

} else {

$text.='<tr bgcolor="#ffffff" onMouseOver=\'this.style.background="#f2f2f2"\' onMouseOut=\'this.style.background="#ffffff"\'><td>[No Username]</td><td>'.$cnt.'</td><td></td><td></td><td></td><td><a href="show-log.php?user='.$user.'">[Page Loads]</a></td><td><a href="show-log-summary.php?user='.$user.'">[Summary]</a></td></tr>';

}

}

$text.='</table><br>';

# referrer

$data=$base_instance->get_data('SELECT referrer, COUNT(*) AS cnt FROM '.$base_instance->entity['LOG']['MAIN'].' WHERE (referrer NOT LIKE "'._HOMEPAGE.'%") AND (referrer NOT LIKE "") '.$query2.' GROUP BY referrer ORDER BY cnt DESC');

if ($data) {

$text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td width="20"><strong>Total</strong></td><td><strong>Referrer</strong></td><td colspan="2">&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

$cnt=$data[$index]->cnt;
$referrer=$data[$index]->referrer;

$ref_short=substr($referrer,0,115);
$ref_encoded=base64_encode($referrer);

$text.='<tr bgcolor="#ffffff" onMouseOver=\'this.style.background="#f2f2f2"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$cnt.'</td><td><a href="load-url.php?url_encoded='.$ref_encoded.'" target="_blank">'.$ref_short.'</a></td><td width="40"><a href="show-log.php?referrer='.$ref_encoded.'">[Show]</a></td></tr>';

}

$text.='</table><br>';

}

$text.='<a href="show-log-summary.php?delete_log=1"><font color="#FF0000">[Delete Activity Log]</font></a><p>';

# request_uri

$data=$base_instance->get_data('SELECT request_uri,COUNT(*) AS cnt FROM '.$base_instance->entity['LOG']['MAIN'].' WHERE (request_uri NOT LIKE "") '.$query2.' GROUP BY request_uri ORDER BY cnt DESC');

if ($data) {

$text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td width="20"><strong>Total</strong></td><td><strong>Request URI</strong></td><td colspan="2">&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

$cnt=$data[$index]->cnt;
$request_uri=$data[$index]->request_uri;

$request_uri_short=substr($request_uri,0,115);

$req_encoded=base64_encode($request_uri);
$req_encoded2=base64_encode(_HOMEPAGE.'/'.$request_uri);

$text.='<tr bgcolor="#ffffff" onMouseOver=\'this.style.background="#f2f2f2"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$cnt.'</td><td><a href="load-url.php?url_encoded='.$req_encoded2.'" target="_blank">'.$request_uri_short.'</a></td><td width="40"><a href="show-log.php?request_uri='.$req_encoded.'">[Show]</a></td></tr>';

}

$text.='</table><br>';

}

# user_agent

$data=$base_instance->get_data('SELECT user_agent,COUNT(*) AS cnt FROM '.$base_instance->entity['LOG']['MAIN'].' '.$query1.' GROUP BY user_agent ORDER BY cnt DESC');

if ($data) {

$text.='<table width="100%" border cellspacing=0 cellpadding=5 class="pastel"><tr><td width="20"><strong>Total</strong></td><td><strong>User Agent</strong></td><td colspan="2">&nbsp;</td></tr>';

for ($index=1; $index <= sizeof($data); $index++) {

$cnt=$data[$index]->cnt;
$user_agent=$data[$index]->user_agent;

$user_agent_encoded=base64_encode($user_agent);
$user_agent=htmlentities($user_agent);

$text.='<tr bgcolor="#ffffff" onMouseOver=\'this.style.background="#f2f2f2"\' onMouseOut=\'this.style.background="#ffffff"\'><td>'.$cnt.'</td><td>'.$user_agent.'</td><td width="40"><a href="show-log.php?user_agent='.$user_agent_encoded.'">[Show]</a></td></tr>';

}

$text.='</table>';

}

#

$data=$base_instance->get_data('SELECT datetime FROM '.$base_instance->entity['LOG']['MAIN'].' '.$query1.' ORDER BY datetime ASC LIMIT 1');

if ($data) {

$datetime=$data[1]->datetime;

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$datetime,$dd);

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

$datetime_converted1=$dd[3].'. '.$month.' '.$dd[1].' ('.$dd[4].':'.$dd[5].') -';

} else { $datetime_converted1=''; }

#

$data=$base_instance->get_data('SELECT datetime FROM '.$base_instance->entity['LOG']['MAIN'].' '.$query1.' ORDER BY datetime DESC LIMIT 1');

if ($data) {

$datetime=$data[1]->datetime;

preg_match("/([0-9]+)-([0-9]+)-([0-9]+) ([0-9]+):([0-9]+):([0-9]+)/",$datetime,$dd);

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

$datetime_converted2=$dd[3].'. '.$month.' '.$dd[1].' ('.$dd[4].':'.$dd[5].')';

} else { $datetime_converted2=''; }

#

$html_instance->add_parameter(
array(
'TEXT'=>'<br><div align="center">
<h1>'.$title.'</h1><table width="95%" cellspacing=5 cellpadding=5 class="pastel2" bgcolor="#ffffff"><tr><td><a href="show-log-summary.php?delete_log=1"><font color="#FF0000">[Delete Activity Log]</font></a> &nbsp;&nbsp; '.$refresh.' &nbsp;&nbsp; <a href="show-log.php">[Show Page Loads]</a> &nbsp;&nbsp; '.$datetime_converted1.' '.$datetime_converted2.'<p>'.$text.'</td></tr></table></div>'
));

$html_instance->process();

?>