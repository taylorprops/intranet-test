<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');

$referral_id = $_POST['referral_id'];
$commission_id = $_POST['commission_id'];

$update = "update company.commission set
	deleted = 'yes',
	deleted_by = '".$_SESSION['S_Username']."',
	deleted_date = CURDATE()
	where id = '".$commission_id."'";
$resultsQuery = $db -> query($update);

$update = "update company.referrals set active = 'no' where id = '".$referral_id."'";
$resultsQuery = $db->query($update);
echo $update;
?>