<?php
include('/var/www/annearundelproperties.net/new/includes/global.php');

	
$mls = $_POST['mls'];
$agent_id = $_POST['agent_id'];
$type = $_POST['type'];

$contractQuery = "SELECT * from company.mls_company where ListingID = '".$mls."'";
$contract = $db -> select($contractQuery);

	
if(stristr($contract[0]['ListOfficeCode'], 'tayl') || stristr($contract[0]['ListOfficeCode'], 'aapi')) {
	$rep_seller = 'yes';
} else {
	$rep_buyer = 'yes';
}

$questionsQuery = "SELECT * from company.mls_uploads_questions where mls = '".$mls."' and agent_id = '".$agent_id."' order by id DESC";
$questions = $db -> select($questionsQuery);

if($questions[0]['earnest'] == 'heritage' || $questions[0]['earnest'] == 'title') {
	$title_earnest = 'yes';
} else {
	$title_earnest = '';
}


if(stristr($contract[0]['PropertyType'], 'lot')) { 
	$lot = "and lot != 'No'";
} else {
	$lot = '';
}



if($contract[0]['YearBuilt'] < 1978 && $contract[0]['YearBuilt'] != '0' && $contract[0]['YearBuilt'] != '' && $contract[0]['YearBuilt'] != '0') {
	$lead = 'yes';
} else {
	$lead = '';
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

$foreclosure = 'no';
if(($contract[0]['ListingTransactionType'] == 'REO/Bank Owned' || $contract[0]['ListingTransactionType'] == 'Foreclosure' || $contract[0]['ListingTransactionType'] == 'Other/Undisclosed' || ($contract[0]['NewConstruction'] == 'Y') && $contract[0]['ListingTransactionType'] != 'Potential Short Sale')) {
	$trans = "and transaction_type like '%Foreclosure%'";
	$foreclosure = 'yes';
} else if($contract[0]['ListingTransactionType'] == 'Potential Short Sale') {
	$trans = "and transaction_type like '%Short Sale%'";
} else if($contract[0]['ListingTransactionType'] == 'Standard') {
	$trans = "and transaction_type like '%Standard%'";
} else if($contract[0]['ListingTransactionType'] == 'FSBO') {
	$trans = "and (transaction_type like '%FSBO%' or transaction_type like '%Standard%')";
} else {
	$trans = "";
}

if($contract[0]['CondoCoopFee'] > 0 || $contract[0]['HOAFee'] > 0) {
	if($foreclosure == 'no') {
		$hoa = 'yes';
	}
} else {
	$hoa = '';
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

$docsQuery = "select * from company.contract_docs where active = 'yes' and (doc_type like '%".$type."%' and ".$contract[0]['State']." = 'Yes' ".$trans." ".$forsale." ".$rep_buyer." ".$lot." and ".$contract[0]['State']."_counties_abbr like '%".$countyAbbr."%') ".$letter." order by doc_name";
$docs = $db -> select($docsQuery);
$docsCount = count($docs);


ob_start();
?>
<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">
	<page_header>
        <div style="font-size:20px; font-weight:bold; margin-left: 35px; margin-top: 25px;">Checklist - <?php echo $doc_type.' - '.$contract[0]['County']; ?> County
        <br><?php echo $mls.' - '.$contract[0]['FullStreetAddress'].' '.$contract[0]['CityName'].', '.$contract[0]['State'].' '.$contract[0]['PostalCode']; ?></div>
    </page_header>
    <style type="text/css">
	.check_box { height: 20px; width: 20px; border: 1px solid #333; }
	th, td { padding: 7px; }
	</style>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="required_docs_table">
    <?php 
	if($buyer == 'yes') { ?>
    <tr>
        <th colspan="2">In-House Docs</th>
    </tr>
    <?php 
    	for($d=0;$d<$docsCount;$d++) {
        	if($docs[$d]['in_house'] == 'Yes') { ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name'];?></td>
    </tr>
    <?php   }
        }
	} else if($doc_type == 'Listing Agreement') { ?>
    <tr>
        <th colspan="2">In-House Docs</th>
    </tr>
    <?php 
    	for($d=0;$d<$docsCount;$d++) {
        	if($docs[$d]['in_house'] == 'Yes') {  ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name']; ?></td>
    </tr>
    <?php 	}
        }
    } ?>
    <tr>
        <th colspan="2">Required Docs</th>
    </tr>
    <?php 
	for($d=0;$d<$docsCount;$d++) {
    	if((($docs[$d]['required'] == 'Yes' && $docs[$d]['in_house'] == 'No') || ($docs[$d]['id'] == 122 && $fairshare == 'yes') || ($docs[$d]['id'] == 118 && $title_earnest == 'yes') || $docs[$d]['id'] == 54) && $docs[$d]['id'] != 56 && $docs[$d]['id'] != 55) {  ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name']; ?></td>
    </tr>
    <?php }
    }?>
    <?php
	if($lead == 'yes') {
		 for($d=0;$d<$docsCount;$d++) {
    		if($docs[$d]['lead'] == 'Yes') { ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td>Built in <?php echo $contract[0]['YearBuilt']; ?> - <?php echo $docs[$d]['doc_name']; ?></td>
    </tr>
    	<?php 
			}
		}
	}?>
    <?php
	if($hoa == 'yes') {
		 for($d=0;$d<$docsCount;$d++) {
    		if($docs[$d]['hoa'] == 'Yes') { ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name']; ?> </td>
    </tr>
    <?php }
    	}
	}?>
    <tr>
        <th colspan="2">Conditional Docs</th>
    </tr>
    <tr>
        <td><div class="check_box"></div></td>
        <td>Disclosure of Licensee Status Addendum </td>
    </tr>
    <tr>
        <td><div class="check_box"></div></td>
        <td>For Sale By Owner Forms </td>
    </tr>
    
</table>
</page>
<?php
$html = ob_get_contents();
ob_end_clean();
$report_name = 'Checklist_'.date('YmdHis');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($html);
$html2pdf->Output('temp/'.$report_name.'.pdf', 'F');
chmod('temp/'.$report_name.'.pdf', 0777);
echo '/new/agents/ajax/dashboard/temp/'.$report_name.'.pdf';
?>