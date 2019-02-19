<?php
/* _DONE */
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];
$mls = $_POST['mls'];

$transQuery = "select ListingSourceRecordID, MlsStatus, FullStreetAddress, City, StateOrProvince, PostalCode, Closed from company.mls_company where ListingSourceRecordID = '".$mls."'";
$trans = $db -> select($transQuery);

$ListingSourceRecordID = $trans[0]['ListingSourceRecordID'];
$status = $trans[0]['MlsStatus'];
$street = $trans[0]['FullStreetAddress'];
$city = $trans[0]['City'];
$state = $trans[0]['StateOrProvince'];
$zip = $trans[0]['PostalCode'];
$closed = $trans[0]['Closed'];

if($closed == 'no') {
	$trans_type = 'open_trans';
	$doc_type = 'Upload';
} else {
	$trans_type = 'closed_trans';
	$doc_type = 'View';
}



?>
<div class="trans keep_width <?php echo $trans_type; ?>" data-info="<?php echo $ListingSourceRecordID.' '.$street; ?>" data-mls="<?php echo $ListingSourceRecordID; ?>">
	<div class="upload_status keep_width"></div>
	<table width="97%" cellpadding="5" cellspacing="0" align="center">
    	<tr>
        	<td width="50%" class="trans_mls"><?php echo $ListingSourceRecordID; ?></td>
            <td width="50%" align="right" class="trans_status"><?php echo $status; ?></td>
        </tr>
        <tr>
        	<td colspan="2" style="padding-top: 10px;"><?php echo $street.'<br>'.$city.', '.$state.' '.$zip; ?></td>
        </tr>
        <tr>
        	<td align="left" style="padding-top: 10px"><a href="/new/agents/uploads/new/upload_b.php?ListingSourceRecordID=<?php echo $ListingSourceRecordID; ?>" class="button button_normal" target="_blank"><?php echo $doc_type; ?> Docs</a></td>
            <td align="right" style="padding-top: 10px"><a href="javascript: void(0)" class="button button_normal status_button" data-mls="<?php echo $ListingSourceRecordID; ?>">View Status</a></td>
        </tr>
    </table>
</div>
