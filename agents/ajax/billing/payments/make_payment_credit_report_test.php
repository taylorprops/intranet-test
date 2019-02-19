<?php


include('/var/www/annearundelproperties.net/new/includes/global.php');

/* Agent Info */
$agent_id = $_POST['agent_id'];
$name = $_POST['name'];
$id = $_POST['id'];

$agentQuery = "select * from company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$fullname = $db -> quote($agent[0]['fullname']);
$agent_first = $agent[0]['nickname'];
$email = $agent[0]['email1'];

$new_card = $_POST['new_card'];
$trans = 'yes';
$amount = trim($_POST['amount']);
if(stristr($amount, '25')) {
	$report_type = 'Single Credit Agency - $25.00';
} else {
	$report_type = 'Triple Credit Agency - $38.00';
}
if(!preg_match('/\./', $amount)) {
	$amount = $amount.'.00';
}

$merchantCustomerId = date('YmdHis');

$cont = 'yes';

if($new_card == 'no') {

	$paid_with = 'creditCard';
	$profile_id = $agent[0]['profileId'];
	$pay_profile_id = $_POST['pay_profile_id'];

	$ccQuery = "select * from company.cc where agent_id = '".$agent_id."' and payProfileID = '".$pay_profile_id."'";
	$cc = $db -> select($ccQuery);

	$c_used = $cc[0]['card_type'].' XXXX-'.$cc[0]['last_four'].' Exp: '.$cc[0]['expire'];

	$code = $cc[0]['cvv'];

} else if($new_card == 'yes') {

	$paid_with = 'newCreditCard';
	$profile_id = '';
	$pay_profile_id = '';
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
	$c_used = $card_type.' XXXX-'.$last_four.' Exp: '.$expire;

	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);

	// Create ProfileID and PayProfileID
	$xml->createCustomerProfileRequest(array(
		'profile' => array(
			'merchantCustomerId' => $merchantCustomerId,
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



	if($xml->isError()) {

		$error = $xml->messages->message->text;

		echo '<span id="error">'.$error.'</span>';

		// Email me the error
		$error_message = $db->quote($agent[0]['fullname']).'<br>profile_id = '.$profile_id.'<br>pay_profile_id = '.$pay_profile_id.'<br>xml = '.$xml;
		if($error_message != '') {
			//sendMail('', 'mike@taylorprops.com', 'Do Not Reply <internal@taylorprops.com>', 'Billing Error', $error_message, '', '', '');
		}
		$cont = 'no';

	}
}


$desc = 'Credit Report Payment - '.$name.' - '.$report_type;

$addInvoice = "update company.credit_report_payments_test set
	agent_id = '".$agent_id."',
	amount = '".$amount."',
	agent_name = '".$fullname."',
	agent_first = '".$agent_first."',
	agent_email = '".$email."',
	client = '".$name."',
	report_type = '".$report_type."',
	charge_desc = '".$desc."'
	where id = '".$id."'";
$queryResults = $db -> query($addInvoice);



// Bill Card
if($cont == 'yes') {

	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);

	$xml->createCustomerProfileTransactionRequest(array(
		'transaction' => array(
			'profileTransAuthCapture' => array(
				'amount' => $amount,
				'customerProfileId' => $profile_id,
				'customerPaymentProfileId' => $pay_profile_id,
				'order' => array(
					'invoiceNumber' => $id,
					'description' => $desc
				),
				'cardCode' => $code
			)
		)
	));

	$directResponse = $xml->directResponse;
	$response = explode(',', $xml->directResponse);
	$responseCode = $response[0];
	$responseReasonText = $response[3];
	$trans_id = $response[6];

	$card_info = ' - Card Info: '.$c_used;
	$trans_desc = 'TransactionID '.$trans_id;


	if($xml->isSuccessful()) {

		$result_text = $xml->messages->resultCode;
		$result_code = $xml->messages->message->code;


		// SEND INVOICE TO AGENT
		$body = '
		Credit report payment reciept<br><br>
		Your payment for a credit report was successfully charged.<br><br>
		Card: '.$c_used .'<br>
		Client: '.$name.'<br>
		Amount: '.$amount.'<br><br>
		Your Credit Report will be emailed to you shortly.<br><br>
		Thank you,<br>
		Taylor Properties';
		sendMail('', $email, 'Taylor Properties <kyle@taylorprops.com>', 'Credit Report Receipt', $body, '', '', '');

	} else if($xml->isError()) {


		$error = $xml->messages->message->text;
		$error_message = $error.'<br>'.$agent[0]['fullname'].'<br>profile_id = '.$profile_id.'<br>pay_profile_id = '.$pay_profile_id.'<br>xml = '.$xml;
		echo '<span id="error">'.$error.'</span>';

		$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);
	    $xml->deleteCustomerPaymentProfileRequest(array(
	        'customerProfileId' => $profile_id,
	        'customerPaymentProfileId' => $pay_profile_id
	    ));

		$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);
	    $xml->deleteCustomerProfileRequest(array(
	        'customerProfileId' => $profile_id
	    ));



	}



	$update = "update company.credit_report_payments_test set
	in_transactionId = '".$trans_id."',
	responseCode = '".$responseCode."',
	responseText = '".$responseReasonText."',
	transactionID = '".$trans_id."',
	profile_id = '".$profile_id."',
	payment_profile_id = '".$pay_profile_id."',
	card_used = '".$c_used."',
	result_code = '".$result_code."',
	result_text = '".$result_text."',
	responseDetails = '".$db -> quote($directResponse)."'
	where id = '".$id."'";
	$queryResults = $db -> query($update);


	if($error_message != '') {
		sendMail('', 'mike@taylorprops.com', 'Do Not Reply <internal@taylorprops.com>', 'Billing Error', $error_message, '', '', '');
	}

}

?>
