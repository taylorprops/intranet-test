<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');


$referralQuery = "select * from company.referrals";
$referral = $db->select($referralQuery);
$referralCount = count($referral);


for($r=0;$r<$referralCount;$r++) {
    $uploadQuery = "select * from company.commission_uploads where comm_id = '".$referral[$r]['commission_id']."'";
    $upload = $db->select($uploadQuery);
    $uploadCount = count($upload);

    for($u=0;$u<$uploadCount;$u++) {

        $add = "insert into company.referral_uploads (
            agent_id, upload_loc, commission_id, referral_id, file_name
        ) VALUES (
            '".$upload[$u]['agent_id']."', '".$upload[$u]['upload_loc']."', '".$upload[$u]['comm_id']."', '".$referral[$r]['id']."', '".$upload[$u]['file_name']."'
        )";
        $resultsQuery = $db->query($add);
    }
}


?>