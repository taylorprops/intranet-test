<?php
include('/var/www/annearundelproperties.net/new/includes/global.php');

$represent = $_POST['checklist_represent']; // buyer, seller
$property_type = $_POST['checklist_prop_type']; // Residential, Lot, Commercial, Multi
$checklist_type = $_POST['checklist_type'];  // listing, contract, lease
$for_sale = $_POST['checklist_forsale']; // Y, N
$state = $_POST['checklist_state'];
$countyAbbr = $_POST['checklist_county'];
$trans_type = $_POST['checklist_trans_type']; // Short Sale, Foreclosure, New Construction, Estate, FSBO, Standard
/* removed
$mls = $_POST['mls'];
$agent_id = $_POST['agent_id'];
*/

if($checklist_type == 'listing') {
	$type = 'listing';
} else {
	$type = 'contract';
}

if($represent == 'seller') {
	$rep_seller = 'yes';
} else {
	$rep_buyer = 'yes';
}

if(stristr($property_type, 'lot')) {
	$lot = "and lot != 'No'";
} else {
	$lot = "and lot != 'Yes'";;
}



if($for_sale == 'Y') {
	$forsale = "and forsale like '%Sale%'";
	if($checklist_type == 'listing') {
		$doc_type = 'Listing Agreement';
	} else {
		$doc_type = 'Sales Contract';
	}

	if(stristr($trans_type, 'foreclosure') || $trans_type == 'New Construction' || $trans_type == 'Estate') {
		$trans = "and transaction_type like '%Foreclosure%'";
	} else if($trans_type == 'Potential Short Sale') {
		$trans = "and transaction_type like '%Short Sale%'";
	} else if($trans_type == 'Standard') {
		$trans = "and transaction_type like '%Standard%'";
	} else if($trans_type == 'FSBO') {
		$trans = "and (transaction_type like '%FSBO%' or transaction_type like '%Standard%')";
	} else {
		$trans = "";
	}

} else {
	$forsale = "and forsale like '%Rental%'";
	if($checklist_type == 'listing') {
		$doc_type = 'Listing Agreement';
	} else {
		$doc_type = 'Lease Agreement';
	}

	$trans = '';
}

if($rep_buyer == 'yes') {
	$buyer = 'yes';
	$rep_buyer = "";
} else {
	$rep_buyer = "and rep_buyer != 'Yes'";
	$buyer = 'no';

}

$letter = ' and id != 118';


$docsQuery = "select * from company.contract_docs where active = 'yes' and (doc_type like '%".$type."%' and ".$state." = 'Yes' ".$trans." ".$forsale." ".$rep_buyer." ".$lot." and ".$state."_counties_abbr like '%".$countyAbbr."%' and ground_rent = 'no') ".$letter." order by doc_name";
$docs = $db -> select($docsQuery);
$docsCount = count($docs);

//echo $docsQuery;

ob_start();
?>
<page backtop="25mm" backbottom="7mm" backleft="10mm" backright="10mm">
	<page_header>
        <div style="font-size:20px; font-weight:bold; margin-left: 35px; margin-top: 25px;">Checklist - <?php echo $doc_type; if($county != '') { ?> - <?php echo $county; ?> County<?php } ?></div>
    </page_header>
    <style type="text/css">
	.check_box { height: 20px; width: 20px; border: 1px solid #333; }
	th, td { padding: 7px; }
	</style>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="required_docs_table">
    <?php
	if($buyer == 'yes') { ?>
    <tr>
        <th colspan="2">In-House Docs</th>
    </tr>
    <?php
    	for($d=0;$d<$docsCount;$d++) {
        	if($docs[$d]['in_house'] == 'Yes') { ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name'];?></td>
    </tr>
    <?php   }
        }
	} else if($doc_type == 'Listing Agreement') { ?>
    <tr>
        <th colspan="2">In-House Docs</th>
    </tr>
    <?php
    	for($d=0;$d<$docsCount;$d++) {
        	if($docs[$d]['in_house'] == 'Yes') {  ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name']; ?></td>
    </tr>
    <?php 	}
        }
    } ?>
    <tr>
        <th colspan="2">Required Docs</th>
    </tr>
    <?php
	for($d=0;$d<$docsCount;$d++) {
    	if((($docs[$d]['required'] == 'Yes' &&
		$docs[$d]['in_house'] == 'No') ||
		($docs[$d]['id'] == 118 && $title_earnest == 'yes') ||
		$docs[$d]['id'] == 54) && $docs[$d]['id'] != 56 && $docs[$d]['id'] != 55) {  ?>
    <tr>
        <td><div class="check_box"></div></td>
        <td><?php echo $docs[$d]['doc_name']; ?></td>
    </tr>
    <?php }
    }?>


    <tr>
        <th colspan="2">Conditional Docs</th>
    </tr>
    <tr>
        <td><div class="check_box"></div></td>
        <td>Disclosure of Licensee Status Addendum </td>
    </tr>
	<?php if($type == 'contract' && $for_sale == 'Y') { ?>
	<tr>
		<td><div class="check_box"></div></td>
		<td>Earnest Deposit Confirmation from Title Company</td>
	</tr>
	<?php } ?>
	<?php if($countyAbbr == 'CH') { ?>
	<tr>
		<td><div class="check_box"></div></td>
		<td>Fair Share School Construction Excise Tax Notice (If Built after 2002)</td>
	</tr>
	<?php } ?>
    <?php if($for_sale == 'Y') { ?>
        <tr>
            <td><div class="check_box"></div></td>
            <td>For Sale By Owner Forms </td>
        </tr>
		<tr>
            <td><div class="check_box"></div></td>
            <td>Ground Rent Disclosures</td>
        </tr>
        <tr>
            <td><div class="check_box"></div></td>
            <td>HOA/Condo Disclosures</td>
        </tr>
        <tr>
            <td><div class="check_box"></div></td>
            <td>Lead Paint Disclosures</td>
        </tr>



    <?php } ?>

</table>
</page>
<?php
$html = ob_get_contents();
ob_end_clean();
$report_name = 'Checklist_'.date('YmdHis');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($html);
$html2pdf->Output('temp/'.$report_name.'.pdf', 'F');
chmod('temp/'.$report_name.'.pdf', 0777);
echo '/new/agents/ajax/dashboard/temp/'.$report_name.'.pdf';
?>
