<?php

$header='Sorry Page Not Found';

$main='<p>

<!-- Search Google -->
<form method="get" action="http://www.google.com/custom" target="_top">
<table bgcolor="#ffffff">
<tr><td nowrap="nowrap" valign="top" align="left" height="32">
<a href="http://www.google.com/">
<img src="http://www.google.com/logos/Logo_25wht.gif" border="0" alt="Google" align="middle"></img></a>
<input type="text" name="q" size="31" maxlength="255" value=""></input>
<input type="submit" name="sa" value="Search"></input>
<input type="hidden" name="client" value="pub-1841153363764743"></input>
<input type="hidden" name="forid" value="1"></input>
<input type="hidden" name="channel" value="8294497443"></input>
<input type="hidden" name="ie" value="ISO-8859-1"></input>
<input type="hidden" name="oe" value="ISO-8859-1"></input>
<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1"></input>
<input type="hidden" name="hl" value="en"></input>
</td></tr></table>
</form>
<!-- Search Google -->';

require 'template.html';

?>