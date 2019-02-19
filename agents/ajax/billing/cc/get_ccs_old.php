<?php
include('/var/www/annearundelproperties.net/new/includes/global.php');


$ccQuery = "select * from company.cc".$test." where agent_id = '".$_POST['agent_id']."'";
$cc = $db -> select($ccQuery);
$ccCount = count($cc);


?>

<table width="100%">
    <?php 
	if($ccCount > 0) { 
		for($c=0;$c<$ccCount;$c++) { ?>
    <tr>
    	<td style="border-bottom:1px dotted #CCCCCC; font-size:15px; line-height:250%; padding-right: 10px;<?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>"><?php echo $cc[$c]['card_type']; ?></td>
        <td style="border-bottom:1px dotted #CCCCCC; font-size:15px; line-height:250%;<?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>">XXXX-<?php echo $cc[$c]['last_four']; ?>&nbsp;&nbsp;&nbsp;&nbsp;Exp: <?php echo $cc[$c]['expire_month'].'-'.$cc[$c]['expire_year']; ?></td>
        <?php if($cc[$c]['expired'] != 'yes') { ?>
        <td align="left" style="border-bottom:1px dotted #CCCCCC; font-size:15px; line-height:250%; padding-left:20px;">
		<?php 
		if($cc[$c]['default_card'] == 'yes') { 
			echo '<span style="color: #043504;">Default Card</span> 
			<span style="position: relative; margin-right: 5px;">
				<img src="/new/images/icons/info.png" id="info_image" style="position: absolute; left:6px;">
				<div class="shadow info_div">This is the card that will be used for any Automated Payments</div>
			</span>'; 
		} else { ?>
            <a class="button button_normal default_card_button" href="javascript:void(0);" payprofileid="<?php echo $cc[$c]['payProfileID']; ?>" style="padding:7px;">Make Default</a>
        <?php 
		} ?>
            <?php if($cc[$c]['expired'] == 'yes') { ?>
            <span style="color: #C65155; font-weight:bold; padding-left:10px;">Expired!</span>
            <?php } ?></td>
        <?php } ?>
        <td style="border-bottom:1px dotted #CCCCCC; padding-left:40px"><a style="padding:7px 7px 7px 35px;" id="<?php echo $cc[$c]['payProfileID']; ?>" class="button button_cancel deleteCC" payprofileid="<?php echo $cc[$c]['payProfileID']; ?>" profileid="<?php echo $cc[$c]['profileID']; ?>" defaultcard="<?php echo $cc[$c]['default_card']; ?>">Delete</a></td>
        <td style="border-bottom:1px dotted #CCCCCC; padding-left:20px"><a href="javascript: void(0);" class="button button_edit editCC" style="padding: 7px 7px 7px 38px; background-position: 10px 5px;" payprofileid="<?php echo $cc[$c]['payProfileID']; ?>" profileid="<?php echo $cc[$c]['profileID']; ?>" ccfirst="<?php echo $cc[$c]['first_name']; ?>" cclast="<?php echo $cc[$c]['last_name']; ?>" street="<?php echo $cc[$c]['street']; ?>" city="<?php echo $cc[$c]['city']; ?>" state="<?php echo $cc[$c]['state']; ?>" zip="<?php echo $cc[$c]['zip']; ?>" cvv="<?php echo $cc[$c]['cvv']; ?>">Update</a></td>
    </tr>
    <?php
		 }
	} else { ?>
    <tr>
        <td><table>
                <tr>
                    <td>No Card on file</td>
                </tr>
            </table></td>
    </tr>
    <?php } ?>
</table>

