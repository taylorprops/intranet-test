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

if($_SESSION['S_Group'] == 'agent') {
	$agent = "and upload_agent_id = '".$_SESSION['S_ID']."'";
} else {
	$agent = '';
}


$totalsQuery = "Select
sum(if(released = 'no' and invalid = 'no' and hide = 'no' and upload_type not like '%comm%',1,0)) as active,
sum(if(released = 'no' and invalid = 'no' and hide = 'no' and upload_type like '%comm%',1,0)) as comm,
sum(if( invalid = 'yes',1,0)) as invalid,
sum(if(released = 'yes' and hide = 'no',1,0)) as released,
sum(if(hide = 'yes',1,0)) as deleted
from  company.mls_uploads where upload_mls = '".$_POST['mls']."' ".$agent." ".$t."";
$totals = $db -> select($totalsQuery);
//echo 'totalsQuery = '.$totalsQuery.'<br>';

$uploadsQuery = "Select *, date_format(upload_date, '%Y-%m-%d') as newDate from  company.mls_uploads where upload_mls = '".$_POST['mls']."' ".$agent." ".$t." order by upload_date DESC";
$uploads = $db -> select($uploadsQuery);
$uploadsCount = count($uploads);
//echo 'uploadsQuery = '.$uploadsQuery;


$invalid = ($totals[0]['invalid'] == '' ? 0 : $totals[0]['invalid']);
if($invalid > 0) {
	$class = 'red_count_circle';
} else {
	$class = 'count_circle';
}

function today($d) {
	if($d == date('Y-m-d')) {
		return '<strong>Today</strong>';
	} else {
		return $d;
	}
}
?>

<div class="uploads_tabs">
    <ul>
        <li><a href="#active">Active &nbsp;&nbsp;<span class="count_circle"><?php echo ($totals[0]['active'] == '' ? 0 : $totals[0]['active']); ?></span></a></li>
        <?php if($_POST['t'] == 'contract') { ?>
		<li><a href="#comm">CB's  &nbsp;&nbsp;<span class="count_circle"><?php echo ($totals[0]['comm'] == '' ? 0 : $totals[0]['comm']); ?></span></a></li>
		<?php } ?>
        <li><a href="#invalid">Invalid  &nbsp;&nbsp;<span class="<?php echo $class; ?>"><?php echo $invalid; ?></span></a></li>
        <li><a href="#released">Released  &nbsp;&nbsp;<span class="count_circle"><?php echo ($totals[0]['released'] == '' ? 0 : $totals[0]['released']); ?></span></a></li>
        <li><a href="#deleted">Deleted  &nbsp;&nbsp;<span class="count_circle"><?php echo ($totals[0]['deleted'] == '' ? 0 : $totals[0]['deleted']); ?></span></a></li>
    </ul>
    <div id="active" class="completed_uploads_div">
        <?php if($totals[0]['active'] > 0) { ?>
        <table class="uploads_table" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Date Uploaded</th>
                    <th>File</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for($u=0;$u<$uploadsCount;$u++) {
                if($uploads[$u]['invalid'] == 'no' && $uploads[$u]['released'] == 'no' && $uploads[$u]['hide'] == 'no' && !stristr($uploads[$u]['upload_type'], 'comm')) {
					$np = false;
					if($uploads[$u]['downloaded_by'] != '') {
						$processed = '<strong>Processed</strong> on '.$uploads[$u]['upload_date_downloaded'];
					} else {
						$processed = 'Not processed';
						$np = true;
					}
					 ?>
                <tr>
                    <td width="120"><?php echo today($uploads[$u]['newDate']); ?></td>
                    <td><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 50) { echo substr($uploads[$u]['upload_orig_filename'], 0, 50).'<br>'.substr($uploads[$u]['upload_orig_filename'],50); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
                        </a></td>
                    <td width="100"><?php echo $processed; ?></td>
                    <?php if($np == false) { ?>
                    <td align="center" width="30"><img height="15" src="/new/images/icons/check_green.png"></td>
                    <td></td>
                    <?php } else { ?>
                    <td><img height="20" src="/new/images/icons/hour_glass_blue.png"></td>
                    <td align="right"><a href="javascript: void(0)" class="delete_upload_button button button_cancel_big" title="Delete Upload" data-id="<?php echo $uploads[$u]['upload_id']; ?>">Delete</a></td>
                    <?php } ?>
                </tr>
                <?php }
				}?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no_uploads_div">No Active Uploads</div>
        <?php } ?>
    </div>
    <?php if($_POST['t'] == 'contract') { ?>
	<div id="comm" class="completed_uploads_div">
        <?php if($totals[0]['comm'] > 0) { ?>
        <table class="uploads_table" cellpadding="0" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Date Uploaded</th>
                    <th>File</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for($u=0;$u<$uploadsCount;$u++) {
                if($uploads[$u]['invalid'] == 'no' && $uploads[$u]['released'] == 'no' && $uploads[$u]['hide'] == 'no' && stristr($uploads[$u]['upload_type'], 'comm')) {
					$np = false;
					if($uploads[$u]['downloaded_by'] != '') {
						$processed = '<strong>Processed</strong> on '.$uploads[$u]['upload_date_downloaded'];
					} else {
						$processed = 'Not processed';
						$np = true;
					}
					 ?>
                <tr>
                    <td width="120"><?php echo today($uploads[$u]['newDate']); ?></td>
                    <td><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 50) { echo substr($uploads[$u]['upload_orig_filename'], 0, 50).'<br>'.substr($uploads[$u]['upload_orig_filename'],50); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
                        </a></td>
                    <td width="100"><?php echo $processed; ?></td>
                    <?php if($np == false) { ?>
                    <td align="center" width="30"><img height="15" src="/new/images/icons/check_green.png"></td>
                    <td></td>
                    <?php } else { ?>
                    <td><img height="20" src="/new/images/icons/hour_glass_blue.png"></td>
                    <td align="right"><a href="javascript: void(0)" class="delete_upload_button button button_cancel_big" title="Delete Upload" data-id="<?php echo $uploads[$u]['upload_id']; ?>">Delete</a></td>
                    <?php } ?>
                </tr>
                <?php }
				}?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no_uploads_div">No Commission Breakdowns</div>
        <?php } ?>
    </div>



	<?php } ?>
    <div id="invalid" class="completed_uploads_div">
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
                <?php $c = 0; for($u=0;$u<$uploadsCount;$u++) {

                if($uploads[$u]['invalid'] == 'yes' && $uploads[$u]['hide'] == 'no') {
					if($c%2 == 0) {
						$color = '#FFF';
					} else {
						$color = '#DDDDDD';
					}
					$c += 1; ?>
                <tr style="background: <?php echo $color; ?>">
                    <td width="120" style="border: none;"><?php echo $uploads[$u]['newDate']; ?></td>
                    <td style="border: none;"><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 70) { echo substr($uploads[$u]['upload_orig_filename'], 0, 70).'<br>'.substr($uploads[$u]['upload_orig_filename'],70); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
                        </a></td>
                    <td style="color: #C65155; font-weight:bold; border:none">Invalid</td>
                    <td width="30"><a href="javascript: void(0)" class="delete_upload_button button button_cancel_big" title="Delete Upload" data-id="<?php echo $uploads[$u]['upload_id']; ?>">Delete</a></td>
                </tr>
                <tr style="background: <?php echo $color; ?>">
                    <td colspan="4">REASON: <?php echo $uploads[$u]['invalid_reason']; ?></td>
                </tr>
                <?php }
            }?>
            </tbody>
        </table>
        <?php } else { ?>
        <div class="no_uploads_div">No Invalid Uploads</div>
        <?php } ?>
    </div>
    <div id="released" class="completed_uploads_div">
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
                    <td width="120"><?php echo $uploads[$u]['newDate']; ?></td>
                    <td><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 70) { echo substr($uploads[$u]['upload_orig_filename'], 0, 70).'<br>'.substr($uploads[$u]['upload_orig_filename'],70); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
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
    <div id="deleted" class="completed_uploads_div">
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
                    <td width="120"><?php echo $uploads[$u]['newDate']; ?></td>
                    <td><a class="dark" href="<?php echo $uploads[$u]['upload_loc']; ?>" target="_blank">
                        <?php if(strlen($uploads[$u]['upload_orig_filename']) > 70) { echo substr($uploads[$u]['upload_orig_filename'], 0, 70).'<br>'.substr($uploads[$u]['upload_orig_filename'],70); } else { echo $uploads[$u]['upload_orig_filename']; } ?>
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
