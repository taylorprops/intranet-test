<?php
include("/var/www/annearundelproperties.net/new/agents/includes/agent_queries_old.php");


$invoiceQuery = "Select *, date_format(date_entered, '%Y-%m-%d %h:%i %p') as date_entered2 from company.billing_invoices".$test." where in_agent_id = '".$_GET['agent_id']."' and (in_paid = 'yes' or in_paid = '' or in_paid is null) order by date_entered DESC";
$invoice = $db -> select($invoiceQuery);
$invoiceCount = count($invoice);

?>

<table border="0" cellpadding="0" cellspacing="0" id="invoiceEnoList" width="100%">
<thead>
  <tr>
    <th>Invoice #</th>
    <th>Date</th>
    <th>Type</th>
    <th>TansactionID</th>
    <th>Amount</th>
  </tr>
  </thead>
  <tbody>
  <?php for($e=0;$e<count($invoice);$e++) {
		if(stristr($invoice[$e]['in_type'], 'eno')) {
			if(stristr($invoice[$e]['in_type'], 'payment')) { 
				$color2 = '#E1FFF0';
			} else {
				$color2 = '#FFF4F4';
			}?>
  <tr bgcolor="<?php echo $color2; ?>">
    <td class="dataTableLeft">
    &nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="<?php echo $invoice[$e]['in_path']; ?>"><?php echo $invoice[$e]['in_id']; ?></a>
    </td>
    <td><?php echo $invoice[$e]['date_entered2']; ?></td>
    <td><?php if($invoice[$e]['in_type'] == 'chargeEno') { echo 'Charge - E&amp;O'; } else if($invoice[$e]['in_type'] == 'paymentEno') { echo 'Payment - E&amp;O'; } ?></td>
    <td><?php if($invoice[$e]['in_transactionId'] != '0') { echo $invoice[$e]['in_transactionId']; } ?></td>
    <td>$<?php echo $invoice[$e]['in_amount']; ?></td>
  </tr>
  <?php } 
}?>
</tbody>
</table>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {

	$('#invoiceEnoList').dataTable({
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
		"aaSorting": [ ],
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": true,
		"bSort": true,
		"bInfo": true,
		"bAutoWidth": false,
		"aLengthMenu": [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
		"iDisplayLength": 15,
		<?php if($_SESSION['S_Group'] == 'admin' || $_SESSION['S_Group'] == 'management') { ?>
		"aoColumnDefs": [ 
		  { "bSortable": false, "aTargets": [ 5 ] }
		],
		<?php } ?>
		"sDom": '<"H"fip<"clear">>rt<"F"ip<"clear">>',
		"oLanguage": {
		 "sSearch": ""
	   }
	});

	
});
</script>