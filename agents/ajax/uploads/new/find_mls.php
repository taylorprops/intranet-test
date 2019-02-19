<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$mls = $_POST['mls'];


$rets = new \PHRETS\Session($rets_config);
$rets->setLogger($log);

$connect = $rets->Login();

$resource = 'Property';
$class = 'ALL';
$query = '(ListingSourceRecordID='.$mls.')';

$results = $rets->Search(
    $resource,
    $class,
    $query,
    [
        'Count' => 0,
		'Limit' => '1',
		'Select' => 'PropertyType, ListingSourceRecordID, FullStreetAddress, City, StateOrProvince, PostalCode, MlsStatus, ListAgentFirstName, ListAgentLastName, ListOfficeName, ListPictureURL, ListPrice'
    ]
);


$results = $results->toArray();

foreach($results as $listing) {

    //echo '<pre>'; print_r($listing); echo '</pre>';

	$PropertyType = $listing['PropertyType'];
	$ListingSourceRecordID = $listing['ListingSourceRecordID'];
	$FullStreetAddress = $listing['FullStreetAddress'];
	$City = $listing['City'];
	$StateOrProvince = $listing['StateOrProvince'];
	$PostalCode = $listing['PostalCode'];
	$MlsStatus = $listing['MlsStatus'];
	$ListAgentFirstName = $listing['ListAgentFirstName'];
	$ListAgentLastName = $listing['ListAgentLastName'];
	$ListOfficeName = $listing['ListOfficeName'];
	$ListPictureURL = $listing['ListPictureURL'];
	if($ListPictureURL != '') {
		$pic = '<img src="'.$ListPictureURL.'" style="max-width: 170px;">';
	} else {
		$pic = '<div style="width: 170px; height: 100px; border: 1px solid #ccc; text-align: center; line-height: 100px; color: #ccc;">No Photo</div>';
	}
	$ListPrice = $listing['ListPrice'];

	$mls_info = '
	<div class="results_header">Results...</div>
	<div class="mls_results">
	<table id="results_table" width="100%">
		<tr>
			<td valign="middle">
				<a href="upload_b.php?ListingSourceRecordID='.$ListingSourceRecordID.'" class="button button_normal" style="padding: 15px; width: 140px; text-align: center">Continue &rarr;</a>
			</td>
			<td valign="top">
				<span style="font-weight: bold; font-size: 30px !important;">'.$ListingSourceRecordID.'</span>
				<br>
				<span style="font-weight: bold; font-size: 30px !important;">'.$PropertyType.'</span>
			</td>
			<td valign="top" style="font-size: 22px !important;">'.$FullStreetAddress.'<br>'.$City.', '.$StateOrProvince.' '.$PostalCode.'</td>
		</tr>
		<tr>
			<td valign="top">'.$pic.'</td>
			<td valign="top"><span class="results_status">'.$MlsStatus.'</span>
				<br>
				<span style="font-size: 30px !important; font-weight: bold;color: #D18359;">$'.number_format($ListPrice, 0, '.', ',').'</span> </td>
			<td style="font-size: 22px !important;" valign="top">
			Listed By<br>'.$ListAgentFirstName.' '.$ListAgentLastName.'<br>'.$ListOfficeName.'</td>
		</tr>
	</table>
	</div>';

}

$rets->Disconnect();

if($FullStreetAddress == '') {
	die();
}


echo $mls_info;

?>
