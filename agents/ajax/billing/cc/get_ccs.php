<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$ccQuery = "select * from company.cc".$test." where agent_id = '".$_SESSION['S_ID']."' order by default_card DESC";
$cc = $db -> select($ccQuery);
$ccCount = count($cc);

$agentQuery = "select * from company.tbl_agents".$test." where id = '".$_SESSION['S_ID']."'";
$agent = $db -> select($agentQuery);

if($agent[0]['auto_bill'] == 'on') { 
	$ab = 'on';
} else {
	$ab = 'off';
}

$dc = 'no';
?>

<table width="100%" id="cc_table" style="border-top: 1px solid #ccc;">
    <?php 
	if($ccCount > 0) { 
		for($c=0;$c<$ccCount;$c++) { ?>
    <tr>
    	<?php if($cc[$c]['expired'] == 'yes') { ?>
			<td><img src="/new/images/icons/warning_red_new.png" height="20"></td>
		<?php } else { ?>
        	<td></td>
        <?php } ?>
        
        <td style=" <?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>"><?php echo $cc[$c]['card_type']; ?></td>
        
        <td style=" <?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>">XXXX-<?php echo $cc[$c]['last_four']; ?></td>
        
        <td style=" <?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>">Exp: <?php echo $cc[$c]['expire_month'].'/'.$cc[$c]['expire_year']; ?>
        <?php if($cc[$c]['expired'] == 'yes') { ?>
            <br><span style="color: #C65155; font-weight:bold;">Expired!</span>
        <?php } ?></td>
        
        <td>
			<?php if($cc[$c]['default_card'] == 'yes') {
				$dc = 'yes';  ?>
        	<table>
            	<tr>
					<td class="no_border"><span style="color: #043504;">Default Card</span></td>
					<td tt="left" class="tooltip no_border" title="This is the card that will be used for any Dues or E&amp;O Automated Payments"><img src="/new/images/icons/info.png" id="info_image"></td>
                </tr>
            </table>
		<?php } else { ?>
            <a class="button button_normal default_card_button" href="javascript:void(0);" payprofileid="<?php echo $cc[$c]['payProfileID']; ?>">Make Default</a>
            <?php 
		} ?>
        </td>
        
        <td align="right">
        <a
        title="Delete Credit Card" 
        id="<?php echo $cc[$c]['payProfileID']; ?>" 
        class="button button_cancel deleteCC" 
        payprofileid="<?php echo $cc[$c]['payProfileID']; ?>" 
        profileid="<?php echo $cc[$c]['profileID']; ?>" 
        defaultcard="<?php echo $cc[$c]['default_card']; ?>"></a>
        </td>
        <?php if($cc[$c]['expired'] == 'yes') { ?>
            <td align="right" width="40">
            <a 
            title="Update Credit Card"
            href="javascript: void(0);" 
            class="button button_edit_small edit_cc_button" 
            lastfour="<?php echo $cc[$c]['last_four']; ?>"
            payprofileid="<?php echo $cc[$c]['payProfileID']; ?>" 
            profileid="<?php echo $cc[$c]['profileID']; ?>" 
            ccfirst="<?php echo $cc[$c]['first_name']; ?>" 
            cclast="<?php echo $cc[$c]['last_name']; ?>" 
            street="<?php echo $cc[$c]['street']; ?>" 
            city="<?php echo $cc[$c]['city']; ?>" 
            state="<?php echo $cc[$c]['state']; ?>" 
            zip="<?php echo $cc[$c]['zip']; ?>" 
            cvv="<?php echo $cc[$c]['cvv']; ?>"
            cardtype="<?php echo $cc[$c]['card_type']; ?>"></a>
            </td>
        <?php } else { ?>
        	<td></td>
        <?php } ?>
    </tr>
    <?php
		 }
	} else { ?>
    <tr>
        <td class="no_border"><table width="100%">
                <tr>
                    <td class="no_border" style="text-align: center; font-size:17px; font-weight:bold;">No Card on file</td>
                </tr>
            </table></td>
    </tr>
    <?php } ?>
</table>
<span id="default_card" style="display:none;"><?php echo $dc; ?></span>
<span id="card_count" style="display:none;"><?php echo $ccCount; ?></span>
<span id="auto_bill" style="display:none;"><?php echo $ab; ?></span>
