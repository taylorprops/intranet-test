<?php
include('/var/www/annearundelproperties.net/new/includes/global.php');

$mls = $_POST['mls'];
$type = $_POST['type'];
$agent_id = $_POST['agent_id'];

$contractQuery = "SELECT * from company.mls_company".$contract_test." where ListingID = '".$mls."'";
$contract = $db -> select($contractQuery);

$questionsQuery = "SELECT * from company.mls_uploads_questions".$contract_test." where mls = '".$mls."' and agent_id = '".$agent_id."' order by id DESC";
$questions = $db -> select($questionsQuery);

if($questions[0]['earnest'] == 'heritage' || $questions[0]['earnest'] == 'title') {
	$title_earnest = 'yes';
}

$submittedDocsQuery = "select * from company.mls_uploads".$contract_test." where upload_mls = '".$mls."' and upload_agent_id = '".$agent_id."' and hide = 'no' and invalid = 'no' and released = 'no' and (downloaded_by is not null and downloaded_by != '') and upload_type like '%".$type."%'";
$submittedDocs = $db -> select($submittedDocsQuery);
if(count($submittedDocs) == 0) {
	$docs_submitted = 'no';
}

if(stristr($contract[0]['ListOfficeCode'], 'tayl') || stristr($contract[0]['ListOfficeCode'], 'aapi')) {
	$rep_seller = 'yes';
} else {
	$rep_buyer = 'yes';
}

if(stristr($contract[0]['PropertyType'], 'lot')) { 
	$lot = "and lot != 'No'";
}

if($contract[0]['CondoCoopFee'] > 0 || $contract[0]['HOAFee'] > 0) {
	$hoa = 'yes';
}

if($contract[0]['YearBuilt'] < 1978 && $contract[0]['YearBuilt'] != '0' && $contract[0]['YearBuilt'] != '') {
	$lead = 'yes';
}
if($contract[0]['YearBuilt'] > 2001 && $contract[0]['YearBuilt'] != '0' && $contract[0]['YearBuilt'] != '' && substr($contract[0]['ListingID'], 0, 2) == 'CH') {
	$fairshare = 'yes';
	$fair  = " or (id = 122)";
} else {
	$fair = ' and id != 122';
}
if($contract[0]['State'] == 'MD') {
	$countyAbbr = substr($contract[0]['ListingID'], 0, 2);
} else {
	$countyAbbr = '';
}
if($contract[0]['ListingTransactionType'] == 'REO/Bank Owned' || $contract[0]['ListingTransactionType'] == 'Foreclosure' || ($contract[0]['NewConstruction'] == 'Y' && $contract[0]['ListingTransactionType'] != 'Potential Short Sale')) {
	$trans = "and transaction_type like '%Foreclosure%'";
} else if($contract[0]['ListingTransactionType'] == 'Potential Short Sale') {
	$trans = "and transaction_type like '%Short Sale%'";
} else if($contract[0]['ListingTransactionType'] == 'Standard') {
	$trans = "and transaction_type like '%Standard%'";
} else {
	$trans = "";
}
if($contract[0]['ForSale'] == 'Y') {
	$forsale = "and forsale like '%Sale%'";
	if($type == 'listing') {
		$doc_type = 'Listing Agreement';
	} else {
		$doc_type = 'Sales Contract';
	}
} else {
	$forsale = "and forsale like '%Rental%'";
	if($type == 'listing') {
		$doc_type = 'Listing Agreement';
	} else {
		$doc_type = 'Lease Agreement';
	}
}

if($rep_buyer == 'yes') {
	$buyer = 'yes';
	$rep_buyer = "";
} else {
	$rep_buyer = "and rep_buyer != 'Yes'";
	$buyer = 'no';

}

if($title_earnest == 'yes') {
	$letter = " or (id = 118)";
} else {
	$letter = ' and id != 118';
}

$docsQuery = "select * from company.contract_docs where active = 'yes' and (doc_type like '%".$type."%' and ".$contract[0]['State']." = 'Yes' ".$trans." ".$forsale." ".$rep_buyer." ".$lot." and ".$contract[0]['State']."_counties_abbr like '%".$countyAbbr."%') ".$letter." ".$fair." order by doc_name";
$docs = $db -> select($docsQuery);
$docsCount = count($docs);
echo $docsQuery;


?>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="required_docs_table">
    <?php if($buyer == 'yes') { ?>
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
        } else if($doc_type == 'Listing Agreement') { ?>
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
    	if((($docs[$d]['required'] == 'Yes' && $docs[$d]['in_house'] == 'No') || ($docs[$d]['id'] == 122 && $fairshare == 'yes') || ($docs[$d]['id'] == 118 && $title_earnest == 'yes') || $docs[$d]['id'] == 54) && $docs[$d]['id'] != 56 && $docs[$d]['id'] != 55) { 
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
    <?php
	if($lead == 'yes') {
		 for($d=0;$d<$docsCount;$d++) {
    		if($docs[$d]['lead'] == 'Yes') {
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
        <td>Built in <?php echo $contract[0]['YearBuilt']; ?> - <?php echo $docs[$d]['doc_name']; 
				if($missingDocs[0]['doc_notes'] != '') { ?> <br>
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
    <?php
	if($hoa == 'yes') {
		 for($d=0;$d<$docsCount;$d++) {
    		if($docs[$d]['hoa'] == 'Yes') {
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
	}?>
</table>
<span style="display:none" id="doc_type"><?php echo $doc_type; ?></span>