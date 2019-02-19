<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Transfer Emails from MRIS.com Acccount to TaylorProps.com Account</title>

<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
.instructions { font-size: 23px; color: #D18359; margin-bottom: 10px; }
.instructions_small { font-size: 16px; color: #516A89; margin-bottom: 20px; }
#transfer_table th { padding: 8px; text-align: left; font-size: 18px; }
#transfer_table td { padding: 8px; font-size: 16px; }
#transfer_table input { width: 300px; font-size: 16px; }
#success { display: none; width: 600px; height: auto; padding: 25px; margin: 0 auto; font-size: 18px; color: #fff; background: #516A89 }
</style>
</head>
<body>

<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

<div class="body_container">
	<h2>Transfer Emails from MRIS.com Acccount to TaylorProps.com Account</h2>
    
    <br><br><br>
    <div style="width: 800px; margin: 0 auto;">
    <div class="instructions">To transfer your emails from your mris.com account to your taylorprops.com or annearundelproperties.com account, just enter the information below</div>
    <br><br>
    <div class="instructions_small">** This process may take from 10 minutes to 24 hours to complete depending on how many emails you have to transfer. Once you have started the process you will be able to see the emails being added to your taylorprops.com account. You will be notified by email once complete. **</div>
    <br>
    <br>
    <table id="transfer_table" align="center">
    	<tr>
        	<th colspan="2">MRIS.com Account</th>
        </tr>
        <tr>
        	<td>Email</td>
            <td><input type="text" id="mris_email"></td>
        </tr>
        <tr>
        	<td>Password</td>
            <td><input type="text" id="mris_pass"></td>
        </tr>
        <tr>
        	<th colspan="2">TaylorProps.com or AnneArundelProperties.com account</th>
        </tr>
        <tr>
        	<td>Email</td>
            <td><input type="text" id="tp_email"></td>
        </tr>
        <tr>
        	<td>Password</td>
            <td><input type="text" id="tp_pass"></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><a href="javascript: void(0)" id="start" class="button button_continue" style="font-size: 18px;">Start Transfer</a></td>
        </tr>
    </table>
    
    <div id="success">
    <table width="100%"><tr><td style="padding-right: 15px;"><img src="/new/images/icons/check_white.png"></td><td align="center">The transfer has begun. Please be patient while the process takes place and we will email you once complete.</td>
    </tr></table></div>
</div>
</div>

<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	
	$('#start').click(function(){
		$(this).text('Starting Transfer...');
		var mris_email = $('#mris_email').val();
		var mris_pass = $('#mris_pass').val();
		var tp_email = $('#tp_email').val();
		var tp_pass = $('#tp_pass').val();
		
		$.ajax({
			type: 'POST',
			data: { mris_email: mris_email, mris_pass: mris_pass, tp_email: tp_email, tp_pass: tp_pass },
			url: '/new/agents/ajax/email/transfer_to_mris.php',
			success: function(response){
				$('#transfer_table').hide();
				$('#success').fadeIn('slow');
			}
		});
	});
});

</script> 
</body>
</html>