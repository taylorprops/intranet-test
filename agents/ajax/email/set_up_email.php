<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include('/var/www/annearundelproperties.net/new/includes/zimbra.php');

if(isset($_POST['aap_email']) && $_POST['aap_email'] != '') {
	$aap_email = trim($_POST['aap_email']).'@annearundelproperties.com';
	if (!filter_var($aap_email, FILTER_VALIDATE_EMAIL)) {
		echo '<span id="aap_invalid">yes</span>';
		die();
	}
} else {
	$aap_email = '';
}
if(isset($_POST['tp_email']) && $_POST['tp_email'] != '') {
	$tp_email = trim($_POST['tp_email']).'@taylorprops.com';
	if (!filter_var($tp_email, FILTER_VALIDATE_EMAIL)) {
		echo '<span id="tp_invalid">yes</span>';
		die();
	}
} else {
	$tp_email = '';
}

if($tp_email != '') {
	$EmailsTPQuery = "select * from company.email_accounts where ac_email = '".$tp_email."' or ac_alias like '%".$tp_email."%'";
	$EmailsTP = $db -> select($EmailsTPQuery);
	$TPCount = count($EmailsTP);
} else {
	$TPCount = 0;
}

if($aap_email != '') {
	$EmailsAAPQuery = "select * from company.email_accounts where ac_email = '".$aap_email."' or ac_alias like '%".$aap_email."%'";
	$EmailsAAP = $db -> select($EmailsAAPQuery);
	$AAPCount = count($EmailsAAP);
} else {
	$AAPCount = 0;
}

if($TPCount > 0) { 
	$tp_avail = 'no'; 
}
if($AAPCount > 0) { 
	$aap_avail = 'no'; 
}
 

if($TPCount > 0 || $AAPCount > 0) {
	
	echo '<span id="tp_avail">'.$tp_avail.'</span>
	<span id="aap_avail">'.$aap_avail.'</span>';
} else {
	$NewUserPassword = $_POST['pass'];
	$user_id = $_SESSION['S_ID'];
	$NewUserName = $db->quote($_SESSION['S_Username']);
	
	
	if($aap_email != '') { 

		ZimbraAdminCreateAccount($Trace, $ServerAddress, $AdminUserName, $AdminPassword, $aap_email, $NewUserPassword, $NewUserName, $COSId);
		if($_POST['aap_forward'] != '') {
			$aap_forward = $_POST['aap_forward'];
			ZimbraAdminAddForward($Trace, $ServerAddress, $AdminUserName, $AdminPassword, $aap_email, $aap_forward, $COSId);	
		} else {
			$aap_forward = '';
		}
		$insertQuery = "INSERT INTO company.email_accounts (ac_email, ac_user, ac_comp, ac_user_id, ac_pass, ac_forward, is_alias, main_account) VALUES ('".trim($db -> quote($aap_email))."', '".trim($db -> quote($NewUserName))."', 'annearundelproperties.com', '".$user_id."', '".trim($db -> quote($NewUserPassword))."', '".trim($db -> quote($aap_forward))."', 'no', '".trim($db -> quote($aap_email))."')"; 
		$queryResults = $db -> query($insertQuery);
	}
	if($tp_email != '') { 
	
		ZimbraAdminCreateAccount($Trace, $ServerAddress, $AdminUserName, $AdminPassword, $tp_email, $NewUserPassword, $NewUserName, $COSId);
		
		if($_POST['tp_forward'] != '') {
			$tp_forward = $_POST['tp_forward'];
			ZimbraAdminAddForward($Trace, $ServerAddress, $AdminUserName, $AdminPassword, $tp_email, $tp_forward, $COSId);	
		} else {
			$tp_forward = '';
		}
		$insertQuery = "INSERT INTO company.email_accounts (ac_email, ac_user, ac_comp, ac_user_id, ac_pass, ac_forward, is_alias, main_account) VALUES ('".trim($db -> quote($tp_email))."', '".trim($db -> quote($NewUserName))."', 'taylorprops.com', '".$user_id."', '".trim($db -> quote($NewUserPassword))."', '".trim($db -> quote($tp_forward))."', 'no', '".trim($db -> quote($tp_email))."')"; 
		$queryResults = $db -> query($insertQuery);
	}
}

?>