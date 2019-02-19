<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');


if(isset($_GET['id']) && $_GET['id'] != '') {	
	$agentQuery = "SELECT * FROM company.tbl_agents where id = '".$_GET['id']."'";
	$agent = $db -> select($agentQuery);
	
	$availableLeadQuery = "SELECT * FROM company.leads_assign where id = '".$_GET['lid']."' and (agent_name = '' or agent_name is null)";
	$availableLead = $db -> select($availableLeadQuery);
	$availableLeadCount = count($availableLead);
	
	
	if($availableLeadCount > 0) {
		
		$addAgent = "update company.leads_assign set agent_name = '".trim($db -> quote($agent[0]['fullname']))."', agent_id = '".$agent[0]['id']."', date_assigned = '".date('Y-m-d H:i:s')."' where id = '".$_GET['lid']."'";
		$queryResults = $db -> query($addAgent);
		
		$addAgentCount = "update company.tbl_agents set leads_received = leads_received + 1, leads_date_last_received = curdate() where id = '".$_GET['id']."'";
		$queryResults = $db -> query($addAgentCount);
		
		if($availableLead[0]['lead_type'] == 'Purchase') {
			
			$addLead = "insert into company.leads (ClientFirst, ClientLast, ClientFullname, ClientPhone, ClientEmail, BuyerAgentName, BuyerAgentID, BuyerAgentPhone, BuyerAgentEmail, PropertyAddress, LeadStatusBuyerAgent, Source, BuyerAgentCompany, OurBuyerAgent) values ('".trim($db -> quote($availableLead[0]['lead_first']))."', '".trim($db -> quote($availableLead[0]['lead_last']))."', '".trim($db -> quote($availableLead[0]['lead_first'])).' '.trim($db -> quote($availableLead[0]['lead_last']))."', '".$availableLead[0]['phone']."', '".$availableLead[0]['email']."', '".trim($db -> quote($agent[0]['fullname']))."', '".$agent[0]['id']."', '".$agent[0]['cell_phone']."', '".$agent[0]['email1']."', '".$db -> quote($availableLead[0]['prop_info'])."', 'Lead', '".trim($db -> quote($availableLead[0]['source']))."', '".$agent[0]['Company']."', 'yes')";
			$queryResults = $db -> query($addLead);
			$leadID = $db -> id();
			
			$add = "insert into company.reminders (r_LeadID, r_Notes, r_Date, r_Creator, r_LeadName, r_Type, r_CreatorEmail, r_CreatorID) values ('".$leadID."', 'Follow up with ".trim($db -> quote($agent[0]['fullname']))." about ".trim($db -> quote($availableLead[0]['lead_first'])).' '.trim($db -> quote($availableLead[0]['lead_last']))."', '".date("Y-m-d")."', '".$GlobAdmin2FullName."', '".trim($db -> quote($availableLead[0]['lead_first'])).' '.trim($db -> quote($availableLead[0]['lead_last']))."', 'lead', '".$GlobAdmin2Email."', '".$GlobAdmin2ID."')";
			$queryResults = $db -> query($add);
			
			
			$b = 'A Lead was assigned to an agent and added to the lead system.<br><br>
			Agent:  '.$agent[0]['fullname'].'<br>
			Lead Name:  '.$availableLead[0]['lead_first'].' '.$availableLead[0]['lead_last'].'<br><br>
			View lead <a href="https://www.annearundelproperties.net/new/leads/edit.php?ID='.$leadID.'">Here</a><br><br>
			A reminder has been set to follow up in an hour.';
			//sendMail('', $GlobAdmin2Email, 'Taylor Properties <internal@taylorprops.com>', 'A Lead Was Assigned To An Agent', $b, '', '', '');
			// internal mail 19
			$description = 'A Lead Was Assigned To An Agent';
			$subject = 'A Lead Was Assigned To An Agent';
			sent_internal_emails($subject, $b, $GlobAdmin2Email, $description, '19', 'yes', __FILE__);
		}
		
		
		$showLead = 'yes';
	} else {
		$showLead = 'no';
	}
	
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lead Available</title>

<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>

</head>
<body>

<div>
	

</div>



<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
});

</script> 
</body>
</html>