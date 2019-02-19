<?php
if (!isset($_SESSION)) {
  session_start();
}
$groupAccess = 'agent';
include('/var/www/annearundelproperties.net/includes/global.php');
include('/var/www/annearundelproperties.net/includes/logged.php');
include('/var/www/annearundelproperties.net/billing/includes/functions.php');


//$test = '_test';
//$test = $test;


require('/var/www/annearundelproperties.net/authnet/config'.$test.'.inc.php');
require('/var/www/annearundelproperties.net/authnet/AuthnetXML.class.php');

$invoiceQuery = "Select * from company.billing_invoices".$test." where in_agent_id = '".$_SESSION['S_ID']."'";
$invoice = $db -> select($invoiceQuery);


$agentQuery = "Select * from company.tbl_agents".$test." where id = '".$_SESSION['S_ID']."'";
$agent = $db -> select($agentQuery);

$emailAccountQuery = "SELECT * FROM  company.email_accounts WHERE ac_user_id = '".$_SESSION['S_ID']."'";
$emailAccount = $db -> select($emailAccountQuery);
$emailAccountCount = count($emailAccount);


$profileId = $agent[0]['profileId'];

if($agent[0]['bill_cycle']=='quarterly') {
	$billingType = 'Quarterly';
	$nextDueDate = $dueDateQuarterly;
	$amount = $agent[0]['bill_amount'];
} else if($agent[0]['bill_cycle']=='monthly') {
	$billingType = 'Monthly';
	$nextDueDate = $dueDateMonthly;
	$amount = $agent[0]['bill_amount'];
}
function balance($bal) {
	if($bal <= 0.00) {
		$col = '#6AA073';
	} else {
		$col = '#C65155';
	}
	return $col;
}

function gradientImage($light, $dark, $img){
	return '
	background: '.$light.' '.$img.';
	background-image: '.$img.', -webkit-gradient(linear, left top, left bottom, from('.$light.'), to('.$dark.'));
	background-image: '.$img.', -webkit-linear-gradient(top, '.$light.', '.$dark.');
	background-image: '.$img.', -moz-linear-gradient(top, '.$light.', '.$dark.');
	background-image: '.$img.', -ms-linear-gradient(top, '.$light.', '.$dark.');
	background-image: '.$img.', -o-linear-gradient(top, '.$light.', '.$dark.');
	background-image: '.$img.', linear-gradient(top, '.$light.', '.$dark.');
 	filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr='.$light.', endColorstr='.$dark.');';
}
function gradient($light, $dark){
	return '
	background: '.$light.';
	background-image: -webkit-gradient(linear, left top, left bottom, from('.$light.'), to('.$dark.'));
	background-image: -webkit-linear-gradient(top, '.$light.', '.$dark.');
	background-image: -moz-linear-gradient(top, '.$light.', '.$dark.');
	background-image: -ms-linear-gradient(top, '.$light.', '.$dark.');
	background-image: -o-linear-gradient(top, '.$light.', '.$dark.');
	background-image: linear-gradient(top, '.$light.', '.$dark.');
 	filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr='.$light.', endColorstr='.$dark.');';
}

function radius($tl, $tr, $br, $bl) {
	return '
	-webkit-border-top-left-radius: '.$tl.'px;
	-webkit-border-top-right-radius: '.$tr.'px;
	-webkit-border-bottom-right-radius: '.$bl.'px;
	-webkit-border-bottom-left-radius: '.$br.'px;
	-moz-border-radius-topleft: '.$tl.'px;
	-moz-border-radius-topright: '.$tr.'px;
	-moz-border-radius-bottomright: '.$bl.'px;
	-moz-border-radius-bottomleft: '.$br.'px;
	border-top-left-radius: '.$tl.'px;
	border-top-right-radius: '.$tr.'px;
	border-bottom-right-radius: '.$bl.'px;
	border-bottom-left-radius: '.$br.'px;';
}
?>