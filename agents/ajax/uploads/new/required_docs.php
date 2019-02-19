<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$mls = $_POST['mls'];
$type = $_POST['type'];
$agent_id = $_POST['agent_id'];

$contractQuery = "SELECT * from company.mls_company where ListingSourceRecordID = '".$mls."'";
$contract = $db -> select($contractQuery);

$ListOfficeMlsId = $contract[0]['ListOfficeMlsId'];
$BuyerOfficeMlsId = $contract[0]['BuyerOfficeMlsId'];
$ListAgentMlsId = $contract[0]['ListAgentMlsId'];
$ListAgentCompID = $contract[0]['ListAgentCompID'];
$SaleAgentCompID = $contract[0]['SaleAgentCompID'];

$agentQuery = "SELECT * from company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);
$mris_ids = $agent[0]['mris_id_tp_va'].$agent[0]['mris_id_tp_md'].$agent[0]['mris_id_aap'];

$docs_submitted = 'yes';

$submittedDocsQuery = "select * from company.mls_uploads where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and hide = 'no' and invalid = 'no' and released = 'no' and (downloaded_by is not null and downloaded_by != '') and upload_type like '%".$type."%'";
$submittedDocs = $db -> select($submittedDocsQuery);
if(count($submittedDocs) == 0) {
	$docs_submitted = 'no';
}

if((stristr($ListOfficeMlsId, 'tayl') || stristr($ListOfficeMlsId, 'aapi')) && (stristr($mris_ids, $ListAgentMlsId)) && !stristr($BuyerOfficeMlsId, 'tayl') && !stristr($BuyerOfficeMlsId, 'aapi')) {
	$rep_buyer = 'no';
} else {
	$rep_buyer = 'yes';
}

if(($type == 'listing' && $ListAgentCompID == $agent_id) || ($type == 'contract' && $SaleAgentCompID == $agent_id)) {
	$in_house = 'yes';
}

$docs = required_docs($mls, $type, $agent_id);
$docsCount = count($docs);
?>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="required_docs_table">
    <?php if($in_house == 'yes') { ?>
    <tr>
        <th colspan="2">In-House Docs</th>
    </tr>
    <?php
            for($d=0;$d<$docsCount;$d++) {
                if($docs[$d]['in_house'] == 'Yes') {
					$missingDocsQuery = "select * from company.contract_docs_missing where mls = '".$mls."' and doc_type = '".$type."' and agent_id = '".$agent_id."' and doc_id = '".$docs[$d]['id']."'";
					$missingDocs = $db -> select($missingDocsQuery); ?>
    <tr>
        <td width="40"><?php if($docs_submitted == 'no') { ?>
            <img src="/new/images/icons/hour_glass_blue.png" height="20">
            <?php } else { if(count($missingDocs) > 0) { ?>
            <img src="/new/images/icons/warning_red_new.png" height="20">
            <?php } else { ?>
            <img src="/new/images/icons/check_green_new.png" height="20">
            <?php } } ?></td>
        <td><?php
				echo $docs[$d]['doc_name'];
				if($missingDocs[0]['doc_notes'] != '') { ?>
            <br>
            <span style="font-style: italic; font-size: 13px; color:#C65155">Notes: <?php echo $missingDocs[0]['doc_notes']; ?></span>
            <?php } else {
					if(count($missingDocs) > 0) { ?>
                    <br>
           			<span style="font-style: italic; font-size: 13px; color:#C65155">Missing</span>
                    <?php }
			} ?></td>
    </tr>
    <?php }
            }
        } ?>
    <tr>
        <th colspan="2">Required Docs</th>
    </tr>

    <?php
	for($d=0;$d<$docsCount;$d++) {
    	if($docs[$d]['required'] == 'Yes' && $docs[$d]['in_house'] == 'No') {
			$missingDocsQuery = "select * from company.contract_docs_missing where mls = '".$mls."' and doc_type = '".$type."' and agent_id = '".$agent_id."' and doc_id = '".$docs[$d]['id']."'";
			$missingDocs = $db -> select($missingDocsQuery);?>
    <tr>
        <td width="40"><?php if($docs_submitted == 'no') { ?>
            <img src="/new/images/icons/hour_glass_blue.png" height="20">
            <?php } else { if(count($missingDocs) > 0) { ?>
            <img src="/new/images/icons/warning_red_new.png" height="20">
            <?php } else { ?>
            <img src="/new/images/icons/check_green_new.png" height="20">
            <?php } } ?></td>
        <td><?php
				echo $docs[$d]['doc_name'];
				if($missingDocs[0]['doc_notes'] != '') { ?>
            <br>
            <span style="font-style: italic; font-size: 13px; color:#C65155">Notes: <?php echo $missingDocs[0]['doc_notes']; ?></span>
            <?php } else {
					if(count($missingDocs) > 0) { ?>
                    <br>
           			<span style="font-style: italic; font-size: 13px; color:#C65155">Missing</span>
                    <?php }
			} ?></td>
    </tr>
    <?php }
    }?>
	<tr>
        <th colspan="2">Conditional Docs</th>
    </tr>

	<?php
	for($d=0;$d<$docsCount;$d++) {
    	if($docs[$d]['required'] == 'No' && $docs[$d]['in_house'] == 'No') {
			$missingDocsQuery = "select * from company.contract_docs_missing where mls = '".$mls."' and doc_type = '".$type."' and agent_id = '".$agent_id."' and doc_id = '".$docs[$d]['id']."'";
			$missingDocs = $db -> select($missingDocsQuery);
			if(count($missingDocs) > 0) {
			?>
    <tr>
        <td width="40"><?php if($docs_submitted == 'no') { ?>
            <img src="/new/images/icons/hour_glass_blue.png" height="20">
            <?php } else { if(count($missingDocs) > 0) { ?>
            <img src="/new/images/icons/warning_red_new.png" height="20">
            <?php } else { ?>
            <img src="/new/images/icons/check_green_new.png" height="20">
            <?php } } ?></td>
        <td><?php
				echo $docs[$d]['doc_name'];
				if($missingDocs[0]['doc_notes'] != '') { ?>
            <br>
            <span style="font-style: italic; font-size: 13px; color:#C65155">Notes: <?php echo $missingDocs[0]['doc_notes']; ?></span>
            <?php } else {
					if(count($missingDocs) > 0) { ?>
                    <br>
           			<span style="font-style: italic; font-size: 13px; color:#C65155">Missing</span>
                    <?php }
			} ?></td>
    </tr>
    <?php }
		}
    }?>

</table>
<span style="display:none" id="doc_type"><?php echo $doc_type; ?></span>
