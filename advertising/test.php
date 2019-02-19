<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('/var/www/annearundelproperties.net/new/includes/global.php');

$year = $_POST['year'];
$month = $_POST['month'];
$year = '2016';
$month = '08';
	
$agentIDQuery = "select agent from company.advertising where year = '".$year."' and month = '".$month."' group by agent";
$agentID = $db -> select($agentIDQuery);
$agentIDCount = count($agentID);
echo $agentIDQuery;
	for($a=0;$a<$agentIDCount;$a++) { 
	
		$agentQuery = "select fullname from company.tbl_agents where id = '".$agentID[$a]['agent']."'";
		$agent = $db -> select($agentQuery);
		$agentCount = count($agent);
		echo $agentQuery;
		$accountQuery = "select * from company.advertising where year = '".$year."' and month = '".$month."' and agent = '".$agentID[$a]['agent']."'";
		$account = $db -> select($accountQuery);
		$accountCount = count($account);
		
	}
?>
