<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


include('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];


$agentQuery = "SELECT * FROM company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$agent_name = $db -> quote($agent[0]['fullname']);
$street = trim($db -> quote($_POST['street']));
$city = trim($db -> quote($_POST['city']));
$state = trim($db -> quote($_POST['state']));
$zip = trim($db -> quote($_POST['zip']));
$amount = trim($db -> quote($_POST['amount']));
$referral_id = $_POST['referral_id'];

// Set the uplaod directory

$base_dir = '/var/www/annearundelproperties.net/new/agents/uploads/referrals/';
if(!is_dir($base_dir.$agent_id.'/')) {
	mkdir($base_dir.$agent_id.'/');
	chmod($base_dir.$agent_id, 0775);
}



$uploadDir = $base_dir.$agent_id.'/';

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