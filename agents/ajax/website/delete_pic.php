<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');


$agent_id = $_SESSION['S_ID'];

$agentQuery = "Select * from  company.tbl_agents".$test." where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);


$link = str_replace('https://', '/var/www/', $agent[0]['picURL']); 
unlink($link);

$delete = "update company.tbl_agents".$test." set picURL = '' where id = '".$agent_id."'";
$queryResults = $db -> query($delete);

?>