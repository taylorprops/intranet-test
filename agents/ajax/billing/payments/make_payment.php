<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include('/var/www/annearundelproperties.net/new/includes/global.php');


/* Agent Info */
$agent_id = $_POST['agent_id'];

$agentQuery = "select * from company.tbl_agents".$test." where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$fullname = $db -> quote($agent[0]['fullname']);
$email = $agent[0]['email1'];
$cell = $agent[0]['cell_phone'];
$company = $agent[0]['lic1_company'];
$agent_street = $db -> quote($agent[0]['street']);
$agent_csz = $db -> quote($agent[0]['city']).', '.$agent[0]['state'].' '.$agent[0]['zip'];
$profile_id = $agent[0]['profileId'];


$new_card = $_POST['new_card'];
$trans = 'yes';
$amount = trim($db -> quote($_POST['amount']));
if(!preg_match('/\./', $amount)) {
	$amount = $amount.'.00';
}

$payment_type = $_POST['payment_type']; /* eno or dues */

$prev_balance = $agent[0]['balance'];
$new_balance = $prev_balance - $amount;
$prev_balance_eno = $agent[0]['balance_eno'];
$new_balance_eno = $prev_balance_eno - $amount;



if($new_card == 'no') {

	$paid_with = 'creditCard';

	$pay_profile_id = $_POST['pay_profile_id'];

	$ccQuery = "select * from company.cc".$test." where agent_id = '".$agent_id."' and payProfileID = '".$pay_profile_id."'";
	$cc = $db -> select($ccQuery);

	$c_used = $cc[0]['card_type'].' XXXX-'.$cc[0]['last_four'].' Exp: '.$cc[0]['expire'];

	$code = $cc[0]['cvv'];

} else if($new_card == 'yes') {

	$paid_with = 'newCreditCard';

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
					'company' => '',
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

		if($error == 'This transaction has been declined') {
			$error = 'There was an error authorizing your Credit Card';
		}

		echo '<span id="error">'.$error.'</span>';

		// Email me the error
		$error_message = $db->quote($agent[0]['fullname']).'<br>profile_id = '.$profile_id.'<br>pay_profile_id = '.$pay_profile_id.'<br>xml = '.$xml;
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

		$AddCC = "insert into company.cc".$test." (agent_id, agent_name, first_name, last_name, last_four, expire, cvv, profileID, payProfileID, default_card, expire_month, expire_year, card_type, street, city, state, zip) values ('".$agent_id."', '".$fullname."', '".$first."', '".$last."', '".$last_four."', '".$expire."', '".$code."', '".$profile_id."', '".$pay_profile_id."', '".$default_card."', '".$expire_month."', '".$expire_year."', '".$card_type."', '".$street."', '".$city."', '".$state."', '".$zip."')";
		$queryResults = $db -> query($AddCC);

		changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Credit Card Added', '', '', 'Card Info: '.$c_used, $agent_id, $fullname, '');

	}
}

if($payment_type == 'dues') {
	$in_type = 'payment';
	$invoice_cols = 'in_agent_balance, in_agent_prev_balance';
	$invoice_vals = "'".$new_balance."', '".$prev_balance."'";
	$balance = "balance = '".$new_balance."'";
	$nb = $new_balance;
	$desc = 'Dues/General Payment';
	// If payment not successful
	$invoice_vals_reset = "in_agent_balance = '".$prev_balance."', in_agent_prev_balance = '".$prev_balance."'";

} else if($payment_type == 'eno') {
	$in_type = 'paymentEno';
	$invoice_cols = 'in_agent_balance_eno, in_agent_prev_balance_eno';
	$invoice_vals = "'".$new_balance_eno."', '".$prev_balance_eno."'";
	$balance = "balance_eno = '".$new_balance_eno."'";
	$nb = $new_balance_eno;
	$desc = 'E&amp;O Payment';
	// If payment not successful
	$invoice_vals_reset = "in_agent_balance_eno = '".$prev_balance_eno."', in_agent_prev_balance_eno = '".$prev_balance_eno."'";
}




// Add invoice, will adjust later if payment does not go through
$addInvoice = "insert into company.billing_invoices".$test." (
in_agent_id,
in_amount,
in_date_sent,
in_agent_fullname,
in_agent_email,
in_type,
in_paid,
in_company,
in_date_paid,
in_notes,
payment_type,
py_desc,
created_by,
".$invoice_cols."
) values (
'".$agent_id."',
'".$amount."',
curdate(),
'".$fullname."',
'".$email."',
'".$in_type."',
'yes',
'".$company."',
curdate(),
'".$desc."',
'".$paid_with."',
'".$desc."',
'".$db->quote($_SESSION['S_Username'])."',
".$invoice_vals."
)";
$queryResults = $db -> query($addInvoice);
$in_id = $db -> id();

// Add invoice items
$addInvoiceItems = "insert into company.billing_invoices_items".$test." (in_item_quantity, in_item_desc, in_item_amount,  in_item_total, in_invoice_id, in_item_agent_id, in_item_agent) values ('1', '".$desc."', '".$amount."', '".$amount."', '".$in_id."', '".$agent_id."', '".$fullname."')";
$queryResults = $db -> query($addInvoiceItems);


// Bill Card
if($test != '') {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, true);
} else {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);
}


$xml->createCustomerProfileTransactionRequest(array(
	'transaction' => array(
		'profileTransAuthCapture' => array(
			'amount' => $amount,
			'customerProfileId' => $profile_id,
			'customerPaymentProfileId' => $pay_profile_id,
			'order' => array(
				'invoiceNumber' => $in_id,
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


if($xml->isSuccessful()) {

	$result_text = $xml->messages->resultCode;
	$result_code = $xml->messages->message->code;

	$refundable = 'yes';
	$in_paid = 'yes';

	$agentBalance = "update company.tbl_agents".$test." set ".$balance." where id = '".$agent_id."'";
	$queryResults = $db -> query($agentBalance);
	/*
	$invoiceHTML = '
	<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">
	<style type="text/css">
	#items_table th, #items_table td { padding: 5px; }
	</style>
	<table cellpadding="0" cellspacing="0" style="font-family:Arial; color:#3A4A63">
		<tr>
			<td width="10" style="background: #3A4A63;"></td>
			<td width="315" style="background: #3A4A63; color:#ffffff; font-size:35px; font-weight:bold; padding: 10px 0;">'.$company.'</td>
			<td valign="top" align="right" width="315"  style="background: #3A4A63; color:#ffffff;font-size:14px; line-height: 17px; padding: 10px 0;">175 Admiral Cochrane Drive<br>Suite 111<br>Annapolis, MD 21401
			</td>
			<td width="10" style="background: #3A4A63;"></td>
		</tr>
		<tr>
			<td colspan="4" height="15">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" style="font-size: 25px; font-weight: bold;">Payment Receipt</td>
		</tr>
		<tr>
			<td colspan="4" height="15">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" width="325">
				<table width="300" cellpadding="0" cellspacing="0" style="font-family:Arial; color:#3A4A63; font-size:15px;">
					<tr>
						<td style="font-weight: bold;">To:</td>
					</tr>
					<tr>
						<td>'.$fullname.'</td>
					</tr>
					<tr>
						<td>'.$agent_street.'</td>
					</tr>
					<tr>
						<td>'.$agent_csz.'</td>
					</tr>
				</table>
			</td>
			<td colspan="2" valign="top" align="right">
				<span style="font-size:15px; line-height: 20px;">
				Invoice#: '.$in_id.'
				<br>
				'.date("n/j/Y").'
				<br>
				Paid With: Credit Card
				<br>
				'.$c_used.'
				<br>
				Transaction ID: '.$trans_id.'
				</span>
			</td>
		</tr>
		<tr>
			<td colspan="4" height="30">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" width="650">
				<table width="610" cellpadding="0" cellspacing="0" style="font-family:Arial; color:#3A4A63" id="items_table">
					<tr>
						<td width="500" align="left" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Description</td>
						<td width="150" align="right" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Amount</td>
					</tr>
					<tr>
						<td align="left" style="border-bottom: 1px dotted #D0CAF5;">'.$desc.'</td>
						<td align="right" style="border-bottom: 1px dotted #D0CAF5;">$'.$amount.'</td>
					</tr>
					<tr>
						<td align="right" style="font-weight: bold; font-size:16px; border-top: 1px solid #D0CAF5;">Total Paid</td>
						<td align="right" style="font-weight: bold; font-size:16px; border-top: 1px solid #D0CAF5;">$'.$amount.'</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</page>';


	$folder_path = '/var/www/annearundelproperties.net/new/billing/invoices/invoices'.$test.'/'.$agent_id;
	if(!is_dir($folder_path)) {
		mkdir($folder_path);
		chmod($folder_path, 0777);
	}

	$path = 'https://annearundelproperties.net/new/billing/invoices/invoices'.$test.'/'.$agent_id.'/'.$in_id.'.pdf';
	$addPath = "update company.billing_invoices".$test." set in_path = '".$path."' where in_id = '".$in_id."'";
	$queryResults = $db -> query($addPath);

	$html2pdf = new HTML2PDF('P','A4','en');
	$html2pdf->WriteHTML($invoiceHTML);
	$html2pdf->Output($folder_path.'/'.$in_id.'.pdf', 'F');
	chmod($folder_path.'/'.$in_id.'.pdf',  0777);
	*/

	$card_info = ' - Card Info: '.$c_used;

	$trans_desc = 'TransactionID '.$trans_id;

	changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Payment Made with Credit Card', '', '', 'Payment '.$trans_desc.' for '.strtoupper($payment_type).' Invoice ID '.$in_id.' in the amount of $'.$amount.' '.$card_info, $agent_id, $fullname, '');

	echo '<div id="nb">'.number_format($nb, '2', '.', '').'</div>';
	if($nb > 0) {
		$nb = '<span style="color: #C65155;">$'.number_format($nb, '2', '.', '').'</span>';
	} else if($nb == 0) {
		$nb = '<span style="color: #6AA073;">$'.number_format($nb, '2', '.', '').'</span>';
	} else if($nb < 0) {
		$nb = '<span style="color: #6AA073;">$'.number_format($nb, '2', '.', '').' (credit)</span>';
	}

	echo '<div id="new_balance">'.$nb.'</div>';

	$updateInvoice = "update company.billing_invoices".$test." set
	in_transactionId = '".$trans_id."',
	responseCode = '".$responseCode."',
	responseText = '".$responseReasonText."',
	transactionID = '".$trans_id."',
	refund = 'no',
	profile_id = '".$profile_id."',
	payment_profile_id = '".$pay_profile_id."',
	refundable = '".$refundable."',
	card_used = '".$c_used."',
	in_paid = '".$in_paid."',
	result_code = '".$result_code."',
	result_text = '".$result_text."',
	responseDetails = '".$db -> quote($directResponse)."'
	where in_id = '".$in_id."'";
	$queryResults = $db -> query($updateInvoice);


} else if($xml->isError()) {

	$refundable = 'no';
	$in_paid = 'no';

	$error = $xml->messages->message->text;
	$error_message = $error.'<br>'.$agent[0]['fullname'].'<br>profile_id = '.$profile_id.'<br>pay_profile_id = '.$pay_profile_id.'<br>xml = '.$xml;
	echo '<span id="error">'.$error.'</span>';

	$decline = "update company.billing_invoices".$test." set in_paid = 'no', responseText = '".$responseReasonText."', refundable = 'no', card_used = '".$c_used."', ".$invoice_vals_reset." where in_id = '".$in_id."'";
	$queryResults = $db -> query($decline);

	changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Payment Declined for '.$paid_with.' Payment', '', '', 'Payment Declined for '.$amount.' - '.$c_used.'. Reason: '.$error, $agent_id, $fullname, '');

}




if($error_message != '') {
	sendMail('', 'mike@taylorprops.com', 'Do Not Reply <internal@taylorprops.com>', 'Billing Error', $error_message, '', '', '');
}

?>
