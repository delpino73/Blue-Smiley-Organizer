<?php

require 'class.base.php';
$base_instance=new base();

$userid=$base_instance->user;

$size=10;

if ($userid) {

$data=$base_instance->get_data("SELECT country FROM {$base_instance->entity['USER']['MAIN']} WHERE ID=$userid");

$country=$data[1]->country;

} else { $country=6; }

if ($country==17) { # Amazon UK

$amazon='<form method="get" action="http://www.amazon.co.uk/exec/obidos/external-search" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/amazon.jpg" alt="Amazon" border="0"></img></td><td valign="top"><input type="text" name="keyword" size="'.$size.'" onFocus="this.select()"><input type="hidden" name="mode" value="blended"><input type="hidden" name="tag" value="bookmarkmanag-21"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==1) { # Amazon Germany

$amazon='<form method="get" action="http://www.amazon.de/exec/obidos/external-search" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/amazon.jpg" alt="Amazon" border="0"></img></td><td valign="top"><input type="text" name="keyword" size="'.$size.'" onFocus="this.select()"><input type="hidden" name="mode" value="blended"><input type="hidden" name="tag" value="freizeitagenturd"><input type="hidden" name="tag-id" value="freizeitagenturd"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==9) { # Amazon France

$amazon='<form method="get" action="http://www.amazon.fr/exec/obidos/external-search" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/amazon.jpg" alt="Amazon" border="0"></img></td><td valign="top"><input type="text" name="keyword" size="'.$size.'" onFocus="this.select()"><input type="hidden" name="mode" value="blended"><input type="hidden" name="tag" value="bso-21"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==30) { # Amazon Japan

$amazon='<form method="get" action="http://www.amazon.co.jp/exec/obidos/external-search" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/amazon.jpg" alt="Amazon" border="0"></img></td><td valign="top"><input type="text" name="keyword" size="'.$size.'" onFocus="this.select()"><input type="hidden" name="mode" value="blended"><input type="hidden" name="tag" value="bookmarkmanag-21"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==26) { # Amazon Canada

$amazon='<form method="get" action="http://www.amazon.ca/exec/obidos/external-search" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/amazon.jpg" alt="Amazon" border="0"></img></td><td valign="top"><input type="text" name="keyword" size="'.$size.'" onFocus="this.select()"><input type="hidden" name="mode" value="blended"><input type="hidden" name="tag" value="organizer0a-20"><input type="submit" value="Search"></td></tr></table></form>';

}

else { # Amazon US

$amazon='<form method="get" action="http://www.amazon.com/exec/obidos/external-search" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/amazon.jpg" alt="Amazon" border="0"></img></td><td valign="top"><input type="text" name="keyword" size="'.$size.'" onFocus="this.select()"><input type="hidden" name="mode" value="blended"><input type="hidden" name="tag" value="bookmarkmanag-20"><input type="submit" value="Search"></td></tr></table></form>';

}

#

if ($country==17) { # eBay UK

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="uk"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==1) { # eBay Germany

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="de"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==9) { # eBay France

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="fr"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==19) { # eBay Australia

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="au"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==28) { # eBay India

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="in"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==26) { # eBay Canada

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="ca"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==14) { # eBay Netherlands

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="nl"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==3) { # eBay Switzerland

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="ch"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==2) { # eBay Austria

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="at"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==7) { # eBay Italy

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="it"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==59) { # eBay Belgium (French)

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="befr"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==13) { # eBay Belgium (Dutch)

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="benl"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==10) { # eBay Singapore

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="sg"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==57) { # eBay HK

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="hk"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else if ($country==11) { # eBay Spain

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="es"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

else { # eBay USA

$ebay='<form method="get" action="load-ebay.php" target="_blank"><table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top"><img src="pics/ebay.gif" alt="eBay" border="0"></img></td><td valign="top"><input type="hidden" name="country" value="usa"><input name="search_text" size="'.$size.'" onFocus="this.select()"><input type="submit" value="Search"></td></tr></table></form>';

}

#

if ($country==1) { # Google Germany

$google_url='http://www.google.de/custom';
$google_ie='ISO-8859-1';
$google_lang='de';

}

else if ($country==2) { # Google Austria

$google_url='http://www.google.at/custom';
$google_ie='ISO-8859-1';
$google_lang='de';

}

else if ($country==3) { # Google Switzerland

$google_url='http://www.google.ch/custom';
$google_ie='ISO-8859-1';
$google_lang='de';

}

else if ($country==4) { # Google Luxembourg

$google_url='http://www.google.lu/custom';
$google_ie='ISO-8859-1';
$google_lang='de';

}

else if ($country==7) { # Google Italy

$google_url='http://www.google.it/custom';
$google_ie='ISO-8859-1';
$google_lang='it';

}

else if ($country==8) { # Google Czech Republic

$google_url='http://www.google.cz/custom';
$google_ie='ISO-8859-2';
$google_lang='cs';

}

else if ($country==9) { # Google France

$google_url='http://www.google.fr/custom';
$google_ie='ISO-8859-1';
$google_lang='fr';

}

else if ($country==10) { # Google Singapore

$google_url='http://www.google.com.sg/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==11) { # Google Spain

$google_url='http://www.google.es/custom';
$google_ie='ISO-8859-1';
$google_lang='es';

}

else if ($country==12) { # Google Poland

$google_url='http://www.google.pl/custom';
$google_ie='ISO-8859-2';
$google_lang='pl';

}

else if ($country==13) { # Google Belgium (Dutch)

$google_url='http://www.google.be/custom';
$google_ie='ISO-8859-1';
$google_lang='nl';

}

else if ($country==14) { # Google Netherlands

$google_url='http://www.google.nl/custom';
$google_ie='ISO-8859-1';
$google_lang='nl';

}

else if ($country==15) { # Google Portugal

$google_url='http://www.google.pt/custom';
$google_ie='ISO-8859-1';
$google_lang='pt';

}

else if ($country==16) { # Google Korea

$google_url='http://www.google.co.kr/custom';
$google_ie='EUC-KR';
$google_lang='ko';

}

else if ($country==17) { # Google UK

$google_url='http://www.google.co.uk/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==18) { # Google Brazil

$google_url='http://www.google.com.br/custom';
$google_ie='ISO-8859-1';
$google_lang='pt';

}

else if ($country==19) { # Google Australia

$google_url='http://www.google.com.au/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==20) { # Google China

$google_url='http://www.google.cn/custom';
$google_ie='GB2312';
$google_lang='zh-CN';

}

else if ($country==21) { # Google Taiwan

$google_url='http://www.google.com.tw/custom';
$google_ie='big5';
$google_lang='zh-TW';

}

else if ($country==22) { # Google Greece

$google_url='http://www.google.gr/custom';
$google_ie='ISO-8859-7';
$google_lang='el';

}

else if ($country==23) { # Google Indonesia

$google_url='http://www.google.co.id/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==24) { # Google Norway

$google_url='http://www.google.no/custom';
$google_ie='ISO-8859-1';
$google_lang='no';

}

else if ($country==25) { # Google Turkey

$google_url='http://www.google.com.tr/custom';
$google_ie='ISO-8859-9';
$google_lang='tr';

}

else if ($country==26) { # Google Canada

$google_url='http://www.google.ca/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==27) { # Google Croatia

$google_url='http://www.google.hr/custom';
$google_ie='ISO-8859-2';
$google_lang='hr';

}

else if ($country==28) { # Google India

$google_url='http://www.google.co.in/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==29) { # Google Israel

$google_url='http://www.google.co.il/custom';
$google_ie='ISO-8859-8-I';
$google_lang='iw';

}

else if ($country==30) { # Google Japan

$google_url='http://www.google.co.jp/custom';
$google_ie='Shift_JIS';
$google_lang='ja';

}

else if ($country==31) { # Google Denmark

$google_url='http://www.google.dk/custom';
$google_ie='ISO-8859-1';
$google_lang='da';

}

else if ($country==32) { # Google Sweden

$google_url='http://www.google.se/custom';
$google_ie='ISO-8859-1';
$google_lang='sv';

}

else if ($country==33) { # Google Finland

$google_url='http://www.google.fi/custom';
$google_ie='ISO-8859-1';
$google_lang='fi';

}

else if ($country==34) { # Google Hungary

$google_url='http://www.google.hu/custom';
$google_ie='ISO-8859-2';
$google_lang='hu';

}

else if ($country==35) { # Google Romania

$google_url='http://www.google.ro/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==36) { # Google Argentina

$google_url='http://www.google.com.ar/custom';
$google_ie='ISO-8859-1';
$google_lang='es';

}

else if ($country==37) { # Google Mexico

$google_url='http://www.google.com.mx/custom';
$google_ie='ISO-8859-1';
$google_lang='es';

}

else if ($country==38) { # Google Colombia

$google_url='http://www.google.com.co/custom';
$google_ie='ISO-8859-1';
$google_lang='es';

}

else if ($country==42) { # Google South Africa

$google_url='http://www.google.co.za/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==43) { # Google Russia

$google_url='http://www.google.ru/custom';
$google_ie='windows-1251';
$google_lang='ru';

}

else if ($country==44) { # Google New Zealand

$google_url='http://www.google.co.nz/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==51) { # Google Ireland

$google_url='http://www.google.ie/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==52) { # Google Ukraine

$google_url='http://www.google.com.ua/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==53) { # Google Iceland

$google_url='http://www.google.is/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==54) { # Google Chile

$google_url='http://www.google.cl/custom';
$google_ie='ISO-8859-1';
$google_lang='es';

}

else if ($country==55) { # Google Dominican Republic

$google_url='http://www.google.com.do/custom';
$google_ie='ISO-8859-1';
$google_lang='es';

}

else if ($country==56) { # Google Slovenia

$google_url='http://www.google.si/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

else if ($country==57) { # Google HK

$google_url='http://www.google.com.hk/custom';
$google_ie='ISO-8859-1';
$google_lang='zh-TW';

}

else if ($country==58) { # Google Thailand

$google_url='http://www.google.co.th/custom';
$google_ie='ISO-8859-1';
$google_lang='th';

}

else if ($country==59) { # Google Belgium (French)

$google_url='http://www.google.be/custom';
$google_ie='ISO-8859-1';
$google_lang='fr';

}

else { # Google USA

$google_url='http://www.google.com/custom';
$google_ie='ISO-8859-1';
$google_lang='en';

}

echo '<html><head><meta http-equiv="cache-control" content="no-cache"><meta http-equiv="content-type" content="text/html;charset=iso-8859-1"><style type="text/css">
input,select,textarea {font:10pt Arial;border-color:#cfcdcb;border-width:1px}
input[type="submit"] {background:#efefef; border:1px solid #8f8f8f}
input[type="submit"]:hover {border:1px solid #474747}
</style></head>

<table><tr><td style="border-top:1px solid #dcdcdc;border-right:1px solid #dcdcdc" valign=top><span style="font-weight:normal;font-family:Verdana,Arial;font-size:15px">Search here to keep site free -><br>

<a href="http://www.english-flashcards.com" target="_blank">Learn a Language!</a>

</span></font></td><td style="border-top:1px solid #dcdcdc;border-right:1px solid #dcdcdc">

<form method="get" action="',$google_url,'" target="google_window">
<input type="hidden" name="client" value="',_GOOGLE_ADSENSE_ID,'"></input>
<input type="hidden" name="forid" value="1"></input>
<input type="hidden" name="channel" value="',_GOOGLE_ADSENSE_CHANNEL,'"></input>
<input type="hidden" name="ie" value="',$google_ie,'"></input>
<input type="hidden" name="oe" value="',$google_ie,'"></input>
<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1"></input>
<input type="hidden" name="hl" value="',$google_lang,'"></input>
<table bgcolor="#ffffff" cellpadding="5"><tr><td valign="top">
<img src="pics/google.gif" border="0" alt="Google" align="middle"></img>
<input type="text" name="q" size="',$size,'" maxlength="255" value="" onFocus="this.select()"></input><input type="submit" name="sa" value="Search"></input>
</td></tr></table></form>

</td>

<td style="border-top:1px solid #dcdcdc;border-right:1px solid #dcdcdc">',$amazon,'</td>

<td style="border-top:1px solid #dcdcdc">',$ebay,'</td>

</tr></table>';

?>