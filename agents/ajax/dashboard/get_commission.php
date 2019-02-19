<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');

$comQuery = "SELECT * from company.commission where mls = '".$_POST['mls']."'";
$com = $db -> select($comQuery);


function date_show($d){
	if($d != '0000-00-00') {
		return $d;
	}
}

function d_type($d) {
	if($d == 'mail') {
		return 'Mail';
	} else if($d == 'pickup') {
		return 'Picking Up';
	}
}

?>
<?php if(count($com) >  0) {  ?>

<table class="commission_table commission_details">
    <tr>
        <td style="font-size: 18px; font-weight:bold; color:#D18359">Checks In</td>
    </tr>
    <tr>
        <td><strong>Commission Check Received:</strong> <?php echo date_show($com[0]['date_received']); ?></td>
    </tr>
    <?php $show = 'yes'; if($com[0]['have_hud'] == 'no' || $com[0]['have_cb'] == 'no') { $show = 'no'; ?>
    <tr>
        <td><hr></td>
    </tr>
    <tr>
        <td style="border:none"><table width="100%">
                <tr>
                    <td width="40"><img src="/new/images/icons/warning_yellow.png" height="20"></td>
                    <td><?php if($com[0]['have_hud'] == 'no') { ?>
                        Closing Disclosures Not Received
                        <?php } ?>
                        <?php if($com[0]['have_hud'] == 'no' && $com[0]['have_cb'] == 'no') { ?>
                        <br>
                        <?php } ?>
                        <?php if($com[0]['have_cb'] == 'no') { ?>
                        Commission Breakdown Not Received
                        <?php } ?></td>
                </tr>
            </table></td>
    </tr>
    <tr>
        <td style="border:none; font-size:13px; color: #A84549;">We cannot process your commission until we have received the Closing Disclosures and Commission Breakdown</td>
    </tr>
</table>
<?php } ?>
<?php if($show == 'yes') { ?>
<table>
    <tr>
        <td><strong>Date Deposited:</strong> <?php echo date_show($com[0]['date_deposited']); ?></td>
    </tr>
    <tr>
        <td><hr></td>
    </tr>
    <tr>
        <td style="font-size: 18px; font-weight:bold; color:#D18359">Checks Out</td>
    </tr>
</table>
<table>
    <tr>
        <td valign="top"><table>
                <tr>
                    <td>Recipient:</td>
                </tr>
                <tr>
                    <td>Check Amount:</td>
                </tr>
                <tr>
                    <td>Receiving By:</td>
                </tr>
                <tr>
                    <td>Date Ready:</td>
                </tr>
            </table></td>
        <?php
	for($i=1;$i<6;$i++) { 
                            
	if($com[0]['recip'.$i.'_check_amount'] != '' && $com[0]['recip'.$i.'_check_amount'] != '0.00' && $com[0]['recip'.$i.'_check_amount'] > 0) {

		if($com[0]['recip'.$i.'_delivery_method'] == 'pickup') { 
			$delivery = 'Picking Up';
			$date_ready = $com[0]['recip'.$i.'_date_ready'];
		} else if($com[0]['recip'.$i.'_delivery_method'] == 'mail') {
			$delivery = 'Mailing To';
			$date_ready = $com[0]['recip'.$i.'_date_mailed'];
		} else {
			$delivery = '';
		}
		if($com[0]['recip'.$i.'_id'] != '' && $com[0]['recip'.$i.'_id'] != 0) {
			$agentQuery = "SELECT * from company.tbl_agents where id = '".$com[0]['recip'.$i.'_id']."'";
			$agent = $db -> select($agentQuery);
			$recip = $agent[0]['fullname'];
		} else {
			$recip = $com[0]['recip'.$i];
		}
		
		?>
        <td valign="top"><table>
                <tr>
                    <td><?php echo $recip; ?></td>
                </tr>
                <tr>
                    <td>$<?php echo money($com[0]['recip'.$i.'_check_amount']); ?></td>
                </tr>
                <tr>
                    <td><?php echo $delivery; ?></td>
                </tr>
                <tr>
                    <td><?php echo date_show($date_ready); ?>
                </tr>
            </table></td>
        <?php }
	}
	}?>
            </td>
</table>
<?php } else { ?>
<div class="not_our_listing_div">Commission Check Not Received</div>
<?php } ?>
