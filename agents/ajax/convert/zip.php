<?php
		
include('/var/www/annearundelproperties.net/new/includes/global.php');

$dir = $_POST['folder'];
$files = array();
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
	$info = new SplFileInfo($filename);
	$ext = $info->getExtension();
	if($ext == 'pdf') { 
		$files[] = $dir.$filename;
	}
}
$new_file = $dir.'ZippeddFile.zip';
$combine = implode(' ', $files);
exec('zip -j '.$new_file.' '.$combine);
?>