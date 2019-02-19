<?php
/* _DONE */
include('/var/www/annearundelproperties.net/new/includes/global.php');

$test = 'no';

if($_SESSION['S_ID'] == '3193' || $_SESSION['S_ID'] == '1000035') {
    $test = 'no';
}

$rets = new \PHRETS\Session($rets_config);
$connect = $rets->Login();

$heritage = $_POST['heritage'];
$earnest_with = $_POST['earnest'];
$agent_id = $_POST['agent_id'];
$mls = $_POST['mls'];

if($test == 'yes') {
    $heritage = 'yes';
    $earnest_with = '';
    $agent_id = 3193;
    $mls = 'BC10063228';
}

$listingQuery = "select * from company.mls_company where ListingSourceRecordID = '".$mls."'";
$listing = $db -> select($listingQuery);


$agentQuery = "select * from company.tbl_agents where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

$agent_name = $agent[0]['fullname'];
if($listing[0]['FullStreetAddress'] == '') {
	$street = 'N/A';
} else {
	$street = $listing[0]['FullStreetAddress'];
}
$city = $listing[0]['City'];
$state = $listing[0]['StateOrProvince'];
$zip = $listing[0]['PostalCode'];
$settle_date = $listing[0]['CloseDate'];
$SalesPrice = $listing[0]['ListPrice'];

if($agent_id != '') {

	if($heritage == 'yes') {

        if($test == 'no') {

    		$subject = $agent_name.' just submitted a contract for clients using Heritage Title';
            $GLOB_email_top = str_replace('%%preheader%%', $subject, $GLOB_email_top);
    		$body = str_replace('%%subject%%', $subject, $GLOB_email_top).
    		$agent_name.' just submitted a contract for clients using Heritage Title.<br>
    		<br>
    		Property: '.$street.' '.$city.', '.$state.' '.$zip.'<br>
    		MLS: '.$mls.' <br>
    		<br>
    		Download the Sales Contract <a href="https://www.annearundelproperties.net/new/real_estate/contracts/contracts_title.php">Here</a>
    		'.$GLOB_email_bottom;

    		$ccs = sendTo(7);
    		sendMail('', 'internal@taylorprops.com', 'Do Not Reply <internal@taylorprops.com>', $subject, $body, $ccs, '', '');
    		// Add contract to mls_new_contracts not to be contacted by HT
    		new_contract($agent_id, $mls, '', 'yes', __FILE__);

        } /* end if($test == 'no') { */



        // New one
        $find1Query = "select * from company.title_deals_new where mls = '".$mls."' and mls is not null and mls != '' and title_status in ('Did Not Settle', 'In-Process')";
        $find1 = $db -> select($find1Query);

        $find2Query = "select * from company.title_deals_new_temp where mls = '".$mls."' and mls is not null and mls != ''";
        $find2 = $db -> select($find2Query);

		$list_office_mls_id = '';
		$list_office_street = '';
		$list_office_city = '';
		$list_office_state = '';
		$list_office_zip = '';
		$list_agent_id = '';
		$list_agent_mls_id = '';
		$list_agent_name = '';
		$list_agent_company = '';
		$list_agent_phone = '';
		$list_agent_email = '';
		$buyers_office_mls_id = '';
		$buyers_office_street = '';
		$buyers_office_city = '';
		$buyers_office_state = '';
		$buyers_office_zip = '';
		$buyers_agent_id = '';
		$buyers_agent_mls_id = '';
		$buyers_agent_name = '';
		$buyers_agent_company = '';
		$buyers_agent_phone = '';
		$buyers_agent_email = '';


        /* Get other agent info if not our listing */
        if(!stristr($listing[0]['ListOfficeMlsId'], 'tayl') && !stristr($listing[0]['ListOfficeMlsId'], 'aapi')) {

			if(!stristr($mls, '_OT')) {

	            $list_agent_mls_id = $listing[0]['ListAgentMlsId'];

	            $resource = 'ActiveAgent';
	            $class = 'ActiveMember';
	            $query = 'MemberMlsId='.$list_agent_mls_id;

	            $results = $rets->Search(
	            	$resource,
	            	$class,
	            	$query,
	            	[
	            		'Count' => 0,
	            		'Limit' => 1,
	            		'Select' => 'MemberFirstName, MemberLastName, OfficeName, MemberPreferredPhone, MemberEmail, OfficeMlsId'
	            	]
	            );


	            $results = $results->toArray();

	            foreach($results as $listing) {

	            	$list_agent_name = $db->quote($listing['MemberFirstName']).' '.$db->quote($listing['MemberLastName']);
	                $list_agent_phone = $db->quote($listing['MemberPreferredPhone']);
	                $list_agent_email = $db->quote($listing['MemberEmail']);
	                $list_office_mls_id = $db->quote($listing['OfficeMlsId']);
	            	$list_agent_company = $db->quote($listing['OfficeName']);

	            }

	            if($list_office_mls_id == '') {
	                echo 'error';
	                die();
	            }
	            $resource = 'Office';
	            $class = 'Office';
	            $query = 'OfficeMlsId='.$list_office_mls_id;
	            $results = $rets->Search(
	                $resource,
	                $class,
	                $query,
	                [
	                    'Count' => 0,
	                    'Limit' => 1
	                ]
	            );
	            $results = $results->toArray();

	            foreach($results as $listing) {

	                $list_office_street = $listing['OfficeAddress1'];
	                $list_office_city = $listing['OfficeCity'];
	                $list_office_state = $listing['OfficeStateOrProvince'];
	                $list_office_zip = $listing['OfficePostalCode'];

	            }
			}

            $buyers_office_mls_id = 'TAYL';
            $buyers_office_street = $agent[0]['street'];
            $buyers_office_city = $agent[0]['city'];
            $buyers_office_state = $agent[0]['state'];
            $buyers_office_zip = $agent[0]['zip'];
            $buyers_agent_id = $agent[0]['id'];
            $buyers_agent_mls_id = $listing[0]['BuyerAgentMlsId'];
            $buyers_agent_name = $agent[0]['fullname'];
            $buyers_agent_company = $agent[0]['lic1_company'];
            $buyers_agent_phone = $agent[0]['cell_phone'];
            $buyers_agent_email = $agent[0]['email1'];

            $insert = "insert into company.title_deals_new_temp (
                mls,
                street,
                city,
                state,
                zip,
                source,
                settle_date,
                sales_price,
                list_office_mls_id,
                list_office_street,
                list_office_city,
                list_office_state,
                list_office_zip,
                list_agent_id,
                list_agent_mls_id,
                list_agent_name,
                list_agent_company,
                list_agent_phone,
                list_agent_email,
                buyers_office_mls_id,
                buyers_office_street,
                buyers_office_city,
                buyers_office_state,
                buyers_office_zip,
                buyers_agent_id,
                buyers_agent_mls_id,
                buyers_agent_name,
                buyers_agent_company,
                buyers_agent_phone,
                buyers_agent_email
            ) values (
                '".$mls."',
                '".$street."',
                '".$city."',
                '".$state."',
                '".$zip."',
                'upload save_questions.php',
                '".$settle_date."',
                '".$SalesPrice."',
                '".$list_office_mls_id."',
                '".$list_office_street."',
                '".$list_office_city."',
                '".$list_office_state."',
                '".$list_office_zip."',
                '".$list_agent_id."',
                '".$list_agent_mls_id."',
                '".$list_agent_name."',
                '".$list_agent_company."',
                '".$list_agent_phone."',
                '".$list_agent_email."',
                '".$buyers_office_mls_id."',
                '".$buyers_office_street."',
                '".$buyers_office_city."',
                '".$buyers_office_state."',
                '".$buyers_office_zip."',
                '".$buyers_agent_id."',
                '".$buyers_agent_mls_id."',
                '".$buyers_agent_name."',
                '".$buyers_agent_company."',
                '".$buyers_agent_phone."',
                '".$buyers_agent_email."'
                )";



        } else {

            /* if our listing */

            $list_office_mls_id = 'TAYL';
            $list_office_street = $agent[0]['street'];
            $list_office_city = $agent[0]['city'];
            $list_office_state = $agent[0]['state'];
            $list_office_zip = $agent[0]['zip'];
            $list_agent_id = $agent[0]['id'];
            $list_agent_mls_id = $listing[0]['ListAgentMlsId'];
            $list_agent_name = $agent[0]['fullname'];
            $list_agent_company = $agent[0]['lic1_company'];
            $list_agent_phone = $agent[0]['cell_phone'];
            $list_agent_email = $agent[0]['email1'];

			if(!stristr($mls, '_OT')) {

	            $buyers_agent_mls_id = $listing[0]['BuyerAgentMlsId'];

	            if($buyers_agent_mls_id != '') {

	                $rets = new \PHRETS\Session($rets_config);
	                $connect = $rets->Login();

	                $resource = 'ActiveAgent';
	                $class = 'ActiveMember';
	                $query = 'MemberMlsId='.$buyers_agent_mls_id;

	                $results = $rets->Search(
	                	$resource,
	                	$class,
	                	$query,
	                	[
	                		'Count' => 0,
	                		'Limit' => 1,
	                		'Select' => 'MemberFirstName, MemberLastName, OfficeName, MemberPreferredPhone, MemberEmail, OfficeMlsId'
	                	]
	                );


	                $results = $results->toArray();

	                foreach($results as $listing) {

	                	$buyers_agent_name = $db->quote($listing['MemberFirstName']).' '.$db->quote($listing['MemberLastName']);
	                    $buyers_agent_phone = $db->quote($listing['MemberPreferredPhone']);
	                    $buyers_agent_email = $db->quote($listing['MemberEmail']);
	                    $buyers_office_mls_id = $db->quote($listing['OfficeMlsId']);
	                	$buyers_agent_company = $db->quote($listing['OfficeName']);

	                }

	                if($buyers_office_mls_id == '') {
	                    echo 'error';
	                    die();
	                }
	                $resource = 'Office';
	                $class = 'Office';
	                $query = 'OfficeMlsId='.$buyers_office_mls_id;
	                $results = $rets->Search(
	                    $resource,
	                    $class,
	                    $query,
	                    [
	                        'Count' => 0,
	                        'Limit' => 1
	                    ]
	                );
	                $results = $results->toArray();

	                foreach($results as $listing) {

	                    $buyers_office_street = $listing['OfficeAddress1'];
	                    $buyers_office_city = $listing['OfficeCity'];
	                    $buyers_office_state = $listing['OfficeStateOrProvince'];
	                    $buyers_office_zip = $listing['OfficePostalCode'];

	                }

	            }

			}

            $insert = "insert into company.title_deals_new_temp (
                mls,
                street,
                city,
                state,
                zip,
                source,
                settle_date,
                sales_price,
                list_office_mls_id,
                list_office_street,
                list_office_city,
                list_office_state,
                list_office_zip,
                list_agent_id,
                list_agent_mls_id,
                list_agent_name,
                list_agent_company,
                list_agent_phone,
                list_agent_email";
            if($buyers_agent_mls_id != '') {
                $insert .= ",
                buyers_office_mls_id,
                buyers_office_street,
                buyers_office_city,
                buyers_office_state,
                buyers_office_zip,
                buyers_agent_id,
                buyers_agent_mls_id,
                buyers_agent_name,
                buyers_agent_company,
                buyers_agent_phone,
                buyers_agent_email";
            }
            $insert .= "
            ) values (
                '".$mls."',
                '".$street."',
                '".$city."',
                '".$state."',
                '".$zip."',
                'upload save_questions.php',
                '".$settle_date."',
                '".$SalesPrice."',
                '".$list_office_mls_id."',
                '".$list_office_street."',
                '".$list_office_city."',
                '".$list_office_state."',
                '".$list_office_zip."',
                '".$list_agent_id."',
                '".$list_agent_mls_id."',
                '".$list_agent_name."',
                '".$list_agent_company."',
                '".$list_agent_phone."',
                '".$list_agent_email."'";

            if($buyers_agent_mls_id != '') {
                $insert .= ",
                '".$buyers_office_mls_id."',
                '".$buyers_office_street."',
                '".$buyers_office_city."',
                '".$buyers_office_state."',
                '".$buyers_office_zip."',
                '".$buyers_agent_id."',
                '".$buyers_agent_mls_id."',
                '".$buyers_agent_name."',
                '".$buyers_agent_company."',
                '".$buyers_agent_phone."',
                '".$buyers_agent_email."'";
            }
            $insert .= ")";

        }

        if(count($find1) == 0 && count($find2) == 0) {


            if($test == 'no') {
			    $queryResults = $db -> query($insert);
            } else {
                echo $insert;
            }

		} else {
			if($test == 'yes') {
				echo 'mls was found, not added';
			}
		}


	} else { /* end if($heritage == 'yes') { */
        if($test == 'no') {
		    new_contract($agent_id, $mls, '', '', __FILE__);
        }

	}

    if($test == 'no') {
    	$save = "insert into company.mls_uploads_questions (earnest, mls, heritage, agent_id, agent_name, address) values ('".$earnest_with."', '".$mls."', '".$heritage."', '".$agent_id."', '".$db->quote($agent_name)."', '".$db->quote($street.'<br>'.$city.', '.$state.' '.$zip)."')";
    	$queryResults = $db -> query($save);

    	if($earnest_with == 'us') {

    		$earnestQuery = "SELECT * FROM company.escrow where mls = '".$mls."' or transfer_mls = '".$mls."' or transfer2_mls = '".$mls."'";
    		$earnest = $db -> select($earnestQuery);
    		$earnestCount = count($earnest);

    		if($earnestCount == 0) {
    			$update = "update company.mls_company set Earnest = 'yes' where ListingSourceRecordID = '".$mls."'";
    			$queryResults = $db -> query($update);
    			$update2 = "update company.mls_uploads set earnest = 'yes' where upload_mls = '".$mls."'";
    			$queryResults = $db -> query($update2);
    		}

    	}


    	$update2 = "update company.mls_uploads set questions_answered = 'yes' where upload_mls = '".$mls."'";
    	$queryResults = $db -> query($update2);

    	$update3 = "update company.mls_company set HeritageTitle = '".$heritage."' where ListingSourceRecordID = '".$mls."'";
    	$queryResults = $db -> query($update3);
    }
}

$rets->Disconnect();

?>
