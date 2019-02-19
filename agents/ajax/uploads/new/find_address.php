<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$id = $_POST['agent_id'];
$q = $_POST['q'];
$streetQuery = "select ListingSourceRecordKey, ListingSourceRecordID, FullStreetAddress, PostalCode from company.mls_company where (ListAgentCompID = '".$id."' or SaleAgentCompID = '".$id."') and ListingSourceRecordID like '%_OT%' and FullStreetAddress like '%".$db->quote($q)."%' group by ListingSourceRecordID";
$street = $db -> select($streetQuery);
$streetCount = count($street);

for($i=0;$i<$streetCount;$i++) {
	echo '<div class="address_results round3" listingid="'.$street[$i]['ListingSourceRecordID'].'" zip="'.$street[$i]['PostalCode'].'">'.$street[$i]['FullStreetAddress'].'</div>';
}


?>
