<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');

$for_sale = $_POST['for_sale'];

if($_POST['t'] == 'contract') {
	$t = "and (upload_type = 'Sales Contract' or upload_type = 'Release' or upload_type = 'Lease Agreement' or upload_type = 'Commission Breakdown')";
	if($for_sale == 'Y') {
		$type = 'Sales Contract';
	} else {
		$type = 'Lease Agreement';
	}
} else if($_POST['t'] == 'listing') {
	$t = "and upload_type = 'Listing Agreement'";
	$type = 'Listing Agreement';
}
 

$totalsQuery = "Select sum(if(released = 'no' and invalid = 'no' and hide = 'no',1,0)) as active, sum(if( invalid = 'yes',1,0)) as invalid, sum(if(released = 'yes',1,0)) as released, sum(if(hide = 'yes',1,0)) as deleted from  company.mls_uploads".$contract_test." where upload_mls = '".$_POST['mls']."' ".$t."";
$totals = $db -> select($totalsQuery);

$uploadsQuery = "Select *, date_format(upload_date, '%Y-%m-%d') as newDate from  company.mls_uploads".$contract_test." where upload_mls = '".$_POST['mls']."' ".$t." order by order_by, upload_date DESC";
$uploads = $db -> select($uploadsQuery);
$uploadsCount = count($uploads);

?>
<div id="upload_tabs">
    <ul>
        <li><a href="#active">Active Documents &nbsp;&nbsp;<span class="count_circle"><?php echo $totals[0]['active']; ?></span></a></li>
        <li><a href="#invalid">Invalid Uploads  &nbsp;&nbsp;<span class="count_circle"><?php echo $totals[0]['invalid']; ?></span></a></li>
        <li><a href="#released">Invalid Uploads  &nbsp;&nbsp;<span class="count_circle"><?php echo $totals[0]['released']; ?></span></a></li>
        <li><a href="#deleted">Invalid Uploads  &nbsp;&nbsp;<span class="count_circle"><?php echo $totals[0]['deleted']; ?></span></a></li>
    </ul>
    <div id="active">
        <?php if($totals[0]['active'] > 0) { ?>
        <table class="uploads_table" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Date Uploaded</th>
                    <th>File</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for($u=0;$u<$uploadsCount;$u++) { 
                if($uploads[$u]['invalid'] == 'no' && $uploads[$u]['released'] == 'no' && $uploads[$u]['hide'] == 'no') { ?>
                <tr>
                    <td><?php echo $uploads[$u]['newDate']; ?></td>
                    <td><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 35) { echo substr($uploads[$u]['upload_orig_filename'], 0, 35).'<br>'.substr($uploads[$u]['upload_orig_filename'],35); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
                        </a></td>
                    <td><?php
                        $np = false;
                        if($uploads[$u]['downloaded_by'] != '') {
                            echo '<strong>Processed</strong> on '.$uploads[$u]['upload_date_downloaded'];
                        } else {
                            echo 'Not processed';
                            $np = true;
                        } ?></td>
                    <td width="30"><?php if($np == false) { ?>
                        <img height="15" src="/new/images/icons/check_green.png">
                        <?php } else { ?>
                        <img height="20" src="/new/images/icons/hour_glass_blue.png">
                        <?php } ?></td>
                </tr>
                <?php } 
				}?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no_uploads_div">No Active Uploads</div>
        <?php } ?>
    </div>
    <div id="invalid">
        <?php if($totals[0]['invalid'] > 0) { ?>
        <table class="uploads_table_invalid released" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Date Uploaded</th>
                    <th>File</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for($u=0;$u<$uploadsCount;$u++) { 
                if($uploads[$u]['invalid'] == 'yes' && $uploads[$u]['hide'] == 'no') {
					if($u%2 == 0) {
						$color = '#FFF';
					} else {
						$color = '#DDDDDD';
					} ?>
                <tr style="background: <?php echo $color; ?>">
                    <td style="border: none;"><?php echo $uploads[$u]['newDate']; ?></td>
                    <td style="border: none;"><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 35) { echo substr($uploads[$u]['upload_orig_filename'], 0, 35).'<br>'.substr($uploads[$u]['upload_orig_filename'],35); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
                        </a></td>
                    <td style="color: #C65155; font-weight:bold; border:none">Invalid</td>
                    <td width="30"><a href="javascript: void(0)" class="delete_upload_button button button_cancel" title="Delete Upload" data-id="<?php echo $uploads[$u]['upload_id']; ?>">Delete</a></td>
                </tr>
                <tr style="background: <?php echo $color; ?>">
                    <td colspan="4" style="border: none;">REASON: <?php echo $uploads[$u]['invalid_reason']; ?></td>
                </tr>
                <?php }
            }?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no_uploads_div">No Invalid Uploads</div>
        <?php } ?>
    </div>
    <div id="released">
        <?php if($totals[0]['released'] > 0) { ?>
        <table class="uploads_table released" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <td class="upload_tr_header_not_active" colspan="5">Released Uploads</td>
                </tr>
                <tr>
                    <th>Date Uploaded</th>
                    <th>File</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for($u=0;$u<$uploadsCount;$u++) { 
                if($uploads[$u]['released'] == 'yes' && $uploads[$u]['hide'] == 'no') { ?>
                <tr>
                    <td><?php echo $uploads[$u]['newDate']; ?></td>
                    <td><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 35) { echo substr($uploads[$u]['upload_orig_filename'], 0, 35).'<br>'.substr($uploads[$u]['upload_orig_filename'],35); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
                        </a></td>
                    <td style="color: #C65155; font-weight:bold;">Released</td>
                    <td width="30"><img height="20" src="/new/images/icons/warning_red_new.png"></td>
                </tr>
                <?php }
            }?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no_uploads_div">No Released Uploads</div>
        <?php } ?>
    </div>
    <div id="deleted">
        <?php if($totals[0]['deleted'] > 0) { ?>
        <table class="uploads_table released" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <td class="upload_tr_header_not_active" colspan="5">Deleted Uploads</td>
                </tr>
                <tr>
                    <th>Date Uploaded</th>
                    <th>File</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for($u=0;$u<$uploadsCount;$u++) {
			if($uploads[$u]['hide'] == 'yes') {  ?>
                <tr>
                    <td><?php echo $uploads[$u]['newDate']; ?></td>
                    <td><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 35) { echo substr($uploads[$u]['upload_orig_filename'], 0, 35).'<br>'.substr($uploads[$u]['upload_orig_filename'],35); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
                        </a></td>
                    <td style="color: #C65155; font-weight:bold;">Deleted <br>
                        <a href="javascript: void(0)" class="undo_delete" style="color: #C65155;" data-id="<?php echo $uploads[$u]['upload_id']; ?>">(Undo Delete)</a></td>
                    <td width="30"><img height="20" src="/new/images/icons/warning_red_new.png"></td>
                </tr>
                <?php }
		}?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no_uploads_div">No Deleted Uploads</div>
        <?php } ?>
    </div>
</div>
