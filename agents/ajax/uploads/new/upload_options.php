<?php
/* _DONE */
include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');

$mls = $_POST['mls'];
$agent_id = $_POST['agent_id'];

// Listing Info
$listingQuery = "select * from company.mls_company where ListingSourceRecordID = '".$mls."'";
$listing = $db -> select($listingQuery);

$PropertyType = $listing[0]['PropertyType'];
$ListOfficeMlsId = $listing[0]['ListOfficeMlsId'];
$HaveListing = $listing[0]['HaveListing'];
$HaveContract = $listing[0]['HaveContract'];

if(stristr($PropertyType, 'lease')) {
	$for_sale = 'N';
	$contract_type = 'Lease Agreement';
} else {
	$for_sale = 'Y';
	$contract_type = 'Sales Contract';
}

if(stristr($ListOfficeMlsId, 'tayl') || stristr($ListOfficeMlsId, 'aapi')) {
	$our_listing = 'yes';
} else {
	$our_listing = 'no';
}

// Missing Docs

$missingQuery = "select * from company.contract_docs_missing where mls = '".$mls."' and agent_id = '".$agent_id."'";
$missing = $db -> select($missingQuery);
$missingCount = count($missing);

$missing_listing_docs = array();
$missing_contract_docs = array();


for($m=0;$m<$missingCount;$m++) {

	if($missing[$m]['doc_type'] == 'listing') {
		$missing_listing_docs[$m]['docs'] = $missing[$m]['doc_name'];
		$missing_listing_docs[$m]['notes'] = $missing[$m]['doc_notes'];
	} else if($missing[$m]['doc_type'] == 'contract') {
		$missing_contract_docs[$m]['docs'] = $missing[$m]['doc_name'];
		$missing_contract_docs[$m]['notes'] = $missing[$m]['doc_notes'];
	}

}

$uploadsQuery = "select * from company.mls_uploads where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and invalid = 'no' and hide = 'no'";
$uploads = $db -> select($uploadsQuery);
$uploadsCount = count($uploads);

if($uploadsCount > 0) {
	$released = 'yes';
}

$listing_submitted = 'no';
$contract_submitted = 'no';

$listingQuery = "select * from company.mls_company where ListingSourceRecordID = '".$mls."'";
$listing = $db -> select($listingQuery);

if($HaveListing == 'yes') {
	$listing_submitted = 'yes';
} else {
	$listing_submitted = 'no';
}
if($HaveContract == 'yes') {
	$contract_submitted = 'yes';
} else {
	$contract_submitted = 'no';
}

for($u=0;$u<$uploadsCount;$u++) {

	//if($uploads[$u]['doc_type'] == 'Listing Docs') {
		//$listing_submitted = 'yes';
	//}

	if($uploads[$u]['doc_type'] == 'Contract Docs') {
		if($uploads[$u]['released'] == 'no') {
			$released = 'no';
		}
	}
	if($uploads[$u]['doc_type'] == 'Lease Docs') {
		$contract_submitted = 'yes';
	}

}

//if($contract_submitted == 'yes' && $released == 'yes') {
	//$contract_submitted = 'no';
//}


?>

<div id="listing">
    <table width="100%">
        <?php if($listing_submitted == 'no') { ?>
        <tr>
            <td>Listing Agreement and Related Documents</td>
            <td align="right"><input type="file" id="listing_docs_upload" class="uploader button"></td>
        </tr>
        <tr>
            <td colspan="2" height="50"></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td colspan="2" height="50"></td>
        </tr>
        <tr>
            <td colspan="2" class="instructions">You may only upload a Listing Withdrawal once we have received and processed a Listing Agreement</td>
        </tr>

        <tr class="disabled_tr">
            <td>Listing Withdrawal</td>
            <td align="right"><input type="file" id="listing_withdrawal_upload" class="uploader button" disabled="disabled"></td>
        </tr>
        <?php } else { ?>
        <tr>
            <td>Additional Listing Agreement Documents</td>
            <td align="right"><input type="file" id="listing_docs_upload" class="uploader button"></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td>Listing Withdrawal</td>
            <td align="right"><input type="file" id="listing_withdrawal_upload" class="uploader button"></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td colspan="2"><div id="listing_queue"></div></td>
        </tr>

    </table>
    <div class="spacer"></div>
</div>
<div id="contract">
    <table width="100%">
        <?php if($contract_submitted == 'no') { ?>
        <tr>
            <td><?php echo $contract_type; ?> and Related Documents
            <table><tr><td><img src="/new/images/icons/warning_red.png" height="11"></td><td style="color: #C65155; font-size: 12px;">Upload Commission Breakdowns and Releases Below</td></tr></table></td>
            <td align="right"><input type="file" id="contract_docs_upload" class="uploader button"></td>
        </tr>
        <tr>
            <td colspan="2" height="50"></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td colspan="2" height="50"></td>
        </tr>
        <tr>
            <td colspan="2" class="instructions">You may only upload a <?php if($for_sale == 'Y') { ?>Release or <?php } ?>Commission Breakdown once we have received and processed a <?php echo $contract_type; ?></td>
        </tr>
        <?php if($for_sale == 'Y') { ?>
        <tr class="disabled_tr">
            <td>Release of Contract</td>
            <td align="right"><input type="file" id="contract_release_upload" class="uploader button" disabled="disabled"></td>
        </tr>
        <?php } ?>
        <tr class="disabled_tr">
            <td>Commission Breakdown<?php if($for_sale == 'Y') { ?> and Closing Disclosure<?php } ?></td>
            <td align="right"><input type="file" id="contract_cb_upload" class="uploader button" disabled="disabled"></td>
        </tr>

        <?php } else { ?>
        <tr>
            <td>Additional <?php echo $contract_type; ?> Documents</td>
            <td align="right"><input type="file" id="contract_docs_upload" class="uploader button"></td>
        </tr>
        <tr>
            <td colspan="2" height="50"></td>
        </tr>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td colspan="2" height="50"></td>
        </tr>
        <?php if($for_sale == 'Y') { ?>
        <tr>
            <td>Release of Contract</td>
            <td align="right"><input type="file" id="contract_release_upload" class="uploader button"></td>
        </tr>
        <?php } ?>
        <tr>
            <td>Commission Breakdown<?php if($for_sale == 'Y') { ?> and Closing Disclosure<?php } ?></td>
            <td align="right"><input type="file" id="contract_cb_upload" class="uploader button"></td>
        </tr>

        <?php } ?>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
         <tr>
            <td colspan="2"><div id="contract_queue"></div></td>
        </tr>
    </table>
    <div class="spacer"></div>
</div>
