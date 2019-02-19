<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

$emailAccountQuery = "SELECT * FROM  company.email_accounts WHERE ac_user_id = '".$_SESSION['S_ID']."' order by ac_comp";
$emailAccount = $db -> select($emailAccountQuery);
$emailAccountCount = count($emailAccount);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Company Email Account Instructions</title>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
.dark {
	font-size: 16px !important
}
.email_instructions_div { border: 1px solid #3A4A63; margin: 10px 0; padding:10px; }
.email_instructions_section_header {
	width: 680px;
	padding: 10px;
	background: #3A4A63;
	color: #fff;
	font-weight: bold;
	font-size: 18px;
}
.email_instructions_header {
	width: 660px;
	padding: 5px;
	background: #3A4A63;
	color: #F3F360;
	font-weight: bold;
	font-size: 16px;
}
.settings_div {
	margin: 10px 0;
}
.settings_header {
	font-size: 17px;
	font-weight: bold;
	color: #3A4A63;
	margin: 10px 0;
	text-decoration: underline;
}
.setup_name {
	font-size: 22px;
	font-weight: bold;
	color: #3A4A63;
}
.step_div {
	font-weight: bold;
	font-size: 25px;
	padding: 10px;
	background: #333;
	color: #fff;
	margin-bottom: 15px
}
.step_table td {
	font-size: 18px;
	line-height: 120%;
}
#email_tabs img:not(.no_show) { border: 5px solid #3A4A63 }
#preview_div { position: fixed; top: 10px; left: 10px; display: none; z-index:60000; border: 10px solid #3A4A63 }
</style>
</head>
<body>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>
<div class="body_container">
    <h2>Company Email Account Instructions</h2>
    <div style="font-size: 15px; width: 700px; margin: 15px auto;"><span style="color: #A84549;">** You can always return to these instructions by clicking the "Company Email" tab above. **</span><br><br> You can access your email via webmail at <a class="dark" href="http://webmail.taylorprops.com" target="_blank">webmail.taylorprops.com</a> or through an email client such as Outlook or even using Gmail or Yahoo mail.<br>
        <br>
        To set up account in Outlook or a web based email provider like Gmail, Yahoo, Hotmail, etc., here is the server and login information.<br>
        <br>
        Instructions on setting up through your Smart Phone or Outlook are below or just click <a href="javascript: void(0)" id="setup_anchor_link" class="dark">Here</a>.<br>
        <br>
        <div class="email_instructions_section_header">Account Information </div>
        <?php 
		for($i=0;$i<$emailAccountCount;$i++) {
			echo '<div class="email_instructions_div">'; ?>
        <div class="email_instructions_header"><?php echo $emailAccount[$i]['ac_email']; ?></div>
        <div class="settings_div">
            <div class="settings_header">Server Settings</div>
            <table>
                <tr>
                    <td align="right">Username</td>
                    <td style="padding-left:20px;"><strong><?php echo $emailAccount[$i]['ac_email']; ?></strong></td>
                </tr>
                <tr>
                    <td align="right">Password</td>
                    <td style="padding-left:20px;">
                    <span class="password_span_hidden">
                    	<strong><?php echo preg_replace('/./', '*', $emailAccount[$i]['ac_pass']); ?></strong>
                    </span>
                    <span class="password_span_visible" style="display:none;">
                    	<strong><?php echo $emailAccount[$i]['ac_pass']; ?></strong>
                    </span> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="javascript: void(0);" class="view_password button button_normal" style="padding: 2px; font-size:12px;">Show Password</a></td>
                </tr>
                <tr>
                    <td align="right">Incoming Server (POP/IMAP)</td>
                    <td style="padding-left:20px;"><strong>mail.tpmailserver.com</strong></td>
                </tr>
                <tr>
                    <td align="right">Outgoing Server (SMTP)</td>
                    <td style="padding-left:20px;"><strong>mail.tpmailserver.com</strong></td>
                </tr>
            </table>
        </div>
        <?php 
			echo '</div>';
		}?>
        <br><br>
        <div class="email_instructions_section_header" id="setup_anchor">Setup Instructions</div>
        <br>
        Select the device or program from the options below<br><br>
        <div id="email_tabs">
            <ul>
                <li><a href="#droid_tab">Droid Phone</a></li>
                <li><a href="#outlook2013_tab">Outlook 2013 / Office 365</a></li>
                <li><a href="#outlook2007_tab">Outlook 2007</a></li>
            </ul>
            <div id="droid_tab">
                <table>
                    <tr>
                        <td><img src="/new/images/email_accounts/droid/droid.png" class="no_show" height="40"></td>
                        <td style="padding-left: 25px;" class="setup_name">Droid Smart Phone</td>
                    </tr>
                </table>
                <hr>
                <table width="100%" class="step_table">
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 1</div>
                            Open up your "Settings". <br>
                            Scroll down to the "Accounts" section. <br>
                            Click on "Add Account". </td>
                        <td align="right"><img src="/new/images/email_accounts/droid/2.jpg" height="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 2</div>
                            select "Email" as the account type. </td>
                        <td align="right"><img src="/new/images/email_accounts/droid/1.jpg" height="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 3</div>
                            Enter your Email Address and Password.<br>
                            <br>
                            If the is the main email account you will be using on your phone, check the box that says "Send email from this account by default". <br>
                            <br>
                            <span style="color: #C65155; font-weight:bold;">DO NOT CLICK "NEXT".</span><br>
                            <br>
                            Click the "Manual setup" button. </td>
                        <td align="right"><img src="/new/images/email_accounts/droid/5.jpg" height="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 4</div>
                            Select "IMAP account".</td>
                        <td align="right"><img src="/new/images/email_accounts/droid/6.jpg" height="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 5</div>
                            Change your "Username" to your full email address.<br><br>
                            Change the "IMAP Server" to "mail.tpmailserver.com"<br><br>
                            Leave everything else as is and click "Next".</td>
                        <td align="right"><img src="/new/images/email_accounts/droid/7.jpg" height="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 6</div>
                            If you get this error message either your email or password is typed incorrectly.<br><br>
                            Otherwise, continue to step 7.</td>
                        <td align="right"><img src="/new/images/email_accounts/droid/8.jpg" height="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 7</div>
                            Change the "SMTP Server" to "mail.tpmailserver.com" and be sure your "Username" and "Password" are filled in.<br><br>
                            Click "Next".</td>
                        <td align="right"><img src="/new/images/email_accounts/droid/10.jpg" height="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="400"><div class="step_div">Step 8</div>
                            You are all set up now.  Continue clicking "Next" and selecting the options your desire.</td>
                        <td align="right"><img src="/new/images/email_accounts/droid/11.jpg" height="300"></td>
                    </tr>
                </table>
            </div>
            
        	<div id="outlook2013_tab">
            	<table>
                    <tr>
                        <td><img src="/new/images/email_accounts/outlook/outlook.png" class="no_show"height="40"></td>
                        <td style="padding-left: 25px;" class="setup_name">Outlook 2013 and Office 365</td>
                    </tr>
                </table>
                <hr>
                <table width="100%" class="step_table">
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 1</div>
                            Click on "File" in the top left of Outlook to go to Account Information.
                            <br>
                            <br>
                            Click on "Account Settings". </td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/1.png" h="500" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 2</div>
                            Click "New". </td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/2.png" h="450" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 3</div>
                            At the bottom select "Manual setup or additional server types" and click "Next". </td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/3.png" h="450" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 4</div>
                            Select "POP or IMAP" and click "Next". </td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/4.png" h="450" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 5</div>
                            Enter your name and email address.<br>
                            Select POP3 or IMAP<br>
                            For both the "Incoming" and "Outgoing" servers enter "mail.tpmailserver.com".<br>
                            Enter your full email address as your "Username" and enter your "Password".
                            <br>
                            Now, select the button in the bottom right that says "More Settings".</td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/5.png" h="450" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 6</div>
                            Click on the second tab at the top that says "Outgoing Server"<br><br>
                            Now check the box that says "Use same settings as my incoming mail server".
                            <br><br>
                            Click "OK".</td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/6.png" h="450" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 7</div>
                            Click "Next". </td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/5.png" h="450" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 8</div>
                            Outlook will now test the settings.  <br><br>
                            Click "Close". </td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/7.png" h="250" width="300"></td>
                    </tr>
                    <tr>
                        <td valign="top" width="330"><div class="step_div">Step 9</div>
                            Your're done! </td>
                        <td align="right"><img src="/new/images/email_accounts/outlook/8.png" h="450" width="300"></td>
                    </tr>
                </table>
            </div>
            
            <div id="outlook2007_tab"> <strong>Outlook Instructions (Outlook 2007 - other versions should be similar) - To set up a new account in Outlook, follow these instructions:</strong><br>
                <br />
                * Open Outlook, select "Tools" from the top menu and then "Account Settings".<br>
                * Select "New".<br>
                * The first option should be checked for "Microsoft Exchange, POP3, IMAP or HTTP", hit "Next"<br>
                * At the bottom look for a checkbox that says "Manually configure server settings or additional server types.  Check that box and hit "Next".<br>
                * The option "Internet Email" should already be selected, hit "Next".<br>
                * Enter the following information<br>
                <div style="margin: 10px 0 10px 15px;"> Your Name: <?php echo $emailAccount[0]['ac_user']; ?><br>
                    Email Address:  Enter Your Email Address<br>
                    Account Type:  POP3 or IMAP<br>
                    Incoming mail server:  mail.tpmailserver.com<br>
                    Outgoing mail server (SMTP):  mail.tpmailserver.com<br>
                    Username: Enter Your Email Address<br>
                    Password:  Enter Your Email Password<br>
                </div>
                * Now click the button on the lower right side that says "More Settings".<br>
                * A new window will popup.  Select the second tab "Outgoing Server".<br>
                * Check the box that says "My outgoing server (SMTP) requires authentication" and then hit OK.<br>
                * Hit the "Test Account Settings" button to verify everything is correct<br>
                <br>
            </div>
        </div>
        Please call me with any questions or if you have any problems. <br />
        Mike Taylor 301-970-2447 </div>
</div>
<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>
<div id="preview_div" class="shadow"></div>
<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	$('#email_tabs').tabs();
	
	$('#setup_anchor_link').click(function(){
		var goto = $('#setup_anchor').offset().top;
		$('html, body').animate({scrollTop: goto - 130}, 'slow');
	});
	
	$('#email_tabs').find('img').not('.no_show').mouseover(function(){
		if($(this).prop('src').match(/droid/)) {
			var h = 600;
		} else {
			var h = $(this).attr('h');
		}
		$('#preview_div').fadeIn('fast').html('<img src="'+$(this).prop('src')+'" height="'+h+'">');
	});
	$('#email_tabs').find('img').mouseout(function(){
		$('#preview_div').fadeOut('fast');
	});
	
	$('.view_password').click(function(){
		$(this).parent('td').children('.password_span_visible, .password_span_hidden').toggle();
		if($(this).text() == 'Show Password') {
			$(this).text('Hide Password');
		} else {
			$(this).text('Show Password');
		}
	});
});

</script>
</body>
</html>