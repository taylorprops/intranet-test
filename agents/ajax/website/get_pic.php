<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');


$agent_id = $_SESSION['S_ID'];

$agentQuery = "select * from company.tbl_agents".$test." where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

echo $agent[0]['picURL'];

?>