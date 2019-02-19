<?php
/* _AGENT _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$agent_id = $_POST['agent_id'];
$state = $_POST['state'];
$mris_id = $_POST['mris_id'];

$agentQuery = "select * from company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$agent_lic1_comp = $agent[0]['lic1_company'];
$agent_first = $agent[0]['nickname'];
$agent_last = $agent[0]['last'];

$rets = new \PHRETS\Session($rets_config);
$connect = $rets->Login();
$resource = 'ActiveAgent';
$class = 'ActiveMember';
$query = '(MemberMlsId='.$mris_id.')';

$results = $rets->Search(
    $resource,
    $class,
    $query,
    [
        'Count' => 0,
		'Select' => 'MemberLastName,MemberFirstName,OfficeName,OfficeMlsId'
    ]
);

$results = $results->toArray();

foreach($results as $listing) {



	$MemberLastName = $listing['MemberLastName'];
	$MemberFirstName = $listing['MemberFirstName'];
	$OfficeName = $listing['OfficeName'];
	$OfficeMlsId = $listing['OfficeMlsId'];




}

$rets->Disconnect();

if(count($results) == 0) {
    $result = 'error';
} else {
    $result = 'success';
}

// test to see if returned info is correct and if so update
$cont = 'no';



$agent_companies = [
    $agent[0]['lic1_company'],
    $agent[0]['lic2_company'],
    $agent[0]['lic3_company'],
    $agent[0]['lic4_company']
];



if(($state == 'MD' || $state == 'DC') && in_array('Taylor Properties', $agent_companies)) {
    $code = 'TAYL1 - TAYL12';
    if(stristr($OfficeMlsId, 'TAYL') && $OfficeMlsId != 'TAYL13') {
        $cont = 'yes';
    }
    $mris_col = 'mris_id_tp_md';
} else if($state == 'MD' && in_array('Anne Arundel Properties', $agent_companies)) {
    $code = 'AAPI1';
    if(stristr($OfficeMlsId, 'AAPI')) {
        $cont = 'yes';
    }
    $mris_col = 'mris_id_aap';
} else if($state == 'VA') {
    $code = 'TAYL13';
    if($OfficeMlsId == 'TAYL13') {
        $cont = 'yes';
    }
    $mris_col = 'mris_id_tp_va';
} else {
    sendMail('', 'mike@taylorprops.com', 'internal@taylorprops.com', 'error on upload page', 'state = '.$state.' and lic comp = '.$agent_lic1_comp, '', '', '');
}

if($result == 'success' && $cont == 'yes') {

    $update = "update company.tbl_agents set ".$mris_col." = '".$mris_id."' where id = '".$agent_id."'";
    $queryResults = $db -> query($update);


} else {

    if($result == 'success' && $cont == 'no') {

        $result = 'error';

        $message = 'We were able to find the account associated with the MRIS ID you entered however it is for the wrong MRIS account.<br><br>
        We found this account:
        <div style="margin: 5px 0 5px 15px; font-weight: bold;">'.$MemberFirstName.' '.$MemberLastName.' | '.$OfficeMlsId.' | '.$OfficeName.'</div>
        However the required account is:
        <div style="margin: 5px 0 5px 15px; font-weight: bold;">'.$agent_first.' '.$agent_last.' | '.$code.' | '.$agent_lic1_comp.'</div>';

    } else if($result == 'error') {

        $message = 'That MRIS ID was not found, please contact the office if you feel this is in error.';

    }
}
?>
<span id="result"><?php echo $result; ?></span>
<div id="message"><?php echo $message; ?></div>
