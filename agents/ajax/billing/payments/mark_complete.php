<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');

$id = $_POST['id'];

$update = "update company.credit_report_payments set completed = 'yes' where id = '".$id."'";
$resultsQuery = $db->query($update);

?>