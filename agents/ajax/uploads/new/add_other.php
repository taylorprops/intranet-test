<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$keyQuery = "SELECT min(ListingSourceRecordKey) as ListKey from company.mls_company where ListType = 'other' or ListType = 'aris' or ListingSourceRecordID like '%_OT%'";
$key = $db -> select($keyQuery);

$new_key = str_replace(':MRIS', '', $key[0]['ListKey']);
$new_key = $new_key - 1;


$agentid = trim($db -> quote($_POST['agentid']));
$agentQuery = "SELECT * from company.tbl_agents where id = '".$agentid."'";
$agent = $db -> select($agentQuery);

$agent_id = $agent[0]['id'];
$agent_company = $agent[0]['lic1_company'];
$agent_first_name = $agent[0]['nickname'];
$agent_last_name = $agent[0]['last'];
$agent_fullname = $agent[0]['fullname'];
$agent_email = $agent[0]['email1'];
$agent_phone = $agent[0]['cell_phone'];

$street = trim($db -> quote($_POST['street']));
$city = trim($db -> quote($_POST['city']));
$state = trim($db -> quote($_POST['state']));
if($state != '') {
	$zip = trim($db -> quote($_POST['zip']));
	$county =trim($db -> quote($_POST['county']));
	$county_abbr = trim($db -> quote($_POST['county_abbr']));
	$represent = trim($db -> quote($_POST['represent']));

	$forsale = $_POST['forsale']; // Y or N
	$PropertyType = $_POST['prop_type']; // Residential, Lot, Commercial, Mutli-Family
	if($PropertyType == 'Residential' || $PropertyType == 'Commercial') {
		if($forsale == 'Y') {
			$PropertyType = $PropertyType.' Sale';
		} else {
			$PropertyType = $PropertyType.' Lease';
		}
	} else if($PropertyType == 'Lot') {
		$PropertyType = 'Land';
	}
	$SaleType = $_POST['transaction_type']; // Standard, Potential Short Sale, Foreclosure, FSBO, New Construction, Estate
	if($SaleType == 'Potential Short Sale') {
		$SaleType = 'Short Sale';
	} else if($SaleType == 'Foreclosure') {
		$SaleType = 'Real Estate Owned';
	}
	if($SaleType == 'New Construction') {
		$SaleType = 'Standard';
		$NewConstructionYN = 'Y';
	} else {
		$NewConstructionYN = 'N';
	}

	$status = $_POST['status']; // ACTIVE, APP REG, CONTRACT, RELEASED, SOLD, RENTED
	if($status == 'APP REG') {
		$status = 'PENDING';
	} else if($status == 'CONTRACT') {
		$status = 'PENDING';
	} else if($status == 'SOLD') {
		$status = 'CLOSED';
	} else if($status == 'RENTED') {
		$status = 'CLOSED';
	}

	$contract_date = trim($db -> quote($_POST['contract_date']));
	$close_date = trim($db -> quote($_POST['close_date']));
	$list_date = trim($db -> quote($_POST['list_date']));



	if($state == 'MD') {
		$newId = $county_abbr.'_OT'.$new_key;
	} else {
		$newId = $state.'_OT'.$new_key;
	}

	if(stristr($agent_company, 'taylor')) {
		$comp = 'Taylor Properties';
		$code = 'tayl';
	} else if(stristr($agent_company, 'anne')) {
		$comp = 'Anne Arundel Properties';
		$code = 'aapi';
	}

	if($represent == 'seller') {
		$list_office_code = $code;
		$list_comp_id = $agent_id;
		$list_first_name = $db->quote($agent_first_name);
		$list_last_name = $db->quote($agent_last_name);
		$list_office_name = $comp;
		$our_list_agent = $db->quote($agent_fullname);
		$list_agent_email = $db->quote($agent_email);
		$list_agent_cell = $agent_phone;
	} else if($represent == 'buyer') {
		$sale_office_code = $code;
		$sale_comp_id = $agent_id;
		$sale_first_name = $db->quote($agent_first_name);
		$sale_last_name = $db->quote($agent_last_name);
		$sale_office_name = $comp;
		$our_buyer_agent = $db->quote($agent_fullname);
		$sale_agent_email = $db->quote($agent_email);
		$sale_agent_cell = $agent_phone;
	}


	$insert = "insert into company.mls_company (ListingSourceRecordKey, ListType, ListingSourceRecordID, FullStreetAddress, City, StateOrProvince, PostalCode, County, ListOfficeMlsId, ListOfficeName, ListAgentCompID, ListAgentFirstName, ListAgentLastName, OurListAgent, BuyerOfficeMlsId, BuyerOfficeName, SaleAgentCompID, BuyerAgentFirstName, BuyerAgentLastName, OurBuyerAgent, PropertyType, SaleType, created_by, added_by, MLSListDate, PurchaseContractDate, CloseDate, MlsStatus, NewConstructionYN, ListAgentEmail, ListAgentPreferredPhone, BuyerAgentEmail, BuyerAgentPreferredPhone) values ('".$new_key.":MRIS', 'other', '".$newId."', '".$street."', '".$city."', '".$state."', '".$zip."', '".$county."', '".$list_office_code."', '".$list_office_name."', '".$list_comp_id."', '".$list_first_name."', '".$list_last_name."', '".$our_list_agent."', '".$sale_office_code."', '".$sale_office_name."', '".$sale_comp_id."', '".$sale_first_name."', '".$sale_last_name."', '".$our_buyer_agent."', '".$PropertyType."', '".$SaleType."', '/new/agents/ajax/ajax/uploads/new/add_other.php', '".$db->quote($_SESSION['S_Username'])."', '".$list_date."', '".$contract_date."', '".$close_date."', '".$status."', '".$NewConstructionYN."', '".$list_agent_email."', '".$list_agent_cell."', '".$sale_agent_email."', '".$sale_agent_cell."')";
	$queryResults = $db -> query($insert);

	echo $newId;
} else {
	$errors = '<pre>'.print_r($_SESSION, true).'<br><br>'.print_r($_SERVER, true).'</pre>';
	$body = 'Username" '.$_SESSION['S_Username'].' - '.$_SESSION['S_ID'].'<br>Page: '.$_SERVER['REQUEST_URI'].'<br>Address: '.$street.' '.$zip.'<br>'. $errors;
	sendMail('', 'mike@tayloprops.com', 'internal@taylorprops.com', 'State Missing in Add Other Upload', $body, '', '', '');
}
?>
