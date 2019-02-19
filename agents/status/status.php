<?php
/* _DONE */
include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

$listing_id = $_GET['ListingSourceRecordID'];

$propQuery = "SELECT * from company.mls_company where ListingSourceRecordID = '".$listing_id."'";
$prop = $db -> select($propQuery);

$PropertyType = $prop[0]['PropertyType'];
$ListOfficeMlsId = $prop[0]['ListOfficeMlsId'];
$BuyerOfficeMlsId = $prop[0]['BuyerOfficeMlsId'];
$HaveListing = $prop[0]['HaveListing'];
$HaveContract = $prop[0]['HaveContract'];
$ListingSourceRecordID = $prop[0]['ListingSourceRecordID'];
$ListAgentFirstName = $prop[0]['ListAgentFirstName'];
$ListAgentLastName = $prop[0]['ListAgentLastName'];
$ListOfficeName = $prop[0]['ListOfficeName'];
$BuyerAgentFirstName = $prop[0]['BuyerAgentFirstName'];
$BuyerAgentLastName = $prop[0]['BuyerAgentLastName'];
$BuyerOfficeName = $prop[0]['BuyerOfficeName'];
$OurListAgent = $prop[0]['OurListAgent'];
$OurBuyerAgent = $prop[0]['OurBuyerAgent'];
$Closed = $prop[0]['Closed'];
$FullStreetAddress = $prop[0]['FullStreetAddress'];
$City = $prop[0]['City'];
$StateOrProvince = $prop[0]['StateOrProvince'];
$PostalCode =  = $prop[0]['PostalCode'];
$MlsStatus = $prop[0]['MlsStatus'];

if($db->quote($_SESSION['S_Username']) == 'Mike Taylor') {
	$uploadQuery = "select * from company.mls_uploads where upload_mls = '".$listing_id."' and invalid = 'no' and hide = 'no'";
} else {
	$uploadQuery = "select * from company.mls_uploads where upload_mls = '".$listing_id."' and upload_agent_id = '".$_SESSION['S_ID']."' and invalid = 'no' and hide = 'no'";
}
$upload = $db -> select($uploadQuery);
$uploadCount = count($upload);

$listing_uploads = 0;
$contract_uploads = 0;
for($t=0;$t<$uploadCount;$t++) {
	if($upload[$t]['upload_type'] == 'Listing Agreement') {
		$listing_uploads += 1;
	} else if($upload[$t]['upload_type'] == 'Lease Agreement' || $upload[$t]['upload_type'] == 'Sales Contract') {
		$contract_uploads += 1;
	}
}

if(stristr($ListOfficeMlsId, 'tayl') || stristr($ListOfficeMlsId, 'aapi')) {
	$our_listing = 'yes';
} else {
	$our_listing = 'no';
}

if(stristr($BuyerOfficeMlsId, 'tayl') || stristr($BuyerOfficeMlsId, 'aapi')) {
	$our_contract = 'yes';
} else {
	$our_contract = 'no';
}

if($HaveListing == 'yes') {
	$listing_status = 'processed';
} else if(($HaveListing == 'no' || $HaveListing == '') && $listing_uploads > 0) {
	$listing_status = 'submitted';
} else if(($HaveListing == 'no' || $HaveListing == '') && $listing_uploads == 0) {
	$listing_status = 'missing';
}

if($HaveContract == 'yes') {
	$contract_status = 'processed';
} else if(($HaveContract == 'no' || $HaveContract == '') && $contract_uploads > 0) {
	$contract_status = 'submitted';
} else if(($HaveContract == 'no' || $HaveContract == '') && $contract_uploads == 0) {
	$contract_status = 'missing';
}



if(stristr($ListingSourceRecordID, '_OT')) {
	$mls_display = 'Not MLS Listed';
} else {
	$mls_display = $ListingSourceRecordID;
}

if($ListAgentLastName != '') {
	$list_agent_info = $ListAgentFirstName.' '.$ListAgentLastName.' - '.$ListOfficeName;
} else if($OurListAgent != '') {
	$list_agent_info = $OurListAgent.'<br>'.$ListOfficeName;
} else {
	$list_agent_info = 'N/A';
}
if($BuyerAgentLastName != '') {
	$sale_agent_info = $BuyerAgentFirstName.' '.$BuyerAgentLastName.' - '.$BuyerOfficeName;
} else if($OurBuyerAgent != '') {
	$sale_agent_info = $OurBuyerAgent.'<br>'.$BuyerOfficeName;
} else {
	$sale_agent_info = 'N/A';
}

if(!stristr('lease', $PropertyType)) {
	$fs = 'For Sale';
	$forsale = 'yes';
	$agent_type = 'Buyer\'s Agent';
	$contract_type = 'Sales Contract';
	$hud = '<br>and Closing Disclosures';
	$doc_type = 'Contract';
	$prop_type = 'Sale';
} else {
	$fs = 'Rental';
	$forsale = 'no';
	$agent_type = 'Renter\'s Agent';
	$contract_type = 'Lease Agreement';
	$hud = '';
	$doc_type = 'Lease';
	$prop_type = 'Rental';
}

$earnest = earnest_dep_agent($listing_id);


// Icomplete Uploads
$incomplete_listings = array();
$incomplete_contracts = array();
$incomplete_count = 0;

if($Closed == 'no') {

	$listing_docs_counted = 'no';
	$contract_docs_counted = 'no';
	$listing_checklist_counted = 'no';
	$contract_checklist_counted = 'no';
	$listing_mls_counted = 'no';
	$contract_mls_counted = 'no';

	$listing_checklist = 'no';
	$listing_mls = 'no';
	$listing_docs = 'no';

	$contract_checklist = 'no';
	$contract_mls = 'no';
	$contract_docs = 'no';


	$have_contract = $HaveContract;
	if($our_listing != 'yes') {
		$have_listing = 'yes';
		$listing_checklist = 'yes';
		$listing_mls = 'yes';
		$listing_docs = 'yes';
	}

	$notify_listing = 'no';
	$notify_contract = 'no';

	for($t=0;$t<$uploadCount;$t++) {

		$upload_type = $upload[$t]['upload_type'];
		$doc_type = $upload[$t]['doc_type'];
		$invalid = $upload[$t]['invalid'];
		$upload_mls = $upload[$t]['upload_mls'];

		if(stristr($upload_type, 'listing') && $our_listing == 'yes') {

			$notify_listing = 'yes';

			if($have_listing == 'yes') {
				$listing_checklist = 'yes';
				$listing_mls = 'yes';
				$listing_docs = 'yes';
			}

			if($invalid == 'no') {


				if($upload[$t]['combo'] == 'yes') {

					$listing_checklist = 'yes';
					$listing_mls = 'yes';
					$listing_docs = 'yes';

				} else if(stristr($upload_mls, '_OT')) {

					if($doc_type == 'Checklist') {
						$listing_checklist = 'yes';
						$listing_mls = 'yes';
					} else if($doc_type == 'Listing Docs') {
						$listing_docs = 'yes';
					}

				} else {

					if($doc_type == 'Checklist') {
						$listing_checklist = 'yes';
					} else if($doc_type == 'MLS Printout') {
						$listing_mls = 'yes';
					} else if($doc_type == 'Listing Docs') {
						$listing_docs = 'yes';
					}

				}
			}
		} else if(stristr($upload_type, 'contract') || stristr($upload_type, 'lease agreement')) {

			$notify_contract = 'yes';

			if($have_contract == 'yes') {
				$contract_checklist = 'yes';
				$contract_mls = 'yes';
				$contract_docs = 'yes';
			}

			if($invalid == 'no') {


				if($upload[$t]['combo'] == 'yes') {
					$contract_checklist = 'yes';
					$contract_mls = 'yes';
					$contract_docs = 'yes';

				} else if(stristr($upload_mls, '_OT')) {

					if($doc_type == 'Checklist') {
						$contract_checklist = 'yes';
						$contract_mls = 'yes';
					} else if($doc_type == 'Contract Docs' || $doc_type == 'Lease Docs') {
						$contract_docs = 'yes';
					}
				} else {
					if($doc_type == 'Checklist') {
						$contract_checklist = 'yes';
					}
					if($doc_type == 'MLS Printout') {
						$contract_mls = 'yes';
					} else if($doc_type == 'Contract Docs' || $doc_type == 'Lease Docs') {
						$contract_docs = 'yes';
					}
				}
			}
		}
		/*
		if($upload[$t]['upload_type'] != 'Commission Breakdown' && $upload[$t]['upload_type'] != 'Release') {
			if(stristr($upload[$t]['upload_type'], 'lease')) {
				$contract_type = 'Lease Agreement';
			} else {
				$contract_type = 'Sales Contract';
			}
		}
		*/

		if($t == ($uploadCount - 1)) {
			if(($listing_checklist == 'no' || $listing_mls == 'no' || $listing_docs == 'no') && $notify_listing == 'yes') {

				$incomplete_count += 1;

				$incomplete_listing_table  =
				'<table width="100%" class="incomplete_table">';
				if($listing_checklist == 'no') {
					$incomplete_listing_table .= '
					<tr><td><img src="/new/images/icons/x_red.png" height="15"></td><td class="incomplete">Missing Listing Checklist</td></tr>';
				} else {
					$incomplete_listing_table .= '
					<tr><td><img src="/new/images/icons/check_green_new.png" height="15"></td><td class="complete">Listing Checklist Received</td></tr>';
				}
				if($listing_mls == 'no') {
					$incomplete_listing_table .= '
					<tr><td><img src="/new/images/icons/x_red.png" height="15"></td><td class="incomplete">Missing MLS Printout/td></tr>';
				} else {
					$incomplete_listing_table .= '
					<tr><td><img src="/new/images/icons/check_green_new.png" height="15"></td><td class="complete">MLS Printout Received</td></tr>';
				}
				if($listing_docs == 'no') {
					$incomplete_listing_table .= '
					<tr><td><img src="/new/images/icons/x_red.png" height="15"></td><td class="incomplete">Missing Listing Agreement</td></tr>';
				} else {
					$incomplete_listing_table .= '
					<tr><td><img src="/new/images/icons/check_green_new.png" height="15"></td><td class="complete">Listing Agreement Received</td></tr>';
				}

				$incomplete_listing_table .= '
				</table>';
				$incomplete_listings[] = $incomplete_listing_table;

			}
			//echo 'check = '.$contract_checklist.' and mls = '.$contract_mls.' and docs = '.$contract_docs.' and notify = '.$notify_contract;
			if(($contract_checklist == 'no' || $contract_mls == 'no' || $contract_docs == 'no') && $notify_contract == 'yes') {
				$incomplete_count += 1;

				$incomplete_contract_table =
				'<table width="100%" class="incomplete_table">';
				if($contract_checklist == 'no') {
					$incomplete_contract_table .= '
					<tr><td><img src="/new/images/icons/x_red.png" height="15"></td><td class="incomplete">Missing Sales Contract Checklist</td></tr>';
				} else {
					$incomplete_contract_table .= '
					<tr><td><img src="/new/images/icons/check_green_new.png" height="15"></td><td class="complete">Sales Contract Checklist Received</td></tr>';
				}
				if($contract_mls == 'no') {
					$incomplete_contract_table .= '
					<tr><td><img src="/new/images/icons/x_red.png" height="15"></td><td class="incomplete">Missing MLS Printout</td></tr>';
				} else {
					$incomplete_contract_table .= '
					<tr><td><img src="/new/images/icons/check_green_new.png" height="15"></td><td class="complete">MLS Printout Received</td></tr>';
				}
				if($contract_docs == 'no') {
					$incomplete_contract_table .= '
					<tr><td><img src="/new/images/icons/x_red.png" height="15"></td><td class="incomplete">Missing Sales Contract</td></tr>';
				} else {
					$incomplete_contract_table .= '
					<tr><td><img src="/new/images/icons/check_green_new.png" height="15"></td><td class="complete">Sales Contract Received</td></tr>';
				}
				$incomplete_contract_table .= '</table>';
				$incomplete_contracts[] = $incomplete_contract_table;

			}

		}
	}
}
// End Incomplete

// Missing Docs
$missingContractDocsQuery = "select * from company.contract_docs_missing where agent_id = '".$_SESSION['S_ID']."' and doc_type = 'contract' and mls = '".$listing_id."'";
$missingContractDocs = $db -> select($missingContractDocsQuery);
$missingContractDocsCount = count($missingContractDocs);

$missingListingDocsQuery = "select * from company.contract_docs_missing where agent_id = '".$_SESSION['S_ID']."' and doc_type = 'listing'  and mls = '".$listing_id."'";
$missingListingDocs = $db -> select($missingListingDocsQuery);
$missingListingDocsCount = count($missingListingDocs);


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Status -<?php echo ' '.$FullStreetAddress.' '.$City.', '.$State.' '.$PostalCode; ?></title>
<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
#prop_info_table td { padding-right: 25px; }
#mls_td { font-size: 25px; font-weight:bold; color: #D18359; }
#status_td { color:#3A4A63; font-size:22px; font-weight:bold; }
#address_td { font-weight:bold; font-size: 18px }
.sec_div { width: 98%; height:auto; padding: 1%; margin: 10px 0 30px 0;  border: 1px solid #3A4A63; background: rgba(42,65,111,0.2); font-size:15px; }
.sec_header { color:#D18359; font-size: 20px; font-weight:bold; margin: 0 0 15px 0; }
.sub_sec_header { font-size: 18px; font-weight:bold; padding: 5px 0; }
.sec_td { width: 49%; border: 1px solid #3A4A63; color:#3A4A63; padding: 5px;  background: #fff; }
.not_our_listing_div { font-size:22px; font-weight:bold; color:#3A4A63; opacity: .5; width: 400px; margin: 25px auto; text-align:center }
.listing_status { border-bottom: 1px dotted #ccc; }
.listing_status td {  padding: 8px; }
.incomplete_table td { padding: 3px; }
.incomplete_table { font-size:13px; }
.incomplete { color: #A84549 }
.complete { color: #003800; }
.missing_notes {
	margin-left: 15px;
	font-size: 12px; color: #A84549;
}
.earnest_deposit_table td {  padding-right: 55px; font-size:16px; }
#commission_details_table tr td { padding-left: 15px; }
</style>
</head>
<body>
<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>
<div class="body_container">
    <h2>Status -<?php echo ' '.$FullStreetAddress.' '.$City.', '.$State.' '.$PostalCode; ?></h2>
    <br>

        <table id="prop_info_table">
            <tr>
                <td id="mls_td"><?php echo $mls_display; ?></td>
                <td id="address_td"><?php echo $FullStreetAddress.' '.$City.', '.$State.' '.$PostalCode; ?></td>
                <td id="status_td">Status: <?php echo $MlsStatus; ?></td>
            </tr>
        </table>


    <div class="spacer"></div>

    <div class="sec_div">
    	<div class="sec_header">Agents</div>
        <table width="100%">
        	<tr>
            	<td class="sec_td" valign="top">
                	<div class="sub_sec_header">List Agent</div>
                	<?php echo $list_agent_info; ?>
                </td>
                <td></td>
                <td class="sec_td" valign="top">
               		<div class="sub_sec_header"><?php echo $agent_type; ?></div>
                    <?php echo $sale_agent_info; ?>
                </td>
            </tr>
        </table>
    </div>
    <div class="sec_div">
    	<div class="sec_header">Documents</div>
        <table width="100%">
        	<tr>
            	<td class="sec_td" valign="top">
                    <div class="sub_sec_header">Listing Agreement</div>
                    <?php if($our_listing == 'yes') { ?>

                        <table width="100%" class="listing_status" style="border-top: 1px dotted #ccc;">
                        	<!-- --------------------------- If Processed ------------------------- -->
                            <?php if($listing_status == 'processed') { ?>

                                <tr>
                                    <td valign="top" width="30" style="border-bottom: 1px dotted #ccc;">
                                        <img src="/new/images/icons/check_green_new.png" height="19">
                                    </td>
                                    <td colspan="2" style="border-bottom: 1px dotted #ccc;"><strong>Listing Agreement received and processed</strong></td>
                                </tr>
                                <?php // If missing listing docs
								if($missingListingDocsCount > 0) { ?>
                                    <tr>
                                        <td valign="top" width="30">
                                            <img src="/new/images/icons/warning_yellow.png">
                                        </td>
                                        <td>
                                        	<strong>Missing/Incomplete Documents</strong>
                                            <br>
                                            <table width="100%" class="incomplete_table">
                                            <?php for($ld=0;$ld<$missingListingDocsCount;$ld++) {
												if($ld != ($missingListingDocsCount - 1)) {
													$border = 'style="border-bottom: 1px dotted #ccc;"';
												} else {
													$border = '';
												} ?>
                                            	<tr>
                                                	<td width="25" <?php echo $border; ?>><img src="/new/images/icons/x_red.png" height="15"></td>
                                                    <td <?php echo $border; ?>>
														<?php echo $missingListingDocs[$ld]['doc_name']; ?>
                                                        <?php if($missingListingDocs[$ld]['doc_notes'] != '') { ?>
                                                            <div class="missing_notes"><em><?php echo $missingListingDocs[$ld]['doc_notes']; ?></em></div>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </table>
                                        </td>
                                        <td valign="top" align="right" width="100"><a href="/new/agents/uploads/uploads.php?mls=<?php echo $listing_id; ?>" target="_blank" class="button button_normal" style="padding: 10px 5px;">Upload Docs</a></td>
                                    </tr>
                                <?php
								} /* end If missing listing docs */ else { ?>
                                	<tr>
                                        <td valign="top" width="30">
                                            <img src="/new/images/icons/check_green_new.png" height="19">
                                        </td>
                                        <td colspan="2"><strong>No Missing/Incomplete Documents</strong></td>
                                    </tr>
                                <?php } ?>
                            <!-- --------------------------- End If Processed ------------------------- -->

                            <!-- --------------------------- If Submitted------------------------- -->
                            <?php } else if($listing_status == 'submitted') { ?>

                                <tr>
                                    <td valign="top" width="30">
                                        <img src="/new/images/icons/warning_yellow.png">
                                    </td>
                                    <?php
                                    if(count($incomplete_listings) > 0) { ?>
                                        <td><strong>Listing Agreement submitted but Incomplete</strong>
                                        <?php
                                        for($il=0;$il<count($incomplete_listings);$il++) {
                                            echo $incomplete_listings[$il];
                                            if($il != (count($incomplete_listings) - 1)) {
                                                echo '<br>';
                                            }
                                        }
                                        ?></td>
                                        <td valign="middle"><a href="/new/agents/uploads/uploads.php?mls=<?php echo $listing_id; ?>" target="_blank" class="button button_normal" style="padding: 5px;">View/Upload Docs</a></td>

                                    <?php } /* end if count complete listings > 0 */ else { ?>

                                        <td colspan="2"><strong>Listing Agreement submitted but not processed</strong></td>

                                    <?php } ?>

                                </tr>
                            <!-- --------------------------- End If Submitted ------------------------- -->

                            <!-- --------------------------- If Missing ------------------------- -->
                            <?php } else if($listing_status == 'missing') { ?>

                            <tr>
                                <td valign="top" width="30">
                                    <img src="/new/images/icons/warning_yellow.png">
                                </td>
                                <td colspan="2"><strong>Listing Agreement not submitted</strong></td>
                            </tr>

                            <?php } ?>
                            <!-- --------------------------- End If Missing ------------------------- -->

                        </table>

                    <?php /* End if our listing */ } else { /* Begin not our listing */ ?>

                    	<div class="not_our_listing_div">Not Our Listing</div>

                    <?php } // End not our listing ?>

                </td>
                <td><!-- spacer --></td>
                <td class="sec_td" valign="top">
                    <div class="sub_sec_header"><?php echo $contract_type; ?></div>
                    	<table width="100%" class="listing_status" style="border-top: 1px dotted #ccc;">
                        <!-- --------------------------- If Processed ------------------------- -->
                        <?php if($contract_status == 'processed') { ?>

                            <tr>
                                <td valign="top" width="30">
                                    <img src="/new/images/icons/check_green_new.png" height="19">
                                </td>
                                <td colspan="2"><strong><?php echo $contract_type; ?> received and processed</strong></td>
                            </tr>
                            <?php // If missing contract docs
                            if($missingContractDocsCount > 0) { ?>
                                <tr>
                                    <td style="border-top: 1px dotted #ccc;" valign="top" width="30">
                                        <img src="/new/images/icons/warning_yellow.png">
                                    </td>
                                    <td style="border-top: 1px dotted #ccc;">
                                        <strong>Missing/Incomplete Documents</strong>
                                        <br>
                                        <table width="100%" class="incomplete_table">
                                        <?php for($cd=0;$cd<$missingContractDocsCount;$cd++) {
                                            if($cd != ($missingContractDocsCount - 1)) {
                                                $border = 'style="border-bottom: 1px dotted #ccc;"';
                                            } else {
                                                $border = '';
                                            } ?>
                                            <tr>
                                                <td width="25" <?php echo $border; ?>><img src="/new/images/icons/x_red.png" height="15"></td>
                                                <td <?php echo $border; ?>>
                                                    <?php echo $missingContractDocs[$cd]['doc_name']; ?>
                                                    <?php if($missingContractDocs[$cd]['doc_notes'] != '') { ?>
                                                        <div class="missing_notes"><em><?php echo $missingContractDocs[$cd]['doc_notes']; ?></em></div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </table>
                                    </td>
                                    <td valign="top" align="right" width="100"><a href="/new/agents/uploads/uploads.php?mls=<?php echo $listing_id; ?>" target="_blank" class="button button_normal" style="padding: 10px 5px;">Upload Docs</a></td>
                                </tr>
                            <?php
								} /* end If missing listing docs */ else { ?>
                                	<tr>
                                        <td valign="top" width="30" style="border-top: 1px dotted #ccc;">
                                            <img src="/new/images/icons/check_green_new.png" height="19">
                                        </td>
                                        <td colspan="2" style="border-top: 1px dotted #ccc;"><strong>No Missing/Incomplete Documents</strong></td>
                                    </tr>
                                <?php } ?>
                        <!-- --------------------------- End If Processed ------------------------- -->

                        <!-- --------------------------- If Submitted------------------------- -->
                        <?php } else if($contract_status == 'submitted') { ?>

                            <tr>
                                <td valign="top" width="30">
                                    <img src="/new/images/icons/warning_yellow.png">
                                </td>
                                <?php
                                if(count($incomplete_contracts) > 0) { ?>
                                    <td><strong><?php echo $contract_type; ?> submitted but Incomplete</strong>
                                    <?php
                                    for($il=0;$il<count($incomplete_contracts);$il++) {
                                        echo $incomplete_contracts[$il];
                                        if($il != (count($incomplete_contracts) - 1)) {
                                            echo '<br>';
                                        }
                                    }
                                    ?></td>
                                    <td valign="middle"><a href="/new/agents/uploads/uploads.php?mls=<?php echo $listing_id; ?>" target="_blank" class="button button_normal" style="padding: 5px;">View/Upload Docs</a></td>

                                <?php } /* end if count complete contracts > 0 */ else { ?>

                                    <td colspan="2"><strong><?php echo $contract_type; ?> submitted but not processed</strong></td>

                                <?php } ?>

                            </tr>
                        <!-- --------------------------- End If Submitted ------------------------- -->

                        <!-- --------------------------- If Missing ------------------------- -->
                        <?php } else if($contract_status == 'missing') { ?>

                        <tr>
                            <td valign="top" width="30">
                                <img src="/new/images/icons/warning_yellow.png">
                            </td>
                            <td colspan="2"><strong><?php echo $contract_type; ?> not submitted</strong></td>
                        </tr>

                        <?php } ?>
                        <!-- --------------------------- End If Missing ------------------------- -->

                    </table>

                </td>
            </tr>
        </table>
    </div>

    <div class="sec_div">
    	<div class="sec_header">Earnest Deposit</div>
        <table width="100%">
        	<tr>
            	<td class="sec_td" valign="top">
					<?php
                    if($earnest['count'] > 0) {
                        echo $earnest['data'];
                    } else {
                        echo '<div class="not_our_listing_div">Not Holding Earnest</div>';
                    }?>
                </td>
            </tr>
        </table>
    </div>

    <div class="sec_div">
    	<div class="sec_header">Commission / Checks</div>
        <table width="100%">
        	<tr>
            	<td class="sec_td" valign="top">
        			<div id="commission_div"></div>
                </td>
            </tr>
        </table>

    </div>
</div>
<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	get_commission();

});

function get_commission(){
	//$('#recip_table tr td, #recip_table tr th').not('.no_border').css({'border-right': '1px solid #ccc'});
	var mls = '<?php echo $ListingSourceRecordID; ?>';
	$.ajax({
		type: 'POST',
		url: '/new/agents/ajax/dashboard/get_commission.php',
		data: { mls: mls },
		success: function(response){
			$('#commission_div').html(response);

			$('#commission_details_table tr:eq(0) td:gt(0)').each(function(){

				if($(this).html() == ''){
					var eq = $(this).index();
					$(this).hide();
					$('#commission_details_table tr:gt(0)').each(function(){
						$(this).find('td:eq('+eq+')').hide();
					});
				}
			});
		}
	});
}
</script>
</body>
</html>
