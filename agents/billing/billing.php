<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

$agent_id = $_SESSION['S_ID'];

$agentQuery = "select *, year(start_date) as y from company.tbl_agents".$test." where id = '".$agent_id."'";
$agent = $db -> select($agentQuery);

if($agent[0]['auto_bill'] == 'on') {
	$auto_bill = 'yes';
} else {
	$auto_bill = 'no';
}
if($agent[0]['commission_plan'] == 'a') {
	$yearly = 'Yearly';
} else if($agent[0]['commission_plan'] == 'b') {
	$yearly = 'Bi-Yearly';
}
if($agent[0]['bill_amount'] > 0) {
	if($agent[0]['bill_cycle'] == 'monthly') {
		$bill_details = 'Monthly Dues and '.$yearly.' E&amp;O Insurance';
	} else if($agent[0]['bill_cycle'] == 'quarterly') {
		$bill_details = 'Quarterly Dues and '.$yearly.' E&amp;O Insurance';
	}
} else {
	$bill_details = $yearly.' E&amp;O Insurance';
}

if($agent[0]['y'] > '2010') {
	$y_start = $agent[0]['y'];
} else {
	$y_start = '2010';
}
$y_end = date('Y', strtotime('-1 year'));


$cal_start = '2015-01-01';

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Billing</title>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
.body_container {
	font-size: 16px;
}
.div_header { font-size:18px;font-weight:bold;color:#d18359;margin-bottom:10px; }
.agent_billing_div {
	width: 100%;
	display: block;
	border:1px solid #d18359;
	padding:10px;
	margin-bottom:20px
}
.agent_billing_head {
	padding: 5px;
}
.balance_td {
	font-size: 22px;
	font-weight: bold
}
#cc_table td:not(.no_border) {
	font-size: 14px;
	font-weight: bold;
	border-bottom: 1px dotted #cccccc;
	padding-bottom: 5px;
}
#add_payment_div {
	display: none;
	position: fixed;
	left: 50%;
	top: 200px;
	margin-left: -340px;
	width: 660px;
	height: auto;
	overflow: visible;
	background: #fff;
	border: 10px solid #3A4A63;
	z-index: 25000;
	padding-bottom: 15px;
}
#add_cc_div, #edit_cc_div {
	display: none;
	position: fixed;
	left: 50%;
	top: 60px;
	margin-left: -340px;
	width: 660px;
	height: auto;
	overflow: visible;
	background: #fff;
	border: 10px solid #3A4A63;
	z-index: 25000;
	padding-bottom: 15px;
}
.add_cc_table td {
	padding: 3px 12px 0 0;
}
#amount_td {
	border: 1px solid #034206;
	background: #036B08;
	color: #fff;
	padding: 5px 15px 15px 15px
}
#p_ccs_table td {
	padding: 5px 5px 5px 0;
}
.payment_divs {
	display: none
}
#card_on_file_div, #new_card_div {
	display: none;
}
#cc_options {
	margin: 30px 0 15px 0;
}
#cc_options td {
	text-align: center
}
#zip_error, #add_cc_zip_error, #edit_cc_zip_error {
	width: 125px;
	padding: 10px 0 10px 40px;
	font-size: 16px;
	background: #C65155 url('/new/images/icons/warning_white_small.png') no-repeat 8px 50%;
	color: #fff;
	display: none;
}
#billing_error_div {
	display: none;
	position: absolute;
	top: 35px;
	right: 15px;
	font-size: 16px;
	font-weight: bold;
	width: 250px;
	height: auto;
	background: #C65155;
	color: #fff;
	padding: 10px;
}
#edit_cc_billing_error_div {
	display: none;
	position: absolute;
	top: 55px;
	right: 15px;
	font-size: 16px;
	font-weight: bold;
	width: 250px;
	height: auto;
	background: #C65155;
	color: #fff;
	padding: 10px;
}
#add_cc_billing_error_div {
	display: none;
	position: absolute;
	top: 110px;
	right: 15px;
	font-size: 16px;
	font-weight: bold;
	width: 250px;
	height: auto;
	background: #C65155;
	color: #fff;
	padding: 10px;
}
#billing_error2_div {
	display: none;
	position: absolute;
	top: -12px;
	right: 15px;
	font-size: 16px;
	font-weight: bold;
	width: 250px;
	height: auto;
	background: #C65155;
	color: #fff;
	padding: 10px;
}
#main_error_div {
	display: none;
	position: fixed;
	left: 50%;
	top: 200px;
	margin-left: -318px;
	width: 600px;
	height: auto;
	background: #fff;
	z-index: 50000;
	padding: 8px;
	border: 10px solid #C65155;
}
#success_div {
	display: none;
	position: fixed;
	left: 50%;
	top: 200px;
	margin-left: -268px;
	width: 500px;
	height: auto;
	background: #fff;
	z-index: 25000;
	padding: 8px;
	border: 10px solid #036B05;
}
#auto_bill_message_div {
	display: none;
	position: fixed;
	left: 50%;
	top: 200px;
	margin-left: -273px;
	width: 500px;
	height: auto;
	background: #fff;
	z-index: 25000;
	padding: 8px;
	border: 10px solid #3A4A63;
}
#auto_bill_message {
	width: 90%;
	margin: 0 auto;
	padding: 10px;
	font-size: 17px;
	text-align: center;
}
#main_error_sub_div {
	padding: 20px;
	background: #C65155;
	color: #fff;
	font-size: 16px;
}
#success_sub_div {
	padding: 20px;
	background: #036B05;
	color: #fff;
	font-size: 20px;
	font-weight: bold;
}
#auto_bill_div {
	width: 100%;
	display: block;
	padding: 10px;
	border: 1px solid #ccc;
	margin-bottom: 10px;
}
.no_default_card {
	display: none;
}

#expense_report_div, #commission_report_div { 
	display: none;
	position: absolute;
	left: 50%;
	top: 200px;
	margin-left: -373px;
	width: 700px;
	height: auto;
	background: #fff;
	z-index: 25000;
	border: 10px solid #3A4A63;
}
#report_table td { padding: 5px; }
#list_of_reports td { border-bottom: 1px dotted #ccc; padding: 5px; }
label:hover { cursor: pointer;}
#error_div { display:none; width: 300px; height:auto; padding: 10px; background: #C65155; font-weight:bold; color: #fff; text-align: center; <?php echo radius(3,3,3,3); ?> }
.datepickers { width: 135px; text-align:center; padding: 15px !important; font-size: 17px !important; }
</style>
</head>
<body>
<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>
<div class="body_container">
    <h2>Billing</h2>
    <br><br>
    <table width="100%">
    	<tr>
        	<td width="49%" valign="top">
                
                <?php if($_AGENT_billed == 'yes') { ?>
                <div class="agent_billing_div keep_width">
                    <div class="div_header"><?php echo $_AGENT_bill_cycle; ?> Dues</div>
                    <div class="agent_billing_head">
                        <table width="100%">
                            <tr>
                                <td width="175" class="balance_td">Dues Balance</td>
                                <td width="200" class="balance_td" id="agent_balance_dues"><?php echo $_AGENT_balance; ?></td>
                                <td rowspan="3" align="right"><a href="javascript: void(0)" class="button button_bill" t="dues" style="font-size:18px; padding: 13px 13px 13px 35px;">Make Payment</a></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php 
								if($_AGENT_bill_cycle != 'None') {
                                    echo $bill_details_dues;
                                }  
								?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php } ?>
                <div class="agent_billing_div keep_width">
                    <div class="div_header">Errors &amp; Ommissions Fees</div>
                    <div class="agent_billing_head">
                        <table width="100%">
                            <tr>
                                <td width="175" class="balance_td">E&amp;O Balance</td>
                                <td width="200" class="balance_td" id="agent_balance_eno"><?php echo $_AGENT_balance_eno; ?></td>
                                <td rowspan="3" align="right"><a href="javascript: void(0)" class="button button_bill" t="eno" style="font-size:18px; padding: 13px 13px 13px 35px;">Make Payment</a></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?php echo $bill_details_eno; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="agent_billing_div keep_width">
                <div class="div_header">Create Expense and Commission Reports</div>
                    <a href="javascript: void(0)" class="button button_reports" id="open_reports_expenses_button" style="width: 150px;">Expense Reports</a> Create yearly reports and print multiple invoices
                    <br><br>
                    <a href="javascript: void(0)" class="button button_reports" id="open_reports_commission_button" style="width: 150px;">Commission Reports</a> Create reports of commission paid to you
                </div>
                
            </td>
            <td width="2%"></td>
            <td width="49%" valign="top">
            	<div class="agent_billing_div keep_width">
                <div class="div_header">Automated Payments</div>
                    <table width="100%">
                        <tr>
                            <td width="300" style="padding-right: 10px">Automatically deduct your dues from your default credit card.</td>
                            <td valign="top" tt="left" class="tooltip no_border" title="For: <?php echo $bill_details; ?>.<br>Your Default Credit Card will be automatically billed for each billing period."><img src="/new/images/icons/info.png" id="info_image"></td>
                            <td style="padding-left:50px"><div id="auto_bill_button_div">
                                    <input type="radio" name="auto_bill" value="on" id="auto_bill_on" <?php if($auto_bill == 'yes') { echo 'checked="checked"'; } ?>>
                                    <label for="auto_bill_on">On</label>
                                    <input type="radio" name="auto_bill" value="off" id="auto_bill_off" <?php if($auto_bill == 'no') { echo 'checked="checked"'; } ?>>
                                    <label for="auto_bill_off">Off</label>
                                </div></td>
                            <td style="font-size:13px;"><div id="autobill_on" style="display:none;">Automated payments are <span style="font-weight:bold; color: #065A06">"ON"</span></div>
                                <div id="autobill_off" style="display:none;">Automated payments are <span style="font-weight:bold; color: #A84549">"OFF"</span></div></td>
                        </tr>
                    </table>
                    <div class="no_default_card">
                        <table>
                            <tr>
                                <td><img src="/new/images/icons/warning_red_new.png" height="18"></td>
                                <td style="padding-left: 8px; font-size:12px;">You do not have a "Default" Credit Card. <span id="cards"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="agent_billing_div keep_width">
                    <div class="div_header">Credit Cards</div>
                    <div class="agent_billing_head">
                        <div style="float: right; display: block; margin-bottom: 15px;"><a href="javascript: void(0);" class="button button_add" id="add_cc_button">Add Card</a></div>
                        <div class="spacer"></div>
                        <div id="cc_div" style="max-height: 210px; overflow:auto;"></div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

<div class="agent_billing_div keep_width">
    <div class="div_header" id="invoice_sec">Payments / Charges</div>
    
    <div id="invoice_list_div" style="width: 80%; margin: 0 auto; min-height: 300px">
        <ul>
            <li><a href="/new/agents/ajax/billing/invoices/invoice_list.php?agent_id=<?php echo $agent_id; ?>">Dues/General</a></li>
            <li><a href="/new/agents/ajax/billing/invoices/invoice_list_eno.php?agent_id=<?php echo $agent_id; ?>">E&amp;O</a></li>
        </ul>
    </div>
</div>
</div>
<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

<!-- Reports Div -->

<div id="expense_report_div" class="blackout draggable">
	<table width="100%" cellpadding="0" cellspacing="0" class="draggable_handle">
        <tr>
            <td class="popup_div_head" width="15%" style="padding-bottom:10px;"></td>
            <td class="popup_div_head" style="padding-bottom:10px;">Expense Reports</td>
            <td class="popup_div_head" width="15%" align="right" style="padding-bottom:10px;"><a href="javascript: void(0);" class="button button_cancel" id="close_expense_report_div">&nbsp;</a></td>
        </tr>
    </table>
    <div style="font-size:16px; padding:10px;">
        <table class="report_table" width="100%">
        	<tr>
            	<td class="report_header">Get a list of all charges and payments to your account with our company.</td>
            </tr>
        	<tr>
            	<td><strong>Select Year</strong>
                    <br>
                    <select id="year" class="chosen-custom" style="width: 125px;">
                    <option value=""></option>
                    <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                    <?php for($y=$y_end;$y>=$y_start;$y--) { ?>
                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                    <?php } ?>
                    </select>
        		</td>
                <td><div id="error_div"></div></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Select Type of Report</strong>
                	<br>
                	<table id="list_of_reports" style="border-top: 1px solid #ccc;" width="100%" cellspacing="0">
                    	<tr>
                        	<td width="30"><input name="report_options" type="radio" id="report_a" value="statement both" class="radio_styled"><label class="label_styled" for="report_a"></label></td>
                            <td><label for="report_a">Line Statement - Charges and Payments</label></td>
                        </tr>
                        <tr>
                        	<td width="30"><input name="report_options" type="radio" id="report_b" value="statement payments" class="radio_styled"><label class="label_styled" for="report_b"></label></td>
                            <td><label for="report_b">Line Statement - Payments Only</label></td>
                        </tr>
                        <tr>
                        	<td width="30"><input name="report_options" type="radio" id="report_c" value="statement charges" class="radio_styled"><label class="label_styled" for="report_c"></label></td>
                            <td><label for="report_c">Line Statement - Charges Only</label></td>
                        </tr>
                        <tr>
                        	<td width="30"><input name="report_options" type="radio" id="report_d" value="invoice both" class="radio_styled"><label class="label_styled" for="report_d"></label></td>
                            <td><label for="report_d">Full Invoices - Charges and Payments</label></td>
                        </tr>
                        <tr>
                        	<td width="30"><input name="report_options" type="radio" id="report_e" value="invoice payments" class="radio_styled"><label class="label_styled" for="report_e"></label></td>
                            <td><label for="report_e">Full Invoices - Payments Only</label></td>
                        </tr>
                        <tr>
                        	<td width="30"><input name="report_options" type="radio" id="report_f" value="invoice charges" class="radio_styled"><label class="label_styled" for="report_f"></label></td>
                            <td><label for="report_f">Full Invoices - Charges Only</label></td>
                        </tr>
                    </table>                
                </td>
            </tr>	
            <tr>
            	<td colspan="2" align="center"><a href="javascript: void(0)" id="print_expense_report" class="button button_export">Create Report</a></td>
            </tr>
        </table>
        
        

       
    </div>
</div>


<div id="commission_report_div" class="blackout draggable">
	<table width="100%" cellpadding="0" cellspacing="0" class="draggable_handle">
        <tr>
            <td class="popup_div_head" width="15%" style="padding-bottom:10px;"></td>
            <td class="popup_div_head" style="padding-bottom:10px;">Commission Reports</td>
            <td class="popup_div_head" width="15%" align="right" style="padding-bottom:10px;"><a href="javascript: void(0);" class="button button_cancel" id="close_commission_report_div">&nbsp;</a></td>
        </tr>
    </table>
    <div style="font-size:16px; padding:30px;">
        <table class="report_table" width="100%">
        	<tr>
            	<td colspan="3" class="report_header">Get a list of all commissions paid to you.</td>
            </tr>
            <tr>
            	<td colspan="3" style="padding-bottom: 15px;"><strong>Select Date Range</strong></td>
            </tr>
            <tr>
                <td width="140">Start Date</td>
                <td width="140">End Date</td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-right: 10px" valign="top"><input type="text" id="commission_start" class="datepickers"></td>
                <td style="padding-right: 10px" valign="top"><input type="text" id="commission_end" class="datepickers"></td>
                <td valign="top"><a href="javascript: void(0)"  id="print_commission_report" class="button button_export" style="padding-top: 17px; padding-bottom:17px;">Create Report</a></td>
            </tr>
        </table>
	</div>
</div>



<!-- Add Payment Div -->
<div id="add_payment_div" class="blackout draggable">
    <div class="spacer"></div>
    <table width="100%" cellpadding="0" cellspacing="0" class="draggable_handle">
        <tr>
            <td class="popup_div_head" width="15%" style="padding-bottom:10px;"></td>
            <td class="popup_div_head" style="padding-bottom:10px;">Make <span id="make_payment_text"></span> Payment</td>
            <td class="popup_div_head" width="15%" align="right" style="padding-bottom:10px;"><a href="javascript: void(0);" class="button button_cancel" id="close_add_payment_div">&nbsp;</a></td>
        </tr>
    </table>
    <table id="cc_options" width="100%">
        <tr>
            <td><input type="radio" class="radio_styled" name="pay_with" value="existing_card" id="pay_with_existing">
                <label class="label_styled" for="pay_with_existing">Use Existing Credit Card</label></td>
            <td><input type="radio" class="radio_styled" name="pay_with" value="new_card" id="pay_with_new">
                <label class="label_styled" for="pay_with_new">Use New Credit Card</label></td>
        </tr>
    </table>
    <hr class="payment_divs" id="payment_hr">
    <div style="padding: 15px; display:none;" id="payment_container">
        <div id="card_on_file_div" class="payment_divs" style="position:relative">
            <div id="billing_error2_div" class="round5">
                <table width="100%">
                    <tr>
                        <td><img src="/new/images/icons/warning_white.png"></td>
                        <td style="padding-left: 10px; text-align:center;"><div id="billing_error2_text"></div></td>
                    </tr>
                </table>
            </div>
            <div id="p_ccs"></div>
        </div>
        <div id="new_card_div" class="payment_divs" style="position:relative; width: 100%">
            <div id="billing_error_div" class="round5">
                <table width="100%">
                    <tr>
                        <td><img src="/new/images/icons/warning_white.png"></td>
                        <td style="padding-left: 10px; text-align:center;"><div id="billing_error_text"></div></td>
                    </tr>
                </table>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="font-weight:bold; padding: 5px; font-size: 15px; border-bottom: 1px solid #ccc;">Enter the Credit Card details</td>
                </tr>
                <tr>
                    <td><table class="add_cc_table" width="100%">
                            <tr>
                                <td>First Name<br>
                                    <input tabindex="1" type="text" id="first" style="width: 90px"></td>
                                <td>Last Name<br>
                                    <input tabindex="2" type="text" id="last" style="width: 90px"></td>
                                <td width="50%" colspan="2"></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td><table class="add_cc_table">
                            <tr>
                                <td>Billing Street<br>
                                    <input tabindex="3" type="text" id="street" style="width: 200px;"></td>
                                <td>Billing Zip<br>
                                    <input tabindex="4" type="text" id="zip" style="width: 50px;" class="numbers_only" maxlength="5" value=""></td>
                                <td><div id="zip_error" class="round5">Invalid Zip Code</div></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td><table class="add_cc_table">
                            <tr>
                                <td class="chosen_height_td_ccs_type">Card Type<br>
                                    <select tabindex="5" id="card_type" class="chosen-custom" style="width: 125px;" data-placeholder="">
                                        <option value=""></option>
                                        <option value="Visa">Visa</option>
                                        <option value="Master Card">Master Card</option>
                                        <option value="Discover">Discover</option>
                                    </select></td>
                                <td>Card Number<br>
                                    <input tabindex="6" type="text" id="number" class="numbers_only" maxlength="16" style="width: 125px;" value="" placeholder=""></td>
                                <td class="chosen_height_td_ccs">Card Expire<br>
                                    <select tabindex="7" id="expire_month" class="chosen-custom" style="width: 120px;" data-placeholder="Month">
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
                                    <select tabindex="8" id="expire_year" class="chosen-custom" style="width: 100px;" data-placeholder="Year">
                                        <option value=""></option>
                                        <?php for($y=date("Y");$y<date("Y")+12;$y++) { ?>
                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                        <?php } ?>
                                    </select></td>
                                <td>CVV Code<br>
                                    <input tabindex="9" type="text" id="code" class="numbers_only" maxlength="4" style="width: 30px;" placeholder=""></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td><table>
                            <tr>
                                <td style="font-weight: bold; color: #A84549">Set as Default Card?</td>
                                <td><input type="checkbox" id="default_card" value="yes" checked="checked"></td>
                                <td tt="left" class="tooltip no_border" title="<u>Default Card</u><br>This is the card that will be used for any Dues or E&amp;O Automated Payments.<br>Your Default Credit Card will be automatically billed for each billing period."><img src="/new/images/icons/info.png" id="info_image"></td>
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
                    <td><table width="100%">
                            <tr>
                                <td width="40%" style="font-size:15px; text-align:center;"><div style="border-top:1px solid #ccc; padding: 10px; border-bottom:1px solid #ccc; padding: 10px; background: #EBE6E6"> Current Balance: <span id="current_balance" style="font-weight: bold; font-size:16px;"></span> </div></td>
                                <td align="center" id="amount_td" class="round5">Amount<br>
                                    <input type="text" id="amount" style="width: 60px; text-align:center; font-size: 17px; font-weight:bold"></td>
                                <td width="40%" align="center"><a href="javascript:void(0);" id="charge_payment_button" class="button button_normal" style="font-size:18px; font-weight:bold; padding: 18px;">Submit Payment</a></td>
                            </tr>
                        </table></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Add Credit Card Div -->
<div id="add_cc_div" class="blackout draggable">
    <div class="spacer"></div>
    <table width="100%" cellpadding="0" cellspacing="0" class="draggable_handle">
        <tr>
            <td class="popup_div_head" width="15%" style="padding-bottom:10px;"></td>
            <td class="popup_div_head" style="padding-bottom:10px;">Add Credit Card</td>
            <td class="popup_div_head" width="15%" align="right" style="padding-bottom:10px;"><a href="javascript: void(0);" class="button button_cancel" id="close_add_cc_div">&nbsp;</a></td>
        </tr>
    </table>
    <div style="padding: 15px; position:relative">
        <div id="add_cc_billing_error_div" class="round5">
            <table width="100%">
                <tr>
                    <td><img src="/new/images/icons/warning_white.png"></td>
                    <td style="padding-left: 10px; text-align:center;"><div id="add_cc_billing_error_text"></div></td>
                </tr>
            </table>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="font-weight:bold; padding: 5px; font-size: 15px; border-bottom: 1px solid #ccc;">Enter the Credit Card details</td>
            </tr>
            <tr>
                <td><table class="add_cc_table" width="100%">
                        <tr>
                            <td>First Name<br>
                                <input tabindex="1" type="text" id="add_cc_first" style="width: 90px"></td>
                            <td>Last Name<br>
                                <input tabindex="2" type="text" id="add_cc_last" style="width: 90px"></td>
                            <td width="50%" colspan="2"></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><table class="add_cc_table">
                        <tr>
                            <td>Billing Street<br>
                                <input tabindex="3" type="text" id="add_cc_street" style="width: 200px;"></td>
                            <td>Billing Zip<br>
                                <input tabindex="4" type="text" id="add_cc_zip" style="width: 50px;" class="numbers_only" maxlength="5" value=""></td>
                            <td><div id="add_cc_zip_error" class="round5">Invalid Zip Code</div></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><table class="add_cc_table">
                        <tr>
                            <td class="chosen_height_td_ccs_type">Card Type<br>
                                <select tabindex="5" id="add_cc_card_type" class="chosen-custom" style="width: 125px;" data-placeholder="">
                                    <option value=""></option>
                                    <option value="Visa">Visa</option>
                                    <option value="Master Card">Master Card</option>
                                    <option value="Discover">Discover</option>
                                </select></td>
                            <td>Card Number<br>
                                <input tabindex="6" type="text" id="add_cc_number" class="numbers_only" maxlength="16" style="width: 125px;" value="" placeholder=""></td>
                            <td class="chosen_height_td_ccs">Card Expire<br>
                                <select tabindex="7" id="add_cc_expire_month" class="chosen-custom" style="width: 120px;" data-placeholder="Month">
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
                                <select tabindex="8" id="add_cc_expire_year" class="chosen-custom" style="width: 100px;" data-placeholder="Year">
                                    <option value=""></option>
                                    <?php for($y=date("Y");$y<date("Y")+12;$y++) { ?>
                                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                    <?php } ?>
                                </select></td>
                            <td>CVV Code<br>
                                <input tabindex="9" type="text" id="add_cc_code" class="numbers_only" maxlength="4" style="width: 30px;" placeholder=""></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><table>
                        <tr>
                            <td style="font-weight: bold; color: #A84549">Set as Default Card?</td>
                            <td><input type="checkbox" id="add_cc_default_card" value="yes" checked="checked"></td>
                            <td tt="left" class="tooltip no_border" title="<u>Default Card</u><br>This is the card that will be used for any Dues or E&amp;O Automated Payments.<br>Your Default Credit Card will be automatically billed for each billing period."><img src="/new/images/icons/info.png" id="info_image"></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td align="center"><a href="javascript:void(0);" id="save_add_card_button" class="button button_normal" style="font-size:18px; font-weight:bold; padding: 18px;">Save Credit Card</a></td>
            </tr>
        </table>
    </div>
</div>
<div id="main_error_div" class="blackout">
    <table width="100%">
        <tr>
            <td align="right"><a href="javascript: void(0);" id="close_main_error" class="button button_cancel_s"></a></td>
        </tr>
        <tr>
            <td><div id="main_error_sub_div" class="round5">
                    <table width="100%">
                        <tr>
                            <td width="50"><img src="/new/images/icons/warning_white.png"></td>
                            <td align="center"><div id="main_error_text"></div></td>
                        </tr>
                    </table>
                </div></td>
        </tr>
    </table>
</div>

<!-- Edit Credit Card Div -->
<div id="edit_cc_div" class="blackout draggable">
    <div class="spacer"></div>
    <table width="100%" cellpadding="0" cellspacing="0" class="draggable_handle">
        <tr>
            <td class="popup_div_head" width="15%" style="padding-bottom:10px;"></td>
            <td class="popup_div_head" style="padding-bottom:10px;">Edit Credit Card</td>
            <td class="popup_div_head" width="15%" align="right" style="padding-bottom:10px;"><a href="javascript: void(0);" class="button button_cancel" id="close_edit_cc_div">&nbsp;</a></td>
        </tr>
    </table>
    <div style="padding: 15px; position:relative">
        <div id="edit_cc_billing_error_div" class="round5">
            <table width="100%">
                <tr>
                    <td><img src="/new/images/icons/warning_white.png"></td>
                    <td style="padding-left: 10px; text-align:center;"><div id="edit_cc_billing_error_text"></div></td>
                </tr>
            </table>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="font-weight:bold; padding: 5px; font-size: 15px; border-bottom: 1px solid #ccc;">Enter the Credit Card details</td>
            </tr>
            <tr>
                <td><table class="add_cc_table" width="100%">
                        <tr>
                            <td>First Name<br>
                                <input tabindex="1" type="text" id="edit_cc_first" style="width: 90px"></td>
                            <td>Last Name<br>
                                <input tabindex="2" type="text" id="edit_cc_last" style="width: 90px"></td>
                            <td width="50%" colspan="2"></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><table class="add_cc_table">
                        <tr>
                            <td>Billing Street<br>
                                <input tabindex="3" type="text" id="edit_cc_street" style="width: 200px;"></td>
                            <td>Billing Zip<br>
                                <input tabindex="4" type="text" id="edit_cc_zip" style="width: 50px;" class="numbers_only" maxlength="5" value=""></td>
                            <td><div id="edit_cc_zip_error" class="round5">Invalid Zip Code</div></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><table class="add_cc_table">
                        <tr>
                            <td class="chosen_height_td_ccs_type" style="font-size:18px; font-weight:bold;"><span id="edit_cc_card_type_text"></span></td>
                            <td>Card Number<br>
                                <input tabindex="6" type="text" id="edit_cc_number" class="numbers_only" maxlength="16" style="width: 125px;" value="" placeholder=""></td>
                            <td class="chosen_height_td_ccs">Card Expire<br>
                                <select tabindex="7" id="edit_cc_expire_month" class="chosen-custom" style="width: 120px;" data-placeholder="Month">
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
                                <select tabindex="8" id="edit_cc_expire_year" class="chosen-custom" style="width: 100px;" data-placeholder="Year">
                                    <option value=""></option>
                                    <?php for($y=date("Y");$y<date("Y")+12;$y++) { ?>
                                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                    <?php } ?>
                                </select></td>
                            <td>CVV Code<br>
                                <input tabindex="9" type="text" id="edit_cc_code" class="numbers_only" maxlength="4" style="width: 30px;" placeholder=""></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td><table>
                        <tr>
                            <td style="font-weight: bold; color: #A84549">Set as Default Card?</td>
                            <td><input type="checkbox" id="edit_cc_default_card" value="yes" checked="checked"></td>
                            <td tt="left" class="tooltip no_border" title="<u>Default Card</u><br>This is the card that will be used for any Dues or E&amp;O Automated Payments.<br>Your Default Credit Card will be automatically billed for each billing period."><img src="/new/images/icons/info.png" id="info_image"></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td align="center"><a href="javascript:void(0);" id="save_edit_card_button" class="button button_normal" style="font-size:18px; font-weight:bold; padding: 18px;">Save Credit Card</a></td>
            </tr>
        </table>
        <input type="hidden" id="pay_profile_id">
        <input type="hidden" id="edit_cc_card_type">
    </div>
</div>
<div id="main_error_div" class="blackout">
    <table width="100%">
        <tr>
            <td align="right"><a href="javascript: void(0);" id="close_main_error" class="button button_cancel_s"></a></td>
        </tr>
        <tr>
            <td><div id="main_error_sub_div" class="round5">
                    <table width="100%">
                        <tr>
                            <td width="50"><img src="/new/images/icons/warning_white.png"></td>
                            <td align="center"><div id="main_error_text"></div></td>
                        </tr>
                    </table>
                </div></td>
        </tr>
    </table>
</div>
<div id="success_div" class="shadow">
    <table width="100%">
        <tr>
            <td align="right"><a href="javascript: void(0);" id="close_success" class="button button_cancel_s_green"></a></td>
        </tr>
        <tr>
            <td><div id="success_sub_div" class="round5">
                    <table width="60%" style="margin: 0 auto;">
                        <tr>
                            <td width="50"><img src="/new/images/icons/check_white.png"></td>
                            <td align="center" id="success_div_text"></td>
                        </tr>
                    </table>
                </div></td>
        </tr>
    </table>
</div>
<div id="auto_bill_message_div" class="blackout">
    <table width="100%">
        <tr>
            <td align="right"><a href="javascript: void(0);" id="close_auto_bill_message" class="button button_cancel_s"></a></td>
        </tr>
    </table>
    <div id="auto_bill_message"></div>
</div>
<input type="hidden" id="balance" value="<?php echo $agent[0]['balance']; ?>">
<input type="hidden" id="balance_eno" value="<?php echo $agent[0]['balance_eno']; ?>">
<input type="hidden" id="payment_type">
<input type="hidden" id="new_card">
<input type="hidden" id="city">
<input type="hidden" id="state">
<input type="hidden" id="add_cc_city">
<input type="hidden" id="add_cc_state">
<input type="hidden" id="edit_cc_city">
<input type="hidden" id="edit_cc_state">
<input type="hidden" id="invalid_zip" value="no">
<input type="hidden" id="add_cc_invalid_zip" value="no">
<input type="hidden" id="edit_cc_invalid_zip" value="no">
<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
	$('.chosen-custom').chosen();
	$('#auto_bill_button_div').buttonset();
	
	$('#show_invoices').click(function(){
		var an = $('#invoice_sec').offset().top;
		$('html, body').animate({scrollTop: an - 120}, "fast");
	});
	
	$('#invoice_list_div').tabs({
		beforeActivate: function( event, ui ) { 
			$('.datatable_container').remove();
		},
		activate: function( event, ui ) { 
			setTimeout(dt, 500);
		 },
		show: { effect: "blind", duration: 200 }, 
		hide: 'fade'
	});
	
	setTimeout(dt, 500);
	get_ccs();
	autobill_status();
	
	$('[name=report_options]').click(function(){
		$('#list_of_reports tr td').css({ background: '#fff' });
		$(this).closest('tr').find('td:eq(1)').css({ background: '#ccc' });
	});
	
	$('#print_expense_report').click(function(){
		$('#error_div').hide();
		if($('#year').val() == '') {
			$('#error_div').show().text('You must select a year');
			return false;
		}
		if($('[name=report_options]:checked').length == 0) {
			$('#error_div').show().text('You must select a report');
			return false;
		}
		var id = '<?php echo $_SESSION['S_ID']; ?>';
		var year = $('#year').val();
		var report = $('[name=report_options]:checked').val();
		loading_bg();
		$.ajax({
			type: 'POST',
			data: { id: id, report: report, year: year },
			url: '/new/agents/ajax/billing/reports/invoice_report.php',
			success: function(response){
				remove_loading_bg();
				window.location = 'https://annearundelproperties.net/new/ajax/file_download/file_download.php?filename='+response+'&fileloc=https://annearundelproperties.net/new/agents/ajax/billing/temp/'+response;
			}
		});		
	});
	
	$('#print_commission_report').click(function(){
		if($('#commission_start').val() == '') {
			$('<div style="width: 100%; color: #8C4E50; font-size: 15px; font-weight: bold;" class="form_error"><span style="font-size: 1.5em;">&#x2191;</span> Required Field</div>').insertAfter($('#commission_start'));
		} else {
			loading_bg();
			var start = $('#commission_start').val();
			var end = $('#commission_end').val();
			$.ajax({
				type: 'POST',
				data: { start: start, end: end },
				url: '/new/agents/ajax/billing/reports/commission_report.php',
				success: function(response){
					remove_loading_bg();
					window.location = 'https://annearundelproperties.net/new/ajax/file_download/file_download.php?filename='+response+'&fileloc=https://annearundelproperties.net/new/agents/ajax/billing/temp/'+response;
				}
			});	
		}
	});
	
	$(".datepickers").datepicker({
		numberOfMonths: 2,
		dateFormat: 'yy-mm-dd',
		gotoCurrent: true,
		showAnim: 'show',
		changeMonth: true,
		changeYear: true,
		selectOtherMonths: true,
		showOtherMonths: true,
		minDate: '<?php echo $cal_start; ?>',
		maxDate: '<?php echo date('Y-m-d'); ?>'
	});
	
	$('#open_reports_expenses_button').click(function(){
		$('#expense_report_div').fadeIn('slow');
	});
	$('#close_expense_report_div').click(function(){
		$('#expense_report_div').fadeOut('slow');
	});
	
	$('#open_reports_commission_button').click(function(){
		$('#commission_report_div').fadeIn('slow');
	});
	$('#close_commission_report_div').click(function(){
		$('#commission_report_div').fadeOut('slow');
	});
	
	
	
	$('[name=auto_bill]').click(auto_bill);
	
	$('#zip').bind('input propertychange', function(){
		$('#zip_error').hide();
		if($(this).val().length == 5) {
			set_loc();
		}
	});
	
	$('#add_cc_zip').bind('input propertychange', function(){
		$('#add_cc_zip_error').hide();
		if($(this).val().length == 5) {
			set_loc_add_cc();
		}
	});
	$('#edit_cc_zip').bind('input propertychange', function(){
		$('#edit_cc_zip_error').hide();
		if($(this).val().length == 5) {
			set_loc_edit_cc();
		}
	});
		
	$('.button_bill').click(make_payment);
	$('#charge_payment_button').click(submit_payment);
	
	$('#add_cc_button').click(add_cc);
	$('#close_add_cc_div').click(function(){
		$('#add_cc_div').fadeOut('slow');
	});
	$('#save_add_card_button').click(save_new_cc);
	
	$('.edit_cc_button').click(edit_cc);
	$('#close_edit_cc_div').click(function(){
		$('#edit_cc_div').fadeOut('slow');
	});
	$('#save_edit_card_button').click(save_edit_cc);
	
	//$('#amount').autoNumeric('init', {
		//aSep: ''
	//});
});

function save_new_cc(){
	
	$('#add_cc_billing_error_div, #main_error_div, #add_cc_zip_error').hide();
	
	var data = { agent_id: '<?php echo $agent_id; ?>' }
	data.first = $('#add_cc_first').val();
	data.last = $('#add_cc_last').val();
	data.street = $('#add_cc_street').val();
	data.city = $('#add_cc_city').val();
	data.state = $('#add_cc_state').val();
	data.zip = $('#add_cc_zip').val();
	data.card_type = $('#add_cc_card_type').val();
	data.number = $('#add_cc_number').val();
	data.expire_month = $('#add_cc_expire_month').val();
	data.expire_year = $('#add_cc_expire_year').val();
	data.code = $('#add_cc_code').val();
	if($('#add_cc_default_card:checked').length > 0) {
		data.default_card = 'yes';
	} else {
		data.default_card = 'no';
	}
	
	if($('#add_cc_first').val() == '') { 
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter a First Name');
		$('#add_cc_first').focus();
		return false;
	}
	
	if($('#add_cc_last').val() == '') { 
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter a Last Name');
		$('#add_cc_last').focus();
		return false;
	}
	
	if($('#add_cc_street').val() == '') { 
		$('add_cc_#billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter the Street Address');
		$('#add_cc_street').focus();
		return false;
	}
	
	if($('#add_cc_invalid_zip').val() == 'yes' || $('#add_cc_zip').val() == '') {
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You have entered an invalid Zip Code');
		$('#add_cc_zip').focus();
		return false;
	}
	
	if($('#add_cc_card_type').val() == '') { 
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter the Card Type');
		$('#add_cc_card_type').trigger('chosen:open');
		return false;
	}
	
	if($('#add_cc_number').val() == '') { 
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter the Card Number');
		$('#add_cc_number').focus();
		return false;
	}
	
	if($('#add_cc_expire_month').val() == '') { 
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter the Expiration Month');
		$('#add_cc_expire_month').trigger('chosen:open');
		return false;
	}
	
	if($('#add_cc_expire_year').val() == '') { 
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter the Expiration Year');
		$('#add_cc_expire_year').trigger('chosen:open');
		return false;
	}
	
	if($('#add_cc_code').val() == '') { 
		$('#add_cc_billing_error_div').show();
		$('#add_cc_billing_error_text').text('You must enter the CVV Code');
		$('#add_cc_code').focus();
		return false;
	}
	
	loading_bg();
	
	$.ajax({
		type: 'POST',
		data: data,
		url: '/new/agents/ajax/billing/cc/add_cc.php',
		success: function(response){
			remove_loading_bg();
			var r = $($.parseHTML(response));
			var error = r.filter('#error').text();
			
			if(error != '') {
				if(error == 'A duplicate customer payment profile already exists.') {
					error = 'There is already an existing card on file with that number.';
				} else if(error == 'You cannot add more than 10 payment profiles.') {
					error = 'You cannot have more than 10 Credit Cards on file.  Please delete at least one to proceed and make a payment';
				}
				$('#main_error_div').fadeIn('slow');
				$('#main_error_text').html('<span style="color: #ff9; font-weight: bold; line-height: 150%;">There was an error adding your Credit Card.</span><br>'+error);
				$('#close_main_error').click(function(){
					$('#main_error_div').fadeOut('slow');
					$('#main_error_text').text('');
				});
			} else {
				$('#add_cc_div').hide();
				$('#success_div').fadeIn();
				$('#success_div_text').text('Credit Card Successfully Added');
				$('#close_success').click(function(){
					$('#success_div').fadeOut('slow');
				});
				get_ccs();
			}
		}
	});
}

function save_edit_cc(){
	
	$('#edit_cc_billing_error_div, #main_error_div, #edit_cc_zip_error').hide();
	
	var data = { agent_id: '<?php echo $agent_id; ?>' }
	data.pay_profile_id = $('#pay_profile_id').val();
	data.first = $('#edit_cc_first').val();
	data.last = $('#edit_cc_last').val();
	data.street = $('#edit_cc_street').val();
	data.city = $('#edit_cc_city').val();
	data.state = $('#edit_cc_state').val();
	data.zip = $('#edit_cc_zip').val();
	data.card_type = $('#edit_cc_card_type').val();
	data.number = $('#edit_cc_number').val();
	data.expire_month = $('#edit_cc_expire_month').val();
	data.expire_year = $('#edit_cc_expire_year').val();
	data.code = $('#edit_cc_code').val();
	if($('#edit_cc_default_card:checked').length > 0) {
		data.default_card = 'yes';
	} else {
		data.default_card = 'no';
	}
	
	if($('#edit_cc_first').val() == '') { 
		$('#edit_cc_billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You must enter a First Name');
		$('#edit_cc_first').focus();
		return false;
	}
	
	if($('#edit_cc_last').val() == '') { 
		$('#edit_cc_billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You must enter a Last Name');
		$('#edit_cc_last').focus();
		return false;
	}
	
	if($('#edit_cc_street').val() == '') { 
		$('edit_cc_#billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You must enter the Street Address');
		$('#edit_cc_street').focus();
		return false;
	}
	
	if($('#edit_cc_invalid_zip').val() == 'yes' || $('#edit_cc_zip').val() == '') {
		$('#edit_cc_billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You have entered an invalid Zip Code');
		$('#edit_cc_zip').focus();
		return false;
	}
	
	if($('#edit_cc_number').val() == '') { 
		$('#edit_cc_billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You must enter the Card Number');
		$('#edit_cc_number').focus();
		return false;
	}
	
	if($('#edit_cc_expire_month').val() == '') { 
		$('#edit_cc_billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You must enter the Expiration Month');
		$('#edit_cc_expire_month').trigger('chosen:open');
		return false;
	}
	
	if($('#edit_cc_expire_year').val() == '') { 
		$('#edit_cc_billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You must enter the Expiration Year');
		$('#edit_cc_expire_year').trigger('chosen:open');
		return false;
	}
	
	if($('#edit_cc_code').val() == '') { 
		$('#edit_cc_billing_error_div').show();
		$('#edit_cc_billing_error_text').text('You must enter the CVV Code');
		$('#edit_cc_code').focus();
		return false;
	}
	
	loading_bg();
	
	$.ajax({
		type: 'POST',
		data: data,
		url: '/new/agents/ajax/billing/cc/edit_cc.php',
		success: function(response){
			remove_loading_bg();
			var r = $($.parseHTML(response));
			var error = r.filter('#error').text();
			
			if(error != '') {
				$('#main_error_div').fadeIn('slow');
				$('#main_error_text').html('<span style="color: #ff9; font-weight: bold; line-height: 150%;">There was an error updating your Credit Card.</span><br>'+error);
				$('#close_main_error').click(function(){
					$('#main_error_div').fadeOut('slow');
					$('#main_error_text').text('');
				});
			} else {
				$('#edit_cc_div').fadeOut('slow');
				$('#success_div').fadeIn();
				$('#success_div_text').text('Credit Card Successfully Updated');
				$('#close_success').click(function(){
					$('#success_div').fadeOut('slow');
				});
				get_ccs();
			}
		}
	});
}

function add_cc(){
	$('#add_cc_div').fadeIn('slow');	
}
function edit_cc(){
	$('#edit_cc_div').fadeIn('slow');
	var pay_profile_id = $(this).attr('payprofileid');
	var profile_id = $(this).attr('profileid');
	var first = $(this).attr('ccfirst');
	var last = $(this).attr('cclast');
	var street = $(this).attr('street');
	var city = $(this).attr('city');
	var state = $(this).attr('state');
	var zip = $(this).attr('zip');
	var cvv = $(this).attr('cvv');
	var last_four = $(this).attr('lastfour');
	var card_type = $(this).attr('cardtype');
	
	$('#edit_cc_first').val(first);
	$('#edit_cc_last').val(last);
	$('#edit_cc_street').val(street);
	$('#edit_cc_city').val(city);
	$('#edit_cc_state').val(state);
	$('#edit_cc_zip').val(zip);
	$('#edit_cc_code').val(cvv);
	$('#edit_cc_card_type').val(card_type);
	$('#edit_cc_card_type_text').text(card_type);
	$('#pay_profile_id').val(pay_profile_id);
	$('#edit_cc_number').prop('placeholder', '(ends with '+last_four+')');
	
}

function autobill_status(){
	var auto_bill = $('[name=auto_bill]:checked').val();
	if(auto_bill == 'on') {
		$('#autobill_on').show();
		$('#autobill_off').hide();	
	} else if(auto_bill == 'off') {
		$('#autobill_on').hide();
		$('#autobill_off').show();	
	} 
}
function auto_bill(){
	var agent_id = '<?php echo $agent_id; ?>';
	var auto_bill = $('[name=auto_bill]:checked').val();
	$.ajax({
		type: 'POST',
		context: auto_bill,
		url: '/new/agents/ajax/billing/settings/auto_bill.php',
		data: { agent_id: agent_id, auto_bill: auto_bill },
		success: function(){
			$('#auto_bill_message_div').fadeIn('slow');
			if(auto_bill == 'on') {
				get_ccs();
				$('#auto_bill_message').html('<img src="/new/images/icons/check_green_new.png" height="19"> <strong>Automated Payments has been turned on.</strong><br><br>Your <?php echo $bill_details; ?> payments will now be deducted automatically each billing period from your "Default" Credit Card.');
			} else if(auto_bill == 'off') {
				$('#auto_bill_message').html('<img src="/new/images/icons/warning_red_new.png" height="20"> <strong>Automated Payments has been turned off.</strong><br><br> You will now have to make your payments manually.');
				$('.no_default_card').hide();
			}
			$('#close_auto_bill_message').click(function(){
				$('#auto_bill_message_div').fadeOut('slow');
			});
			autobill_status();
		}
	});	
}

function submit_payment(){
	$('#billing_error_div, #billing_error_div2, #main_error_div, #zip_error').hide();
	var t = $('#payment_type').val();
	if(t == 'dues') {
		var desc = 'Dues/General Payment';
		var invoice_type = 'payment';
	} else if(t == 'eno') {
		var desc = 'E&O Payment';
		var invoice_type = 'paymentEno';
	}
	
	var new_card = $('#new_card').val();
	var amount =  $('#amount').val();

	var payment_type = $('#payment_type').val();
	var agent_id = '<?php echo $agent_id; ?>';
	
	var data = { desc: desc, new_card: new_card, amount: amount, payment_type: payment_type, agent_id: agent_id }
	
	if(new_card == 'yes') {
		
		data.first = $('#first').val();
		data.last = $('#last').val();
		data.street = $('#street').val();
		data.city = $('#city').val();
		data.state = $('#state').val();
		data.zip = $('#zip').val();
		data.card_type = $('#card_type').val();
		data.number = $('#number').val();
		data.expire_month = $('#expire_month').val();
		data.expire_year = $('#expire_year').val();
		data.code = $('#code').val();
		if($('#default_card:checked').length > 0) {
			data.default_card = 'yes';
		} else {
			data.default_card = 'no';
		}
		
		if($('#first').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter a First Name');
			$('#first').focus();
			return false;
		}
		
		if($('#last').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter a Last Name');
			$('#last').focus();
			return false;
		}
		
		if($('#street').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter the Street Address');
			$('#street').focus();
			return false;
		}
		
		if($('#invalid_zip').val() == 'yes' || $('#zip').val() == '') {
			$('#billing_error_div').show();
			$('#billing_error_text').text('You have entered an invalid Zip Code');
			$('#zip').focus();
			return false;
		}
		
		if($('#card_type').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter the Card Type');
			$('#card_type').trigger('chosen:open');
			return false;
		}
		
		if($('#number').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter the Card Number');
			$('#number').focus();
			return false;
		}
		
		if($('#expire_month').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter the Expiration Month');
			$('#expire_month').trigger('chosen:open');
			return false;
		}
		
		if($('#expire_year').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter the Expiration Year');
			$('#expire_year').trigger('chosen:open');
			return false;
		}
		
		if($('#code').val() == '') { 
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter the CVV Code');
			$('#code').focus();
			return false;
		}
		
		if(amount == '' || amount == '0.00' || amount == '0') {
			$('#billing_error_div').show();
			$('#billing_error_text').text('You must enter an Amount to charge');
			$('#amount').focus();
			return false;
		}	

		
	} else {
		
		data.pay_profile_id = $('[name=card_to_use]:checked').val();
			
		if(amount == '' || amount == '0.00' || amount == '0') {
			$('#billing_error2_div').show();
			$('#billing_error2_text').text('You must enter an Amount to charge');
			$('#amount').focus();
			return false;
		}

	}
	

	loading_bg();
	
	$.ajax({
		type: 'POST',
		context: t,
		data: data,
		url: '/new/agents/ajax/billing/payments/make_payment.php',
		success: function(response){
			remove_loading_bg();
			var r = $($.parseHTML(response));
			var error = r.filter('#error').text();
			var new_balance = r.filter('#new_balance').html();
			var nb = r.filter('#nb').html();
			if(error != '') {
				if(error == 'A duplicate customer payment profile already exists.') {
					error = 'There is already an existing card on file with that number.<br><div style="font-size: 12px; margin-top: 15px;">Select the option above that says "Use Existing Credit Card" to use that card.</div>';
				} else if(error == 'You cannot add more than 10 payment profiles.') {
					error = 'You cannot have more than 10 Credit Cards on file.  Please delete at least one to proceed and make a payment';
				}
				$('#main_error_div').fadeIn('slow');
				$('#main_error_text').html('<span style="color: #ff9; font-weight: bold; line-height: 150%;">There was an error processing your payment.</span><br>'+error);
				$('#close_main_error').click(function(){
					$('#main_error_div').fadeOut('slow');
					$('#main_error_text').text('');
				});
			} else {
				$('#add_payment_div').fadeOut('slow');
				$('#success_div').fadeIn();
				$('#success_div_text').text('Payment Successful!');
				$('#close_success').click(function(){
					$('#success_div').fadeOut('slow');
				});

				if(t == 'dues') {
					$('#agent_balance_dues').html(new_balance);
					$('#balance').val(nb);
				} else if(t == 'eno') {
					$('#agent_balance_eno').html(new_balance);
					$('#balance_eno').val(nb);
				}
				reset_payment_div();
				get_ccs();
			}
		}
		
	});
	
}

function reset_payment_div(){
	$('#payment_type, #new_card').val('');
	$('#add_payment_div, .payment_divs, #cc_options, #card_on_file_div, #new_card_div, #payment_container').hide();
	$('[name=pay_with]').prop('checked', false);
	$('#add_payment_div').css({ top: '200px' });
}

function make_payment(){
	$('#add_payment_div').fadeIn('slow');
	
	$('#close_add_payment_div').click(function(){
		reset_payment_div();
	});
	get_ccs_payment();
	var t = $(this).attr('t');
	if(t == 'dues') {
		var a = $('#balance').val();
		$('#current_balance').html('$'+$('#balance').val().replace(/\$/, ''));
		$('#make_payment_text').html('Dues/General');
		$('#payment_type').val('dues');
	} else if(t == 'eno') {
		var a = $('#balance_eno').val();
		$('#current_balance').html('$'+$('#balance_eno').val().replace(/\$/, ''));
		$('#make_payment_text').html('E&amp;O');
		$('#payment_type').val('eno');
	}
	if(!a.match(/-/)) {
		$('#amount').val(a.replace(/\$/, '').replace(/,/, ''));
	}
}

function get_ccs_payment(){
	$.ajax({
		type: 'POST',
		url: '/new/agents/ajax/billing/cc/get_ccs_payment.php',
		success: function(response){
			//var r = $($.parseHTML(response));
			if(response.match(/no\scard\son\sfile/)) { 
				$('#add_payment_div').css({ top: '60px' });
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
					$('#add_payment_div').animate({ top: '60px' });
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
function delete_cc() {
	var pay_profile_id = $(this).attr('payprofileid');	
	var profile_id = $(this).attr('profileid');	
	$(this).animate({ opacity: '.01' });
	$.ajax({
		type: 'POST',
		context: $(this),
		url: '/new/agents/ajax/billing/cc/delete_cc.php',
		data: { pay_profile_id: pay_profile_id, profile_id: profile_id },
		success: function(){
			$(this).parent('td').parent('tr').fadeOut('slow');
			setTimeout(get_ccs, 500)
			$('#cc_div').fadeIn('slow');
		}
	});
}
function default_cc(){
	var pay_profile_id = $(this).attr('payprofileid');
	$.ajax({
		type: 'POST',
		url: '/new/agents/ajax/billing/cc/default_cc.php',
		data: { agent_id: '<?php echo $agent_id; ?>', pay_profile_id: pay_profile_id },
		success: function(){
			$('#cc_div').fadeOut('slow');
			setTimeout(get_ccs, 500)
			$('#cc_div').fadeIn('slow');
		}
	});
}
function get_ccs(){
	$.ajax({
		type: 'POST',
		url: '/new/agents/ajax/billing/cc/get_ccs.php',
		data: { agent_id: '<?php echo $agent_id; ?>' },
		success: function(response){
			var r = $($.parseHTML(response));
			$('#cc_div').html(r);
			setTimeout(function(){
				var dc = r.filter('#default_card').text();
				var ab = r.filter('#auto_bill').text();
				if(dc == 'yes') {
					$('.no_default_card').hide();
				} else {
					if(ab == 'on') {
						$('.no_default_card').show();
					} else {
						$('.no_default_card').hide();
					}
				}
				var card_count = r.filter('#card_count').text();
				if(card_count > 0) {
					$('#cards').text('Select a Credit Card as your "Default Card"');
				} else {
					$('#cards').text('Add a Credit Card and set as your "Default Card"');
				}
			}, 200);
			tooltip();
			$('.deleteCC').click(delete_cc);
			$('.edit_cc_button').click(edit_cc);
			$('.default_card_button').click(default_cc);
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

function set_loc_add_cc(){
	$('#add_cc_zip_error').hide();
	var r = add_loc($('#add_cc_zip').val());
	setTimeout(function(){
		if(r['city'] != 'error') {
			$('#add_cc_city').val(r['city']);
			$('#add_cc_state').val(r['state']);
			$('#add_cc_invalid_zip').val('no');
		} else {
			$('#add_cc_zip_error').show();
			$('#add_cc_invalid_zip').val('yes');
		}
	}, 300);	
}
function set_loc_edit_cc(){
	$('#edit_cc_zip_error').hide();
	var r = add_loc($('#edit_cc_zip').val());
	setTimeout(function(){
		if(r['city'] != 'error') {
			$('#edit_cc_city').val(r['city']);
			$('#edit_cc_state').val(r['state']);
			$('#edit_cc_invalid_zip').val('no');
		} else {
			$('#edit_cc_zip_error').show();
			$('#edit_cc_invalid_zip').val('yes');
		}
	}, 300);	
}
function set_loc(){
	$('#zip_error').hide();
	var r = add_loc($('#zip').val());
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

function dt(){
	
	var dt = $(".invoice_list").dataTable({
		"bDestroy" : true,
		"sPaginationType": "full_numbers",
		"bJQueryUI": true,
		"aaSorting": [ ],
		"bPaginate": true,
		"bLengthChange": true,
		"bFilter": true,
		"bSort": true,
		"bInfo": true,
		"bAutoWidth": false,
		"iDisplayLength": 10,
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"sDom": '<"H"flip<"clear">>rt<"F"ip<"clear">>',
		"oLanguage": {
			"sSearch": ""
	   },
	   "aoColumnDefs": [ 
		  { "bSortable": false, "aTargets": [ 0 ] }
		]
	});
	setTimeout(function(){
		$('.dataTables_length').children('label').children('select').addClass('chosen-custom').css({ width: '80px', padding: '2px'}).chosen({allow_single_deselect:false, disable_search: true});
	$('.dataTables_filter input').css({ padding:  '10px', width: '80px' }).attr("placeholder", "Search");
	}, 500);
}

</script>
</body>
</html>