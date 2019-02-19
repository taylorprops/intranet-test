<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$ListingSourceRecordID = $_GET['ListingSourceRecordID'];
$agent_id = $_GET['agent_id'];
$dir = $_GET['dir'];


$fileParts = pathinfo($_POST['filename']);
$fName = $fileParts['filename'];
$fExt = $fileParts['extension'];

$filename = preg_replace($_GLOB_bad_characters, '_', $fName).'.'.$fExt;


$targetFolder =  '/var/www/annearundelproperties.net/new/agents/uploads/uploads/'.$agent_id.'/'.$ListingSourceRecordID.'/'.$dir;

if (file_exists($targetFolder . '/' . $filename)) {
	echo 1;
} else {
	echo 0;
}
?>
