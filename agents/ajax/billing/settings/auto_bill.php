<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$auto_bill = $_POST['auto_bill'];
$agent_id = $_POST['agent_id'];

$update = "update company.tbl_agents".$test." set auto_bill = '".$auto_bill."' where id = '".$agent_id."'";
$queryResults = $db -> query($update);

changes($db->quote($_SESSION['S_Username']), 'Billing'.$test, 'Auto Bill Changed', '', '', 'Auto Bill Set to '.$auto_bill, $agent_id, $db->quote($_SESSION['S_Username']), '');
?>