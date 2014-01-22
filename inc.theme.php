<?php

if ($this->color_main==1) { $hover_color='#646464'; $block_line_color='#6495ed'; $link_color='#4070A0'; }
else if ($this->color_main==2) { $hover_color='#646464'; $block_line_color='#8fbc8f'; $link_color='#008000'; }
else if ($this->color_main==3) { $hover_color='#6d2336'; $block_line_color='#ff0000'; $link_color='#cc0033'; }
else if ($this->color_main==4) { $hover_color='#696969'; $block_line_color='#a52a2a'; $link_color='#a52a2a'; }
else if ($this->color_main==5) { $hover_color='#696969'; $block_line_color='#800080'; $link_color='#800080'; }
else if ($this->color_main==6) { $hover_color='#696969'; $block_line_color='#000000'; $link_color='#000000'; }
else { $hover_color='#646464'; $block_line_color='#6495ed'; $link_color='#4070A0'; }

if ($this->color_navigation==1) { $hover_color_nav='#646464'; $body_color_nav='#000000'; $background_color_nav='#f3f3f3'; $current_tablist_color_nav='#e3e9fd'; $tablist_color_nav='#4682b4'; } # blue

else if ($this->color_navigation==2) { $hover_color_nav='#024607'; $body_color_nav='#008000'; $background_color_nav='#f7fcf3'; $current_tablist_color_nav='#e6ffe6'; $tablist_color_nav='#008000'; } # green

else if ($this->color_navigation==3) { $hover_color_nav='#6d2336'; $body_color_nav='#cc0033'; $background_color_nav='#fecbc2'; $current_tablist_color_nav='#ffebe8'; $tablist_color_nav='#cc0033'; } # red

else if ($this->color_navigation==4) { $hover_color_nav='#696969'; $body_color_nav='#a52a2a'; $background_color_nav='#fde8c8'; $current_tablist_color_nav='#fdf4ec'; $tablist_color_nav='#a52a2a'; } # brown

else if ($this->color_navigation==5) { $hover_color_nav='#696969'; $body_color_nav='#800080'; $background_color_nav='#f8cefd'; $current_tablist_color_nav='#ffe8fa'; $tablist_color_nav='#800080'; } # purple

else if ($this->color_navigation==6) { $hover_color_nav='#696969'; $body_color_nav='#000000'; $background_color_nav='#dededa'; $current_tablist_color_nav='#f3f3f3'; $tablist_color_nav='#000000'; } # black

else { $hover_color_nav='#646464'; $body_color_nav='#000000'; $background_color_nav='#f3f3f3'; $current_tablist_color_nav='#e3e9fd'; $tablist_color_nav='#4682b4'; }

if ($this->background==1) { $bg='background="pics/themes/diag_stripes.gif"'; }
else if ($this->background==2) { $bg='background="pics/themes/sand.gif"'; }
else if ($this->background==3) { $bg='background="pics/themes/bricks.gif"'; }
else if ($this->background==4) { $bg='background="pics/themes/rough.jpg"'; }
else if ($this->background==5) { $bg='background="pics/themes/pink_pattern.gif"'; }
else if ($this->background==6) { $bg='background="pics/themes/horiz_stripes.gif"'; }
else if ($this->background==7) { $bg='background="pics/themes/purple_stripes.jpg"'; }
else if ($this->background==8) { $bg='background="pics/themes/squares.gif"'; }
else if ($this->background==9) { $bg='background="pics/themes/grid.gif"'; }
else if ($this->background==10) { $bg='background="pics/themes/horiz_stripes2.gif"'; }
else if ($this->background==11) { $bg='bgcolor="#d3d3d3"'; }
else if ($this->background==12) { $bg='bgcolor="#faf18d"'; }
else if ($this->background==13) { $bg='bgcolor="#e1e0fe"'; }
else if ($this->background==14) { $bg='bgcolor="#fbfbfb"'; }
else if ($this->background==15) { $bg='bgcolor="#f0dbc8"'; }
else if ($this->background==16) { $bg='bgcolor="#fed6cb"'; }
else if ($this->background==17) { $bg='bgcolor="#e1fce0"'; }
else if ($this->background==18) { $bg='bgcolor="#f8cefd"'; }
else if ($this->background==19) { $bg='background="pics/themes/diag_stripes2.gif"'; }
else if ($this->background==20) { $bg='background="pics/themes/grid2.gif"'; }
else if ($this->background==21) { $bg='background="pics/themes/dots.gif"'; }
else if ($this->background==22) { $bg='bgcolor="#eeeeee"'; }
else { $bg='bgcolor="#fbfbfb"'; }

if ($this->font_size==1) { $font_size_1='11'; $font_size_2='14'; $font_size_nav='9'; }
else if ($this->font_size==2) { $font_size_1='10'; $font_size_2='13'; $font_size_nav='8'; }
else if ($this->font_size==3) { $font_size_1='9'; $font_size_2='12';  $font_size_nav='7'; }
else { $font_size_1='10'; $font_size_2='13'; $font_size_nav='8'; }

if ($this->font_face_main==1) { $font_face_main='Arial'; }
else if ($this->font_face_main==2) { $font_face_main='Verdana'; }
else if ($this->font_face_main==3) { $font_face_main='Tahoma'; }
else if ($this->font_face_main==4) { $font_face_main='Gulim'; }
else if ($this->font_face_main==5) { $font_face_main='Comic Sans MS'; }
else if ($this->font_face_main==6) { $font_face_main='Georgia'; }
else if ($this->font_face_main==7) { $font_face_main='Arial Narrow'; }
else if ($this->font_face_main==8) { $font_face_main='Helvetica'; }
else if ($this->font_face_main==9) { $font_face_main='Trebuchet MS'; }
else if ($this->font_face_main==10) { $font_face_main='Times New Roman'; }
else if ($this->font_face_main==11) { $font_face_main='Sans-serif'; }
else { $font_face_main='Arial'; }

if ($this->font_face_navigation==1) { $font_face_navigation='Arial'; }
else if ($this->font_face_navigation==2) { $font_face_navigation='Verdana'; }
else if ($this->font_face_navigation==3) { $font_face_navigation='Tahoma'; }
else if ($this->font_face_navigation==4) { $font_face_navigation='Gulim'; }
else if ($this->font_face_navigation==5) { $font_face_navigation='Comic Sans MS'; }
else if ($this->font_face_navigation==6) { $font_face_navigation='Georgia'; }
else if ($this->font_face_navigation==7) { $font_face_navigation='Arial Narrow'; }
else if ($this->font_face_navigation==8) { $font_face_navigation='Helvetica'; }
else if ($this->font_face_navigation==9) { $font_face_navigation='Trebuchet MS'; }
else if ($this->font_face_navigation==10) { $font_face_navigation='Times New Roman'; }
else if ($this->font_face_navigation==11) { $font_face_navigation='Sans-serif'; }
else { $font_face_navigation='Arial'; }

define('_INNER_TABLE_PROPERTY','cellpadding=4 cellspacing=0 border=0 bgcolor="#ffffff"');

define('_BACKGROUND',$bg);

define('_BLOCK_LINE_COLOR',$block_line_color);

define('_CSS','<style type="text/css">
form {margin:0}
A:visited.link {font-weight:700}
body,td {font-family:'.$font_face_main.'; font-size:'.$font_size_1.'pt}
A:link,A:visited {font-family:'.$font_face_main.'; font-size:'.$font_size_1.'pt; color:'.$link_color.'; text-decoration: none}
.fakelink {color:'.$link_color.'}
.header,h1 {font-family:'.$font_face_main.'; font-size:'.$font_size_2.'pt; font-weight:700}
A:hover {color:'.$hover_color.'}
table.pastel4 {border-bottom:1px solid #6a5acd}
table.pastel3 {border-left:1px solid #ececec; border-right:1px solid #ececec; border-bottom:1px solid #ececec}
table.pastel2 {border-top:1px solid #ececec; border-left:1px solid #ececec; border-right:1px solid #ececec; border-bottom:1px solid #ececec}
table.pastel {border:1px solid #ececec; border-collapse:collapse}
table.pastel td {border:1px solid #ececec}
table.no_border td {border:0}
.pages a {border-top:#dcdcdc 1px solid; border-bottom:#dcdcdc 1px solid; border-right:#dcdcdc 1px solid; border-left:#dcdcdc 1px solid; background:#FFF; margin-right:0.3em; padding:0.4em}
.pages span.dots {margin-right:0.4em; padding:0.4em}
.pages span.current {border-top:#dcdcdc 1px solid; border-bottom:#dcdcdc 1px solid; border-right:#dcdcdc 1px solid; border-left:#dcdcdc 1px solid; background:#FFF;
margin-right:0.3em; font-weight:700; padding:0.4em}
.pages a:hover {border-color:#000}
input,select,textarea {font:'.$font_size_1.'pt '.$font_face_main.';border-color:#cfcdcb;border-width:1px;background:#fcfcfc}
input:focus,textarea:focus { border:1px solid '.$link_color.'}
input[type="submit"] {background:#efefef; border:1px solid #8f8f8f}
input[type="submit"]:hover {border:1px solid #474747}
</style>');

define('_CSS_NAV','<style type="text/css">
#tablist {padding:3px 0; margin-left:0; margin-bottom:0; margin-top:0.1em; font:bold 12px '.$font_face_navigation.'}
#tablist li {list-style:none; display:inline; margin:0}
#tablist li a {text-decoration:none; padding:3px 0.5em; margin-left:3px; border:1px solid #d4d0c8; border-bottom:none; background:#ffffff}
#tablist li a:link, #tablist li a:visited {color:'.$tablist_color_nav.'}
#tablist li a.current {background:'.$current_tablist_color_nav.'}
.tabcontent {display:none}
body {font-family:'.$font_face_navigation.'; background:'.$background_color_nav.'; color:'.$body_color_nav.'}
A:link {font-family:'.$font_face_navigation.'; font-size:'.$font_size_nav.'pt; color:'.$body_color_nav.'; font-weight:800}
A:visited {font-family:'.$font_face_navigation.'; font-size:'.$font_size_nav.'pt; color:'.$body_color_nav.'; font-weight:800}
A:hover {color:'.$hover_color_nav.'}
input,select,textarea {font:'.$font_size_1.'pt '.$font_face_main.';border-color:#cfcdcb;border-width:1px}
input:focus { border:1px solid '.$link_color.'}
input[type="submit"] {background:#efefef; border:1px solid #8f8f8f}
input[type="submit"]:hover {border:1px solid #474747}
</style>');

?>