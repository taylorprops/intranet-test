<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$street = $_POST['street'];

$addressQuery = "SELECT * FROM company.commission where referral_street = '".$street."' and recip1_id = '".$_SESSION['S_ID']."' and processed = 'no' and deleted = 'no'";
$address = $db->select( $addressQuery );
$addressCount = count( $address );

if($addressCount > 0) {
    echo 'error';
}
?>
