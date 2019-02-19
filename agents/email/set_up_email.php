<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

$emailAccountQuery = "SELECT * FROM  company.email_accounts WHERE ac_user_id = '".$_SESSION['S_ID']."'";
$emailAccount = $db -> select($emailAccountQuery);
$emailAccountCount = count($emailAccount);

if($emailAccountCount > 0) {
	header("Location: email_instructions.php");	
}

$agentQuery = "SELECT * FROM  company.tbl_agents WHERE id = '".$_SESSION['S_ID']."'";
$agent = $db -> select($agentQuery);


if(stristr($agent[0]['lic1_company'], 'taylor') || stristr($agent[0]['lic2_company'], 'taylor') || stristr($agent[0]['lic3_company'], 'taylor') || stristr($agent[0]['lic4_company'], 'taylor')) {
	$tayl = 'yes';
}
if(stristr($agent[0]['lic1_company'], 'anne') || stristr($agent[0]['lic2_company'], 'anne') || stristr($agent[0]['lic3_company'], 'anne') || stristr($agent[0]['lic4_company'], 'anne')) {
	$anne = 'yes';
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Company Email Account</title>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
#email_container {
	width: 730px;
	margin: 0 auto;
}
#email_header {
	width: 100%;
	height: auto;
	padding: 15px;
	text-align: center;
	font-size: 16px;
	font-weight: bold;
	border: 1px solid #3A4A63;
	color: #3A4A63;
}

#email_table { width: 640px; margin: 25px auto }
#email_table td { padding: 5px; font-size:15px; }
.header_tr { background: #3A4A63; color: #fff; font-size:18px; }


#error_div {
	display: none;
	position: fixed;
	left: 50%;
	top: 220px;
	margin-left: -280px;
	width: 500px;
	height: auto;
	overflow: auto;
	background: #fff;
	border: 10px solid #C65155;
	z-index: 30000;
	padding: 20px;
	padding-top: 5px;
	font-size: 16px;
}
.error_input { border: 1px solid #C65155 !important; }
</style>
</head>
<body>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>
<div class="body_container">
    <h2>Company Email Account</h2>
    <div id="email_container">
        <div id="email_header" class="round3">To create your new email, submit the form below.  Once complete you will be given further instructions.  You also have the option to forward all emails sent to your company email account to another such as a gmail or yahoo account.</div>
        <form id="email_form" action="">
            <table id="email_table">
            	<tr>
                	<td colspan="2" class="header_tr">Email Account<?php if($anne == 'yes' && $tayl == 'yes') { ?>s<?php } ?></td>
                </tr>
                <?php if($anne == 'yes' && $tayl == 'yes') { ?>
                <tr>
                	<td colspan="2">Since you are licensed with both Anne Arundel and Taylor Properties you may set up an email for both or just one.</td>
                </tr>
                <tr>
                	<td colspan="2"><hr></td>
                </tr>
                <?php } ?>
                <?php if($anne == 'yes') { ?>
                <tr>
                	<td><strong>Select Email</strong></td>
                    <td><input type="text" name="aap_email" id="aap_email" placeholder="Enter Desired Email" />
                        <span style="font-weight:bold; color: #3A4A63; font-size:18px;">@AnneArundelProperties.com</span></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="checkbox" id="aap_forward" />
                        Forward Emails to another address?</td>
                </tr>
                <tr id="aap_forward_tr" style="display:none;">
                    <td><strong>Forward emails to</strong></td>
                    <td><input type="text" name="forwardAAP" id="forwardAAP" placeholder="Enter Forwarding Address" /></td>
                </tr>
                <?php } ?>
                <?php if($anne == 'yes' && $tayl == 'yes') { ?>
                <tr>
                	<td colspan="2"><hr></td>
                </tr>
                <?php } ?>
                <?php if($tayl == 'yes') { ?>
                <tr>
                	<td><strong>Select Email</strong></td>
                    <td><input type="text" name="tp_email" id="tp_email" placeholder="Enter Desired Email" />
                        <span style="font-weight:bold; color: #3A4A63; font-size:18px;">@TaylorProps.com</span></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="checkbox" id="tp_forward" />
                        Forward Emails to another address?</td>
                </tr>
                <tr id="tp_forward_tr" style="display:none;">
                    <td><strong>Forward emails to</strong></td>
                    <td><input type="text" name="forwardTP" id="forwardTP" placeholder="Enter Forwarding Address" /></td>
                </tr>
                <?php } ?>
                <tr>
                	<td colspan="2" class="header_tr">Password</td>
                </tr>
                <tr>
                    <td colspan="2" style="color: #A84549">Passord must be at least 6 characters long and can contain any combination of characters.</td>
                </tr>
                <tr>
                    <td><strong>Enter Password</strong></td>
                    <td><input type="password" name="pass" id="pass" placeholder="Enter Password" /></td>
                </tr>
                <tr>
                    <td><strong>Re-Enter Password</strong></td>
                    <td><input type="password" name="pass2" id="pass2" placeholder="Enter Password Again" /></td>
                </tr>
                <tr>
                	<td colspan="2"><hr></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><a href="javascript: void(0)" style="padding: 15px; padding-right: 45px; font-size:19px" class="button button_continue">Continue</a></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>
<div id="error_div" class="shadow">
	<table width="100%">
    	<tr>
        	<td colspan="2" align="right"><a href="javascript: void(0);" id="close_error_div" class="button button_cancel_s"></a></td>
        </tr>
        <tr>
        	<td width="60"><img src="/new/images/icons/warning_red_new.png" height="30"></td>
        	<td><div id="error_div_text"></div></td>
        </tr>
    </table>	
</div>
<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	
	$('#tp_forward').click(function(){
		$('#tp_forward_tr').toggle();
		if(!$('#tp_forward').is(':checked')) {
			$('#forwardTP').val('');
		}
	});
	
	$('#aap_forward').click(function(){
		$('#aap_forward_tr').toggle();
		if(!$('#aap_forward').is(':checked')) {
			$('#forwardAAP').val('');
		}
	});
	
	$('.button_continue').click(function(){
		
		$('#error_div').hide();
		$('#error_div_text').html('');
		$('input').removeClass('error_input');
		
		<?php if($anne == 'yes' && $tayl == 'yes') { ?>
		if($('#tp_email').val() == '' && $('#aap_email').val() == '') {
		<?php } else if($anne == 'yes') { ?>
		if($('#aap_email').val() == '') {
		<?php } else if($tayl == 'yes') { ?>
		if($('#tp_email').val() == '') {
		<?php } ?>
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('You must enter the email address you would like');
			$('#aap_email, #tp_email').addClass('error_input');
			return false;
		}
		
		if($('#aap_forward').is(':checked') && $('#forwardAAP').val() == '') {
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('You must enter the email address to forward your mail to');
			$('#forwardAAP').addClass('error_input');
			return false;
		}
		
		if($('#tp_forward').is(':checked') && $('#forwardTP').val() == '') {
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('You must enter the email address to forward your mail to');
			$('#forwardTP').addClass('error_input');
			return false;
		}	
		var p1 = $('#pass').val();
		var p2 = $('#pass2').val();
		if(p1 == '') {
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('You must enter your password two times.');
			$('#pass').addClass('error_input');
			return false;
		}
		if(p2 == '') {
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('You must enter your password two times.');
			$('#pass2').addClass('error_input');
			return false;
		}
		if(p1.length < 6) {
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('Your password must be atleast 6 characters.');
			$('#pass').addClass('error_input');
			return false;
		}
		if(p2.length < 6) {
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('Your password must be atleast 6 characters.');
			$('#pass1').addClass('error_input');
			return false;
		}
		if(p1 != p2) {
			$('#error_div').fadeIn('slow');
			$('#error_div_text').html('Your passwords do not match.');
			$('#pass, #pass2').addClass('error_input');
			return false;
		}
		
		var tp_email = $('#tp_email').val();
		var aap_email = $('#aap_email').val();
		var pass = $('#pass').val();
		var tp_forward = $('#forwardTP').val();
		var aap_forward = $('#forwardAAP').val();
		loading_bg();
		$.ajax({
			type: 'POST',
			url: '/new/agents/ajax/email/set_up_email.php',
			data: { tp_email: tp_email, aap_email: aap_email, pass: pass, tp_forward: tp_forward, aap_forward: aap_forward },
			success: function(response){
				remove_loading_bg();
				if(response == '') {
					window.location = 'email_instructions.php';
				} else {
					if($(response).filter('#tp_avail').text() == 'no') {
						$('#error_div').fadeIn('slow');
						$('#error_div_text').append('<span style="font-weight: bold; color: #C65155;">'+$('#tp_email').val()+'@TaylorProps.com</span> is not available.  Please select another email to use.');
						$('#tp_email').addClass('error_input');
					} 
					if($(response).filter('#tp_avail').text() == 'no' && $(response).filter('#aap_avail').text() == 'no') {
						$('#error_div_text').append('<br><br>');
					}
					if($(response).filter('#aap_avail').text() == 'no') {
						$('#error_div').fadeIn('slow');
						$('#error_div_text').append('<span style="font-weight: bold; color: #C65155;">'+$('#aap_email').val()+'@AnneArundelProperties.com</span> is not available.  Please select another email to use.');
						$('#aap_email').addClass('error_input');
					}
					if($(response).filter('#tp_invalid').text() == 'yes') {
						$('#error_div').fadeIn('slow');
						$('#error_div_text').append('<span style="font-weight: bold; color: #C65155;">'+$('#tp_email').val()+'@TaylorProps.com</span> is an invalid email address.  Please select another email to use.');
						$('#tp_email').addClass('error_input');
					}
					if($(response).filter('#aap_invalid').text() == 'yes') {
						$('#error_div').fadeIn('slow');
						$('#error_div_text').append('<span style="font-weight: bold; color: #C65155;">'+$('#aap_email').val()+'@AnneArundelProperties.com</span> is an invalid email address.  Please select another email to use.');
						$('#aap_email').addClass('error_input');
					}
					return false;
				}
				
			}
		});
		
	});
	
	$('#close_error_div').click(function(){
		$('#error_div').hide();
	});
});

</script>
</body>
</html>