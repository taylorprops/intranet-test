<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


include('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];
$file_size = $_FILES['Filedata']['size'];

$agentQuery = "SELECT * FROM company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$agent_name = $db -> quote($agent[0]['fullname']);
$street = trim($db -> quote($_POST['street']));
$city = trim($db -> quote($_POST['city']));
$state = trim($db -> quote($_POST['state']));
$zip = trim($db -> quote($_POST['zip']));
$amount = trim($db -> quote($_POST['amount']));
$group_id = $_POST['group_id']; 

$addedQuery = "SELECT * FROM company.commission where group_id = '".$group_id."'";
$added = $db -> select($addedQuery);


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
;
	if(count($added) == 0) {
	
        $insert = "insert into company.commission (
        recip1, 
        recip1_id, 
        recip1_check_amount,
        referral_street, 
        referral_city, 
        referral_state, 
        referral_zip, 
        referral_only,
        group_id
        ) values (
        '".$agent_name."',
        '".$agent_id."', 
        '".$amount."',
        '".$street."', 
        '".$city."', 
        '".$state."', 
        '".$zip."', 
        'yes',
        '".$group_id."'
        )";
        
        $queryResults = $db -> query($insert);
        $id = $db -> id();
        
        $subject = 'Referral Agreement Uploaded by '.$agent_name;
        $GLOB_email_top = str_replace('%%preheader%%', '', $GLOB_email_top);
        $body = str_replace('%%subject%%', $subject, $GLOB_email_top).$agent_name.' just uploaded a referral agreement for '.$street.' '.$city.', '.$state.' '.$zip.'.<br><a href="https://annearundelproperties.net/new/real_estate/agents/referral_commission/referral_commissions.php" target="_blank">View Uploads</a>
        '.$GLOB_email_bottom;
        
        if($agent_id != 3193) { 
            sendMail('', 'admin@taylorprops.com', 'internal@taylorprops.com', $subject, $body, '', '', '');
        }
    }

    $insert_upload = "insert into company.commission_uploads (agent_id, upload_loc, comm_id, file_name, group_id) values ('".$agent_id."', '".$link."','".$id."', '".$origFileName."', '".$group_id."')";
	$queryResults = $db -> query($insert_upload);


	// Save the file
	move_uploaded_file($tempFile, $targetFile);
	if(file_exists($targetFile)) {
		chmod($targetFile, 0775);
		echo 1;
	} else {
		echo 'error '.$_FILES['Filedata']['tmp_name']; 
	}


	
}
?>