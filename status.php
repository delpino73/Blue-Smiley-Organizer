<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->get_userid();

if (isset($_GET['initial'])) {

$data=$base_instance->get_data("SELECT logins,lastlogin,online_status FROM organizer_user WHERE ID=$userid");

$user_logins=$data[1]->logins;
$user_lastlogin=$data[1]->lastlogin;
$online_status=$data[1]->online_status;

if ($online_status==3) { $online_status='<tr><td><font size="2"><b><u><a href="edit-online-status.php" target="main">Status:</a></u></b></font> <font color="#FF0000" size="1">Offline</font></td></tr>'; }
else { $online_status=''; }

$datetime_converted=$base_instance->convert_date($user_lastlogin);

$stats='<table cellpadding="0">'.$online_status.'<tr><td><font size="2"><b><u><a href="show-account-stats.php" target="main">Logins:</a></u></b></font> <font size="1">'.$user_logins.'</font></td>
</tr><tr><td><font size="2"><b><u><a href="show-account-stats.php" target="main">Last Login:</a></u></b></font><br><font size="1">'.$datetime_converted.'</font></td></tr></table>';

} else { $stats=''; }

echo '<head><meta http-equiv="cache-control" content="no-cache">
',_CSS_NAV,'
<script language="JavaScript" type="text/javascript">
<!--

function createRequestObject() {

try { var requester=new XMLHttpRequest(); }
catch (error) {
try { var requester=new ActiveXObject("Microsoft.XMLHTTP"); }
catch (error) { return false; }
}
return requester;

}

var http=createRequestObject();
window.setInterval("load_again()",20000);
';

if (empty($_GET['initial'])) { echo 'load_again();'; }

echo '

function load_again() {

http.open("GET","status-check.php",true);
http.onreadystatechange=handleHttpResponse;
http.send(null);

}

function handleHttpResponse() {

if (http.readyState==4) {

var response=http.responseText;

if (response==1) {

document.getElementById(\'description\').innerHTML=\'<font size="2">New Reminder. If no window opens click <a href="pop-up-window.php?manual=1" target="_blank"><u>here</u></a></font><br><embed src="remind.wav" autostart="true" hidden="true"></embed>\';

popwin=window.open(\'pop-up-window.php\',\'\',\'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=1,resizable=no,width=650,height=450,top=100,left=100\');

}

else if (response==2) {

document.getElementById(\'description\').innerHTML=\'<font size="2">New Live Help Request. If no window opens click <a href="pop-up-window.php?manual=1" target="_blank"><u>here</u></a></font><br><embed src="live-help.wav" autostart="true" hidden="true"></embed>\';

popwin=window.open(\'pop-up-window.php\',\'\',\'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=1,resizable=no,width=650,height=450,top=100,left=100\');

}

else if (response==3) {

document.getElementById(\'description\').innerHTML=\'<img src="pics/ball.gif"> <font size="2">New Instant Message. If no window opens click <a href="pop-up-window.php?manual=1" target="_blank"><u>here</u></a></font><br>\';

popwin=window.open(\'pop-up-window.php\',\'\',\'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=1,resizable=no,width=650,height=450,top=100,left=100\');

}

else if (response) { document.getElementById(\'description\').innerHTML=response; }

else { document.getElementById(\'description\').innerHTML=\'<font size="2" color="#ff0000">Connection Problems. Trying to reconnect..</font>\'; }

}

}

//-->
</script>

</head>

<div id="description"><font size="1">',$stats,'</font></div>';

?>