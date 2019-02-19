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
$new_file = $dir.'CombinedFile.pdf';
$combine = implode(' ', $files);
exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile='.$new_file.' '.$combine);


foreach($files as $delete_me) {
	unlink($delete_me);
}

?>