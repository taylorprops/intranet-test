<?php
/* _DONE */
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];
$mls = $_POST['mls'];


$transQuery = "select ListingSourceRecordID, ListOfficeMlsId, MlsStatus, FullStreetAddress, City, StateOrProvince, PostalCode, Closed, HaveContract, HaveListing from company.mls_company where ListingSourceRecordID = '".$mls."'";
$trans = $db -> select($transQuery);

$docsQuery = "SELECT * from company.contract_docs_missing where mls = '".$mls."' and agent_id = '".$agent_id."'";
$docs = $db -> select($docsQuery);
$docsCount = count($docs);

$uploadListingQuery = "SELECT * from company.mls_uploads where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and upload_type like '%listing%'";
$uploadListing = $db -> select($uploadListingQuery);
$uploadListingCount = count($uploadListing);

$uploadContractQuery = "SELECT * from company.mls_uploads where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and upload_type not like '%listing%'";
$uploadContract = $db -> select($uploadContractQuery);
$uploadContractCount = count($uploadContract);

$contract_not_submitted = array('ACTIVE UNDER CONTRACT', 'PENDING', 'CLOSED');
if(in_array($trans[0]['MlsStatus'], $contract_not_submitted) && ($trans[0]['HaveContract'] == 'no' || $trans[0]['HaveContract'] == '') && $uploadContractCount == 0 && $trans[0]['Closed'] == 'no') {
	$error = 'Contract Not Submitted';
}

if(($trans[0]['HaveListing'] == 'no' || $trans[0]['HaveListing'] == '') && (stristr($trans[0]['ListOfficeMlsId'], 'tayl') || stristr($trans[0]['ListOfficeMlsId'], 'aapi')) && $uploadListingCount == 0 && $trans[0]['Closed'] == 'no') {
	if($error != '') {
		$error = 'Listing and Contract Not Submitted';
	} else {
		$error= 'Listing Not Submitted';
	}
}
if($docsCount > 0) {
	$error = 'Missing Docs';
}

echo $error;
?>
