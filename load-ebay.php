<?php

$country=isset($_GET['country']) ? $_GET['country'] : '';
$search_text=isset($_GET['search_text']) ? $_GET['search_text'] : '';

$encoded=urlencode($search_text);

if ($search_text && $country=='usa') {

$url='http://rover.ebay.com/rover/1/711-53200-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='uk') {

$url='http://rover.ebay.com/rover/1/710-53481-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='fr') {

$url='http://rover.ebay.com/rover/1/709-53476-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='au') {

$url='http://rover.ebay.com/rover/1/705-53470-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='in') {

$url='http://rover.ebay.com/rover/1/4686-53472-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='ca') {

$url='http://rover.ebay.com/rover/1/706-53473-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='nl') {

$url='http://rover.ebay.com/rover/1/1346-53482-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='de') {

$url=urlencode('http://search.ebay.de/ws/search/SaleSearch?satitle='.$search_text);

$url='http://partners.webmasterplan.com/click.asp?ref=136628&site=1382&type=text&tnb=23&diurl='.$url;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='ch') {

$url=urlencode('http://search.ebay.ch/ws/search/SaleSearch?satitle='.$search_text);

$url='http://partners.webmasterplan.com/click.asp?ref=136628&site=1384&type=text&tnb=1&diurl='.$url;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='at') {

$url=urlencode('http://search.ebay.at/ws/search/SaleSearch?satitle='.$search_text);

$url='http://partners.webmasterplan.com/click.asp?ref=136628&site=1383&type=text&tnb=1&diurl='.$url;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='it') {

$url='http://rover.ebay.com/rover/1/724-53478-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='befr') {

$url='http://rover.ebay.com/rover/1/1553-53471-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='benl') {

$url='http://rover.ebay.com/rover/1/1553-53471-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='sg') {

$url='http://rover.ebay.com/rover/1/3423-53474-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='hk') {

$url='http://rover.ebay.com/rover/1/3422-53475-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='se') {

$url='http://www.tradera.com/search/imp.aspx?search='.$search_text.'&catid=&county=0&l_desc=';

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else if ($search_text && $country=='es') {

$url='http://rover.ebay.com/rover/1/1185-53479-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_'.$country.'&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

else {

$url='http://rover.ebay.com/rover/1/711-53200-19255-0/1?type=3&campid=5336024889&toolid=10001&customid=org_other&ext='.$encoded.'&satitle='.$encoded;

echo '<meta http-equiv="refresh" content="0; URL=',$url,'">';

}

?>