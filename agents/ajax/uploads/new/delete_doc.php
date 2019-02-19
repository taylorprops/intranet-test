<?php
include('/var/www/annearundelproperties.net/new/includes/global.php');

$id = $_POST['id'];

$findQuery = "select * from company.mls_uploads".$uploads_test." where upload_id = '".$id."'";
$find = $db -> select($findQuery);

if($find[0]['upload_type'] == 'Listing Agreement') { 
	$upload_type = "upload_type = 'Listing Agreement'";
} else {
	$upload_type = "upload_type in ('Sales Contract', 'Commission Breakdown', 'Release')"; 
}

$folder_path = '/var/www/annearundelproperties.net/new/agents/uploads/uploads/deleted/'.$find[0]['upload_agent_id'].'/'.$find[0]['upload_mls'].'/';

if(!is_dir($folder_path)) {
	mkdir($folder_path, 0777, true);
}

// Check to make sure the file being removed is not needed by another upload
$checkQuery = "select * from company.mls_uploads".$uploads_test." where upload_mls = '".$find[0]['upload_mls']."' and ".$upload_type." and upload_file_name = '".$find[0]['upload_file_name']."' and upload_id != '".$id."'";
$check = $db -> select($checkQuery);
if(count($check) == 0) {

	rename(str_replace('https://', '/var/www/', $find[0]['upload_loc']), '/var/www/annearundelproperties.net/new/agents/uploads/uploads/deleted/'.$find[0]['upload_agent_id'].'/'.$find[0]['upload_mls'].'/'.$find[0]['upload_file_name']);
	
}

$delete = "update company.mls_uploads".$uploads_test." set hide = 'yes', upload_loc = '".str_replace('/var/www/', 'https://', $folder_path).$find[0]['upload_file_name']."' where upload_id = '".$id."'";
$queryResults = $db -> query($delete);

$desc = 'Deleted upload: '.$find[0]['upload_orig_filename'].'. <a href="'.$find[0]['upload_loc'].'" target="_blank">View Deleted Upload</a>';
changes($_SESSION['S_Username'], 'Uploads', 'Deleted Uploaed', '', '', $desc, '', '', $find[0]['upload_mls']);

?>