<?php

include("/var/www/annearundelproperties.net/new/agents/includes/agent_queries_old.php");

$invoiceQuery = "Select *, date_format(date_entered, '%Y-%m-%d %h:%i %p') as date_entered2 from company.billing_invoices".$test." where in_agent_id = '".$_GET['agent_id']."' and (in_paid = 'yes' or in_paid = '' or in_paid is null) order by date_entered DESC";
$invoice = $db -> select($invoiceQuery);
$invoiceCount = count($invoice);
?>
<style type="text/css">
#emailTable td { padding:5px; }
</style>
<table border="0" cellpadding="0" cellspacing="0" id="invoiceList" width="100%">
<thead>
  <tr>
    <th>Invoice #</th>
    <th>Date</th>
    <th>Type</th>
    <th>TrasactionID</th>
    <th>Amount</th>
  </tr>
  </thead>
  <tbody>
  <?php for($i=0;$i<count($invoice);$i++) {
	  if(!stristr($invoice[$i]['in_type'], 'eno')) {
		  if(stristr($invoice[$i]['in_type'], 'payment')) { 
		  $color = '#E1FFF0';
		  } else if(stristr($invoice[$i]['in_type'], 'refund')) {
			  $color = '';
		  } else {
			  $color = '#FFF4F4';
		  }?>
  <tr bgcolor="<?php echo $color; ?>">
    <td class="dataTableLeft">
    	&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="<?php echo $invoice[$i]['in_path']; ?>"><?php echo $invoice[$i]['in_id']; ?></a>
    </td>
    <td><?php echo $invoice[$i]['date_entered2']; ?></td>
    <td><?php echo ucwords($invoice[$i]['in_type']); ?></td>
    <td><?php if($invoice[$i]['in_transactionId'] != '0') { echo $invoice[$i]['in_transactionId']; } ?></td>
    <td>$<?php echo $invoice[$i]['in_amount']; ?></td>
  </tr>
  <?php } 
}?>
     </tbody>
</table>

   
   
   
   
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	
	$('#invoiceList').dataTable({
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
		  { "bSortable": false, "aTargets": [ 1, 6 ] }
		],
		<?php } ?>
		"sDom": '<"H"fip<"clear">>rt<"F"ip<"clear">>',
		"oLanguage": {
			"sSearch": ""
	   }
	});
});

</script>