<?php 
include("/var/www/annearundelproperties.net/new/agents/includes/agent_queries_old.php");

$ccQuery = "select * from company.cc".$test." where agent_id = '".$_SESSION['S_ID']."'";
$cc = $db -> select($ccQuery);
$ccCount = count($cc);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="background:<?php echo $GLOB_BG_Color; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Credit Cards</title>
<?php include('/var/www/annearundelproperties.net/page_includes/styles.php'); ?>
<style type="text/css">
#add_cc_div {
	display: none;
	position: fixed;
	background: #fff;
	left: 50%;
	top: 20px;
	margin-left: -210px;
	width: 400px;
	height: auto;
	background: #fff;
	border: 10px solid #2A4165;
	z-index: 1000;
}
#add_cc_error_div {
	display: none;
	position: fixed;
	left: 50%;
	top: 150px;
	margin-left: -260px;
	width: 500px;
	height: auto;
	padding: 10px;
	z-index: 6000;
}
.add_cc_head {
	height: 40px;
	background: #2A4165;
	color: #fff;
	font-weight: bold;
	font-size: 18px;
	text-align: center;
	padding: 5px 0
}
.cc_holder {
	width: 95%;
	height: auto;
	margin: 10px auto;
	border: 2px solid #2A4165;
	padding: 10px;
}
#info_div, .info_div {
	display: none;
	width: 200px;
	line-height: 120%;
	height: auto;
	padding: 10px;
	border: 1px solid #2A4165;
	background: #fff;
	color: #2A4165;
	text-align: center;
	position: absolute;
	left: 30px;
	top: -20px;
	z-index: 5000;
}
#loading_bg { position: fixed; left: 0; top: 0; width: 100%; height: 100%; background-color: #000000; filter: alpha(opacity=70); -moz-opacity: .7;	opacity: .7;z-index: 11000; }
#loading_bg_image { position: fixed; top: 50%; left: 50%; margin-top: -100px; margin-left: -235px; background-color:#2A4165; height: auto; width: 380px; z-index: 12000; padding:80px 45px; border:3px solid #cccccc; text-align: center; background-image: url(/new/images/loading/loading.gif); background-repeat: no-repeat; background-position: center; <?php echo radius(15, 15, 15, 15); ?> }
.shadow {
	moz-box-shadow: 3px 5px 3px #333333;
	-webkit-box-shadow: 3px 5px 3px #333333;
	box-shadow: 3px 5px 3px #333333;
}
.blackout {
	display: none;
	width: 100%;
	height: auto;
	position: fixed;
	top: 0;
	left: 0;
	filter: alpha(opacity=100);	-moz-opacity: 1;	opacity: 1;
	moz-box-shadow: 0 0 500px 200px #fff; -webkit-box-shadow: 0 0 500px 200px #fff; box-shadow: 0 0 500px 200px #fff;
}
.round3 {
	<?php echo radius(3, 3, 3, 3); ?>
}
.round5 {
	<?php echo radius(5, 5, 5, 5); ?>
}
.button, .uploadifive-button {
	text-shadow: 0 1px 0 rgba(0,0,0,.5);
	-moz-box-shadow: 0 0 1px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.3) inset;
	-webkit-box-shadow: 0 0 1px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.3) inset;
	box-shadow: 0 0 1px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.3) inset;
	height: auto;
	padding: 10px;
	width: auto;
	cursor: pointer;
	font: normal 14px Arial, Helvetica;
	text-decoration: none;
	color: #fff !important;
	font-weight: bold;
}
.button_normal, .uploadifive-button {
 	<?php echo gradient('#516A89', '#2A4165'); ?>  
	<?php echo radius(5, 5, 5, 5); ?>  
	border: 1px solid #2A4165;
}
.button_normal:hover, .uploadifive-button:hover {
 	<?php echo gradient('#6381A5', '#516A89'); ?>
}
.button_save {
	padding-left: 35px;
 	<?php echo gradientImage('#516A89', '#2A4165', 'url(/new/images/icons/save.png) 10px 8px no-repeat'); ?>  
	<?php echo radius(5, 5, 5, 5); ?>  
	border: 1px solid #2A4165;
}
.button_save:hover {
 	<?php echo gradientImage('#6381A5', '#516A89', 'url(/new/images/icons/save.png) 10px 8px no-repeat'); ?>
}
.button_save_big {
	padding: 15px 15px 15px 40px;
	font-size:24px;
 	<?php echo gradientImage('#516A89', '#2A4165', 'url(/new/images/icons/save.png) 10px 20px no-repeat'); ?>  
	<?php echo radius(5, 5, 5, 5); ?>  
	border: 1px solid #2A4165;
}
.button_save_big:hover {
 	<?php echo gradientImage('#6381A5', '#516A89', 'url(/new/images/icons/save.png) 10px 20px no-repeat'); ?>
}
.button_cancel {
	padding-left: 20px;
	padding-bottom: 6px;
 	<?php echo gradientImage('#900000', '#400000', 'url(/new/images/icons/cancel.png) 8px 8px no-repeat'); ?>  
	<?php echo radius(5, 5, 5, 5); ?>  
	border: 1px solid #400000;
}
.button_cancel:hover {
 	<?php echo gradientImage('#D30000', '#900000', 'url(/new/images/icons/cancel.png) 8px 8px no-repeat'); ?>
}
.button_cancel_small {
	padding: 2px 8px 0px;
 	<?php echo gradientImage('#900000', '#400000', 'url(/new/images/icons/cancel_small.png) 2px 3px no-repeat'); ?>  
	<?php echo radius(5, 5, 5, 5); ?>  
	border: 1px solid #400000;
}
.button_cancel_small:hover {
 	<?php echo gradientImage('#D30000', '#900000', 'url(/new/images/icons/cancel_small.png) 2px 3px no-repeat'); ?>
}
.button_edit {
	padding-left: 38px;
 	<?php echo gradientImage('#516A89', '#2A4165', 'url(/new/images/icons/edit.png) 10px 8px no-repeat'); ?>  
	<?php echo radius(5, 5, 5, 5); ?>  
	border: 1px solid #2A4165;
}
.button_edit:hover {
 	<?php echo gradientImage('#6381A5', '#516A89', 'url(/new/images/icons/edit.png) 10px 8px no-repeat'); ?>
}
.button_add {
	padding-left: 35px;
 	<?php echo gradientImage('#516A89', '#2A4165', 'url(/new/images/icons/plus.png) 10px 8px no-repeat'); ?>  
	<?php echo radius(5, 5, 5, 5); ?>  
	border: 1px solid #2A4165;
}
.button_add:hover {
 	<?php echo gradientImage('#6381A5', '#516A89', 'url(/new/images/icons/plus.png) 10px 8px no-repeat'); ?>
}
#add_cc_table td { padding: 3px 5px; }
</style>
<script src="https://annearundelproperties.net/scripts/js.js"></script>
</head>
<body style="display:none">
<div class="container">
    <?php include('/var/www/annearundelproperties.net/page_includes/header.php'); ?>
    <?php include('/var/www/annearundelproperties.net/page_includes/menu.php'); ?>
    <div class="main">
        <div class="spacerSmall"></div>
        <div class="pageHeader round">Manage Credit Cards</div>
        <div class="infoBox1230 round">
        <div style="width: 200px; margin:30px 200px;"><a href="/agents/billing/billing_home.php" class="button button_normal">Back to Billing</a></div>
            <div class="cc_holder round5" style="width: 700px; margin: 50px auto; background: #fff; padding: 20px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="font-size:16px; font-weight:bold">Credit Cards</td>
                    </tr>
                    <tr>
                        <td><div id="cc_div"></div>
                            <table>
                                <tr>
                                    <td colspan="3" style="padding: 30px 0 20px 0"><a href="javascript: void(0);" class="button button_add" id="add_cc_button">Add New Card</a></td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="spacerSmall"></div>
</div>

<!-- Add Credit Card Div -->
<div id="add_cc_div" class="blackout draggable">
    <table width="100%" cellpadding="0" cellspacing="0" class="draggable_handle">
        <tr>
            <td class="add_cc_head" width="15%" style="padding-bottom:10px;"></td>
            <td class="add_cc_head" style="padding-bottom:10px;"><span id="cc_head_text"></span> Credit Card Information</td>
            <td class="add_cc_head" width="15%" align="right" style="padding-bottom:10px;"><a href="javascript: void(0);" class="button button_cancel" id="close_add_cc_div" style="padding-left: 23px;"></a></td>
        </tr>
    </table>
    <table width="100%" style="font-size: 13px; font-family:Arial" id="add_cc_table">
        <tr>
            <td colspan="2" style="font-weight:bold; padding: 5px; border-bottom: 1px solid #ccc;">Enter the Credit Card details</td>
        </tr>
        <tr>
            <td align="right">First Name</td>
            <td><input type="text" id="cc_first"></td>
        </tr>
        <tr>
            <td align="right">Last Name</td>
            <td><input type="text" id="cc_last"></td>
        </tr>
        <tr>
            <td align="right">Billing Street</td>
            <td><input type="text" id="cc_street" style="width: 200px;"></td>
        </tr>
        <tr>
            <td align="right">Billing Zip</td>
            <td><input type="text" id="cc_zip" style="width: 50px;" class="numbers_only" maxlength="5"></td>
        </tr>
        <tr>
            <td align="right">Billing City</td>
            <td><input type="text" id="cc_city"></td>
        </tr>
        <tr>
            <td align="right">Billing State</td>
            <td><input type="text" id="cc_state" style="width: 30px;"></td>
        </tr>
        <tr>
            <td align="right">Card Type</td>
            <td><select id="card_type" class="chosen-custom chosen-bill" style="width: 150px;" data-placeholder="Card Type">
            		<option value=""></option>
                    <option value="Visa">Visa</option>
                    <option value="Master Card">Master Card</option>
                    <option value="Discover">Discover</option>
                </select></td>
        </tr>
        <tr>
            <td align="right">Card Number</td>
            <td><input type="text" id="cc_number" maxlength="16" style="width: 125px;"></td>
        </tr>
        <tr>
            <td align="right">Card Expire</td>
            <td><select id="cc_expire_month" class="chosen-custom chosen-bill" style="width: 100px;" data-placeholder="Month">
                    <option value=""></option>
                    <option value="01">01-Jan</option>
                    <option value="02">02-Feb</option>
                    <option value="03">03-Mar</option>
                    <option value="04">04-Apr</option>
                    <option value="05">05-May</option>
                    <option value="06">06-Jun</option>
                    <option value="07">07-Jul</option>
                    <option value="08">08-Aug</option>
                    <option value="09">09-Sep</option>
                    <option value="10">10-Oct</option>
                    <option value="11">11-Nov</option>
                    <option value="12">12-Dec</option>
                </select>
                &nbsp;&nbsp;&nbsp;
                <select id="cc_expire_year" class="chosen-custom chosen-bill" style="width: 100px;" data-placeholder="Year">
                    <option value=""></option>
                    <?php for($y=date("Y");$y<date("Y")+12;$y++) { ?>
                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                    <?php } ?>
                </select></td>
        </tr>
        <tr>
            <td align="right">Card Code</td>
            <td><input type="text" id="cc_code" class="numbers_only" maxlength="4" style="width: 60px;"></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><div style="width: 240px; height:auto; background: #900; color: #fff; padding:5px;" class="round5">Save as Default Credit Card?
                    <input type="checkbox" id="cc_default_card" value="yes" checked>
                </div></td>
        </tr>
        <tr>
            <td colspan="2" align="center" height="50"><a href="javascript: void(0);" id="save_cc_button" class="button button_save">Save Credit Card</a></td>
        </tr>
    </table>
</div>
<!-- Add Credit Card Div --> 

<!-- Credit Card Error Div -->
<div id="add_cc_error_div" class="ui-state-error ui-corner-all">
    <div style="text-align: right; width: 100%; margin-top: 5px;"><a href="javascript: void(0);" id="close_add_cc_error_div" class="button button_cancel_small"></a></div>
    <p align="center"> <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> <strong>Alert:</strong> <span id="add_cc_error_message"></span> </p>
</div>
<!-- End Credit Card Error Div --> 

<input type="hidden" id="cc_count" value="<?php echo $ccCount; ?>">
<input type="hidden" id="cc_function">
<input type="hidden" id="profile_id" value="<?php if($cc[0]['profileID'] == '') { echo $agent[0]['profileId']; } else { echo $cc[0]['profileID']; } ?>">

<script src="https://annearundelproperties.net/scripts/jqueryCompile.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$.getScript("/scripts/jqueryCommon.js");
	get_ccs();
	
	$('#add_cc_button').click(add_cc);
	$('#new_cc_zip').keyup(function(){
		if($(this).val().length == 5) {
			var loc = add_loc($(this).val());
			setTimeout(function(){
				$('#new_cc_city').val(loc['city']);
				$('#new_cc_state').val(loc['state']);
			}, 500);
		}
	});
	$('#new_cc_save_card').change(function(){
		if(!$(this).is(':checked')) {
			$('#new_cc_default_card').prop('checked', false);
		}
	});
	$('#save_cc_button').click(function(e) {
		save_cc();
	});
	$('#close_add_cc_error_div').click(function(){
		$('#add_cc_error_div').hide();
	});
	$('#close_add_cc_div').click(function(){
		$('#add_cc_div').hide();
	});

});
function get_ccs(){
	$.ajax({
		type: 'POST',
		url: '/new/agents/ajax/billing/cc/get_ccs_old.php',
		data: { agent_id: '<?php echo $agent[0]['id']; ?>' },
		success: function(response){
			$('#cc_div').html(response);
			$('.default_card_button').click(function(){
				default_cc($(this).attr('payprofileid'), '<?php echo $agent[0]['id']; ?>');
			});
			$('.deleteCC').click(function(){
				delete_cc($(this).attr('defaultcard'), $(this).attr('profileid'), $(this).attr('payprofileid'), $(this).attr('id'));
			});
			$('.editCC').click(function(){
				edit_cc($(this).attr('ccfirst'), $(this).attr('cclast'), $(this).attr('street'), $(this).attr('zip'), $(this).attr('payprofileid'), $(this).attr('cvv'));
			});
			$('#info_image').mouseenter(function(){
				$(this).next('div.info_div').show();
			});
			$('#info_image').mouseleave(function(){
				$(this).next('div.info_div').hide();
			});
		}
	});	
}
function delete_cc(default_card, profile_id, payment_profile_id, ele) {
	var c = confirm('\n** All previous charges to this card will no longer be refundable. **\n\nAre you sure you want to permantely delete this Credit Card?\n\n');
	if(c) {
		/*
		<?php //if($agent[0]['auto_bill'] == 'on') { ?>
		var ab = 'on';
		<?php //} else { ?>
		var ab = 'off';
		<?php //} ?>
		if(default_card == 'yes' && ab == 'on') {
			var d = confirm('\nDeleting this card will remove you from Automated Billing because this is your default card.  Continue?\n\n');
			if(d) {
				$.ajax({
					type: 'POST',
					url: '/new/ajax/billing/cc/remove_auto_billing.php',
					data: { agent_id: '<?php //echo $agent[0]['id']; ?>' }
				});
			} else {
				return false;
			}
		}
		*/
		var e = $('#'+ele);
		$.ajax({
			type: 'POST',
			url: '/new/ajax/billing/cc/delete_cc.php',
			data: { profile_id: profile_id, payment_profile_id: payment_profile_id },
			success: function(response){
				var r = $(response);
				e.parent('td').parent('tr').fadeOut('slow');
				get_ccs();
			}
		});
	}
}
function edit_cc(f, l, s, z, p, c) {
	$('#add_cc_div').show();
	$('#cc_function').val('edit');
	$('#cc_head_text').text('Edit');

	var loc = add_loc(z);	
	setTimeout(function(){
		$('#cc_first').val(f);
		$('#cc_last').val(l);
		$('#cc_street').val(s);
		$('#cc_zip').val(z);
		$('#cc_city').val(loc['city']);
		$('#cc_state').val(loc['state']);
		$('#cc_code').val(c);
		$('#payment_profile_id').val(p);
	}, 500);	
}
function add_cc(){
	$('#add_cc_div').show();
	$('#cc_function').val('add');
	$('#cc_head_text').text('Add');
	
	var loc = add_loc('<?php echo $agent[0]['zip']; ?>');	
	setTimeout(function(){
		$('#cc_first').val('<?php echo $agent[0]['first']; ?>');
		$('#cc_last').val('<?php echo $agent[0]['last']; ?>');
		$('#cc_street').val('<?php echo $agent[0]['street']; ?>');
		$('#cc_zip').val('<?php echo $agent[0]['zip']; ?>');
		$('#cc_city').val(loc['city']);
		$('#cc_state').val(loc['state']);
	}, 500);	
}
function default_cc(id, agent_id) {
	$.ajax({
		type: 'POST',
		url: '/new/ajax/billing/cc/make_default_cc.php',
		data: { payment_profile_id: id, agent_id: agent_id },
		success: function(){
			get_ccs();
		}
	});
}
function save_cc(){ 
	$('#add_cc_error_div').hide();
	var fields = ['cc_first', 'cc_last', 'cc_street', 'cc_city', 'cc_state', 'cc_zip', 'card_type', 'cc_number', 'cc_expire_month', 'cc_expire_year', 'cc_code'];
	var em = ['You Must Enter a First Name', 'You Must Enter a Last Name', 'You Must Enter a Street Address', 'You Must Enter a City', 'You Must Enter a State', 'You Must Enter a Zip Code', 'You Must Enter the Card Type', 'You Must Enter a Credit Card Number', 'You Must Enter an Expiration Month', 'You Must Enter an Expiration Year', 'You Must Enter a Card Code'];
	var errors = 'no';
	for(i=0;i<fields.length;i++) {
		if(errors == 'no') {
			if($('#'+fields[i]).val() == '') {
				$('#add_cc_error_div').show();
				$('#add_cc_error_message').text(em[i]);
				errors = 'yes';
			}
		}
	}
	if(errors == 'no') {
		var f = $('#cc_function').val();
		if(f == 'add') {
			u = '/new/ajax/billing/cc/add_cc.php';
		} else if(f == 'edit') {
			u = '/new/ajax/billing/cc/edit_cc.php';
		}
		loading_bg();
		$.ajax({
			type: 'POST',
			url: u,
			data: { cc_first: $('#cc_first').val(), cc_last: $('#cc_last').val(), street: $('#cc_street').val(), city: $('#cc_city').val(), state: $('#cc_state').val(), zip: $('#cc_zip').val(), card_type: $('#card_type').val(), number: $('#cc_number').val(), month: $('#cc_expire_month').val(), year: $('#cc_expire_year').val(), code: $('#cc_code').val(), agent_id: '<?php echo $agent[0]['id']; ?>', email: '<?php echo $agent[0]['email1']; ?>', profile_id: $('#profile_id').val(), payment_profile_id: $('#payment_profile_id').val(), default_card: $('#cc_default_card:checked').val() },
			success: function(response){
				var r = $(response);
				var e = r.filter('#error').text();
				if(e != '') {	
					if(e.match(/duplicate\scustomer\spayment/)) {
						e = 'You already has a card with that number.';
					}
					$('#add_cc_error_div').show();
					$('#add_cc_error_message').text(e);
					remove_loading_bg();
				} else {
					if(r.filter('#profile_id').text() != '') {	
						$('#profile_id').val(r.filter('#profile_id').text());
					}
					$('#add_cc_div').hide();
					remove_loading_bg();
					get_ccs();
					var ccc = r.filter('#cc_count').text();
					$('#cc_count').val(ccc);
				}
				
			}
		});
	}
}

</script> 
<a href="http://apycom.com/"></a>
</body>
</html>