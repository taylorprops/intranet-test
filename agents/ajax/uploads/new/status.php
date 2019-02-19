<?php
/* _DONE */
include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');

$mls = $_POST['mls'];
$agent_id = $_POST['agent_id'];

// Listing Info
$listingQuery = "select * from company.mls_company where ListingSourceRecordID = '".$mls."'";
$listing = $db -> select($listingQuery);

$ListOfficeMlsId = $listing[0]['ListOfficeMlsId'];
$HaveListing = $listing[0]['HaveListing'];
$HaveContract = $listing[0]['HaveContract'];

$PropertyType = $listing[0]['PropertyType'];

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
		$missing_listing_docs[] = $missing[$m]['doc_name'];
	} else if($missing[$m]['doc_type'] == 'contract') {
		$missing_contract_docs[] = $missing[$m]['doc_name'];
	}

}

$uploadsQuery = "select * from company.mls_uploads where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and invalid = 'no' and hide = 'no'";
$uploads = $db -> select($uploadsQuery);
$uploadsCount = count($uploads);

$contractDocsQuery = "select * from company.mls_uploads where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and invalid = 'no' and hide = 'no' and doc_type = 'Contract Docs'";
$contractDocs = $db -> select($contractDocsQuery);
$contractDocsCount = count($contractDocs);

$released = 'yes';

if($contractDocsCount == 0) {
	$released = 'no';
}

$listing_submitted = 'no';
$contract_submitted = 'no';

for($u=0;$u<$uploadsCount;$u++) {

	if($uploads[$u]['doc_type'] == 'Contract Docs' || $uploads[$u]['doc_type'] == 'Lease Docs') {
		$contract_submitted = 'yes';
		if($uploads[$u]['released'] == 'no') {
			$released = 'no';
		}
	} else if($uploads[$u]['doc_type'] == 'Listing Docs') {
		$listing_submitted = 'yes';
	}

}

?>

<div id="listing">
<?php if($our_listing == 'yes') {
    if($HaveListing == 'yes') { ?>

        <table>
            <tr>
                <td style="padding-right: 15px"><img src="/new/images/icons/check_green_new.png" height="25"></td>
                <td>Listing Agreement Submitted and Processed</td>
            </tr>
        </table>

        <?php
        if(count($missing_listing_docs) == 0) { ?>

        <table>
            <tr>
                <td style="padding-right: 15px"><img src="/new/images/icons/check_green_new.png" height="25"></td>
                <td>All Documents Received</td>
            </tr>
        </table>

        <?php
        } else { ?>

        <table>
            <tr>
                <td style="padding-right: 15px"><img src="/new/images/icons/warning_red_new.png" height="25"></td>
                <td>Missing Documents</td>
                <td><a href="javascript: void(0)" class="required_listing_docs_link upload_link">View Missing Docs</a></td>
            </tr>
        </table>

        <?php
        } ?>
    <?php
    } else {

		if($listing_submitted == 'yes') { ?>

            <table>
                <tr>
                    <td style="padding-right: 15px"><img src="/new/images/icons/hour_glass_blue.png" height="25"></td>
                    <td>Listing Agreement Submitted But Not Processed Yet</td>
                </tr>
            </table>

        <?php } else { ?>

            <table>
                <tr>
                    <td style="padding-right: 15px"><img src="/new/images/icons/warning_red_new.png" height="25"></td>
                    <td>Listing Agreement Not Submitted</td>
                </tr>
            </table>

    <?php
		}
    }
} ?>
</div>
<div id="contract">
<?php
if($released == 'yes') {  ?>
	<table>
        <tr>
            <td style="padding-right: 15px"><img src="/new/images/icons/warning_red_new.png" height="25"></td>
            <td>All Documents Have Been Released</td>
        </tr>
    </table>
<?php
} else {
	if($HaveContract == 'yes') {
	?>

		<table>
			<tr>
				<td style="padding-right: 15px"><img src="/new/images/icons/check_green_new.png" height="25"></td>
				<td><?php echo $contract_type; ?> Submitted and Processed</td>
			</tr>
		</table>

		<?php
		if(count($missing_contract_docs) == 0) { ?>

			<table>
				<tr>
					<td style="padding-right: 15px"><img src="/new/images/icons/check_green_new.png" height="25"></td>
					<td>All Documents Received</td>
				</tr>
			</table>

		<?php
		} else {
		?>

			<table>
				<tr>
					<td style="padding-right: 15px"><img src="/new/images/icons/warning_red_new.png" height="25"></td>
					<td>Missing Documents</td>
                    <td><a href="javascript: void(0)" class="required_contract_docs_link upload_link">View Missing Docs</a></td>
				</tr>
			</table>

		<?php
		}
	} else if($HaveContract == 'no' || $HaveContract == '') {

		if($contract_submitted == 'yes') {
	?>
			<table>
					<tr>
						<td style="padding-right: 15px"><img src="/new/images/icons/hour_glass_blue.png" height="25"></td>
						<td><?php echo $contract_type; ?> Submitted But Not Processed Yet</td>
					</tr>
				</table>

		<?php } else { ?>

		<table>
			<tr>
				<td style="padding-right: 15px"><img src="/new/images/icons/warning_red_new.png" height="25"></td>
				<td><?php echo $contract_type; ?> Not Submitted</td>
			</tr>
		</table>

	<?php }
	}
}?>
</div>
