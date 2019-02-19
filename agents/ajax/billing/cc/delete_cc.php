<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$profile_id = $_POST['profile_id'];
$pay_profile_id = $_POST['pay_profile_id'];

$ccQuery = "select * from company.cc".$test." where payProfileID = '".$pay_profile_id."'";
$cc = $db -> select($ccQuery);

$delete = "delete from company.cc".$test." where payProfileID = '".$pay_profile_id."'";
$queryResults = $db -> query($delete);


if($test != '') {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, true);
} else {
	$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, false);
}

$xml->deleteCustomerPaymentProfileRequest(array(
	'customerProfileId' => $profile_id,
	'customerPaymentProfileId' => $pay_profile_id
));

// Set payment status to not refundable

$noRefund = "update company.billing_invoices".$test." set refundable = 'no' where payment_profile_id = '".$pay_profile_id."'";
$queryResults = $db -> query($noRefund);


changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Credit Card Deleted', '', '', 'Card Info: '.$cc[0]['card_type'].' XXXX-'.$cc[0]['last_four'].' Exp: '.$cc[0]['expire'], $cc[0]['agent_id'], $cc[0]['first_name'].' '.$cc[0]['last_name'], '');


?>