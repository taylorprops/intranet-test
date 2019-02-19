<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];
$pay_profile_id = $_POST['pay_profile_id'];

$ccQuery = "select * from company.cc".$test." where payProfileID = '".$pay_profile_id."'";
$cc = $db -> select($ccQuery);

$updateDefault = "update company.cc".$test." set default_card = 'no' where agent_id = '".$agent_id."'";
$queryResults = $db -> query($updateDefault);
	
	
$default = "update company.cc".$test." set default_card = 'yes' where payProfileID = '".$pay_profile_id."'";
$queryResults = $db -> query($default);

changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Default Card Set', '', '', 'Card Info: '.$cc[0]['card_type'].' XXXX-'.$cc[0]['last_four'].' Exp: '.$cc[0]['expire'], $agent_id, $db->quote($_SESSION['S_Username']), '');

?>