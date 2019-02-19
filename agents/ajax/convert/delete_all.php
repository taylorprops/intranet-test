<?php
		
include('/var/www/annearundelproperties.net/new/includes/global.php');

$dir = $_POST['folder'];
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	if($filename != '.' && $filename != '..') {
		unlink($dir.$filename);
	}
}
?>