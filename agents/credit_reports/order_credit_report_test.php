<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Order Credit Report</title>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>

<script src="https://annearundelproperties.net/new/scripts/upload/jquery.uploadifive.js" type="text/javascript"></script>
<link href="https://annearundelproperties.net/new/scripts/upload/uploadifive.css" rel="stylesheet" type="text/css" />

<style type="text/css">
.credit-container {
	width: 700px;
	margin: 0 auto;
}
.credit-header {
	width: 100%; text-align: center; font-size: 18px; margin: 15px 0 30px 0; font-weight: bold;}
.section-header { color: #D18359; font-weight: bold; font-size: 17px; margin-top: 25px;}
.credit-table td { padding: 8px 10px; font-size: 17px; }
#credit_error { display: none; width: 100%; padding: 15px; background: #900; text-align: center; border-radius: 3px;font-size: 17px; color: #fff; }
#payment_div, #upload_success, #credit_submit, #add_payment_div { display: none; width: 100%; }
#upload_success { width: 600px; margin: 200px auto; padding: 15px; font-size: 18px; font-weight: bold; background: #0b8a18; color: #fff; text-align: center; border-radius: 3px; }
#p_ccs_table td{padding:5px 5px 5px 0}
.payment_divs{display:none}
#card_on_file_div,#new_card_div{display:none}
#cc_options{margin:30px 0 15px 0}
#cc_options td{text-align:center}
#zip_error,#add_cc_zip_error,#edit_cc_zip_error{width:125px;padding:10px 0 10px 40px;font-size:16px;background:#c65155 url('/new/images/icons/warning_white_small.png') no-repeat 8px 50%;color:#fff;display:none}
#new_card_div td { padding-right: 10px; }
.warning { color: #c65155; font-weight: bold; width: 100%; margin: 0px auto 45px auto; text-align: center; font-size: 17px; border-bottom: 1px solid #ccc }
#credit_submit { width: 100%; text-align: center; margin-top: 25px; }
.upload_section { text-align: center;  }
.report_type_div { width: 100%; height: auto; display: block;padding: 10px; border: 1px solid #516A89; border-radius: 4px; margin-bottom: 10px}
.report_price { font-size: 25px; font-weight: bold; color: #516A89}
.report_header { font-weight: bold; font-size: 14px}
li { font-size: 13px; }
.report_selected { background: #a2c7e8; }
</style>
</head>
<body>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

<div class="body_container">
	<h2>Order Credit Report</h2>
	<br><br>
	<div class="credit-container">

		<div class="credit-header">To order a credit report enter the requested information below, submit payment and upload the credit application.</div>
		<div class="warning">Credit Reports provided only during the hours of 9-5 Monday - Friday</div>

		<div id="upload_div">
			<div class="section-header">Step 1: Upload Application</div>

			<div class="upload_section"><strong>Select the files to upload</strong><br><br>
				<input type="file" id="upload">
				<br><br>
				<div id="queue"></div>

			</div>

			<div id="credit_submit">
				<a href="javascript: void(0)" id="submit" class="button button_normal" style="font-size: 20px; padding: 15px">Submit</a>
			</div>

		</div>



		<div id="payment_div">

			<div class="section-header">Step 2: Enter details and Report Type</div>
			<table width="100%" class="credit-table">
				<tr>
					<td align="right">Client's Name</td>
					<td><input type="text" id="name" style="width: 277px" placeholder="Enter Client's Name"></td>
				</tr>
				<tr>
					<td align="right">Select Type Of Report</td>
					<td>
						<div class="report_type_div">
							<table width="100%">
								<tr>
									<td width="10%" align="center">
										<input type="radio" class="radio_styled" id="report1" name="report_type" value="25.00">
										<label for="report1" class="label_styled"></label>
									</td>
									<td width="10%" align="center">
										<div class="report_price">$25</div>
									</td>
									<td width="80%">
										<div class="report_header">Report Includes:</div>
										<ul>
											<li>Experian Credit Score</li>
											<li>SSN Verification</li>
											<li>Single State Criminal Search</li>
											<li>Single State Eviction Search
										</ul>
									</td>
								</tr>
							</table>
						</div>
						<div class="report_type_div">
							<table width="100%">
								<tr>
									<td width="10%" align="center">
										<input type="radio" class="radio_styled" id="report2" name="report_type" value="38.00">
										<label for="report2" class="label_styled"></label>
									</td>
									<td width="10%" align="center">
										<div class="report_price">$38</div>
									</td>
									<td width="80%">
										<div class="report_header">Report Includes:</div>
										<ul>
											<li>Experian Credit Score</li>
											<li>TransUnion Credit Score</li>
											<li>Equifax Credit Score</li>
											<li>SSN Verification</li>
											<li>Single State Criminal Search</li>
											<li>Single State Eviction Search
										</ul>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr id="continue_tr">
					<td colspan="2" align="center"><a href="javascript: void(0)" id="continue" class="button button_cont">Continue</a></td>
				</tr>
			</table>


			<div id="add_payment_div">
				<div class="section-header">Step 3: Submit Payment</div>

			    <table id="cc_options" width="100%">
			        <tr>
			            <td><input type="radio" class="radio_styled" name="pay_with" value="existing_card" id="pay_with_existing">
			                <label class="label_styled" for="pay_with_existing">Use Existing Credit Card</label></td>
			            <td><input type="radio" class="radio_styled" name="pay_with" value="new_card" id="pay_with_new">
			                <label class="label_styled" for="pay_with_new">Use New Credit Card</label></td>
			        </tr>
			    </table>
				<hr>
			    <div style="padding: 15px; display:none;" id="payment_container">
			        <div id="card_on_file_div" class="payment_divs" style="position:relative">

			            <div id="p_ccs"></div>
			        </div>
			        <div id="new_card_div" class="payment_divs" style="position:relative; width: 100%">
			            <table cellpadding="0" cellspacing="0" width="100%">
			                <tr>
			                    <td style="font-weight:bold; padding: 5px; font-size: 15px; border-bottom: 1px solid #ccc;">Enter the Credit Card details</td>
			                </tr>
			                <tr>
			                    <td><table class="add_cc_table">
			                            <tr>
			                                <td>First Name<br>
			                                    <input tabindex="1" type="text" id="ccFirst" name="ccFirst" style="width: 90px"></td>
			                                <td>Last Name<br>
			                                    <input tabindex="2" type="text" id="ccLast" name="ccLast" style="width: 90px"></td>
			                                <td width="50%" colspan="2"></td>
			                            </tr>
			                        </table></td>
			                </tr>
			                <tr>
			                    <td><table class="add_cc_table">
			                            <tr>
			                                <td>Billing Street<br>
			                                    <input tabindex="3" type="text" id="ccStreet" name="ccStreet" style="width: 200px;"></td>
			                                <td>Billing Zip<br>
			                                    <input tabindex="4" type="text" id="ccZip" name="ccZip" style="width: 50px;" class="numbers_only" maxlength="5" value="" autocomplete="off"></td>
			                                <td><div id="zip_error" class="round5">Invalid Zip Code</div></td>
			                            </tr>
			                        </table></td>
			                </tr>
			                <tr>
			                    <td><table class="add_cc_table">
			                            <tr>
			                                <td class="chosen_height_td_ccs_type">Card Type<br>
			                                    <select tabindex="5" id="card_type" name="card_type" class="chosen-custom" style="width: 125px;" data-placeholder="">
			                                        <option value=""></option>
			                                        <option value="Visa">Visa</option>
			                                        <option value="Master Card">Master Card</option>
			                                        <option value="Discover">Discover</option>
			                                    </select></td>
			                                <td>Card Number<br>
			                                    <input tabindex="6" type="text" id="ccNumber" name="ccNumber" class="numbers_only" maxlength="16" style="width: 125px;" value="" placeholder=""></td>
			                                <td class="chosen_height_td_ccs">Card Expire<br>
			                                    <select tabindex="7" id="ccMonth" name="ccMonth" class="chosen-custom" style="width: 120px;" data-placeholder="Month">
			                                        <option value=""></option>
			                                        <option value="01">01 - Jan</option>
			                                        <option value="02">02 - Feb</option>
			                                        <option value="03">03 - Mar</option>
			                                        <option value="04">04 - Apr</option>
			                                        <option value="05">05 - May</option>
			                                        <option value="06">06 - Jun</option>
			                                        <option value="07">07 - Jul</option>
			                                        <option value="08">08 - Aug</option>
			                                        <option value="09">09 - Sep</option>
			                                        <option value="10">10 - Oct</option>
			                                        <option value="11">11 - Nov</option>
			                                        <option value="12">12 - Dec</option>
			                                    </select>
			                                    &nbsp;
			                                    <select tabindex="8" id="ccYear" name="ccYear" class="chosen-custom" style="width: 100px;" data-placeholder="Year">
			                                        <option value=""></option>
			                                        <?php for($y=date("Y");$y<date("Y")+12;$y++) { ?>
			                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
			                                        <?php } ?>
			                                    </select></td>
			                                <td>CVV Code<br>
			                                    <input tabindex="9" type="text" id="code" name="CVV" class="numbers_only" maxlength="4" style="width: 30px;" placeholder=""></td>
			                            </tr>
			                        </table></td>
			                </tr>
			            </table>
			        </div>
			        <div class="payment_divs" id="submit_div">
			            <table width="100%">
			                <tr>
			                    <td><hr></td>
			                </tr>
			                <tr>
								<td align="center" style="padding-bottom: 25px; padding-top: 10px">Amount <input type="text" id="amount" style="width: 60px; padding: 15px; text-align:center; font-size: 17px; font-weight:bold"></td>
							</tr>
							<tr>
			                    <td align="center"><a href="javascript:void(0);" id="charge_payment_button" class="button button_normal" style="font-size:18px; font-weight:bold; padding: 18px;">Submit Payment</a></td>
			                </tr>
			            </table>
			        </div>
			    </div>
			</div>

		</div>


		<div id="credit_error">
			<table align="center">
				<tr>
					<td style="padding-right: 20px"><img src="/new/images/icons/warning_white_small.png"></td>
					<td id="credit_error_text"></td>
				</tr>
			</table>
		</div>


	</div>

	<div id="upload_success">
		<table align="center" width="100%">
			<tr>
				<td style="padding-right: 20px"><img src="/new/images/icons/check_white_small.png"></td>
				<td>Your upload and payment were successfull. Your credit report will be emailed to you shortly.</td>
			</tr>
		</table>
	</div>


</div>

<input type="hidden" id="new_card">
<input type="hidden" id="city">
<input type="hidden" id="state">
<input type="hidden" id="invalid_zip" value="no">
<input type="hidden" id="id">

<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	$('.chosen-custom').chosen({ disable_search: true });


	upload();

	$('[name="report_type"]').click(function() {
		$('.report_selected').removeClass('report_selected');
		$(this).closest('.report_type_div').addClass('report_selected');
	});

	$('#continue').click(function() {

		if($('#name').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('Please enter a name');
			return false;
		}
		if(!$('[name="report_type"]').is(':checked')) {
			$('#credit_error').show();
			$('#credit_error_text').html('Please enter a Report Type');
			return false;
		}
		$('#continue_tr').hide();
		$('#add_payment_div').show();

		tooltip();
		get_ccs_payment();

		$('#amount').val($('[name="report_type"]:checked').val());

		$('#ccZip').bind('input propertychange', function(){
			$('#zip_error').hide();
			if($(this).val().length == 5) {
				set_loc();
			}
		});

		$('#charge_payment_button').click(function() {
			loading_bg();
			$(this).text('Submitting Payment...');
			setTimeout(submit_payment, 1000);
		});


	});
});

function submit_payment(){

	$('#zip_error, #credit_error').hide();
	$('#credit_error_text').html('');
	var desc = 'Payment for Credit Report for '+$('#name').val();
	var new_card = $('#new_card').val();
	var amount =  $('#amount').val();
	var agent_id = '<?php echo $_SESSION['S_ID']; ?>';

	var data = { desc: desc, new_card: new_card, amount: amount, agent_id: agent_id }

	if(new_card == 'yes') {

		data.first = $('#ccFirst').val();
		data.last = $('#ccLast').val();
		data.street = $('#ccStreet').val();
		data.city = $('#city').val();
		data.state = $('#state').val();
		data.zip = $('#ccZip').val();
		data.card_type = $('#card_type').val();
		data.number = $('#ccNumber').val();
		data.expire_month = $('#ccMonth').val();
		data.expire_year = $('#ccYear').val();
		data.code = $('#code').val();
		data.name = $('#name').val();
		data.id = $('#id').val();


		if($('#ccFirst').val() == '') {
			$('#credit_error').show()
			$('#credit_error_text').html('You must enter a First Name');
			$('#ccFirst').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#ccLast').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter a Last Name');
			$('#ccLast').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#ccStreet').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter the Street Address');
			$('#ccStreet').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#invalid_zip').val() == 'yes' || $('#ccZip').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You have entered an invalid Zip Code');
			$('#ccZip').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#card_type').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter the Card Type');

			$('#card_type').trigger('chosen:open');
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#ccNumber').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter the Card Number');
			$('#ccNumber').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#ccMonth').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter the Expiration Month');
			$('#ccMonth').trigger('chosen:open');
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#ccYear').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter the Expiration Year');
			$('#ccYear').trigger('chosen:open');
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if($('#code').val() == '') {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter the CVV Code');
			$('#code').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

		if(amount == '' || amount == '0.00' || amount == '0' || !amount.match(/[0-9\.]+/)) {
			$('#billing_error_div').show();
			$('credit_error_text').html('You must enter an Amount to charge');
			$('#amount').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

	} else {

		data.pay_profile_id = $('[name=card_to_use]:checked').val();
		data.name = $('#name').val();

		if(amount == '' || amount == '0.00' || amount == '0' || !amount.match(/[0-9\.]+/)) {
			$('#credit_error').show();
			$('#credit_error_text').html('You must enter an Amount to charge');
			$('#amount').focus();
			remove_loading_bg();
			$('#charge_payment_button').text('Submit Payment');
			return false;
		}

	}

	$.ajax({
		type: 'POST',
		data: data,
		url: '/new/agents/ajax/billing/payments/make_payment_credit_report_test.php',
		success: function(response){
			remove_loading_bg();
			var r = $($.parseHTML(response));
			if(r.filter('#error').length > 0) {
				var error = r.filter('#error').html();
				remove_loading_bg();
				$('#charge_payment_button').text('Submit Payment');
			} else {
				var error = '';
			}
			var id = $('#id').val();

			if(error != '') {
				remove_loading_bg();
				$('#charge_payment_button').text('Submit Payment');
				if(error == 'A duplicate customer payment profile already exists.') {
					error = 'There is already an existing card on file with that number. Please contact Mike at 800-590-0925.';
				} else if(error == 'You cannot add more than 10 payment profiles.') {
					error = 'You cannot have more than 10 Credit Cards on file.  Please delete at least one to proceed and make a payment';
				}
				$('#credit_error').show();
				$('#credit_error_text').html(error);
			} else {
				$('#upload_success').fadeIn();
				$('#upload_div, #payment_div, #add_payment_div').hide();

			}
		}

	});

}

function upload() {
	var agent_id = '<?php echo $_SESSION['S_ID']; ?>';
	$('#upload').uploadifive({
		'auto'          : true,
		'multi'			: false,
		'fileSizeLimit' : '100MB',
		'method'   		: 'post',
		'removeCompleted' : false,
		'onAddQueueItem' : function(file) {
			var fileName = file.name;
			var ext = fileName.substring(fileName.lastIndexOf('.') + 1); // Extract EXT
			ext = ext.toLowerCase();
			if(ext != 'pdf') {
				alert('All Uploads Must Be in PDF Format');
				$('#upload').uploadifive('cancel', file);
			};
			this.data('uploadifive').settings.formData = {
				'agent_id'	: agent_id
			};

			$('#submit').unbind('click').bind('click', function() {
				$('#upload').uploadifive('upload');
			});
			$('#payment_div').show();
		},
		'queueID'          : 'queue',
		'uploadScript'     : '/new/agents/ajax/billing/payments/upload_script_credit_report_test.php',
		'onError'      : function(errorType) {
			alert('There was an error uploading: ' + errorType + '. Please try again');
		},
        'onUploadComplete' : function(file, data) {
            $('#id').val(data);
        }

	});
}

function reset_payment_div(){
	$('#new_card').val('');
	$('.payment_divs, #cc_options, #card_on_file_div, #new_card_div, #payment_container').hide();
	$('[name=pay_with]').prop('checked', false);

}

function get_ccs_payment(){
	$.ajax({
		type: 'POST',
		url: '/new/agents/ajax/billing/cc/get_ccs_payment.php',
		success: function(response){
			//var r = $($.parseHTML(response));
			if(response.match(/no\scard\son\sfile/)) {
				$('#card_on_file_div').hide();
				$('#new_card_div').fadeIn('slow');
				$('#cc_options').hide();
				$('#new_card').val('yes');
				$('#submit_div').show();
				$('#payment_container').show();
			} else {
				$('#cc_options').show();
				$('#p_ccs').html(response);
				$('[name=pay_with]').click(function(){
					if($(this).val() == 'existing_card') {
						$('#card_on_file_div').fadeIn('slow');
						$('#new_card_div').hide();
						$('#new_card').val('no');
					} else if($(this).val() == 'new_card') {
						$('#card_on_file_div').hide();
						$('#new_card_div').fadeIn('slow');
						$('#new_card').val('yes');
					}
					$('#submit_div').show();
					$('#payment_hr').show();
					$('#payment_container').show();
				});
			}


		}
	});
}

function tooltip(){
	$('.tooltip').each(function(){
		if($(this).attr('tt') == 'left') {
			var position =  { my: 'right center', at: 'left-10 center' };
			var tooltipClass = 'tt_left_info';
		} else if($(this).attr('tt') == 'right') {
			var position =  { my: 'right center', at: 'left-15 center' };
			var tooltipClass = 'tt_right_info';
		} else {
			var position =  { my: 'right center', at: 'left-15 center' };
			var tooltipClass = 'tt_right_info';
		}

		$(this).tooltip({
			content: function () {
				return $(this).prop('title');
			},
			position: position,
			tooltipClass: tooltipClass
		});
	});
}

function set_loc(){
	$('#zip_error').hide();
	var r = add_loc($('#ccZip').val());
	setTimeout(function(){
		if(r['city'] != 'error') {
			$('#city').val(r['city']);
			$('#state').val(r['state']);
			$('#invalid_zip').val('no');
		} else {
			$('#zip_error').show();
			$('#invalid_zip').val('yes');
		}
	}, 300);
}

</script>
</body>
</html>