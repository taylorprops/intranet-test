<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');

$referral_id = $_POST['referral_id'];
$agent_id = $_POST['agent_id'];
// Set the uplaod directory

$uploadDir = '/var/www/annearundelproperties.net/new/agents/uploads/referrals/'.$agent_id.'/';


if (!empty($_FILES)) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];

	$origFileName = $_FILES['Filedata']['name'];
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$fName = $fileParts['filename'];
	$fExt = $fileParts['extension'];
	$fileName = preg_replace($_GLOB_bad_characters, '_', $fName).'_'.date('YmdHis').'.'.$fExt;
    $targetFile = $uploadDir . $fileName;

    $link = str_replace('/var/www/', 'https://', $targetFile);

	$insert_upload = "insert into company.referral_uploads (agent_id, upload_loc, file_name, referral_id) values ('".$agent_id."', '".$link."', '".$origFileName."', '".$referral_id."')";
	$queryResults = $db -> query($insert_upload);


	// Save the file
	move_uploaded_file($tempFile, $targetFile);
	if(file_exists($targetFile)) {
		chmod($targetFile, 0775);
	}



}
?>