<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$update = "update company.tbl_agents set ind_contractor_agreement = 'yes' where id = '".$_SESSION['S_ID']."'";
$queryResults = $db -> query($update);

?>
