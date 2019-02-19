<?php
/* _DONE */
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


include('/var/www/annearundelproperties.net/new/includes/global.php');

$mls = strtoupper($_POST['mls']);
$agent_id = $_POST['agent_id'];
$doc_type = $_POST['doc_type'];
$upload_type = $_POST['upload_type'];
$file_size = $_FILES['Filedata']['size'];


$contractQuery = "SELECT FullStreetAddress, City, StateOrProvince, PostalCode FROM company.mls_company where ListingSourceRecordID = '".$mls."'";
$contract = $db -> select($contractQuery);

$agentQuery = "SELECT * FROM company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$agent_name = $db -> quote($agent[0]['fullname']);
$agent_email = $db -> quote($agent[0]['email1']);
$street = trim($db -> quote($contract[0]['FullStreetAddress']));
$city = trim($db -> quote($contract[0]['City']));
$state = trim($db -> quote($contract[0]['StateOrProvince']));
$zip = trim($db -> quote($contract[0]['PostalCode']));


// Set the uplaod directory
if(stristr($upload_type, 'listing')) {
	$dir_type = 'listing';
	$upload_sql = "upload_type = 'Listing Agreement'";
} else {
	$dir_type = 'contract';
	$upload_sql = "upload_type != 'Listing Agreement'";
}

$base_dir = '/var/www/annearundelproperties.net/new/agents/uploads/uploads/';
if(!is_dir($base_dir.$agent_id.'/')) {
	mkdir($base_dir.$agent_id.'/');
	chmod($base_dir.$agent_id, 0777);
}
if(!is_dir($base_dir.$agent_id.'/'.$mls.'/')) {
	mkdir($base_dir.$agent_id.'/'.$mls.'/');
	chmod($base_dir.$agent_id.'/'.$mls, 0777);
}
if(!is_dir($base_dir.$agent_id.'/'.$mls.'/'.$dir_type.'/')) {
	mkdir($base_dir.$agent_id.'/'.$mls.'/'.$dir_type.'/');
	chmod($base_dir.$agent_id.'/'.$mls.'/'.$dir_type, 0777);
}



$uploadDir = $base_dir.$agent_id.'/'.$mls.'/'.$dir_type.'/';

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];

	$origFileName = $_FILES['Filedata']['name'];
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$fName = $fileParts['filename'];
	$fExt = $fileParts['extension'];

	$fileName = preg_replace($_GLOB_bad_characters, '_', $fName).'.'.$fExt;




	if($doc_type == 'Release') {

		release_all($mls, $agent_id);

		$released = 'yes';

        chmod('/var/www/annearundelproperties.net/new/agents/uploads/uploads/released/'.$agent_id.'/', 0777);

		$targetFile = '/var/www/annearundelproperties.net/new/agents/uploads/uploads/released/'.$agent_id.'/'.$mls.'/'.$fileName;
		$link = str_replace('/var/www/', 'https://', $targetFile);

		$subject = 'Contract Released';
        $GLOB_email_top = str_replace('%%preheader%%', 'Contract Released', $GLOB_email_top);
		$body = str_replace('%%subject%%', $subject, $GLOB_email_top).'
		<a href="https://annearundelproperties.net/new/real_estate/contracts/edit.php?ListingSourceRecordID='.$mls.'">'.$mls.'</a> was released. Make sure that it is released in the system and if it is a listing that the listing is not closed out.
		'.$GLOB_email_bottom;

		$ccs = sendTo(8);
		sendMail('', 'internal@taylorprops.com', 'Do Not Reply <internal@taylorprops.com>', $subject, $body, $ccs, '', '');


	} else {

		$released = 'no';

		$targetFile = $uploadDir . $fileName;

		$link = str_replace('/var/www/', 'https://', $targetFile);

	}


	$insert = "insert into company.mls_uploads (upload_agent, upload_agent_id, upload_agent_email, upload_mls, upload_address, upload_street, upload_city, upload_state, upload_zip, upload_loc, upload_type, upload_file_name, upload_file_size, doc_type, upload_orig_filename, uploaded_by, released) values ('".$agent_name."','".$agent_id."', '".$agent_email."', '".$mls."','".$street."', '".$street."', '".$city."', '".$state."', '".$zip."' ,'".$link."', '".$upload_type."', '".$fileName."', '".$file_size."', '".$doc_type."', '".addslashes($origFileName)."', '".$db->quote($agent_name)."', '".$released."')";
	$queryResults = $db -> query($insert);
	$file_id = $db -> id();

	if(stristr($doc_type, 'contract')) {
		$updateQA = "update company.mls_uploads set questions_answered = 'yes' where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."'";
		$queryResults = $db -> query($updateQA);
	}

	// Save the file
	move_uploaded_file($tempFile, $targetFile);
	if(file_exists($targetFile)) {
		chmod($targetFile, 0777);
		changes($_SESSION['S_Username'], 'Uploads', 'Uploaded Doc', '', '', 'Uploaded '.$targetFile, $agent_id, $agent_name, $mls);
		echo 1;
	} else {
		echo 'error '.$_FILES['Filedata']['tmp_name'];
	}



	$cmd = '/usr/bin/php /var/www/annearundelproperties.net/new/agents/ajax/uploads/new/set_encrypted.php '.$file_id.' '.$agent_id.' >/dev/null &';

	exec($cmd);


	$updateIncomplete = "update company.mls_uploads set incomplete = 'no' where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and ".$upload_sql."";
	$queryResults = $db -> query($updateIncomplete);



}
?>
