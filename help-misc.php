<?php

require 'class.base.php';
require 'class.html.php';

$base_instance=new base();
$html_instance=new html();

$userid=$base_instance->get_userid();

$data=$base_instance->get_data("SELECT username,user_password,country FROM organizer_user WHERE ID=$userid");

$username=$data[1]->username;
$password=$data[1]->user_password;
$country=$data[1]->country;

$url=$username.'/'.$password;
$encoded_url=base64_encode($url);

if (_SHORT_URLS==1) { $url=_HOMEPAGE.'/login-'.$encoded_url; }
else { $url=_HOMEPAGE.'/autologin.php?code='.$encoded_url; }

if (stristr($_SERVER['HTTP_USER_AGENT'],'firefox')) { $ff=1; }
else if (stristr($_SERVER['HTTP_USER_AGENT'],'opera')) { $opera=1; }

if (isset($ff) or isset($opera)) { $save_link='To save link drag and drop it onto your toolbar.'; }
else { $save_link='Right Mouse Click on link and choose "Add to Favorites". Then save into "Links" Folder.'; }

$help_text='<br><div align="center"><table width="95%" cellspacing=5 cellpadding=5  bgcolor="#ffffff" class="pastel2"><tr><td>

<br><div class="pages">
<a href="help-intro.php">Intro</a>
<a href="help-home.php">Home</a>
<a href="help-link.php">Link</a>
<a href="help-knowledge.php">Knowledge</a>
<a href="help-diary.php">Diary</a>
<a href="help-to-do.php">To-Do</a>
<a href="help-contact.php">Contact</a>
<a href="help-reminder.php">Reminder</a>
<a href="help-database.php">Database</a>
<a href="help-live-help.php">Live Help</a>
<a href="help-misc.php">Misc</a>
</div><br>

<h3><u>Misc</u></h3>

<h3>Internal Links</h3>

Diaries, To-Do Items, Contact Items can be linked to internally. For instance
you could upload an image and then link to it from a diary entry.<p>Examples:<p>

[d123 link text for this diary entry]<p>
[k123 link text for this knowledge entry]<p>
[t123 link text for this to-do entry]<p>
[c123 link text for this contact entry]<p>
[n123 link text for this notes entry]<p>
[i123 link text for this image]<p>
[f123 link text for this file]<p>
[b123 link text for this blog]<p>
[l123 link text for this live chat]<p>

The first letter indicates where it links to (like d for diary) and the number specifies the ID. You can also link without a title like this: [d123]<p>

To display an image directly without a link use this format: [image-123]. Replace 123 with the File ID.<p>

If you want to publish an image in your blog make the image public first and then use the File Token instead of the File ID, like this [image-t1a7ba0ca5a435b8fb3067d982dc2633f]

<h3>External Links</h3>

If you want to link to an external URL use the following syntax:<p>

[http://www.cnn.com/ CNN News]<p>

For instance you could link from your blog or diary to external links.

<h3>Auto-Login</h3><p>

If you want to quickly log into the Organizer without having to type in the password every time, bookmark the following link. '.$save_link.'<p>

<a href="'.$url.'"><u>Organizer</u></a><p>

Or copy and paste the following link:<p>

<strong>'.$url.'</strong><p>

This link is for quickly bookmarking a website you are currently on. '.$save_link.'<p>

<a href="javascript:void(window.open(\''._HOMEPAGE.'/autolink.php?code='.$encoded_url.'&url=\'+location.href,\'_blank\',\'width=550,height=525,status=no,resizable=yes,scrollbars=auto\'))"><u>Add Link</u></a><p>

<p>

<h3>Bookmarklets</h3><p>

With so-called bookmarklets you can quickly look up words by selecting it in
your browser and then clicking on the bookmarklet. For instance you can use
Google, Wikipedia, eBay etc in this way.

<p>

<table border cellspacing=0 cellpadding=15 class="pastel">

<tr>';

if (isset($ff) or isset($opera)) {

$help_text.='<td width="350" valign="top">

<em>'.$save_link.'</em><p>';

# Google

if ($country==17) { # UK

$help_text.='<a href="javascript:win=window.open(\'http://www.google.co.uk/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.co.uk</u></a><p>';

}

else if ($country==1) { # Germany

$help_text.='<a href="javascript:win=window.open(\'http://www.google.de/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=de&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.de</u></a><p>';

}

else if ($country==14) { # Netherlands

$help_text.='<a href="javascript:win=window.open(\'http://www.google.nl/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=nl&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.nl</u></a><p>';

}

else if ($country==11) { # Spain

$help_text.='<a href="javascript:win=window.open(\'http://www.google.es/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=es&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.es</u></a><p>';

}

else if ($country==2) { # Austria

$help_text.='<a href="javascript:win=window.open(\'http://www.google.at/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=de&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.at</u></a><p>';

}

else if ($country==3) { # Switzerland

$help_text.='<a href="javascript:win=window.open(\'http://www.google.ch/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=de&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.ch</u></a><p>';

}

else if ($country==26) { # Canada

$help_text.='<a href="javascript:win=window.open(\'http://www.google.ca/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.ca</u></a><p>';

}

else if ($country==19) { # Australia


$help_text.='<a href="javascript:win=window.open(\'http://www.google.com.au/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.com.au</u></a><p>';

}

else if ($country==9) { # France

$help_text.='<a href="javascript:win=window.open(\'http://www.google.fr/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=fr&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.fr</u></a><p>';

}

else if ($country==30) { # Japan

$help_text.='<a href="javascript:win=window.open(\'http://www.google.co.jp/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=Shift_JIS&oe=Shift_JIS&hl=ja&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.co.jp</u></a><p>';

}

else if ($country==7) { # Italy


$help_text.='<a href="javascript:win=window.open(\'http://www.google.it/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=it&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.it</u></a><p>';

}

else if ($country==51) { # Ireland

$help_text.='<a href="javascript:win=window.open(\'http://www.google.ie/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.ie</u></a><p>';

}

else { # USA

$help_text.='<a href="javascript:win=window.open(\'http://www.google.com/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\'+document.getSelection());win.focus();"><u>Google.com</u></a><p>';

}

# Amazon

if ($country==17) { # UK

$help_text.='<a href="javascript:win=window.open(\'http://www.amazon.co.uk/exec/obidos/external-search/?
tag=bookmarkmanag-21&mode=blended&field-keywords=\' + document.getSelection());win.focus();"><u>Amazon.co.uk</u></a><p>';

}

else if ($country==1 or $country==2 or $country==3) { # Germany

$help_text.='<a href="javascript:win=window.open(\'http://www.amazon.de/exec/obidos/external-search/?
tag=freizeitagenturd&mode=blended&field-keywords=\' + document.getSelection());win.focus();"><u>Amazon.de</u></a><p>';

}

else if ($country==9) { # France

$help_text.='<a href="javascript:win=window.open(\'http://www.amazon.fr/exec/obidos/external-search/?
tag=bso-21&mode=blended&field-keywords=\' + document.getSelection());win.focus();"><u>Amazon.fr</u></a><p>';

}

else if ($country==30) { # Japan

$help_text.='<a href="javascript:win=window.open(\'http://www.amazon.co.jp/exec/obidos/external-search/?
tag=bookmarkmanag-21&mode=blended&field-keywords=\' + document.getSelection());win.focus();"><u>Amazon.co.jp</u></a><p>';

}

else if ($country==26) { # Canada

$help_text.='<a href="javascript:win=window.open(\'http://www.amazon.ca/exec/obidos/external-search/?
tag=organizer0a-20&mode=blended&field-keywords=\' + document.getSelection());win.focus();"><u>Amazon.ca</u></a><p>';

}

else { # USA

$help_text.='<a href="javascript:win=window.open(\'http://www.amazon.com/exec/obidos/external-search/?
tag=bookmarkmanag-20&mode=blended&field-keywords=\' + document.getSelection());win.focus();"><u>Amazon.com</u></a><p>';

}

$help_text.='

<a href="javascript:win=window.open(\'http://en.wikipedia.org/w/wiki.phtml?search=\' + document.getSelection());win.focus();"><u>Wiki</u></a><p>

<a href="javascript:win=window.open(\'http://www.m-w.com/cgi-bin/dictionary?va=\' + document.getSelection());win.focus();"><u>Merriam-Webster</u></a><p>

<a href="javascript:win=window.open(\'http://uk.imdb.com/Find?for=\' + document.getSelection());win.focus();"><u>IMDB</u></a><p>

</td>';

}

else {

$help_text.='<td width="350" valign="top">

<em>'.$save_link.'</em><p>';

# Google

if ($country==17) { # UK

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.co.uk/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.co.uk</u></a><p>';

}

else if ($country==1) { # Germany

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.de/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=de&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.de</u></a><p>';

}

else if ($country==14) { # Netherlands

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.nl/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=nl&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.nl</u></a><p>';

}

else if ($country==11) { # Spain

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.es/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=es&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.es</u></a><p>';

}

else if ($country==2) { # Austria

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.at/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=de&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.at</u></a><p>';

}

else if ($country==3) { # Switzerland

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.ch/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=de&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.ch</u></a><p>';

}

else if ($country==26) { # Canada

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.ca/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.ca</u></a><p>';

}

else if ($country==19) { # Australia

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.com.au/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.com.au</u></a><p>';

}

else if ($country==9) { # France

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.fr/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=fr&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.fr</u></a><p>';

}

else if ($country==30) { # Japan

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.co.jp/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=Shift_JIS&oe=Shift_JIS&hl=ja&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.co.jp</u></a><p>';

}

else if ($country==7) { # Italy

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.it/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=it&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.it</u></a><p>';

}

else if ($country==51) { # Ireland

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.ie/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.ie</u></a><p>';

}

else { # USA

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.google.com/custom?client=pub-1841153363764743&forid=1&channel=1296029227&ie=ISO-8859-1&oe=ISO-8859-1&hl=en&cof=\'+encodeURIComponent(\'GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1\')+\'&q=\' + str);win.focus();"><u>Google.com</u></a><p>';

}

# Amazon

if ($country==17) { # UK

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.amazon.co.uk/exec/obidos/external-search/?
tag=bookmarkmanag-21&mode=blended&field-keywords=\' + str);win.focus();"><u>Amazon.co.uk</u></a><p>';

}

else if ($country==1 or $country==2 or $country==3) { # Germany

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.amazon.de/exec/obidos/external-search/?
tag=freizeitagenturd&mode=blended&field-keywords=\' + str);win.focus();"><u>Amazon.de</u></a><p>';

}

else if ($country==9) { # France

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.amazon.fr/exec/obidos/external-search/?
tag=bso-21&mode=blended&field-keywords=\' + str);win.focus();"><u>Amazon.fr</u></a><p>';

}

else if ($country==30) { # Japan

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.amazon.co.jp/exec/obidos/external-search/?
tag=bookmarkmanag-21&mode=blended&field-keywords=\' + str);win.focus();"><u>Amazon.co.jp</u></a><p>';

}

else if ($country==26) { # Canada

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.amazon.ca/exec/obidos/external-search/?
tag=organizer0a-20&mode=blended&field-keywords=\' + str);win.focus();"><u>Amazon.ca</u></a><p>';

}

else { # USA

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.amazon.com/exec/obidos/external-search/?
tag=bookmarkmanag-20&mode=blended&field-keywords=\' + str);win.focus();"><u>Amazon.com</u></a><p>';

}

$help_text.='<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://en.wikipedia.org/w/wiki.phtml?search=\' + str);win.focus();"><u>Wiki</u></a><p>

<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://www.m-w.com/cgi-bin/dictionary?va=\' + str);win.focus();"><u>Merriam-Webster</u></a><p>

<a href="javascript:var range=document.selection.createRange(); var str=range.text; win=window.open(\'http://uk.imdb.com/Find?for=\' + str);win.focus();"><u>IMDB</u></a><p>

</td>';

}

$help_text.='</tr></table></td></tr></table></div>';

$html_instance->add_parameter(
array(
'TEXT'=>$help_text
));

$html_instance->process();

?>