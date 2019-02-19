<?php
include('/var/www/annearundelproperties.net/new/includes/global.php'); 

$queryAgents = "select * from company.tbl_agents".$test." where id = '".$_GET['id']."'";
//$queryAgents = "select * from company.tbl_agents where id = '".$_GET['id']."' and code = '".$_GET['code']."'";
$agent = $db -> select($queryAgents);
if($agent[0]['company'] == 'Anne Arundel Properties') {
	$company = 'Anne Arundel Properties';
	$siteAddress = 'https://annearundelproperties.com/'.$agent[0]['website_folder'];
	$loginSite = 'https://annearundelproperties.com';
} else {
	$company =  'Taylor Properties';
	$siteAddress = 'http://www.taylorprops.com/'.$agent[0]['website_folder'];
	$loginSite = 'http://www.taylorprops.com';
}
$first  = $agent[0]['nickname'];
$email = $agent[0]['email1'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Set up Information</title>
</head>
<body>
<div style="width:650px; height:auto; color: #2D7FA4;">
    <table width="650" cellpadding="0" cellspacing="0" style="font-family:Arial;font-size:15px; color:#003366">
        <tr>
            <td><table cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="100" align="left" style="background: #2D7FA4"><img src="http://www.taylorpropertiescdn.com/header_tp.png" alt="Taylor Properties" /></td>
                        <td width="500" style="font-size: 24px; font-weight: bold; font-family: Arial; text-align:center; color: #fff;background: #2D7FA4">Taylor/Anne Arundel<br />
                            Properties</td>
                        <td width="100" align="right" style="background: #2D7FA4"><img src="http://www.taylorpropertiescdn.com/header_aap.png" alt="Anne Arundel Properties" /></td>
                    </tr>
                </table></td>
        </tr>
        <tr>
            <td style="border:1px solid #2D7FA4; padding:10px;"> Welcome <?php echo $first; ?>,<br>
                This email is to inform you about our company website and your personal company website.<br />
                <br />
                <i>Please be sure to add your photo and bio to our company websites and your personal website (see below)</i> <br />
                <br />
                <span style="font-size:18px; font-weight:bold; text-decoration:underline;">Company Website</span> <br />
                <br />
                Our company website for employees can be accessed by going to <a href="https://annearundelproperties.net">www.AnneArundelProperties.net</a>.<br />
                <br />
                <strong>Login Information</strong><br>
                Username: <?php echo $email; ?><br>
                Password:  To retrieve you password go to the login page and click on the link that says "<strong>Forgot Password or Don't Have One? Click HERE</strong>"<br>
                (You can change it once logged on) <strong>.</strong> <br />
                <br />
                Features are:
                <ul>
                    <li>Billing
                        <ul>
                            <li>Pay your Monthly/Quarterly Fees</li>
                            <li>View your previous invoices and payments</li>
                            <li>Update your Credit Card information for automated payments</li>
                        </ul>
                    </li>
                    <blockquote style="border:1px solid #333333; padding:5px;"> Invoices will be sent on the 27th of each month (every 3rd month if   your are on quarterly billing) and balances will be applied to your account.<br>
                        If you are on automated payments your credit card will be billed on the   1st of each month.<br>
                        All payments are due by the 15th of each month.<br>
                        <br>
                        All accounts with balances remaining after the 15th will be billed a   $25 late fee.</blockquote>
                    <li>Update Your Information
                        <ul>
                            <li>Add your photo and bio for your profile on our public websites and your personal website provided by <?php echo $company; ?></li>
                        </ul>
                    </li>
                    <li>Setup your company email address</li>
                    <li>Upload your Buyer Contracts and Listing Agreements instead of emailing them or bringing them to the office (<i>Escrow checks still need to be submitted to the office for contracts</i>)</li>
                    <li>Download contract forms and intra-company forms</li>
                    <li>View important information sent to agents</li>
                    <li>Download Logos</li>
                </ul>
                <p><br>
                    <br>
                    <span style="font-size:18px; font-weight:bold; text-decoration:underline;">Your Personal Website</span> <br />
                    <br />
                    All Employees have their own website that can be accessed by going to <a href="<?php echo $siteAddress; ?>" target="_blank"><?php echo $siteAddress; ?></a><br>
                    This is your own personal website for generating leads.  Any leads captured by the website will be listed in your leads section on our company website. </p>
                <p><u><strong>You may also purchase a domain name to use for you site or use one you already own. For information on that, contact me any time.</strong></u></p>
                <p>To add your Agent Bio and Picture to your site, login to the company site and click on the link for "Website Info"</p>
                The site features are:
                </p>
                <ul>
                    <li>MLS search for MD, VA and DC</li>
                    <li>Ability to save properties and searches for future reference</li>
                    <li>Email updates of new properties for their saved searches</li>
                    <li>Registration required to save properties and searches.  The registration info gets emailed to you and is also stored in your leads</li>
                    <li>Showing Requests - Also emailed to you</li>
                </ul>
                Please contact me at any time with any questions. Thanks,<br>
                Mike Taylor<br>
                <?php echo $company; ?><br>
                410-224-3600 </td>
        </tr>
    </table>
</div>
</div>
</body>
</html>