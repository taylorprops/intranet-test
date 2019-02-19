<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$q = trim($db -> quote($_POST['q']));
$agent_id = $_SESSION['S_ID'];
/* _CHANGED */
$contractQuery = "select * from company.mls_company where (ListingSourceRecordID like '%".$q."%' or FullStreetAddress like '%".$q."%') and (ListAgentCompID = '".$agent_id."' or SaleAgentCompID = '".$agent_id."')";
$contract = $db -> select($contractQuery);
$contractCount = count($contract);
?>
<?php

if($contractCount > 0) {
	for($c=0;$c<$contractCount;$c++) {
?>

		<div class="contract_results_div" data-mls="<?php echo $contract[$c]['ListingSourceRecordID']; ?>">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="font-weight:bold; font-size: 16px; width: 100px"><?php echo $contract[$c]['ListingSourceRecordID']; ?></td>
                    <td style="font-size: 14px;"><?php echo $contract[$c]['FullStreetAddress'].'<br>'.$contract[$c]['City'].', '.$contract[$c]['StateOrProvince'].' '.$contract[$c]['PostalCode']; ?>
                    </td>
                    <td align="right"><a href="/new/agents/status/status.php?ListingSourceRecordID=<?php echo $contract[$c]['ListingSourceRecordID']; ?>" class="button button_normal" target="_blank">View Status</a></td>
                </tr>
            </table>
		</div>
		<?php
	} ?>
<?php
} else { ?>
No Results Found
<?php } ?>
