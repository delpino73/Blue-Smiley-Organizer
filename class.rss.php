<?php

class rss {

var $tag,$title,$description,$link,$content,$inside=false,$max_items,$counter;

function rss($feed,$max_items=0) {

	$this->max_items=$max_items;

	if (empty($feed)) { $this->content.='RSS Feed empty'; } else {

	$parser=xml_parser_create('');
	#$parser=xml_parser_create('ISO-8859-1');
	#$parser=xml_parser_create('UTF-8');

	xml_set_object($parser,$this);
	xml_set_element_handler($parser,'tag_open','tag_close');
	xml_set_character_data_handler($parser,'cdata');

	$fp=@fopen($feed,'r');

	if ($fp) {

	while ($data=fread($fp,4096)) {

	xml_parse($parser,$data,feof($fp));

	}

	xml_parser_free($parser);
	fclose($fp);

	} else {

	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$feed);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	$data=curl_exec($ch);
	curl_close($ch);

	if (empty($data)) {

	$this->content.='<table><tr><td>RSS Feed not working, try again later. Make sure CURL is enabled in your server configuration!</td></tr></table>';

	} else {

	xml_parse($parser,$data) or die(sprintf("XML error: %s at line %d",xml_error_string(xml_get_error_code($parser)),xml_get_current_line_number($parser)));
	xml_parser_free($parser);

	}

	}

	}

}

function tag_open($parser, $tag_name, $attrs) {

	if ($this->inside) { $this->tag=$tag_name; }
	else if ($tag_name=='ITEM') { $this->inside=true; }

}

function tag_close($parser, $tag_name) {

	if ($tag_name=='ITEM') {

	$this->counter++;

	if ($this->counter <= $this->max_items or $this->max_items==0) {

	$this->content.='<table><tr><td><a href="'.trim($this->link).'" target="_blank">'.$this->title.'</a><br>'.$this->description.'</td></tr></table>';

	$this->title='';
	$this->description='';
	$this->link='';
	$this->inside=false;

	}

	}

}

function cdata($parser, $data) {

	if ($this->inside) {

	if ($this->tag=='TITLE') { $this->title.=$data; }
	else if ($this->tag=='DESCRIPTION') { $this->description.=$data; }
	else if ($this->tag=='LINK') { $this->link.=$data; }

	}

}

}

?>