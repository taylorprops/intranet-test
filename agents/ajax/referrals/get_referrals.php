<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');

if(isset($_POST['agent_id'])) {
    if($_POST['agent_id'] == '') {
        $comQuery = "SELECT * FROM company.commission where referral_only = 'yes' and deleted = 'no' order by processed ASC, recip1_date_ready DESC";
    } else {
        $comQuery = "SELECT * FROM company.commission where referral_only = 'yes' and deleted = 'no' and recip1_id = '".$_POST['agent_id']."' order by processed ASC, recip1_date_ready DESC";
    }
} else {
    $comQuery = "SELECT * FROM company.commission where referral_only = 'yes' and deleted = 'no' and recip1_id = '".$_SESSION['S_ID']."' order by processed ASC, recip1_date_ready DESC";
}
$com = $db->select( $comQuery );
$comCount = count( $com );

?>
    <div class="datatable_container">
        <table border="0" width="100%" id="commission_table" cellspacing="0">
            <thead>
                <tr>
                    <th></th>
                    <th>Date Ready</th>
                    <th>Agent</th>
                    <th>Address</th>
                    <th>Amount</th>
                    <th>Files Uploaded</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for($c=0;$c<$comCount;$c++) {
                if($com[$c]['processed'] == 'no') {
                    $class = 'not_processed';
                } else {
                    $class = '';
                }
            if($com[$c]['recip1_check_amount'] != '') {
                $amount = preg_replace('/,/', '', $com[$c]['recip1_check_amount']);
            } else {
                $amount = 0;
            }

            $uploadQuery = "SELECT * FROM company.referral_uploads where referral_id = '".$com[$c]['referral_id']."'";
            $upload = $db->select( $uploadQuery );
            $uploadCount = count( $upload );
        ?>
                    <tr class="<?php echo $class; ?>">
                        <td><a class="button button_edit edit_referral" href="javascript: void(0)" data-referral_id="<?php echo $com[$c]['referral_id']; ?>">Add Docs</a></td>
            <td width="150">
                <?php echo $com[$c]['recip1_date_ready']; ?>
            </td>
            <td><?php echo $com[$c]['recip1']; ?></td>
            <td>
                <?php echo $com[$c]['referral_street'].' '.$com[$c]['referral_city'].', '.$com[$c]['referral_state'].' '.$com[$c]['referral_zip']; ?>
            </td>
            <td>$<?php if($amount != '') { echo number_format($amount, 2, '.', ','); } else { echo '0'; } ?>
            </td>
            <td>
                <?php for($u=0;$u<$uploadCount;$u++) { ?>
                <a href="<?php echo $upload[$u]['upload_loc']; ?>" target="_blank" class="dark"><?php echo $upload[$u]['file_name']; ?></a>
                            <br>
                            <?php } ?>
                        </td>
                        <td width="50" align="center">
                            <?php if($com[$c]['processed'] == 'no') { ?>
                                <a class="button button_cancel delete_referral_commission_button" href="javascript: void(0)" data-commission_id="<?php echo $com[$c]['id']; ?>" data-referral_id="<?php echo $com[$c]['referral_id']; ?>">
                                </a>
                                <?php } ?>
                        </td>
                    </tr>
                    <?php }  ?>
            </tbody>
        </table>
    </div>