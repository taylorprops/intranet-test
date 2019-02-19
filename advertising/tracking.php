<?php

include('/var/www/annearundelproperties.net/new/includes/global.php');
include('/var/www/annearundelproperties.net/new/includes/logged.php');

$yearQuery = "select * from company.advertising group by year order by year DESC";
$year = $db -> select($yearQuery);
$yearCount = count($year);


$agentQuery = "select * from company.tbl_agents where active = 'yes' or id in (select agent from company.advertising group by agent) order by last";
$agent = $db -> select($agentQuery);
$agentCount = count($agent);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Advertising Tracking</title>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
#adv_table { border: 1px solid #ccc; }
#mid_td { background: #3A4A63 }
#year_holder, #month_td { margin: 0; padding: 0; }
#month_td { background: #3A4A63; }
.year_div { height: auto; padding: 15px 0 15px 5px; background: #fff; font-size: 20px; font-weight: bold; cursor: pointer; text-align:center }
.year_div:not(.active_year):hover { background: #4972AF; color: #fff; }
#month_holder { background: #fff; height: 100%; border: 10px solid #3A4A63; }
#month_div { width: 110px; height: 500px; background: #3A4A63; }
.month_slider { height: auto; padding: 10px 0 10px 5px; background: #3A4A63; color: #fff; font-size: 17px; font-weight: bold; cursor: pointer;  }
.month_slider:not(.active_month):hover { background: #4972AF; color: #fff; }
.active_year { background: #3A4A63; color: #fff }
.active_month { background: #fff; color: #3A4A63; }
.adv_info_div { border: 1px solid #ccc; margin: 8px 5px 8px 0; }
#add_div {
	width: 280px; /* 1200 when opened */
	height: auto;
	display: none;
	position: absolute;
	left: 50%;
	margin-left: -165px; /* -610 when opened */
	border: 20px solid #516A89;
	background: #fff;
	z-index: 25000;
}

#add_table { font-size: 15px; margin: 15px; }
#add_table td { padding: 4px; }
.settle_header { font-size: 16px; font-weight:bold; }
.settle { background: rgba(215,84,6,.4) }
#add_account_div { display: none; margin-top: 10px; }
#info_holder { width: 98%  }
#month_details { padding: 20px; }
.lo_header { margin: 10px 0 5px 5px; color: #D18359; font-size: 18px; font-weight:bold; }
.agent_header { margin: 5px 0 5px 5px; color: #3A4A63; font-size: 16px; font-weight:bold; }
.active_account { border: 1px solid #0E630E; }
.accounts_table_div { border: 1px solid #3A4A63; padding: 10px; margin: 10px 0; }
.accounts_table th { padding: 3px; border-bottom: 1px solid #333; }
.account_row {padding: 6px 3px; border-bottom: 1px dotted #ccc; }
.divider { padding: 2px; background: #D18359; margin-bottom: 15px; margin-top: 15px; }
#month_sub_td { background: #3A4A63 }
.error { border: 5px solid #C65155; }
</style>
</head>
<body>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

<div class="body_container">
	<h2>Advertising Tracking</h2>
    <?php echo breadcrumbs('', '', '', '', 'Advertising Tracking'); ?>
    <br>
    <br>
    <a href="javascript: void(0)" id="add" class="button button_add">Add New Entry</a>
    <br><br>
    <table cellpadding="0" cellspacing="0" width="100%" id="adv_table">
    	<tr>
        	<td valign="top" width="80">

            	<div id="year_holder" class="scroll">
					<?php
                    for($y=0;$y<$yearCount;$y++) {
                    ?>
                    <div class="year_div <?php if($y == 0) { ?>active_year<?php } ?>" data-year="<?php echo $year[$y]['year']; ?>"><?php echo $year[$y]['year']; ?></div>
                    <?php
                    }
                    ?>
                </div>

            </td>
            <td width="30" id="mid_td"></td>
            <td valign="top" id="month_td">

            	<div id="month_holder">

                	<table width="100%" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<td width="110" valign="top" id="month_sub_td"><div id="month_div"></div></td>
                        	<td id="month_details" valign="top"></td>
                        </tr>
                    </table>


                </div>

            </td>
        </tr>
    </table>


</div>

<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

<div id="add_div" class="shadow draggable">
    <table width="100%" cellpadding="0" cellspacing="0" class="draggable_handle">
        <tr>
            <td class="popup_div_head" width="15%"></td>
            <td class="popup_div_head" style="padding-bottom:10px;">Enter Details</td>
            <td class="popup_div_head" width="15%" align="right" style="text-align:right;"><a href="javascript: void(0);" class="button button_cancel" id="close_add_div" style="margin-right: 10px;"></a></td>
        </tr>
    </table>
    </table>
    <form id="ad_form">
	<table width="100%" cellpadding="0" cellspacing="0" id="add_table">
    	<tr>
        	<td valign="top">
            	<table>
                    <tr>
                        <td>Month<br>
                        <select id="month" name="month" class="chosen-custom" style="width: 120px;">
                            <option value=""></option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                            </select></td>
                            <td>Year<br>
                            <select id="year" name="year" class="chosen-custom" style="width: 80px;">
                            <option value=""></option>
                            <?php for($i=date("Y");$i>date("Y", strtotime("-5 year"));$i--) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                            </select></td>
                    </tr>
                	<tr>
                        <td colspan="2">Agent<br>
                        <select id="agent" name="agent" class="chosen-custom" style="width: 215px;">
                        <option value=""></option>
                        <?php for($a=0;$a<$agentCount;$a++) { ?>
                        <option value="<?php echo $agent[$a]['id']; ?>"><?php echo $agent[$a]['fullname']; ?></option>
                        <?php } ?>
                        </select>
                        </td>
                    </tr>
                </table>
                <br>
                <div id="add_account_holder" style="display:none">
                <a href="javascript: void(0)" id="add_account" class="dark">+ Add Advertising Account</a><br>to "Account" dropdown menu
                </div>
                <br>
                <div id="add_account_div">
                <input type="text" id="new_account" style="width: 200px;">
                <br>
                <a href="javascript: void(0)" id="save_new_account" class="button button_save" style="margin-top: 10px;">Save</a>
                </div>
            </td>
            <td valign="top">

                <div id="info_holder">

                </div>

                <table width="95%" style="display: none" id="button_table">
                    <tr>
                        <td width="25%"></td>
                        <td align="center"><a href="javascript: void(0)" id="save" class="button button_save" style="font-size: 22px; padding-top: 15px; padding-bottom: 15px">Save</a></td>
                        <td width="25%"></td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');

	get_month('<?php echo date('Y'); ?>');


	$('.chosen-custom').chosen();
	$('.scroll').mCustomScrollbar({
		autoHideScrollbar:true,
		scrollButtons:{
			enable:true,
			scrollAmount: '5'
		},
		theme: 'rounded',
		scrollbarPosition:'outside'
	});

	get_details('<?php echo date('m'); ?>', '<?php echo date('Y'); ?>');

	$('.year_div').click(function(){
		$('.active_year').removeClass('active_year');
		$(this).addClass('active_year');
		get_month($(this).data('year'));
	});

	$('#add').click(function(){
		var top = $(document).scrollTop() + 200;
		$('#add_div').fadeIn('slow').css({ top: top });
		if($('.active_month').length > 0) {
			var m = $('.active_month').data('month');
			var y = $('.active_month').data('year');
			$('#month').val(m).trigger('chosen:updated');
			$('#year').val(y).trigger('chosen:updated');
		} else {
			$('#month').val('<?php echo date('m'); ?>').trigger('chosen:updated');
			$('#year').val('<?php echo date('Y'); ?>').trigger('chosen:updated');
		}
	});
	$('#close_add_div').click(close_add_div);

	$('#agent, #month, #year').change(get_info);

	$('#add_account').click(function(){
		$('#add_account_div').slideFadeToggle();
	});
	$('#save_new_account').click(add_account);


	$('#save').click(save);


});

function close_add_div(){
	$('#agent, #month, #year').val('');
	$('#info_holder').html('');
	$('.chosen-custom').trigger('chosen:updated');
	$('#add_div').fadeOut('slow').animate({ width: '280px', 'margin-left': '-165px' });
	$('#button_table, #add_account_div, #add_account_holder').hide();
	$('#year, #month, #agent').prop('disabled', false).trigger('chosen:updated');
}

function save() {

	var t = $('#save_type').val();

	$('.error').removeClass('error');
	var cont = 'yes';

	var empty = 5;
	$('.adv_info_div').find('input, select').each(function(){
		if($(this).val() != '') {
			empty -= 1;
		}
	});
	if(empty != 0 && empty != 5) {
		$('.adv_info_div').addClass('error');
		cont = 'no';
		alert('All Fields are Required');
	}


	if(cont == 'yes') {
		$('#year, #month, #agent').prop('disabled', false).trigger('chosen:updated');
		var data = $('#ad_form').serialize();
		$.ajax({
			type: 'POST',
			url: '/new/ajax/advertising/save_'+t+'.php',
			data: data ,
			success: function() {
				close_add_div();
				if($('.active_month').length > 0) {
					var m = $('.active_month').data('month');
					var y = $('.active_month').data('year');

				} else {
					var m = '<?php echo date('m'); ?>';
					var y = '<?php echo date('Y'); ?>';
				}
				if(t == 'add') {
					get_month(y);
				} else {
					get_details(m,y);
				}

			}
		});
	}
}
function delete_account(){
	var id = $(this).data('id');
	$.ajax({
		type: 'POST',
		context: $(this),
		url: '/new/ajax/advertising/delete_account.php',
		data: { id: id },
		success: function(){
			var tr = $(this).parent('td').parent('tr');
			setTimeout(function(){
				tr.fadeOut('slow');
			}, 200);
			get_month('<?php echo date('Y'); ?>');

		}
	});
}

function get_info(){
	if($('#agent').val() != '' && $('#month').val() != '' && $('#year').val() != '') {
		$('#add_div').animate({ width: '1200px', 'margin-left': '-610px' });
		$.ajax({
			type: 'POST',
			url: '/new/ajax/advertising/get_info.php',
			data: { agent: $('#agent').val(), month: $('#month').val(), year: $('#year').val() },
			success: function(response) {
				$('#info_holder').html(response);
				$('[type=number]').css({ width: '70px', 'text-align': 'center' });
				$('.accounts').chosen();
				$('#button_table, #add_account_holder').show();
			}
		});
	}
}




function edit_account(){

	var year = $(this).data('year');
	var month = $(this).data('month');
	var agent = $(this).data('agent');


	$('#add_div').animate({ width: '1200px', 'margin-left': '-610px' });
	var top = $(document).scrollTop() + 200;
	$('#add_div').fadeIn('slow').css({ top: top });

	$('#year').prop('disabled', true).val(year);
	$('#month').prop('disabled', true).val(month);
	$('#agent').prop('disabled', true).val(agent);

	$('.chosen-custom').trigger('chosen:updated');

	$.ajax({
		type: 'POST',
		url: '/new/ajax/advertising/get_info.php',
		data: { agent: agent, month: month, year: year },
		success: function(response) {
			$('#info_holder').html(response);
			$('[type=number]').css({ width: '70px', 'text-align': 'center' });
			$('.accounts').chosen();
			$('#button_table, #add_account_holder').show();

		}
	});
}

function get_details(m,y) {
	$.ajax({
		type: 'POST',
		url: '/new/ajax/advertising/get_details.php',
		data: { month: m, year: y },
		success: function(response){
			$('#month_details').html(response);
			$('.button_edit').click(edit_account);
			$('.delete').click(delete_account);
		}
	});
}

function get_month(year) {
	$.ajax({
		type: 'POST',
		url: '/new/ajax/advertising/get_month.php',
		data: { year: year },
		success: function(response){
			$('#month_div').html(response);
			$('.month_slider').eq(0).addClass('active_month');
			var m = $('.active_month').data('month');
			var y = $('.active_month').data('year');
			get_details(m,y);
			$('.month_slider').click(function(){
				$('.active_month').removeClass('active_month');
				$(this).addClass('active_month');
				var m = $(this).data('month');
				var y = $(this).data('year');
				get_details(m,y);
			});
		}
	});
}

</script>
</body>
</html>
