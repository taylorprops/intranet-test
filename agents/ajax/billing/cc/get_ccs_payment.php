<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$ccQuery = "select * from company.cc".$test." where agent_id = '".$_SESSION['S_ID']."' order by default_card DESC";
$cc = $db -> select($ccQuery);
$ccCount = count($cc);

// Make sure that at least one card is not expired to show for making payments
$expiredQuery = "select * from company.cc".$test." where agent_id = '".$_SESSION['S_ID']."' and expired = 'yes'";
$expired = $db -> select($expiredQuery);
$expiredCount = count($expired);
?>
<?php if($ccCount > 0 && $ccCount != $expiredCount) { ?>

<div style="font-size:15px; text-align:left; font-weight:bold;">Credit Cards On File</div>
<div style="max-height:200px; overflow:auto;">
    <table id="p_ccs_table">
        <?php for($c=0;$c<$ccCount;$c++) { ?>
        <tr>
            <?php if($cc[$c]['expired'] == 'yes') { ?>
            <td><img src="/new/images/icons/warning_red_new.png" height="20"></td>
            <?php } else { ?>
            <td><input type="radio" name="card_to_use" value="<?php echo $cc[$c]['payProfileID']; ?>" id="<?php echo $cc[$c]['payProfileID']; ?>" <?php if($ccCount == '1') { echo 'checked'; } else if($cc[$c]['default_card'] == 'yes') { echo 'checked'; } ?>></td>
            <?php } ?>
            <td style=" <?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>"><?php echo $cc[$c]['card_type']; ?></td>
            <td style=" <?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>">XXXX-<?php echo $cc[$c]['last_four']; ?></td>
            <td style=" <?php if($cc[$c]['expired'] == 'yes') { ?>color: #C65155; font-weight:bold;<?php } ?>">Exp: <?php echo $cc[$c]['expire_month'].'/'.$cc[$c]['expire_year']; ?></td>
            <?php if($cc[$c]['expired'] == 'yes') { ?>
            <td><span style="color: #C65155; font-weight:bold;">Expired!</span></td>
            <?php } else { ?>
            <td></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</div>
<?php } else { ?>
no card on file
<?php } ?>
