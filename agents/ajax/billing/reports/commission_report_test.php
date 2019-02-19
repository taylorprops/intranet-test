<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');


$start = $_POST['start'];
$end = $_POST['end'];
if($end == '') {
	$end = date('Y-m-d');
}
$id = $_SESSION['S_ID'];
/*
$id = 3468;
$start = '2017-01-01';
$end = '2017-12-31';
*/
$agentQuery = "Select * from company.tbl_agents where id = '".$id."'";
$agent = $db -> select($agentQuery);
$recipient = $agent[0]['fullname'];
if($agent[0]['llc_name'] != '') {
	$recipient .= '<br>'.$agent[0]['llc_name'];
}


$title = 'Taylor/Anne Arundel Properties<br>Commission Report - '.$start.' through '.$end;

$commQuery = "Select * from company.commission where deleted = 'no' and ";
for($i=1;$i<6;$i++) {
	$commQuery .= "(recip".$i."_id = '".$id."' and date_deposited >= '".$start."' and date_deposited <= '".$end."')";
	//$commQuery .= "(recip".$i."_id = '".$id."' and date_deposited >= '".$start."' and date_deposited <= '".$end."')";
	if($i < 5) {
		$commQuery .= " or ";
	}
}

$comm = $db -> select($commQuery);

$commCount = count($comm);

$commissions = array();

$nc = 0;
$total_amount = 0;
$total_count = 0;

for($c=0;$c<$commCount;$c++) {

	$mls = $comm[$c]['mls'];

	for($i=1;$i<6;$i++) {

		if($comm[$c]['recip'.$i.'_id'] == $id && $comm[$c]['recip'.$i.'_check_amount'] != '' && $comm[$c]['recip'.$i.'_check_amount'] > '0') {

			$nc += 1;

			if($comm[$c]['referral_street'] == '') {
				/* _CHANGED */
				$propQuery = "Select * from company.mls_company where ListingSourceRecordID = '".$mls."'";
				$prop = $db -> select($propQuery);

				$commissions[$nc]['address'] = $prop[0]['FullStreetAddress'].' '.$prop[0]['City'].', '.$prop[0]['StateOrProvince'].' '.$prop[0]['PostalCode'];

			} else {

				$commissions[$nc]['address'] = $comm[$c]['referral_street'].' '.$comm[$c]['referral_city'].', '.$comm[$c]['referral_state'].' '.$comm[$c]['referral_zip'];

			}

			$commissions[$nc]['amount'] = $comm[$c]['recip'.$i.'_check_amount'];


			$commissions[$nc]['date'] = $comm[$c]['date_deposited'];


			//$commissions[$nc]['date'] = $comm[$c]['date_deposited'];
			$total_amount += $comm[$c]['recip'.$i.'_check_amount'];
			$total_count += 1;

		}
	}


}

function sortFunction( $a, $b ) {
    return strtotime($a["date"]) - strtotime($b["date"]);
}
usort($commissions, "sortFunction");

$html = '<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">
<page_header>
	<div style="font-size:18px; font-weight:bold">'.$title.'</div>
</page_header>';
$html .= '
<style type="text/css">
th, td { padding: 5px; }
td { border-bottom: 1px dotted #ccc; }
</style>
<span style="font-size: 17px; font-weight: bold;">'.$total_count.' checks totaling $'.money($total_amount).'</span><br><br>
<table style="font-size: 11px;">
	<tr>
		<th style="border-bottom: 2px solid #ccc; padding: 3px;">Check Date</th>
		<th style="border-bottom: 2px solid #ccc; padding: 3px;">Recipient</th>
		<th style="border-bottom: 2px solid #ccc; padding: 3px;">Amount</th>
		<th style="border-bottom: 2px solid #ccc; padding: 3px;">Property</th>
	</tr>';

for($c=0;$c<count($commissions);$c++) {

		$html .= '
		<tr>
			<td>'.$commissions[$c]['date'].'</td>
			<td>'.$recipient.'</td>
			<td>$'.money($commissions[$c]['amount']).'</td>
			<td>'.$commissions[$c]['address'].'</td>
		</tr>';

}
$html .= '</table></page>';


$filename = date('Ymdhis');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($html);
$html2pdf->Output('../temp/Report_'.$filename.'.pdf','F');
chmod('../temp/Report_'.$filename.'.pdf', 0777);
echo 'Report_'.$filename.'.pdf';

?>
