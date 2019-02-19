<?php 
include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');

$mls = $_POST['mls'];
$agent_id = $_POST['agent_id'];

// Listing Info
$listingQuery = "select * from company.mls_company where ListingID = '".$mls."'";
$listing = $db -> select($listingQuery);

$for_sale = $listing[0]['ForSale'];
if($for_sale == 'Y') {
	$contract_type = 'Sales Contract';
} else {
	$contract_type = 'Lease Agreement';
}
if(stristr($listing[0]['ListOfficeCode'], 'tayl') || stristr($listing[0]['ListOfficeCode'], 'aapi')) {
	$our_listing = 'yes';
} else {
	$our_listing = 'no';
}

// Missing Docs

$missingQuery = "select * from company.contract_docs_missing where mls = '".$mls."' and agent_id = '".$agent_id."'";
$missing = $db -> select($missingQuery);
$missingCount = count($missing);

$missing_listing_docs = array();
$missing_contract_docs = array();
$released = 'yes';

for($m=0;$m<$missingCount;$m++) {
	
	if($missing[$m]['doc_type'] == 'listing') { 
		$missing_listing_docs[$m]['docs'] = $missing[$m]['doc_name'];
		$missing_listing_docs[$m]['notes'] = $missing[$m]['doc_notes'];
	} else if($missing[$m]['doc_type'] == 'contract') { 
		$missing_contract_docs[$m]['docs'] = $missing[$m]['doc_name'];
		$missing_contract_docs[$m]['notes'] = $missing[$m]['doc_notes'];
		if($missing[$m]['released'] == 'no') { 
			$released = 'no';
		}
	}
	
}
?>

<div id="listing">
<?php if($our_listing == 'yes') {
    if($listing[0]['HaveListing'] == 'yes') { ?>
        
        <table>
            <tr>
                <td><img src="/new/images/icons/check_green_new.png" height="25"></td>
                <td>Listing Agreement Received</td>
            </tr>
        </table>
        
        <?php 
        if(count($missing_listing_docs[0]) == 0) { ?>
        
        <table>
            <tr>
                <td><img src="/new/images/icons/check_green_new.png" height="25"></td>
                <td>All Documents Received</td>
            </tr>
        </table>
        
        <?php 
        } else { ?>
        
        <table>
            <tr>
                <td><img src="/new/images/icons/warning_red_new.png" height="25"></td>
                <td>Missing Documents</td>
            </tr>
        </table>
        
        <?php 
        } ?>
    <?php 
    } else { ?>
        
        <table>
            <tr>
                <td><img src="/new/images/icons/warning_red_new.png" height="25"></td>
                <td>Listing Agreement Not Submitted</td>
            </tr>
        </table>
  
    <?php 
    }
} ?>
</div>
<div id="contract">
<?php 
if($listing[0]['HaveContract'] == 'yes' && $released == 'no') { 
?>

    <table>
        <tr>
            <td><img src="/new/images/icons/check_green_new.png" height="25"></td>
            <td>Submitted</td>
        </tr>
    </table>
    
    <?php 
    if(count($missing_contract_docs[0]) == 0) { ?>
        
        <table>
            <tr>
                <td><img src="/new/images/icons/check_green_new.png" height="25"></td>
                <td>All Docs Received</td>
            </tr>
        </table>
      
    <?php 
    } else { 
	?>
        
        <table>
            <tr>
                <td><img src="/new/images/icons/warning_red_new.png" height="25"></td>
                <td>Missing Docs</td>
            </tr>
        </table>
        
    <?php 
    }
} else { 
?>
    
    <table>
        <tr>
            <td><img src="/new/images/icons/warning_red_new.png" height="25"></td>
            <td>Not Submitted</td>
        </tr>
    </table>
   
<?php 
} ?>
</div>