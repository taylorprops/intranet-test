<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$mls = $_POST['mls'];
$agent_id = $_POST['agent_id'];
$type = $_POST['type'];

$contractQuery = "SELECT ListingSourceRecordID, FullStreetAddress, City, StateOrProvince, PostalCode, ListOfficeMlsId, PropertyType, AssociationFee2, AssociationFee, YearBuilt, SaleType, NewConstructionYN, County, GroundRentAmount, ListAgentMlsId from company.mls_company where ListingSourceRecordID = '".$mls."'";
$contract = $db -> select($contractQuery);

$agentQuery = "SELECT * from company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);
$mris_ids = $agent[0]['mris_id_tp_va'].$agent[0]['mris_id_tp_md'].$agent[0]['mris_id_aap'];

$ListingSourceRecordID = $contract[0]['ListingSourceRecordID'];
$ListOfficeMlsId = $contract[0]['ListOfficeMlsId'];
$PropertyType = $contract[0]['PropertyType'];
$YearBuilt = $contract[0]['YearBuilt'];
$AssociationFee2 = $contract[0]['AssociationFee2'];
$AssociationFee = $contract[0]['AssociationFee'];
$FullStreetAddress = $contract[0]['FullStreetAddress'];
$City = $contract[0]['City'];
$StateOrProvince = $contract[0]['StateOrProvince'];
$PostalCode = $contract[0]['PostalCode'];
$County = $contract[0]['County'];
$SaleType = $contract[0]['SaleType'];
$NewConstructionYN = $contract[0]['NewConstructionYN'];
if(stristr($PropertyType, 'lease')) {
	$ForSale = 'N';
} else {
	$ForSale = 'Y';
}

$GroundRentAmount = $contract[0]['GroundRentAmount'];
$ListAgentMlsId = $contract[0]['ListAgentMlsId'];


if(count($contract) == 0) {

	$rets = new \PHRETS\Session($rets_config);


	$connect = $rets->Login();

	$resource = 'Property';
	$class = 'ALL';
	$query = '(ListingSourceRecordID='.$mls.')';

	$results = $rets->Search(
		$resource,
		$class,
		$query,
		[
			'Count' => 0,
			'Select' => 'FullStreetAddress, City, StateOrProvince, PostalCode, ListOfficeMlsId, PropertyType, AssociationFee2, AssociationFee, YearBuilt, SaleType, NewConstructionYN, County, GroundRentAmount, ListingSourceRecordID, ListAgentMlsId',
			'Limit' => '1'
		]
	);

	$results = $results->toArray();

	$found = count($results);

	if($found == 0) {

		echo 'no mls';
		die();

	} else {

		foreach($results as $listing) {

			$ListAgentMlsId = $listing['ListAgentMlsId'];
			$NewConstructionYN = $listing['NewConstructionYN'];
			$GroundRentAmount = $listing['GroundRentAmount'];
			$ListingSourceRecordID = $listing['ListingSourceRecordID'];
			$PropertyType = $listing['PropertyType'];
			if(stristr($PropertyType, 'lease')) {
				$ForSale = 'N';
			} else {
				$ForSale = 'Y';
			}
			$YearBuilt = $listing['YearBuilt'];
			$AssociationFee2 = $listing['AssociationFee2'];
			$AssociationFee = $listing['AssociationFee'];
			$ListOfficeMlsId = $listing['ListOfficeMlsId'];
			$City = $listing['City'];
			$PostalCode = $listing['PostalCode'];
			$StateOrProvince = $listing['StateOrProvince'];
			$FullStreetAddress = $listing['FullStreetAddress'];
			$County = $listing['County'];
			$SaleType = $listing['SaleType'];

			/*
			$colNames = array();
			$colValues = array();

			foreach ($listing as $key => $val) {
				$colNames[] = '`'.$key.'`';
				$colValues[] = $db -> quote($val);
			}
			*/
		}
	}
}

if((stristr($ListOfficeMlsId, 'tayl') || stristr($ListOfficeMlsId, 'aapi')) && (stristr($mris_ids, $ListAgentMlsId))) {
	$rep_seller = 'yes';
	$rep_buyer = 'no';
} else {
	$rep_buyer = 'yes';
	$rep_seller = 'no';
	if($type == 'listing') {
		echo 'not our listing';
		die();
	}
}

if($StateOrProvince == 'MD') {
	$CountyAbbr = substr($ListingSourceRecordID, 0, 2);
} else {
	$CountyAbbr = '';
}

$ands = array();
$ors = array();
$foreclosure = 'no';
if($SaleType == 'Real Estate Owned' || $SaleType == 'Bankruptcy Property' || $SaleType == 'HUD Owned' || $SaleType == 'Auction' || $SaleType == 'Probate Listing' || $SaleType == 'Undisclosed' || ($NewConstructionYN == 'Y' && $SaleType != 'Short Sale')) {
	$ands[] = "and transaction_type like '%Foreclosure%'";
	$foreclosure = 'yes';
} else if($SaleType == 'Short Sale') {
	$ands[] = "and transaction_type like '%Short Sale%'";
} else if($SaleType == 'FSBO') {
	$ands[] = "and transaction_type like '%FSBO%'";
} else {
	$ands[] = "and transaction_type like '%Standard%'";
}

if($ForSale == 'Y') {
	$ands[] = "and forsale like '%Sale%'";
	if($type == 'listing') {
		$doc_type = 'Listing Agreement';
	} else {
		$doc_type = 'Sales Contract';
	}
} else {
	$ands[] = "and forsale like '%Rental%'";
	if($type == 'listing') {
		$doc_type = 'Listing Agreement';
	} else {
		$doc_type = 'Lease Agreement';
	}
}

if($rep_buyer == 'yes') {
	$buyer = 'yes';
} else {
	$ands[] = "and rep_buyer != 'Yes'";
	$buyer = 'no';

}



$questionsQuery = "SELECT * from company.mls_uploads_questions where mls = '".$ListingSourceRecordID."' and agent_id = '".$agent_id."' order by id DESC";
$questions = $db -> select($questionsQuery);

if(stristr($PropertyType, 'land')) {
	$ands[] = "and lot != 'No'";
} else {
	$ands[] = "and lot != 'Yes'";
}

if(($ForSale == 'Y') && (($AssociationFee2 > 0 && $AssociationFee2 != '0.00') || ($AssociationFee > 0 && $AssociationFee != '0.00')) && $foreclosure == 'no') {
	$ors[] = "or (hoa = 'yes')";
} else {
	$ands[] = "and hoa = 'no'";
}
if($YearBuilt < '1978' && $YearBuilt != '0' && $YearBuilt != '' && !stristr($PropertyType, 'land')) {
	$ors[] = "or (lead = 'yes')";
} else {
	$ands[] = "and lead = 'no'";
}
if(($ForSale == 'Y') && ($GroundRentAmount > '0' && $GroundRentAmount != '0.00' && $GroundRentAmount != '')) {
	$ors[] = "or (ground_rent = 'yes')";
} else {
	$ands[] = "and ground_rent = 'no'";
}
if($YearBuilt > 2001 && $YearBuilt != '0' && $YearBuilt != '' && substr($ListingSourceRecordID, 0, 2) == 'CH') {
	$ors[]  = " or (fairshare = 'yes')";
} else {
	$ands[]  = " and fairshare = 'no'";
}

if(($questions[0]['earnest'] == 'heritage' || $questions[0]['earnest'] == 'title') && ($ForSale == 'Y' && stristr($doc_type, 'contract'))) {
	$ors[] = " or (title_letter = 'yes')";
} else {
	$ands[] = " and title_letter = 'no'";
}

$docsQuery = "select * from company.contract_docs where active = 'yes' and (doc_type like '%".$type."%' and ".$StateOrProvince." = 'Yes' and ".$StateOrProvince."_counties_abbr like '%".$CountyAbbr."%' ".implode(" ", $ands).") ".implode(" ", $ors)." order by doc_name";
$docs = $db -> select($docsQuery);
$docsCount = count($docs);



ob_start();
?>
<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">
	<page_header>
        <div style="margin-left: 35px; margin-top: 25px; line-height: 140%"><span style="font-size:18px; font-weight:bold;">Checklist - <?php echo $doc_type.' - '.$County; ?> County</span>
        <br><span style="font-size:16px; font-weight:bold;"><?php echo $ListingSourceRecordID.' - '.$FullStreetAddress.' '.$City.', '.$StateOrProvince.' '.$PostalCode; ?></span></div>
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
    	if($docs[$d]['required'] == 'Yes' && $docs[$d]['in_house'] == 'No') {  ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name']; ?></td>
    </tr>
    <?php }
    }?>
    <tr>
        <th colspan="2">Conditional Docs</th>
    </tr>
    <tr>
        <td><div class="check_box"></div></td>
        <td>Disclosure of Licensee Status Addendum </td>
    </tr>
    <?php if($ForSale == 'Y') { ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td>For Sale By Owner Forms </td>
    </tr>
    <?php } ?>
</table>
</page>
<?php
$html = ob_get_contents();
ob_end_clean();
//echo $html;
$report_name = 'Checklist_'.date('YmdHis');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($html);
$html2pdf->Output('temp/'.$report_name.'.pdf', 'F');
chmod('temp/'.$report_name.'.pdf', 0777);
echo '/new/agents/ajax/dashboard/temp/'.$report_name.'.pdf';
?>
