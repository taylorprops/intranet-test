<?php 

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');


$emailQuery = "select ac_email from company.email_accounts where ac_user_id = '".$_SESSION['S_ID']."'";
$email = $db -> select($emailQuery);

if(count($email) > 0) {
    $agent_comp_email = $email[0]['ac_email'];
} else {
    $agent_comp_email = '';
}


?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transfer Emails</title>

    <?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
    <style type="text/css">
        .instructions {
            font-size: 18px;
            color: #D18359;
            margin-bottom: 10px;
        }
        
        .instructions_small {
            font-size: 16px;
            color: #516A89;
            margin-bottom: 20px;
        }
        
        #transfer_table th {
            padding: 8px;
            text-align: left;
            font-size: 18px;
        }
        
        #transfer_table td {
            padding: 8px;
            font-size: 16px;
        }
        
        #transfer_table input {
            width: 300px;
            font-size: 16px;
        }
        
        .chosen-search input {
            width: 100% !important
        }
        
        #success {
            display: none;
            width: 600px;
            height: auto;
            padding: 25px;
            margin: 0 auto;
            font-size: 18px;
            color: #fff;
            background: #507756
        }
        
        #fail {
            display: none;
            width: 600px;
            height: auto;
            padding: 25px;
            margin: 0 auto;
            font-size: 18px;
            color: #fff;
            background: #8C4E50
        }
        
        .info_div {
            background: #8C4E50;
            color: #fff;
            font-size: 17px !important;
            font-weight: normal !important;
            padding: 30px;
            padding-top: 3px;
            display: none;
            position: fixed;
            top: 250px;
            right: 0;
            left: 0;
            margin-right: auto;
            margin-left: auto;
            width: 400px;
            text-align: left;
        }
        
        .chosen-single span {
            font-size: 17px !important;
            color: #7F7F7F;
        }

    </style>
</head>

<body>

    <?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

    <div class="body_container">
        <h2>Transfer Emails from your taylorprops.com or annearundelproperties.com Acccount</h2>

        <br><br><br>
        <div style="width: 800px; margin: 0 auto;">
            <div class="instructions">To transfer your emails from your taylorprops.com or annearundelproperties.com account to another account, just enter the information below</div>
            <br><br>
            <div class="instructions_small">** This process may take from 10 minutes to 24 hours to complete depending on how many emails you have to transfer. Once you have started the process you will be able to see the emails being added to your new email account. You will be notified by email once complete. **
            <br><br>
            Call Mike at the office with any questions - 301-970-2447</div>
            <br>
            <br>
            <table id="transfer_table" align="center">
                <tr>
                    <th colspan="3">TaylorProps.com or AnneArundelProperties.com</th>
                </tr>
                <tr>
                    <td>Email</td>
                    <td colspan="2"><input type="text" id="tp_email" class="required" value="<?php echo $agent_comp_email; ?>" placeholder="Enter Your Email"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" id="tp_pass" class="required" placeholder="Enter Your Password"></td>
                    <td><a href="javascript: void(0)" id="show_tp_pass" class="button button_view">Show Password</a></td>
                </tr>
                <tr>
                    <th colspan="3">New Email Address</th>
                </tr>
                <tr>
                    <td>Account Type</td>
                    <td colspan="2">
                        <select id="new_server" class="chosen chosen-custom required" style="width: 320px;" data-placeholder="Enter Email Type">
                            <option value=""></option>
                            <option value="gmail">@Gmail.com</option>
                            <option value="yahoo">@Yahoo.com</option>
                            <option value="outlook">@Outlook.com</option> 
                            <option value="other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr id="other_tr" style="display: none;">
                    <td>IMAP Server Address</td>
                    <td colspan="2"><input type="text" id="other_server" style="width: 300px;" placeholder="i.e. imap.godaddy.com"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td colspan="2"><input type="text" id="new_email" class="required" placeholder="Enter Your New Email"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td width="300"><input type="password" id="new_pass" class="required" placeholder="Enter Your New Password"></td>
                    <td><a href="javascript: void(0)" id="show_new_pass" class="button button_view">Show Password</a></td>
                </tr>
                <tr>
                    <td colspan="3" align="center"><a href="javascript: void(0)" id="start" class="button button_continue" style="font-size: 18px;">Start Transfer</a></td>
                </tr>
            </table>

            <div id="success">
                <table width="100%">
                    <tr>
                        <td style="padding-right: 15px;"><img src="/new/images/icons/check_white.png"></td>
                        <td>The transfer has begun. Please be patient while the process takes place and we will email you once complete.</td>
                    </tr>
                </table>
            </div>
            <div id="fail">
                <table width="100%">
                    <tr>
                        <td style="padding-right: 15px;"><img src="/new/images/icons/x_white.png" height="30"></td>
                        <td><span id="error"></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div id="yahoo" class="info_div shadow">
        <table width="100%">
            <tr>
                <td colspan="2" align="right"><a href="javascript: void(0)" class="button button_cancel close_info_div"></a></td>
            </tr>
            <tr>
                <td valign="top" style="padding-right: 25px"><img src="/new/images/icons/warning_white.png"></td>
                <td>To transfer to a yahoo.com account you must first login to your yahoo.com account and go to <a class="light" href="https://login.yahoo.com/account/security#other-apps" target="_blank">https://login.yahoo.com/account/security#other-apps</a>.<br><br> Once there turn on "Allow apps that use less secure sign in".</td>
            </tr>
        </table>
    </div>
    <div id="gmail" class="info_div shadow">
        <table width="100%">
            <tr>
                <td colspan="2" align="right"><a href="javascript: void(0)" class="button button_cancel close_info_div"></a></td>
            </tr>
            <tr>
                <td valign="top" style="padding-right: 25px"><img src="/new/images/icons/warning_white.png"></td>
                <td>To transfer to a gmail.com account you must first login to your gmail.com account and go to your Settings.<br><br>Click on the gear icon in the top right and selecting "Settings".<br><br>From there select "Forwarding and POP/IMAP".<br><br>In the "IMAP Access" section select "Enable IMAP" and select "Save Changes" at the bottom.</td>
            </tr>
        </table>
    </div>

    <?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $.getScript('/new/scripts/common.js');
            $('.chosen-custom').chosen();

            $('#show_new_pass').click(function() {
                if ($(this).text() == 'Show Password') {
                    $('#new_pass').prop('type', 'text');
                    $(this).text('Hide Password');
                } else {
                    $('#new_pass').prop('type', 'password');
                    $(this).text('Show Password');
                }
            });
            $('#show_tp_pass').click(function() {
                if ($(this).text() == 'Show Password') {
                    $('#tp_pass').prop('type', 'text');
                    $(this).text('Hide Password');
                } else {
                    $('#tp_pass').prop('type', 'password');
                    $(this).text('Show Password');
                }
            });

            $('#new_server').change(function() {
                $('#yahoo, #gmail, #other_tr').hide();
                $('.error_tr').remove();
                $('#other_server').removeClass('required');
                if ($(this).val() == 'yahoo') {
                    $('#yahoo').slideFadeToggle();
                } else if ($(this).val() == 'gmail') {
                    $('#gmail').slideFadeToggle();
                } else if ($(this).val() == 'other') {
                    $('#other_tr').show();
                    $('#other_server').addClass('required');
                }
            });
            
            $('.close_info_div').click(function(){
                $('.info_div').fadeOut('slow');
            });

            $('#start').click(function() {

                $('.error_tr').remove();
                var cont = 'yes';
                $('.required').not('.chosen-container').each(function() {

                    if (cont == 'yes') {

                        if ($(this).val() == '') {
                            cont = 'no';
                            $('<tr class="error_tr"><td></td><td colspan="2" style="color: #8C4E50;"><span style="font-size: 1.5em;">&#x2191;</span> Required Field</td><tr>').insertAfter($(this).parent('td').parent('tr'));
                        }

                    }
                });

                if (cont == 'yes') {

                    loading_bg();
                    $('#fail, #success').hide();
                    $(this).text('Starting Transfer...');

                    var tp_email = $('#tp_email').val();
                    var tp_pass = $('#tp_pass').val();
                    var new_email = $('#new_email').val();
                    var new_pass = $('#new_pass').val();
                    var new_server = $('#new_server').val();
                    var other_server = $('#other_server').val();

                    $.ajax({
                        type: 'POST',
                        data: {
                            new_email: new_email,
                            new_pass: new_pass,
                            tp_email: tp_email,
                            tp_pass: tp_pass,
                            new_server: new_server,
                            other_server: other_server
                        },
                        url: '/new/agents/ajax/email/transfer_to_new_account_script.php',
                        success: function(response) {
                            if (response.match(/ERROR/)) {
                                $('#fail').fadeIn('slow');
                                $('#error').html(response);
                            } else {
                                $('#success').fadeIn('slow');
                            }
                            remove_loading_bg();
                            $('#start').text('Start Transfer');
                        }
                    });

                }
            });
        });

    </script>
</body>

</html>
