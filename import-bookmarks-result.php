<?php

require 'class.base.php';
$base_instance=new base();

if (isset($_GET['error'])) {

$base_instance->show_message('Import Result','Your Bookmark file could not be imported (due to wrong format). Please <a href="import-bookmarks-start.php">try again</a> or contact admin.'); exit;

}

if (isset($_GET['bookmarks'])) { $bookmarks=$_GET['bookmarks']; }
if (isset($_GET['duplicates'])) { $duplicates=$_GET['duplicates']; }

$bookmarks_imported=$bookmarks-$duplicates;

if ($duplicates > 0) { $duplicates_text='<p>Duplicate Bookmarks: '.$duplicates; } else { $duplicates_text=''; }

$base_instance->show_message('Bookmark Import Result','Imported Bookmarks: '.$bookmarks_imported.$duplicates_text.'<p>Click <a href="show-links.php">here</a> to see your imported links.<br>');

?>