<div id="agentHead" class="billingAgentInfoHead round shadow">
    <table class="agentHomeTable" width="100%">
        <tr>
            <td style="font-size:23px; font-weight:bold; color: #0C0"><?php echo $agent[0]['fullname']; ?></a>
                <?php if($agent[0]['llc_name'] != '') { ?>
                <br />
                <span class="agentCompany"><?php echo $agent[0]['llc_name']; ?></span>
                <?php } ?></td>
            <td class="agentHomeInfo">Plan <?php echo strtoupper($agent[0]['commission_plan']); ?><br>
                <?php echo $agent[0]['commission']; ?> Commission<br>
                <?php echo ucwords($agent[0]['bill_cycle']); ?> Billing</td>
            <td class="agentAddress"><u>Next Bill Due Date</u><br />
                <?php echo $nextDueDate; ?><br>
                <u>Next Bill Amount</u><br />
                $<?php echo $amount; ?></td>
            <td class="agentBalance" align="left" valign="top">Balance: <span style="color:<?php echo balance($agent[0]['balance']); ?>;">$<?php echo $agent[0]['balance']; ?></span><br>
                <button class="buttonNormal" onclick="window.location='https://www.annearundelproperties.net/agents/billing/make_payment.php'" style="width: 150px; margin-top:8px;">Make Payment</button></td>
            <?php if($agent[0]['balance_eno'] > 0) { ?>
            <td class="agentBalance" align="left" valign="top">E&O Balance: <span style="color:<?php echo balance($agent[0]['balance_eno']); ?>;">$<?php echo $agent[0]['balance_eno']; ?></span><br>
                <button class="buttonNormal" onclick="window.location='https://www.annearundelproperties.net/agents/billing/make_payment_eno.php'" style="width: 150px; margin-top:8px;">Make E&O Payment</button></td>
            <?php } ?>
        </tr>
    </table>
</div>
