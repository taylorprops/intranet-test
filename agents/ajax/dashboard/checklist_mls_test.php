<?php
include('/var/www/annearundelproperties.net/new/includes/global.php');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
	
$mls = $_POST['mls'];
$agent_id = $_POST['agent_id'];
$type = $_POST['type'];

$contractQuery = "SELECT ListingID, FullStreetAddress, CityName, State, PostalCode, ListOfficeCode, PropertyType, CondoCoopFee, HOAFee, YearBuilt, ListingTransactionType, NewConstruction, ForSale, County, AnnualGroundRent from company.mls_company where ListingID = '".$mls."'";
$contract = $db -> select($contractQuery);

$agentQuery = "SELECT * from company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);
$mris_ids = $agent[0]['mris_id_tp_va'].$agent[0]['mris_id_tp_md'].$agent[0]['mris_id_aap'];

$ListingID = $contract[0]['ListingID']; 
$ListOfficeCode = $contract[0]['ListOfficeCode'];
$PropertyType = $contract[0]['PropertyType'];
$YearBuilt = $contract[0]['YearBuilt'];
$CondoCoopFee = $contract[0]['CondoCoopFee'];
$HOAFee = $contract[0]['HOAFee'];
$FullStreetAddress = $contract[0]['FullStreetAddress'];
$City = $contract[0]['CityName'];
$State = $contract[0]['State'];
$PostalCode = $contract[0]['PostalCode'];
$County = $contract[0]['County'];
$ListingTransactionType = $contract[0]['ListingTransactionType'];
$NewConstruction = $contract[0]['NewConstruction'];
$ForSale = $contract[0]['ForSale'];
$AnnualGroundRent = $contract[0]['AnnualGroundRent'];
$ListAgentAgentID = $contract[0]['ListAgentAgentID']; 


if(count($contract) == 0) {
	
	$rets = new \PHRETS\Session($rets_config);
	//$rets->setLogger($log);
	
	$connect = $rets->Login();
	
	$resource = 'Property';
	$class = 'ALL';
	$query = '(ListingID='.$mls.')';
	
	$results = $rets->Search(
		$resource,
		$class,
		$query,
		[
			'QueryType' => 'DMQL2',
			'Count' => 0, 
			'Format' => 'COMPACT-DECODED',
			'StandardNames' => 0,
			'Select' => 'FullStreetAddress, CityName, State, PostalCode, ListOfficeCode, PropertyType, CondoCoopFee, HOAFee, YearBuilt, ListingTransactionType, NewConstruction, ForSale, County, AnnualGroundRent, ListingID, ListAgentAgentID',
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
	
			$colNames = array();
			$colValues = array();
		
			foreach ($listing as $key => $val) {
				
				$colNames[] = '`'.$key.'`';
				$colValues[] = $db -> quote($val);
				
			}	
							
			$ForSale = $colValues[0];
			$ListAgentAgentID = $colValues[1]; 
			$NewConstruction = $colValues[2];
			$AnnualGroundRent = $colValues[3];
			$ListingID = $colValues[4];
			$PropertyType = $colValues[5];
			$YearBuilt = $colValues[6];
			$CondoCoopFee = $colValues[7];
			$HOAFee = $colValues[8];
			$ListOfficeCode = $colValues[9];
			$State = $colValues[10];
			$PostalCode = $colValues[11];
			$City = $colValues[12];
			$FullStreetAddress = $colValues[13];	
			$County = $colValues[14];
			$ListingTransactionType = $colValues[15];
			
			
			/*
			echo '<pre>';
			print_r($colNames);
			echo '</pre>';
			*/

		
		}
	}
}




if(stristr($ListOfficeCode, 'tayl') || stristr($ListOfficeCode, 'aapi') && (stristr($mris_ids, $ListAgentAgentID))) {
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

if($State == 'MD') {
	$CountyAbbr = substr($ListingID, 0, 2);
} else {
	$CountyAbbr = '';
}

$ands = array();
$ors = array();

if($ListingTransactionType == 'REO/Bank Owned' || $ListingTransactionType == 'Foreclosure' || ($NewConstruction == 'Y' && $ListingTransactionType != 'Potential Short Sale')) {
	$ands[] = "and transaction_type like '%Foreclosure%'";
} else if($ListingTransactionType == 'Potential Short Sale') {
	$ands[] = "and transaction_type like '%Short Sale%'";
} else if($ListingTransactionType == 'Standard' || stristr($contract[0]['ListingTransactionType'], 'other')) {
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



$questionsQuery = "SELECT * from company.mls_uploads_questions where mls = '".$ListingID."' and agent_id = '".$agent_id."' order by id DESC";
$questions = $db -> select($questionsQuery);

if(stristr($PropertyType, 'lot')) { 
	$ands[] = "and lot != 'No'";
} 

if(($ForSale == 'Y') && (($CondoCoopFee > 0 && $CondoCoopFee != '0.00') || ($HOAFee > 0 && $HOAFee != '0.00'))) {
	$ors[] = "or (hoa = 'yes')";
} else {
	$ands[] = "and hoa = 'no'";
}
if($YearBuilt < '1978' && $YearBuilt != '0' && $YearBuilt != '' && !stristr($PropertyType, 'lot')) {
	$ors[] = "or (lead = 'yes')";
} else {
	$ands[] = "and lead = 'no'";
}
if(($ForSale == 'Y') && ($AnnualGroundRent > '0' && $AnnualGroundRent != '0.00' && $AnnualGroundRent != '')) {
	$ors[] = "or (ground_rent = 'yes')";
} else {
	$ands[] = "and ground_rent = 'no'";
}
if($YearBuilt > 2001 && $YearBuilt != '0' && $YearBuilt != '' && substr($ListingID, 0, 2) == 'CH') {
	$ors[]  = " or (fairshare = 'yes')";
} else {
	$ands[]  = " and fairshare = 'no'";
}

if(($questions[0]['earnest'] == 'heritage' || $questions[0]['earnest'] == 'title') && ($ForSale == 'Y' && stristr($doc_type, 'contract'))) {
	$ors[] = " or (title_letter = 'yes')";
} else {
	$ands[] = " and title_letter = 'no'";
}

$docsQuery = "select * from company.contract_docs where active = 'yes' and (doc_type like '%".$type."%' and ".$State." = 'Yes' and ".$State."_counties_abbr like '%".$CountyAbbr."%' ".implode(" ", $ands).") ".implode(" ", $ors)." order by doc_name";
$docs = $db -> select($docsQuery);
$docsCount = count($docs);	

ob_start();
?>
<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">
	<page_header>
        <div style="margin-left: 35px; margin-top: 25px; line-height: 140%"><span style="font-size:18px; font-weight:bold;">Checklist - <?php echo $doc_type.' - '.$County; ?> County</span>
        <br><span style="font-size:16px; font-weight:bold;"><?php echo $ListingID.' - '.$FullStreetAddress.' '.$City.', '.$State.' '.$PostalCode; ?></span></div>
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