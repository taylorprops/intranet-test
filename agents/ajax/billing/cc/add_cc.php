<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include('/var/www/annearundelproperties.net/new/includes/global.php');


/* Agent Info */
$agent_id = $_POST['agent_id'];

$agentQuery = "select * from company.tbl_agents".$test." where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$profile_id = $agent[0]['profileId'];
$fullname = $db -> quote($agent[0]['fullname']);
$email = $agent[0]['email1'];
$cell = $agent[0]['cell_phone'];
$company = $agent[0]['lic1_company'];

$first = trim($db -> quote($_POST['first']));
$last = trim($db -> quote($_POST['last']));
$expire_month = trim($db -> quote($_POST['expire_month']));
$expire_year = trim($db -> quote($_POST['expire_year']));
$number = trim($db -> quote($_POST['number']));
$street = trim($db -> quote($_POST['street']));
$city = trim($db -> quote($_POST['city']));
$state = trim($db -> quote($_POST['state']));
$zip = trim($db -> quote($_POST['zip']));
$default_card = trim($db -> quote($_POST['default_card']));
$card_type = trim($db -> quote($_POST['card_type']));
$expire = $expire_year.'-'.$expire_month;
// Remove first two digits from year
$expire_year = substr($expire_year, 2);
$last_four = substr($number, -4);
$code = trim($db -> quote($_POST['code']));


if($test != '') {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, true);
} else {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);
}

// Create ProfileID and PayProfileID
if($profile_id == '') {
	$xml->createCustomerProfileRequest(array(
		'profile' => array(
			'merchantCustomerId' => $agent_id,
			'description' => $first.' '.$last,
			'email' => $email,
			'paymentProfiles' => array(
				'billTo' => array(
					'firstName' => $first,
					'lastName' => $last,
					'address' => $street,
					'city' => $city,
					'state' => $state,
					'zip' => $zip,
					'phoneNumber' => $cell
				),
				'payment' => array(
					'creditCard' => array(
					'cardNumber' => $number,
					'expirationDate' => $expire,
					'cardCode' => $code
					)
				)
			)
		),
		'validationMode' => 'none'
	));
	$profile_id = $xml->customerProfileId;
	$pay_profile_id = $xml->customerPaymentProfileIdList->numericString;
	
	// Add profile_id to tbl_agents
	if($profile_id != '') {
		$AddProfileID = "update company.tbl_agents".$test." set profileId = '".$profile_id."' where id = '".$agent_id."'";
		$queryResults = $db -> query($AddProfileID);
	}
	
// Add payProfileID
} else {
	$xml->createCustomerPaymentProfileRequest(array(
		'customerProfileId' => $profile_id,
		'paymentProfile' => array(
			'billTo' => array(
				'firstName' => $first,
				'lastName' => $last,
				'company' => $company,
				'address' => $street,
				'city' => $city,
				'state' => $state,
				'zip' => $zip,
				'country' => 'USA'
			),
			'payment' => array(
				'creditCard' => array(
					'cardNumber' => $number,
					'expirationDate' => $expire,
					'cardCode' => $code
				)
			)
		),
		'validationMode' => 'none'
	));
	
	
	$pay_profile_id = $xml->customerPaymentProfileId;
	 
}

if($xml->isError()) {
	
	$error = $xml->messages->message->text;
   	if(stristr($error, 'duplicate customer payment profile already exists')) {
	   	// find cc in our db
	   	$ccQuery = "select * from company.cc where agent_id = '".$agent_id."' and last_four = '".$last_four."'";
		$cc = $db -> select($ccQuery);
	  	
	   	if(count($cc) == 0) {
		   // if not found, add it
		   $AddCC = "insert into company.cc".$test." (agent_id, agent_name, first_name, last_name, last_four, expire, cvv, profileID, payProfileID, default_card, expire_month, expire_year, card_type, street, city, state, zip) values ('".$agent_id."', '".$fullname."', '".$first."', '".$last."', '".$last_four."', '".$expire."', '".$code."', '".$profile_id."', '".$pay_profile_id."', '".$default_card."', '".$expire_month."', '".$expire_year."', '".$card_type."', '".$street."', '".$city."', '".$state."', '".$zip."')";
			$queryResults = $db -> query($AddCC);
		
			$c_used = $card_type.' XXXX-'.$last_four.' Exp: '.$expire;
		
			// remove default from other cards and make this one default
			if($default_card == 'yes') {
				$updateDefault = "update company.cc".$test." set default_card = 'no' where agent_id = '".$agent_id."'";
				$queryResults = $db -> query($updateDefault);
			}
			changes($db->quote($db->quote($_SESSION['S_Username'])), 'Billing'.$test, 'Credit Card Added', '', '', 'Card Info: '.$c_used.' | Default Card: '.$default_card, $agent_id, $fullname, '');
			$error = '';
			$display_error = 'no';
	   }
   	}
	
	if($error == 'This transaction has been declined') {
		$error = 'We were unable to authorize your Credit Card';
	}
	if($display_error == 'yes') {
		echo '<span id="error">'.$error.'</span>';
		// Email me the error
		$error_message = $db->quote($agent[0]['fullname']).'<br>profile_id = '.$profile_id.'<br>pay_profile_id = '.$pay_profile_id.'<br>xml = '.$xml;
		if($error_message != '') { 
			sendMail('', 'mike@taylorprops.com', 'Do Not Reply <internal@taylorprops.com>', 'Billing Error', $error_message, '', '', '');
	}
	
	
	}
	die();

} else {
	// IF no error
	
	$c_used = $card_type.' XXXX-'.$last_four.' Exp: '.$expire;
	
	// remove default from other cards and make this one default
	if($default_card == 'yes') {
		$updateDefault = "update company.cc".$test." set default_card = 'no' where agent_id = '".$agent_id."'";
		$queryResults = $db -> query($updateDefault);
	}
	
	$AddCC = "insert into company.cc".$test." (agent_id, agent_name, first_name, last_name, last_four, expire, cvv, profileID, payProfileID, default_card, expire_month, expire_year, card_type, street, city, state, zip) values ('".$agent_id."', '".$fullname."', '".$first."', '".$last."', '".$last_four."', '".$expire."', '".$code."', '".$profile_id."', '".$pay_profile_id."', '".$default_card."', '".$expire_month."', '".$expire_year."', '".$card_type."', '".$street."', '".$city."', '".$state."', '".$zip."')";
	$queryResults = $db -> query($AddCC);
	
	changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Credit Card Added', '', '', 'Card Info: '.$c_used.' | Default Card: '.$default_card, $agent_id, $fullname, '');

}



?>