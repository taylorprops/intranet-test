<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');


$empQuery = "select * from company.tbl_agents where id = '".$_SESSION['S_ID']."'";
$emp = $db -> select($empQuery);

$t = 'Agent';
$phone = $emp[0]['cell_phone'];
$email = $emp[0]['email1'];
$name = $emp[0]['fullname'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Request HUD1</title>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
</head>
<body>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>
<style type="text/css">
#hudTable { width: 700px; margin: 0 auto; }
#hudTable td {
	padding: 6px;
}
.secHeader {
	font-size: 15px;
	font-weight: bold;
	background: #3A4A63;
	color: #fff;
}
.addressTR {
	display: none;
}
.loanTR {
	display: none;
}
#successTR {
	display: none;
}
#errorDiv {
	width: auto;
	height: auto;
	margin: 5px auto;
	border: 3px solid #A84549;
	color: #A84549;
	font-size: 16px;
	text-align: center;
	background: #fff;
	padding: 5px 0;
}
#successDiv {
	width: 500px;
	height: auto;
	margin: 10px auto;
	border: 3px solid #065E0D;
	color: #065E0D;
	font-size: 16px;
	text-align: center;
	background: #fff;
	padding: 10px 0;
}
</style>
<div class="body_container">
    <h2>Request HUD1</h2>
    <br>
    <form action="" id="hudForm">
        <table cellpadding="0" cellspacing="0" border="0" id="hudTable">
            <tr>
                <td colspan="2" align="center" style="font-size: 18px; padding: 25px;">Enter all information below.  Once complete, this form will be sent to Heritage Title.  You will then be contacted by Heritage Title with the preliminary HUD1.</td>
            </tr>
            <tr id="successTR">
                <td colspan="2" align="center"><div id="successDiv" class="round">HUD1 Successfully Submitted<br />
                        To follow up you may contact Heritage Title at (866) 913-4095</div></td>
            </tr>
            <tr class="secHeaderTR">
                <td class="secHeader" colspan="2">Your Information</td>
            </tr>

            <tr>
                <td width="180" align="right">Your Name</td>
                <td><input type="text" name="ordered_by_name" id="ordered_by_name" style="width: 200px;" class="required" mes="You must enter Your Name" value="<?php echo $name; ?>" /></td>
            </tr>
            <tr>
                <td align="right">Your Phone</td>
                <td><input type="text" name="ordered_by_phone" id="ordered_by_phone" style="width: 200px;" class="required phone" mes="You must enter Your Phone Number" value="<?php echo $phone; ?>" /></td>
            </tr>
            <tr>
                <td align="right">Your Email</td>
                <td><input type="text" name="ordered_by_email" id="ordered_by_email" style="width: 200px;" class="required" mes="You must enter Your Email Address" value="<?php echo $email; ?>" /></td>
            </tr>
            <tr class="secHeaderTR">
                <td class="secHeader" colspan="2">Client Information</td>
            </tr>
            <tr>
                <td width="180" align="right">Client Name</td>
                <td><input type="text" name="client_name" id="client_name" style="width: 200px;" class="required" mes="You must enter the Client's Name" /></td>
            </tr>
            <tr>
                <td align="right">Client Home Phone</td>
                <td><input type="text" name="client_home_phone" id="client_home_phone" style="width: 200px;" class="phone" /></td>
            </tr>
            <tr>
                <td align="right">Client Cell Phone</td>
                <td><input type="text" name="client_cell_phone" id="client_cell_phone" style="width: 200px;" class="phone" /></td>
            </tr>
            <tr>
                <td align="right">Client Email</td>
                <td><input type="text" name="client_email" id="client_email" style="width: 200px;" /></td>
            </tr>
            <tr>
                <td align="right">First Time Home Buyer</td>
                <td class="noSearch"><select name="first_time_buyer" id="first_time_buyer" class="chosen-custom required" style="width: 210px;" mes="You must select if First Time Buyer">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select></td>
            </tr>
            <tr class="secHeaderTR">
                <td class="secHeader" colspan="2">Property Information</td>
            </tr>
            <tr>
                <td align="right">Sales Price</td>
                <td><input type="text" name="sales_price" id="sales_price" style="width: 100px" class="currency required" mes="You must enter the Sales Price" /></td>
            </tr>
            <tr>
                <td align="right">Primary Residence</td>
                <td class="noSearch"><select name="primary_res" id="primary_res" class="chosen-custom required" style="width: 210px;" mes="You must select if Primary Residence">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select></td>
            </tr>
            <tr>
                <td align="right">Transaction Type</td>
                <td class="noSearch"><select name="transaction_type" id="transaction_type" class="chosen-custom required" style="width: 210px;" mes="You must select the Transaction Type">
                        <option value=""></option>
                        <option value="Conventional Sale">Conventional Sale</option>
                        <option value="Bank Sale/REO">Bank Sale/REO</option>
                        <option value="Short Sale">Short Sale</option>
                    </select></td>
            </tr>
            <tr>
                <td align="right">Street Address</td>
                <td><input type="text" name="prop_street" id="prop_street" style="width: 300px" class="required"  mes="You must enter the Street Address"/></td>
            </tr>
            <tr>
                <td align="right">Zip Code</td>
                <td><input type="text" name="prop_zip" id="prop_zip" maxlength="5" style="width: 70px; text-align:center" class="required" mes="You must enter the Zip Code" /></td>
            </tr>
            <tr id="addressError" style="display: none;">
                <td></td>
                <td style="color: #A84549; font-weight:bold;">Invalid Zip Code</td>
            </tr>
            <tr class="addressTR">
                <td align="right">City</td>
                <td><input type="text" name="prop_city" id="prop_city" style="width: 200px" class="required" mes="You must enter The City" /></td>
            </tr>
            <tr class="addressTR">
                <td align="right">State</td>
                <td><input type="text" name="prop_state" id="prop_state" style="width: 50px; text-align:center" class="required" mes="You must enter the State" /></td>
            </tr>
            <tr class="addressTR">
                <td align="right">County</td>
                <td><input type="text" name="prop_county" id="prop_county" style="width: 200px" class="required" mes="You must enter the County" /></td>
            </tr>
            <tr class="secHeaderTR">
                <td class="secHeader" colspan="2">Purchase Type</td>
            </tr>
            <tr>
                <td align="right">Purchase Type</td>
                <td class="noSearch"><select name="purchase_type" id="purchase_type" class="chosen-custom required" style="width: 210px;" mes="You must enter the Purchase Type">
                        <option value=""></option>
                        <option value="Financed">Financed</option>
                        <option value="Cash">Cash</option>
                    </select></td>
            </tr>
            <tr class="loanTR secHeaderTR">
                <td class="secHeader" colspan="2">Loan Information</td>
            </tr>
            <tr class="loanTR">
                <td align="right">Loan Amount</td>
                <td><input type="text" name="loan_amount" id="loan_amount" style="width: 100px" class="currency required" mes="You must enter the Loan Amount" /></td>
            </tr>
            <tr class="loanTR">
                <td align="right">Closing Cost Help</td>
                <td><input type="text" name="closing_help" id="closing_help" style="width: 100px" class="currency required" mes="You must enter the Closing Cost Help" /></td>
            </tr>
            <tr class="loanTR">
                <td class="secHeader" colspan="2">Closing Information</td>
            </tr>
            <tr>
                <td align="right">Settlement Date</td>
                <td><input type="text" id="close_date" name="close_date" style="width: 85px; text-align:center" class="datepicker2 required" mes="You must enter the Settlement Date" /></td>
            </tr>
            <tr>
                <td align="right">Taxes Split 50/50</td>
                <td class="noSearch"><select name="taxes_split" id="taxes_split" class="chosen-custom required" style="width: 210px;" mes="You must select if Taxes are Split">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select></td>
            </tr>
            <tr>
                <td align="right">Admin Fee Charged</td>
                <td><input type="text" name="admin_fee" id="admin_fee" class="currency required" mes="You must enter the Admin Fee" /></td>
            </tr>
            <tr class="secHeaderTR">
                <td class="secHeader" colspan="2">Commission Splits</td>
            </tr>
            <tr>
                <td align="right">Listing Agent</td>
                <td><input type="text" name="list_agent_split" id="list_agent_split" style="width: 70px;" class="required"  mes="You must enter the Listing Agent Commission Split"/>
                    %</td>
            </tr>
            <tr>
                <td align="right">Selling Agent</td>
                <td><input type="text" name="sale_agent_split" id="sale_agent_split" style="width: 70px;" class="required"   mes="You must enter the Selling Agent Commission Split"/>
                    %</td>
            </tr>
            <tr>
                <td colspan="2"><hr /></td>
            </tr>
            <tr>
                <td align="right">Additional Notes</td>
                <td><textarea name="notes" id="notes" style="width: 500px; height: 40px;"></textarea></td>
            </tr>
            <tr>
                <td colspan="2"><hr /></td>
            </tr>
            <tr>
                <td colspan="2" align="center" style="padding: 10px 0"><a href="javascript: void(0)" id="saveHUD" class="button button_save_big">Submit to Title</a></td>
            </tr>
        </table>
        <input type="hidden" id="ordered_by_type" name="ordered_by_type" value="Taylor/Anne Arundel Agent">
    </form>
</div>
<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	$('#prop_zip').keyup(addLoc);
	$('.chosen-custom').chosen({ allow_single_deselect: true });
	$('.currency').keyup(function() {
        $('.currency').formatCurrency({roundToDecimalPlace: 0 });
    });
	$('#purchase_type').change(function(){
		if($(this).val() == 'Cash') { 
			$('.loanTR').hide();
		} else {
			$('.loanTR').show();
		}
	});
	$('#saveHUD').click(function(e){
		e.preventDefault();
		$(this).text('Sending Request');
		saveHUD();
	});
});
$('input, textarea').change(function(){
	$('#saveHUD').removeAttr('disabled');
});

function saveHUD(){
	$('#saveHUD').attr('disabled', 'disabled');
	var errors = 'no';
	$('#errorTR').remove();
	$('.required').parent('td').parent('tr').children('td').css({ background: '#fff', color: '#333'});
	$('.required').not('[id$=chosen]').each(function(){
		if(errors == 'no') {
			if(($(this).attr('id') == 'loan_amount' || $(this).attr('id') == 'closing_help') && $('#purchase_type').val() == 'Cash') {
				var a = 'b';
			} else {
				if($(this).val() == '') {
					$(this).parent('td').parent('tr').prevAll('.secHeaderTR:first').after('<tr id="errorTR"><td colspan="2" align="center"><div id="errorDiv" class="round"><table><tr><td><img src="/images/warning.png"></td><td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Error - '+$(this).attr('mes')+'</td></tr></table></div></td></tr>');
					$('html, body').animate({scrollTop:$('#errorTR').offset().top - 150}, 'slow');
					$(this).parent('td').parent('tr').children('td').css({ background: '#A84549', color: '#ff9'});
					errors = 'yes';
				}
			}
		}
	});
	if(errors == 'no') {
		loading_bg();
		$.ajax({
			type: "POST",
			url: "/new/ajax/title/save_hud.php",
			data: $('#hudForm').serialize(),
			success: function(){
				$('#successTR').show();
				$('html, body').animate({scrollTop:$('#successTR').offset().top - 150}, 'slow');	
				$('#saveHUD').text('Submit To Title');
				remove_loading_bg();
			}
		});
	}
}
function addLoc() {
	var zipCode = $("#prop_zip").val();
	$('#addressError').hide();
	if(zipCode.length == 5) {
		$.ajax({
			type: "GET",
			url: "/new/ajax/title/get_address.php",
			data: "prop_zip="+zipCode,
			success: function(data){
				if(!data.match(/error/)) {
					$(".addressTR").show();
					$('#prop_city').val($(data).filter('#city').text());
					$('#prop_state').val($(data).filter('#state').text());
					$('#prop_county').val($(data).filter('#county').text());
				} else {
					$(".addressTR").hide();
					$('#addressError').show();
				}
			}
		});
	} else {
		$(".addressTR").hide();
	}
}


</script>
</body>
</html>