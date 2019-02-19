<?php
include('/var/www/annearundelproperties.net/new/includes/global.php');

$file_id = $argv[1];
$ses_id = $argv[2];
$fileQuery = "select * from company.mls_uploads where upload_id = '".$file_id."'";
$file = $db -> select($fileQuery);

$pdf = str_replace('https://', '/var/www/', $file[0]['upload_loc']);

$temp = '/var/www/annearundelproperties.net/new/real_estate/contracts/temp/'.$ses_id;
if(!is_dir($temp)) {
	mkdir($temp);
	chmod($temp, 0777);
}

$cmd = "/usr/bin/pdfinfo";
exec("$cmd \"$pdf\"", $output);

// Iterate through lines
$encrypted = 'no';
foreach($output as $op)
{
	// Extract the number
	if(preg_match("/Encrypted:\s*([a-z]+)/i", $op, $matches) === 1)
	{
		$encrypted = $matches[1];
		break;
	}
}
if($encrypted == 'yes') {
	$encrypted = "update company.mls_uploads set encrypted = 'yes' where upload_id = '".$file_id."'";
	$queryResults = $db -> query($encrypted);
}


?>