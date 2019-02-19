<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


include('/var/www/annearundelproperties.net/new/includes/global.php');


$agent_id = $_POST['agent_id'];

$agentQuery ="SELECT * FROM company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$agent_name = $agent[0]['fullname'];

// Set the uplaod directory

$uploadDir = '/var/www/annearundelproperties.net/new/agents/credit_reports/uploads/';

if (!empty($_FILES)) {
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$origFileName = $_FILES['Filedata']['name'];
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	$fName = $fileParts['filename'];
	$fExt = $fileParts['extension'];
	$fileName = preg_replace($_GLOB_bad_characters, '_', $fName).'_'.date('YmdHis').'.'.$fExt;
    $targetFile = $uploadDir . $fileName;

    $link = str_replace('/var/www/', 'https://', $targetFile);



	// Save the file
	move_uploaded_file($tempFile, $targetFile);

	if(file_exists($targetFile)) {
		chmod($targetFile, 0775);

		//sendMail('', 'kyle@taylorprops.com', 'internal@taylorprops.com', 'Credit Report Requested', $body, '', '', '');

	    $addInvoice = "insert into company.credit_report_payments_test (
			agent_id,
			created_by
		) values (
			'".$agent_id."',
			'".$db->quote($_SESSION['S_Username'])."'
		)";
		$queryResults = $db -> query($addInvoice);
		$id = $db -> id();

		echo $id;
	} else {
		//echo 'error '.$_FILES['Filedata']['tmp_name'];
		$error_body = 'Credit report upload error.<br>Error uploading<br>'.$agent_name.'<br>Report ID: '.$id;
		//sendMail('', 'mike@taylorprops.com', 'internal@taylorprops.com', 'Credit Report Upload Error', $body, '', '', '');

	}

}else {
	$error_body = 'Credit report upload error.<br>No file exists<br>'.$agent_name.'<br>Report ID: '.$id;
	//sendMail('', 'mike@taylorprops.com', 'internal@taylorprops.com', 'Credit Report Upload Error', $body, '', '', '');

}




?>