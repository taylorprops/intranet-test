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
$pay_profile_id = $_POST['pay_profile_id'];

if($test != '') {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, true);
} else {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);
}

$xml->updateCustomerPaymentProfileRequest(array(
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
		),
		'customerPaymentProfileId' => $pay_profile_id
	),
	'validationMode' => 'none'
));


if($xml->isError()) {
	
	$error = $xml->messages->message->text;

	echo '<span id="error">'.$error.'</span>';
	
	// Email me the error
	$error_message = $agent[0]['fullname'].'<br>profile_id = '.$profile_id.'<br>pay_profile_id = '.$pay_profile_id.'<br>xml = '.$xml;
	if($error_message != '') { 
		sendMail('', 'mike@taylorprops.com', 'Do Not Reply <internal@taylorprops.com>', 'Billing Error', $error_message, '', '', '');
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
	
	$updateCC = "update company.cc".$test." set first_name = '".$first."', last_name = '".$last."', last_four = '".$last_four."', expire = '".$expire."', cvv = '".$code."', default_card = '".$default_card."', expire_month = '".$expire_month."', expire_year = '".$expire_year."', card_type = '".$card_type."', street = '".$street."', city = '".$city."', state = '".$state."', zip = '".$zip."', expired = 'no' where payProfileID = '".$pay_profile_id."'";
	$queryResults = $db -> query($updateCC);
	
	changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Credit Card Updated', '', '', 'Card Info: '.$c_used, $agent_id, $fullname, '');

}



?>