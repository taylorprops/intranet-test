<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$responded = $_POST['responded'];
$attending = $_POST['attending'];
$agent_id = $_SESSION['S_ID'];
$email_id = $_POST['email_id'];

if($responded == 'yes') {

	$update = "update company.meeting_emails_replies set response = '".$attending."' where agent_id = '".$agent_id."' and email_id = '".$email_id."'";
	$queryResults = $db -> query($update);
	
} else {
	
	$meetingQuery = "select * from company.meeting_emails where email_id = '".$email_id."'";
	$meeting = $db -> select($meetingQuery);
	
	$agentQuery = "select * from company.tbl_agents where id = '".$agent_id."'";
	$agent = $db -> select($agentQuery);
	
	$countQuery = "select max(counter) as max_count from company.meeting_emails_replies where email_id = '".$email_id."'";
	$count = $db -> select($countQuery);
	$c = $count[0]['max_count'] + 1;
	
	$details = $db -> quote($meeting[0]['details']);
	$agent_name = $db -> quote($agent[0]['fullname']);
	$agent_first = $db -> quote($agent[0]['nickname']);
	$agent_last = $db -> quote($agent[0]['last']);
	$cat = $db -> quote($meeting[0]['category']);
	$total = $meeting[0]['total_sent'];


	$add = "insert into company.meeting_emails_replies (details, agent_id, agent_name, email_id, total_count, counter, category, response, agent_first, agent_last, emp_type) values ('".$details."', '".$agent_id."', '".$agent_name."', '".$email_id."', '".$total."', '".$c."', '".$cat."', '".$attending."', '".$agent_first."', '".$agent_last."', 'agent')";
	$queryResults = $db -> query($add);
}