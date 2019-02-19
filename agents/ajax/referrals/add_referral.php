<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];

$agentQuery = "SELECT * FROM company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$agent_name = $db -> quote($agent[0]['fullname']);
$street = trim($db -> quote($_POST['street']));
$city = trim($db -> quote($_POST['city']));
$state = trim($db -> quote($_POST['state']));
$zip = trim($db -> quote($_POST['zip']));
$amount = trim($db -> quote($_POST['amount']));

$insert = "insert into company.commission (
recip1,
recip1_id,
recip1_check_amount,
referral_street,
referral_city,
referral_state,
referral_zip,
referral_only
) values (
'".$agent_name."',
'".$agent_id."',
'".$amount."',
'".$street."',
'".$city."',
'".$state."',
'".$zip."',
'yes'
)";

$queryResults = $db -> query($insert);
$commission_id = $db -> id();

$subject = 'Referral Agreement Uploaded by '.$agent_name;
$GLOB_email_top = str_replace('%%preheader%%', '', $GLOB_email_top);
$body = str_replace('%%subject%%', $subject, $GLOB_email_top).$agent_name.' just uploaded a referral agreement for '.$street.' '.$city.', '.$state.' '.$zip.'.<br><a href="https://annearundelproperties.net/new/real_estate/agents/referral_commission/referral_commissions.php" target="_blank">View Uploads</a>
'.$GLOB_email_bottom;

if($agent_id != 3193) {
    sendMail('', 'admin@taylorprops.com', 'internal@taylorprops.com', $subject, $body, '', '', '');
}

$addReferral = "insert into company.referrals (agent_id, agent_name, street, city, state, zip, amount, commission_id) values ('".$agent_id."', '".$agent_name."','".$street."', '".$city."', '".$state."', '".$zip."', '".$amount."', '".$commission_id."')";
$queryResults = $db -> query($addReferral);
$referral_id = $db->id();

$update = "update company.commission set referral_id = '".$referral_id."' where id = '".$commission_id."'";
$resultsQuery = $db->query($update);

echo $referral_id;

?>