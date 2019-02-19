<?php
/* _DONE */
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];

$two_months_ago = date("Y-m-d", strtotime("-2 month"));

$transQuery = "select * from company.mls_company where (ListAgentCompID = '".$agent_id."' or SaleAgentCompID = '".$agent_id."') and (Closed = 'no' or CloseDate > '".$two_months_ago."') and DisplayOnCompanySite = 'yes' order by MLSListDate DESC";
$trans = $db -> select($transQuery);
$transCount = count($trans);

$mls = array();

for($t=0;$t<$transCount;$t++) {
	$mls[] = $trans[$t]['ListingSourceRecordID'];
}
echo implode(',',$mls);
?>
