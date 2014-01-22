<?php

require 'class.base.php';
require 'class.html.php';
require 'class.user.php';

$base_instance=new base();
$html_instance=new html();
$user_instance=new user();

$userid=$base_instance->get_userid();

$theme_preset=isset($_POST['theme_preset']) ? $_POST['theme_preset'] : 0;

if (isset($_POST['save'])) {

	$error='';

	if (!$error) {

	if ($theme_preset==1) { # random

	$color_main=mt_rand(1,6);
	$color_navigation=mt_rand(1,6);
	$font_face_main=mt_rand(1,11);
	$font_face_navigation=mt_rand(1,11);
	$font_size=mt_rand(1,3);
	$background=mt_rand(1,21);

	}

	else if ($theme_preset==2) { # blue

	$color_main=1;
	$color_navigation=1;
	$font_face_main=1;
	$font_face_navigation=1;
	$font_size=2;
	$background=14;

	}

	else if ($theme_preset==3) { # green

	$color_main=2;
	$color_navigation=2;
	$font_face_main=1;
	$font_face_navigation=1;
	$font_size=2;
	$background=14;

	}

	else if ($theme_preset==4) { # purple

	$color_main=5;
	$color_navigation=5;
	$font_face_main=3;
	$font_face_navigation=8;
	$font_size=2;
	$background=14;

	}

	else if ($theme_preset==5) { # black

	$color_main=6;
	$color_navigation=6;
	$font_face_main=1;
	$font_face_navigation=1;
	$font_size=2;
	$background=14;

	}

	else if ($theme_preset==6) { # red

	$color_main=3;
	$color_navigation=3;
	$font_face_main=1;
	$font_face_navigation=1;
	$font_size=2;
	$background=14;

	}

	else if ($theme_preset==7) { # brown

	$color_main=4;
	$color_navigation=4;
	$font_face_main=1;
	$font_face_navigation=1;
	$font_size=2;
	$background=14;

	}

	else {

	$color_navigation=(int)$_POST['color_navigation'];
	$color_main=(int)$_POST['color_main'];
	$font_face_navigation=(int)$_POST['font_face_navigation'];
	$font_face_main=(int)$_POST['font_face_main'];
	$font_size=(int)$_POST['font_size'];
	$background=(int)$_POST['background'];

	}

	if (!$color_navigation) { $color_navigation=1; }
	if (!$color_main) { $color_main=1; }
	if (!$font_face_navigation) { $font_face_navigation=1; }
	if (!$font_face_main) { $font_face_main=1; }
	if (!$background) { $background=1; }
	if (!$font_size) { $font_size=2; }

	$base_instance->query("UPDATE {$base_instance->entity['USER']['MAIN']} SET font_face_main='$font_face_main',font_face_navigation='$font_face_navigation',font_size=$font_size,color_navigation='$color_navigation',color_main='$color_main',background='$background' WHERE ID=$userid");

	$sid=(int)$_COOKIE['sid'];

	$base_instance->query("UPDATE {$base_instance->entity['SESSION']['MAIN']} SET font_face_main=$font_face_main,font_face_navigation=$font_face_navigation,font_size=$font_size,color_main=$color_main,color_navigation=$color_navigation,background=$background WHERE session_id=$sid");

	header('Location: start.php'); exit;

	}

	else { $html_instance->error_message=$error; }

}

$data=$user_instance->get_userinfo($userid);

$font_face_navigation=$data[1]->font_face_navigation;
$font_face_main=$data[1]->font_face_main;
$font_size=$data[1]->font_size;
$color_navigation=$data[1]->color_navigation;
$color_main=$data[1]->color_main;
$background=$data[1]->background;

$html_instance->add_parameter(
array('ACTION'=>'show_form',
'HEADER'=>'Edit Theme',
'FORM_ACTION'=>$_SERVER['PHP_SELF'],
'FORM_ATTRIB'=>'target="_top"',
'TD_WIDTH'=>'30%',
'BUTTON_TEXT'=>'Save Theme'
));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'font_face_main','VALUE'=>"$font_face_main",'OPTION'=>'font_face_array','TEXT'=>'Font Face<br>(Main)'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'font_face_navigation','VALUE'=>"$font_face_navigation",'OPTION'=>'font_face_array','TEXT'=>'Font Face<br>(Navigation)'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'font_size','VALUE'=>"$font_size",'OPTION'=>'font_size_array','TEXT'=>'Font Size'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'background','VALUE'=>"$background",'OPTION'=>'background_array','TEXT'=>'Background<br>(Main)'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'color_main','VALUE'=>"$color_main",'OPTION'=>'color_array','TEXT'=>'Font Color<br>(Main)'));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'color_navigation','VALUE'=>"$color_navigation",'OPTION'=>'color_array','TEXT'=>'Font Color<br>(Navigation)'));

$html_instance->add_form_field(array('TYPE'=>'label','TEXT1'=>'','TEXT2'=>'Or choose a preset here:','SECTIONS'=>2));

$html_instance->add_form_field(array('TYPE'=>'select','NAME'=>'theme_preset','VALUE'=>'','OPTION'=>'theme_presets_array','TEXT'=>'Theme Presets'));

$html_instance->process();

?>