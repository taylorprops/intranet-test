<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$test = '';

include('/var/www/annearundelproperties.net/new/includes/global.php');

$id = $_POST['id'];
$report = $_POST['report'];
$year = $_POST['year'];

$start_year =  date($year."-12-27");
$start = date("Y-m-d", strtotime("$start_year -1 year"));
$end = date($year.'-12-26');


$agentQuery = "Select * from company.tbl_agents".$test." where id = '".$id."'";
$agent = $db -> select($agentQuery);

if(stristr($report, 'invoice')) {
	$report_type = 'invoice';
} else {
	$report_type = 'statement';
}

if(stristr($report, 'charge')) {
	$opt = " and (in_type like 'charge%' or in_type like 'late%')";
	$title = $year.' Invoices';
} else if(stristr($report, 'payment')) {
	$opt = " and in_type like 'payment%' and in_paid = 'yes'";
	$title = $year.' Payments';
} else {
	$opt = '';
	$title = $year.' Invoices and Payments';
}



$invoiceQuery = "Select * from company.billing_invoices".$test." where in_agent_id = '".$id."' and in_date_sent between '".$start."' and '".$end."' and in_type != 'refund' and refunded = 'no' ".$opt." order by in_date_sent DESC";
$invoice = $db -> select($invoiceQuery);
$invoiceCount = count($invoice);

$invoiceHTML = '';
$in_id = '';
$invoice_type = '';
$in_date  = '';
$in_amount = '';

if($report_type == 'statement') {
	$invoiceHTML .= '<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">
	<page_header>
        <div style="font-size:20px; font-weight:bold">'.$title.'</div>
    </page_header>';
	$invoiceHTML .= '
	<table style="font-size: 11px;">
		<tr>
			<th style="border-bottom: 2px solid #ccc; padding: 3px;">Invoice #</th>
			<th style="border-bottom: 2px solid #ccc; padding: 3px;">Date</th>
			<th style="border-bottom: 2px solid #ccc; padding: 3px;">Invoice Type</th>
			<th style="border-bottom: 2px solid #ccc; padding: 3px;">Description</th>
			<th style="border-bottom: 2px solid #ccc; padding: 3px;">Amount</th>
		</tr>';
		
	for($i=0;$i<$invoiceCount;$i++) {
		
		$in_id = $invoice[$i]['in_id'];
	
		$itemsQuery = "Select * from company.billing_invoices_items".$test." where in_invoice_id = '".$in_id."'";
		$items = $db -> select($itemsQuery);
		$itemsCount = count($items);
		
		
		$in_amount = $invoice[$i]['in_amount'];
		$in_date = $invoice[$i]['in_date_sent'];
		
		if($invoice[$i]['in_type'] == 'charge') {
			$invoice_type = 'Charge';
		} else if($invoice[$i]['in_type'] == 'chargeEno') {
			$invoice_type = 'Charge';
		} else if($invoice[$i]['in_type'] == 'lateFee') {
			$invoice_type = 'Charge';
		} else if($invoice[$i]['in_type'] == 'lateFeeEno') {
			$invoice_type = 'Charge';
		} else if($invoice[$i]['in_type'] == 'payment') {
			$invoice_type = 'Payment';
		} else if($invoice[$i]['in_type'] == 'paymentEno') {
			$invoice_type = 'Payment';
		}
		
		$invoiceHTML .= '
		<tr>
			<td>'.$in_id.'</td>
			<td>'.date("n/j/Y", strtotime("$in_date +0 day")).'</td>
			<td>'.$invoice_type.'</td>
			<td>';

		for($it=0;$it<count($items);$it++) {
			$invoiceHTML .= 
			$items[$it]['in_item_desc'].'<br>';
		}
		
		$invoiceHTML .= '</td>
			<td><strong>'.$in_amount.'</strong></td>
		</tr>
		<tr><td colspan="5" style="border-bottom: 1px solid #ccc;"></td></tr>';
		
	}
	$invoiceHTML .= '</table>
	</page>';
}
	
if($report_type == 'invoice') {
	for($i=0;$i<$invoiceCount;$i++) {
		
		$in_id = $invoice[$i]['in_id'];
		
		$itemsQuery = "Select * from company.billing_invoices_items".$test." where in_invoice_id = '".$in_id."'";
		$items = $db -> select($itemsQuery);
		$itemsCount = count($items);
		
		
		$in_amount = $invoice[$i]['in_amount'];
		$in_date = $invoice[$i]['in_date_sent'];
		
		$company = $agent[0]['lic1_company'];
		$fullname = $db->quote($agent[0]['fullname']);
		$agent_street = $agent[0]['street'];
		$agent_csz = $agent[0]['city'].', '.$agent[0]['state'].' '.$agent[0]['zip'];
		
		if($invoice[$i]['in_type'] == 'charge') {
			$invoice_type = 'Invoice';
		} else if($invoice[$i]['in_type'] == 'chargeEno') {
			$invoice_type = 'E&amp;O Invoice';
		} else if($invoice[$i]['in_type'] == 'lateFee') {
			$invoice_type = 'Late Fee Invoice';
		} else if($invoice[$i]['in_type'] == 'lateFeeEno') {
			$invoice_type = 'E&amp;O Late Fee Invoice';
		} else if($invoice[$i]['in_type'] == 'payment') {
			$invoice_type = 'Payment Receipt';
		} else if($invoice[$i]['in_type'] == 'paymentEno') {
			$invoice_type = 'E&amp;O Payment Receipt';
		}
		
		$invoiceHTML .= '<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">';
		$invoiceHTML .= '
		
		<style type="text/css">
		#items_table th, #items_table td { padding: 5px; }
		</style>
		<table cellpadding="0" cellspacing="0" style="font-family:Arial; color:#3A4A63">
			<tr>
				<td width="10" style="background: #3A4A63;"></td>
				<td width="315" style="background: #3A4A63; color:#ffffff; font-size:30px; font-weight:bold; padding: 10px 0;">'.$company.'</td>
				<td valign="top" align="right" width="315"  style="background: #3A4A63; color:#ffffff;font-size:14px; line-height: 17px; padding: 10px 0;">175 Admiral Cochrane Drive<br>Suite 111<br>Annapolis, MD 21401
				</td>
				<td width="10" style="background: #3A4A63;"></td>
			</tr>
			<tr>
					<td colspan="4" height="15">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" style="font-size: 25px; font-weight: bold;">'.$invoice_type.'</td>
				</tr>
				<tr>
					<td colspan="4" height="15">&nbsp;</td>
				</tr>
			<tr>
				<td colspan="2" width="325">
					<table width="300" cellpadding="0" cellspacing="0" style="font-family:Arial; color:#3A4A63; font-size:15px;">
						<tr>
							<td style="font-weight: bold;">To:</td>
						</tr>
						<tr>
							<td>'.$fullname.'</td>
						</tr>
						<tr>
							<td>'.$agent_street.'</td>
						</tr>
						<tr>    
							<td>'.$agent_csz.'</td>
						</tr>
					</table>
				</td>
				<td colspan="2" valign="top" align="right">
					<span style="font-size:15px; line-height: 20px;">Invoice#: '.$in_id.'<br>'.date("n/j/Y", strtotime("$in_date +0 day"));
				
				if(stristr($invoice[$i]['payment_type'], 'creditcard')) {
					$invoiceHTML .= '
					<br>
					Paid With: Credit Card
					<br>';
					if($invoice[$i]['card_used'] != '') {
						$invoiceHTML .= '
						Card Used: '.$invoice[$i]['card_used'].'
						<br>';
					}
					$invoiceHTML .= 'Transaction ID: '.$invoice[$i]['transactionID'];
				} else if($invoice[$i]['payment_type'] == 'check') {
					$invoiceHTML .= '
					<br>
					Paid With: Check<br>
					Check Number: '.$invoice[$i]['check_num'];
				} else if($invoice[$i]['payment_type'] == 'commission') {
					$invoiceHTML .= '
					<br>
					Paid With: Commission';
				} else if($invoice[$i]['payment_type'] == 'credit') {
					$invoiceHTML .= '
					<br>
					Paid With: Company Credit';
				} 
				
				$invoiceHTML .= '</span></td>
			</tr>
			<tr>
				<td colspan="4" height="30">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" width="650">';
				
				if($invoice[$i]['in_type'] == 'charge' || $invoice[$i]['in_type'] == 'chargeEno' || $invoice[$i]['in_type'] == 'lateFee' || $invoice[$i]['in_type'] == 'lateFeeEno') {
				
					$invoiceHTML .= '<table width="610" cellpadding="0" cellspacing="0" style="font-family:Arial; color:#3A4A63" id="items_table">
						<tr>
							<td width="365" align="left" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Description</td>
							<td width="80" align="center" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Quantity</td>
							<td width="75" align="center" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Unit Price</td>
							<td width="80" align="right" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Total</td>
						</tr>';			
		for($it=0;$it<count($items);$it++) {
			if($it < (count($items) - 1)) {
				$invoiceHTML .= '
							<tr>
								<td align="left" style="border-bottom: 1px dotted #D0CAF5;">'.$items[$it]['in_item_desc'].'</td>
								<td align="center" style="border-bottom: 1px dotted #D0CAF5;">'.$items[$it]['in_item_quantity'].'</td>
								<td align="center" style="border-bottom: 1px dotted #D0CAF5;">$'.$items[$it]['in_item_amount'].'</td>
								<td align="right" style="border-bottom: 1px dotted #D0CAF5;">$'.$items[$it]['in_item_total'].'</td>
							</tr>';
			} else {
				$invoiceHTML .= '
							<tr>
								<td align="left">'.$items[$it]['in_item_desc'].'</td>
								<td align="center">'.$items[$it]['in_item_quantity'].'</td>
								<td align="center">$'.$items[$it]['in_item_amount'].'</td>
								<td align="right">$'.$items[$it]['in_item_total'].'</td>
							</tr>';
			}
		}
		$invoiceHTML .= '
					<tr>
						<td style="border-top: 1px solid #D0CAF5;">&nbsp;</td>
						<td style="border-top: 1px solid #D0CAF5;">&nbsp;</td>
						<td align="right" style="font-weight: bold; font-size:16px; border-top: 1px solid #D0CAF5;">Total Due</td>
						<td align="right" style="font-weight: bold; font-size:16px; border-top: 1px solid #D0CAF5;">$'.$in_amount.'</td>
					</tr>';
		$invoiceHTML .= '
					</table>';
				} // end if  charge or late fee
				// begin if payment
				else if(stristr($invoice[$i]['in_type'], 'payment')) {
				
				$total_text = 'Amount Paid';
				
				$invoiceHTML .= '<table width="610" cellpadding="0" cellspacing="0" style="font-family:Arial; color:#3A4A63" id="items_table">
					<tr>
						<td width="365" align="left" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Description</td>
						<td width="80" align="center" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;"></td>
						<td width="75" align="center" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;"></td>
						<td width="80" align="right" style="background: #D0CAF5; color:#3A4A63; font-weight:bold; font-size:15px;">Amount</td>
					</tr>';			
		for($it=0;$it<count($items);$it++) {
			if($it < (count($items) - 1)) {
				$invoiceHTML .= '
							<tr>
								<td align="left" style="border-bottom: 1px dotted #D0CAF5;">'.$items[$it]['in_item_desc'].'</td>
								<td align="center" style="border-bottom: 1px dotted #D0CAF5;"></td>
								<td align="center" style="border-bottom: 1px dotted #D0CAF5;"></td>
								<td align="right" style="border-bottom: 1px dotted #D0CAF5;">$'.$items[$it]['in_item_total'].'</td>
							</tr>';
			} else {
				$invoiceHTML .= '
							<tr>
								<td align="left">'.$items[$it]['in_item_desc'].'</td>
								<td align="center"></td>
								<td align="center"></td>
								<td align="right">$'.$items[$it]['in_item_total'].'</td>
							</tr>';
			}
		}
		$invoiceHTML .= '
					<tr>
						<td style="border-top: 1px solid #D0CAF5;">&nbsp;</td>
						<td style="border-top: 1px solid #D0CAF5;">&nbsp;</td>
						<td align="right" style="font-weight: bold; font-size:16px; border-top: 1px solid #D0CAF5;">'.$total_text.'</td>
						<td align="right" style="font-weight: bold; font-size:16px; border-top: 1px solid #D0CAF5;">$'.$in_amount.'</td>
					</tr>';
		$invoiceHTML .= '
				</table>';
					
				}
				// end if payment 
				
				$invoiceHTML .= '</td>
			</tr>
		</table>';
		
	
		$invoiceHTML .= '</page>';
	}
}

$filename = date('Ymdhis');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($invoiceHTML);
$html2pdf->Output('temp/Report_'.$filename.'.pdf','F');
chmod('temp/Report_'.$filename.'.pdf', 0777);
echo 'Report_'.$filename.'.pdf';
?>