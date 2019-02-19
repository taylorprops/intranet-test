<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_SESSION['S_ID'];
//$agent_id = 3193;

$listingQuery = "select ListPictureURL, PropertyType, Closed, ListingSourceRecordID, MlsStatus, FullStreetAddress, City, StateOrProvince, PostalCode from company.mls_company where ListAgentCompID = '".$agent_id."' or SaleAgentCompID = '".$agent_id."' order by Closed, DateAdded DESC";
$listing = $db -> select($listingQuery);
$listingCount = count($listing);

?>

<table width="100%" class="upload_table" cellpadding="0" cellspacing="0">
    <tbody>
        <?php for($l=0;$l<$listingCount;$l++) {
			$Closed = $listing[$l]['Closed'];
            $ListingSourceRecordID = $listing[$l]['ListingSourceRecordID'];
            $FullStreetAddress = $listing[$l]['FullStreetAddress'];
            $City = $listing[$l]['City'];
            $StateOrProvince = $listing[$l]['StateOrProvince'];
            $PostalCode = $listing[$l]['PostalCode'];
            $ListPictureURL = $listing[$l]['ListPictureURL'];
            $PropertyType = $listing[$l]['PropertyType'];
            $MlsStatus = $listing[$l]['MlsStatus'];

             ?>
        <tr style="background: <?php if($Closed == 'no') { echo '#6AA073'; } else { echo '#C65155'; } ?>" data-info="<?php echo $ListingSourceRecordID.' '.$FullStreetAddress.' '.$City.' '.$StateOrProvince.' '.$PostalCode; ?>">
            <td width="60"><a href="upload_b.php?ListingSourceRecordID=<?php echo $ListingSourceRecordID; ?>" class="button button_normal button_existing" style="padding: 20px; font-size: 22px;">Select</a></td>
            <td align="center"><?php if($ListPictureURL != '' && $ListPictureURL != 'N/A') { ?>
            <img src="<?php echo $ListPictureURL; ?>" style="max-height: 75px; max-width: 100px;">
            <?php } else { ?>
            <div style="width: 75px; height: 75px; border: 1px solid #fff; text-align: center; line-height: 75px; color: #fff;">No Photo</div>
            <?php } ?></td>
            <td width="100"><span style="font-size: 17px !important; font-weight:bold;"><?php echo $ListingSourceRecordID; ?></span>
            <br>
            <strong><?php echo $PropertyType; ?></strong>
            </td>
            <td ><?php echo $FullStreetAddress.'<br>'.$City.' '.$StateOrProvince.' '.$PostalCode; ?></td>
            <td><strong><?php echo $MlsStatus; ?></strong>
            <br>
            <?php if($Closed == 'yes') { ?>
            Closed Out
            <?php } else { ?>
            Active
            <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
