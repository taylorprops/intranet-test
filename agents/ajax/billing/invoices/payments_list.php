<?php
include('/var/www/annearundelproperties.net/includes/global.php');
require('/var/www/annearundelproperties.net/authnet/config'.$test.'.inc.php');
require('/var/www/annearundelproperties.net/authnet/AuthnetXML.class.php');

$agentQuery = "select * from company.billing_agent_info".$test." where ag_agent_id = '".$_GET['ag_agent_id']."'";
$mainDao->setQuery($agentQuery);
$mainDao->runQuery();
$agent = $mainDao->getMultiAssocArray();

$xml = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, $TestServer);
$xml->getCustomerProfileRequest(array(
	'customerProfileId' => $agent[0]['profileId']
));
$profiles = array();
$cards = array();
$firstName = array();
$lastName = array();
$address = array();
$zip = array();
$city = array();
$state = array();
foreach ($xml->profile->paymentProfiles as $profile) {
	$profiles[] = $profile->customerPaymentProfileId;
	$cards[] = $profile->payment->creditCard->cardNumber;
	$firstName[] = $profile->billTo->firstName;
	$lastName[] = $profile->billTo->lastName;
	$address[] = $profile->billTo->address;
	$zip[] = $profile->billTo->zip;
	$locQuery = "select * from company.tbl_zips where zip = '".$profile->billTo->zip."'";
	$mainDao->setQuery($locQuery);
	$mainDao->runQuery();
	$loc = $mainDao->getMultiAssocArray();
	$city[] = ucwords(strtolower($loc[0]['city']));
	$state[] = $loc[0]['state'];
}
$payQuery = "Select *, date_format(py_date, '%m/%d/%Y') as py_date2 from company.billing_payments".$test." where py_agent_id = '".$_GET['ag_agent_id']."' order by py_date DESC";
$mainDao->setQuery($payQuery);
$mainDao->runQuery();
$pay = $mainDao->getMultiAssocArray();
$payCount = count($pay);

?>

<table border="0" cellpadding="0" cellspacing="0" id="paymentList" width="100%">
                <thead>
                  <tr>
                    <th>Invoice #</th>
                    <th>TansactionID</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Result</th>
                    <th>Message</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for($p=0;$p<count($pay);$p++) {
					  $pathQuery = "Select in_path from company.billing_invoices where in_id = '".$pay[$p]['py_invoice_id']."'";
					$mainDao->setQuery($pathQuery);
					$mainDao->runQuery();
					$path = $mainDao->getMultiAssocArray();
	  if(($pay[$p]['responseCode']=='1' || $pay[$p]['py_payment_type'] == 'phyCheck' || $pay[$p]['py_payment_type'] == 'credit' || $pay[$p]['py_payment_type'] == 'cash') and $pay[$p]['refunded'] == 'no') {
		  $color2 = '#E1FFF0';
	  } else {
		  $color2 = '#FFF4F4';
	  }
	  if($pay[$p]['py_credit_desc'] == 'paymentEno') {
		  $eno = '_eno';
	  } else {
		  $eno = '';
	  }
	  ?>
                  <tr  bgcolor="<?php echo $color2; ?>">
                    <td class="dataTableLeft"><?php if($pay[$p]['responseCode']=='1' || $pay[$p]['py_payment_type'] == 'phyCheck' || $pay[$p]['py_payment_type'] == 'credit' || $pay[$p]['py_payment_type'] == 'cash') { ?>
                      <a target="_blank" href="<?php echo $path[0]['in_path']; ?>"><?php echo $pay[$p]['py_invoice_id']; ?></a>
                      <?php } ?></td>
                    <td><?php echo $pay[$p]['transactionID']; ?></td>
                    <td><?php echo $pay[$p]['py_date2']; ?></td>
                    <td nowrap="nowrap"><?php echo paytype($pay[$p]['py_payment_type']); ?></td>
                    <td>$<?php echo $pay[$p]['py_amount']; ?></td>
                    <td><?php if($pay[$p]['responseCode']=='1' || $pay[$p]['py_payment_type'] == 'phyCheck' || $pay[$p]['py_payment_type'] == 'credit' || $pay[$p]['py_payment_type'] == 'cash') { echo "Success"; } else { echo "Failed"; } ?></td>
                    <td width="150"><?php if($pay[$p]['responseText'] != 'This transaction has been approved.') { echo $pay[$p]['responseText']; } ?></td>
                    <td class="dataTableRight"><?php
	if($pay[$p]['refunded'] == 'yes') { 
		echo '<span style="color:#990000;">Refunded</span>';
	} else {
		if($_SESSION['S_Group'] == 'admin' || $_SESSION['S_Group'] == 'management') {
			if(stristr($pay[$p]['py_payment_type'], 'creditCard')) {
  
				  if($pay[$p]['refundable'] == 'yes') {
					  if($pay[$p]['py_date'] > date("Y-m-d", strtotime("-2 day"))) {
						  $td = new AuthnetXML(AUTHNET_LOGIN, AUTHNET_TRANSKEY, $TestServer);
						  $td->getTransactionDetailsRequest(array(
							  'transId' => $pay[$p]['transactionID']
						  ));
				  		  $transactionStatus = $td->transaction->transactionStatus;
						  if(stristr($transactionStatus, 'pending')) { ?>
						 
						 	  <a href="javascript:void(0); "class="voidButton" transId="<?php echo $pay[$p]['transactionID']; ?>" py_id="<?php echo $pay[$p]['py_id']; ?>" ag_agent_id="<?php echo $pay[$p]['py_agent_id']; ?>" in_id="<?php echo $pay[$p]['py_invoice_id']; ?>" profileId="<?php echo $agent[0]['profileId']; ?>" payProfileId="<?php echo $pay[$p]['py_payment_profile_id']; ?>" py_amount="<?php echo $pay[$p]['py_amount']; ?>" eno="<?php echo $eno; ?>">Void</a>
						 
					<?php } else if(stristr($transactionStatus, 'settled')) { ?>
						  
						      <a href="javascript:void(0);" class="refundButton" transId="<?php echo $pay[$p]['transactionID']; ?>" py_id="<?php echo $pay[$p]['py_id']; ?>" ag_agent_id="<?php echo $pay[$p]['py_agent_id']; ?>" in_id="<?php echo $pay[$p]['py_invoice_id']; ?>" profileId="<?php echo $agent[0]['profileId']; ?>" payprofileid="<?php echo $pay[$p]['py_payment_profile_id']; ?>" py_amount="<?php echo $pay[$p]['py_amount']; ?>" ag_email="<?php echo $agent[0]['ag_email'] ; ?>" ag_fullname="<?php echo $agent[0]['ag_fullname'] ; ?>" ag_balance="<?php echo $agent[0]['ag_balance']; ?>" eno="<?php echo $eno; ?>">Refund</a>
						  
					<?php }
					} else { ?>
                    	<a href="javascript:void(0);" class="refundButton" transId="<?php echo $pay[$p]['transactionID']; ?>" py_id="<?php echo $pay[$p]['py_id']; ?>" ag_agent_id="<?php echo $pay[$p]['py_agent_id']; ?>" in_id="<?php echo $pay[$p]['py_invoice_id']; ?>" profileId="<?php echo $agent[0]['profileId']; ?>" payprofileid="<?php echo $pay[$p]['py_payment_profile_id']; ?>" py_amount="<?php echo $pay[$p]['py_amount']; ?>" ag_email="<?php echo $agent[0]['ag_email'] ; ?>" ag_fullname="<?php echo $agent[0]['ag_fullname'] ; ?>" ag_balance="<?php echo $agent[0]['ag_balance']; ?>" eno="<?php echo $eno; ?>">Refund</a>
               <?php }
				}
			} else if($pay[$p]['py_payment_type'] == 'phyCheck' || $pay[$p]['py_payment_type'] == 'credit' || $pay[$p]['py_payment_type'] == 'cash') { ?>
				<a href="javascript:void(0);" class="deletePayment" py_amount="<?php echo $pay[$p]['py_amount']; ?>" py_id="<?php echo $pay[$p]['py_id']; ?>" ag_agent_id="<?php echo $pay[$p]['py_agent_id']; ?>" in_id="<?php echo $pay[$p]['py_invoice_id']; ?>">Delete</a>
			<?php
            } 
		}
	}?></td>
      </tr>
 <?php } ?>
                </tbody>
              </table>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$(".voidButton").one("click", function(){
		$("#loadingDiv, #blackout").show();
		$in_id = $(this).attr("in_id");
		$ag_agent_id = $(this).attr("ag_agent_id");
		$py_id = $(this).attr("py_id");
		$transId = $(this).attr("transId");
		$profileId = $(this).attr("profileId");
		$payProfileId = $(this).attr("payprofileid");
		$py_amount = $(this).attr("py_amount");
		$.ajax({
			type: "POST",
			url: "../page_includes/billing/void_transaction.php",
			data: "in_id="+$in_id+"&ag_agent_id="+$ag_agent_id+"&py_id="+$py_id+"&transId="+$transId+"&profileId="+$profileId+"&payProfileId="+$payProfileId+"&py_amount="+$py_amount,
			success: function(results){
				if(results == 'ok') {
					$("#blackout, #loadingDiv").hide();
					showAgent();
				} else {
					$("#loadingDiv, #blackout").hide();
					$("#voidResults").dialog("open");
					$("#voidResults").html(results);
				}
			}
		});
	});
	$(".refundButton").bind("click", function(){
		$("#loadingDiv, #blackout").show();
		$in_id = $(this).attr("in_id");
		$ag_agent_id = $(this).attr("ag_agent_id");
		$ag_fullname = $(this).attr("ag_fullname");
		$ag_email = $(this).attr("ag_email");
		$py_id = $(this).attr("py_id");
		$transId = $(this).attr("transId");
		$profileId = $(this).attr("profileId");
		$payProfileId = $(this).attr("payProfileId");
		$py_amount = $(this).attr("py_amount");
		$ag_balance = $(this).attr("ag_balance");
		$.ajax({
			type: "POST",
			url: "../page_includes/billing/refund_transaction.php",
			data: "in_id="+$in_id+"&ag_agent_id="+$ag_agent_id+"&py_id="+$py_id+"&transId="+$transId+"&profileId="+$profileId+"&payProfileId="+$payProfileId+"&py_amount="+$py_amount+"&ag_email="+$ag_email+"&ag_balance="+$ag_balance,
			success: function(results){
				if(results == 'ok') {
					showAgent();
				} else {
					$("#loadingDiv, #blackout").hide();
					$("#refundResults").dialog("open");
					$("#refundResults").html(results);
				}
			}
		});							
	});
	$('#paymentList').dataTable({
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
		"aaSorting": [ ],
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": true,
		"bSort": true,
		"bInfo": true,
		"bAutoWidth": false,
		"iDeferLoading": <?php echo $payCount; ?>,
		"aLengthMenu": [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
		"iDisplayLength": 15,
		"aoColumnDefs": [ 
		  { "bSortable": false, "aTargets": [ 7 ] }
		],
		"sDom": '<"H"fip<"clear">>rt<"F"ip<"clear">>',
		"oLanguage": {
		 	"sSearch": ""
	   }
	});
});
</script>