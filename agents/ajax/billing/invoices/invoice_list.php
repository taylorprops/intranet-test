<?php
include_once('/var/www/annearundelproperties.net/new/includes/global.php');

$invoiceQuery = "
select *, date_format(date_entered, '%Y-%m-%d %h:%i %p') as date_entered2 
from company.billing_invoices".$test." 
where 
in_agent_id = '".$_GET['agent_id']."' 
and 
(in_type like '%charge%' or in_type like '%refund%' or in_type like 'late%' or (in_type like '%payment%' and in_paid = 'yes')) and in_type not like '%eno%'  
order by date_entered DESC
";
$invoice = $db -> select($invoiceQuery);
$invoiceCount = count($invoice);
?>

<div class="datatable_container" style="width: 100%;">
    <table border="0" cellpadding="0" cellspacing="0" class="invoice_list" width="100%">
        <thead>
            <tr>
            	<th width="120"></th>
                <th width="80">Invoice #</th>
                <th>Date</th>
                <th>Type</th>
                <th>Paid With</th>
                <th>Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
        <?php 
		for($i=0;$i<count($invoice);$i++) {
		  	if(stristr($invoice[$i]['in_type'], 'payment')) { 
		  		$color = '#E1FFF0';
		  	} else if(stristr($invoice[$i]['in_type'], 'refund')) {
			  	$color = '';
		  	} else {
			  	$color = '#FFF4F4';
		  	}
			?>
            <tr bgcolor="<?php echo $color; ?>">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;<a class="button button_normal" target="_blank" href="/new/ajax/billing/invoices/generate_invoice.php?id=<?php echo $invoice[$i]['in_id']; ?>">View Invoice</a></td>
                <td align="center"><?php echo $invoice[$i]['in_id']; ?></td>
                <td align="center"><?php echo $invoice[$i]['date_entered2']; ?></td>
                <td align="center"><?php echo in_type($invoice[$i]['in_type']); ?></td>
                <td align="center"><?php echo paytype($invoice[$i]['payment_type']); if(stristr($invoice[$i]['payment_type'], 'creditcard')) { echo ' || '.substr($invoice[$i]['card_used'], 0, -13); } ?></td>
                <td align="center" style="font-size:16px; font-weight:bold;">$<?php echo $invoice[$i]['in_amount']; ?></td>
                <td align="center">$<?php echo $invoice[$i]['in_agent_balance']; ?></td>
            </tr>
        <?php
        } ?>
        </tbody>
    </table>
</div>
