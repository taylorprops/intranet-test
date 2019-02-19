<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');


?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Forms</title>

    <?php
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
    <style type="text/css">
        #tab_div {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 300px;
            height: auto;
            margin: 0;
            z-index: 1000;
            overflow: visible
        }

        #tab_container {
            position: relative;
            display: block;
            height: auto;
            margin: 0 auto;
            width: 80%
        }

        #form_holder {
            display: block;
            float: left;
            margin-left: 299px;
            width: 885px;
            height: auto;
            z-index: 1
        }

        #tab_div ul {
            margin: 0px;
            padding-left: 0px;
        }

        #tab_div ul li {
            background: #3A4A63;
            padding: 7px 0 5px 7px;
            margin-bottom: 3px;
            color: #fff;
            font-weight: bold;
            font-size: 15px;
            text-decoration: none;
            border: 1px solid #3A4A63;
        }


        #tab_div ul li.li_spacer {
            margin-bottom: 20px;
        }

        #tab_div ul li:hover {
            cursor: pointer;
            background: #4871AD;
        }

        #tab_div ul li.active {
            color: #3A4A63;
            background: #fff;
            border-right: none;
        }

        .table_container {
            padding: 15px;
            min-height: 400px;
            z-index: 1;
            border: 1px solid #3A4A63;
        }

        .form_table th {
            border-bottom: 2px solid #ccc;
            text-align: left;
            font-size: 17px
        }

        .form_table td {
            border-bottom: 1px dotted #ccc;
            text-align: left;
            font-size: 15px;
            padding: 4px
        }

        .forms_header {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #D18359
        }
        .ui-tabs-panel, .ui-tabs .ui-widget-content, .ui-tabs-nav .ui-state-active a { border: none !important; }
        #zipforms_info { display: none; padding: 15px; margin-top: 20px; margin-bottom: 20px;}
        .forms_update { width: 900px; margin: 25px auto; font-size: 17px}
        .dark { font-size: 17px !important }

    </style>
</head>

<body>

    <?php
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>
    <div class="spacer"></div>
    <div class="body_container">
        <h2>Forms</h2>
        <br>
        <br>
        <div class="forms_update">Only Commission Breakdowns are supplied here. <br><br>
            In-House docs are now on SkySlope. Just click on "Working Documents" and then "Office Docs". They will all be in the "Company" folder.
            <br><br>
            Any general Real Estate Listing and Contract forms can be found on ZipformsPlus. <a href="javascript: void(0)" id="show_zipforms_info" class="dark">View Details</a>
            <br>
            <div id="zipforms_info">
                To get ZipformsPlus just go to <a href="https://www.ziplogix.com/zipformplus/" class="dark">https://www.ziplogix.com/zipformplus/</a> and click on the "Sign Up" button".<br><br>
                Zipformsplus is integrated into SkySlope. This makes document management processes such as filling, signing and submitting quite simple and it's done all in one place. I'd say it's the best and cheapest source of forms available. Zipformsplus is the NAR's trusted, official and exclusive forms software provider. NAR even provides your local and state association forms for free. If you need forms from other regions, here is a breakdown of the cost.
                <br><br>
                Yearly Fees<br>
                Greater Baltimore Board of Realtors - $10.00<br>
                Anne Arundel County Association of Realtors $30.00<br>
                Greater Capitol Area Association of Realtors - $30.00 (includes DC, Frederick County, Montgomery County & Price Georges)<br>
                Northern Virginia Association of Realtors - $20.00<br>
                All other associations - FREE
                <br><br>
                If you are a member of one of the associations above you get it for free.
                For MD agents (assuming you are a member of AACAR or GCAAR), the average cost is $40 per year for all forms from every association.
                <br><br>
                As you can see, for the most complete and up to date Real Estate forms library available, the cost is quite minimal.
            </div>
        </div>
        <div id="tab_container">
            <div id="tab_div">
                <ul>
                    <!--
                    <li class="form_page_link li_spacer active" id="show_rf">Required In-House Forms</li>

                    <li class="form_page_link li_spacer" id="show_rc">Residential Contract of Sale</li>
                    <li class="form_page_link" id="show_la">Listing Agreement and Related Forms</li>
                    <li class="form_page_link li_spacer" id="show_lea">Lease Agreement</li>
                    <li class="form_page_link" id="show_misc">Misc. Forms</li>
                    -->
                    <li class="form_page_link li_spacer" id="show_cb">Commission Breakdown Forms</li>



                </ul>
            </div>
            <div id="form_holder" class="table_container">
                <div id="rf" class="form_div">
                    <?php

            $formQuery = "select * from company.tbl_forms where category =  'required' order by description, view_type";
            $form = $db -> select($formQuery);
            $formCount = count($form);

            ?>

                        <div class="forms_header">Required In-House Forms</div>
                        <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Region</th>
                                    <th>Sale/Rental</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" style="font-size: 16px; font-weight:bold; color:#D18359;">Editable Forms</td>
                                </tr>
                                <?php
                        for($f=0;$f<$formCount;$f++) {
                            if($form[$f]['view_type'] == 'edit') { ?>
                                <tr>
                                    <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                                    <td>
                                        <?php echo $form[$f]['description']; ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['view_type'] == 'print') { echo 'Print Only'; } else { echo 'Editable'; } ?>
                                    </td>
                                    <td>All Regions</td>
                                    <td>
                                        <?php if($form[$f]['prop_type'] != '') { echo $form[$f]['prop_type']; } else { echo 'Sale and Rental'; } ?>
                                    </td>

                                </tr>
                                <?php } }?>
                            </tbody>
                        </table>


                </div>

                <div id="rc" class="form_div">
                    <?php

            $formQuery = "select * from company.tbl_forms where category = 'Contract' order by state, county, description";
            $form = $db -> select($formQuery);
            $formCount = count($form);
            ?>

                        <div class="forms_header">Sales Contract Docs</div>
                        <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Region</th>
                                    <th>Sale/Rental</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php for($f=0;$f<$formCount;$f++) { ?>
                                <tr>
                                    <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                                    <td>
                                        <?php echo $form[$f]['description']; ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['view_type'] == 'print') { echo 'Print Only'; } else { echo 'Editable'; } ?>
                                    </td>
                                    <td>
                                        <?php echo $form[$f]['state'].' - '; if($form[$f]['county'] != '') { echo $form[$f]['county']; } else { echo 'All Counties'; } ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['prop_type'] != '') { echo $form[$f]['prop_type']; } else { echo 'Sale and Rental'; } ?>
                                    </td>

                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>



                </div>

                <div id="la" class="form_div">
                    <?php
            $formQuery = "select * from company.tbl_forms where category = 'Listing' order by state, county, description";
            $form = $db -> select($formQuery);
            $formCount = count($form);
            ?>

                        <div class="forms_header">Listing Agreement Docs</div>
                        <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Region</th>
                                    <th>Sale/Rental</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php for($f=0;$f<$formCount;$f++) { ?>
                                <tr>
                                    <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                                    <td>
                                        <?php echo $form[$f]['description']; ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['view_type'] == 'print') { echo 'Print Only'; } else { echo 'Editable'; } ?>
                                    </td>
                                    <td>
                                        <?php echo $form[$f]['state'].' - '; if($form[$f]['county'] != '') { echo $form[$f]['county']; } else { echo 'All Counties'; } ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['prop_type'] != '') { echo $form[$f]['prop_type']; } else { echo 'Sale and Rental'; } ?>
                                    </td>

                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>


                </div>

                <div id="lea" class="form_div">
                    <?php

            $formQuery = "select * from company.tbl_forms where category = 'Lease' order by state, county, description";
            $form = $db -> select($formQuery);
            $formCount = count($form);
            ?>

                        <div class="forms_header">Lease Agreement Docs</div>
                        <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Region</th>
                                    <th>Sale/Rental</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php for($f=0;$f<$formCount;$f++) { ?>
                                <tr>
                                    <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                                    <td>
                                        <?php echo $form[$f]['description']; ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['view_type'] == 'print') { echo 'Print Only'; } else { echo 'Editable'; } ?>
                                    </td>
                                    <td>
                                        <?php echo $form[$f]['state'].' - '; if($form[$f]['county'] != '') { echo $form[$f]['county']; } else { echo 'All Counties'; } ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['prop_type'] != '') { echo $form[$f]['prop_type']; } else { echo 'Sale and Rental'; } ?>
                                    </td>

                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>


                </div>

                <div id="cb" class="form_div">
                    <?php

                    $hoffmanQuery = "select * from company.tbl_agent_teams where team_leader = 20000230";
                    $hoffman = $db->select($hoffmanQuery);
                    $hoffman_ids = explode(',', $hoffman[0]['members']);

                    if(in_array($_SESSION['S_ID'], $hoffman_ids)) {

                    	$formQuery = "select * from company.tbl_forms where team = 'hoffman' order by view_type";
                    	$form = $db -> select($formQuery);
                    	$formCount = count($form);

                    	for($f=0;$f<$formCount;$f++) {
                    		if($form[$f]['view_type'] == 'print') {
                    			$cb_print_link = '/new/ajax/file_download/file_download.php?filename='.urlencode(str_replace(' ', '_', $form[$f]['description'])).'.pdf&fileloc=https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path'];
                    		} else if($form[$f]['view_type'] == 'edit') {
                    			$cb_editable_link = '/new/ajax/file_download/file_download.php?filename='.urlencode(str_replace(' ', '_', $form[$f]['description'])).'.pdf&fileloc=https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path'];
                    		}
                    	}

                    } else {

                    	$formQuery = "select * from company.tbl_forms where category = 'breakdowns' and commission_plan = '".$_SESSION['S_Plan']."' and description like '%".$_SESSION['S_Commission']."%' order by view_type";
                    	$form = $db -> select($formQuery);
                    	$formCount = count($form);

                    	for($f=0;$f<$formCount;$f++) {
                    		if($form[$f]['view_type'] == 'print') {
                    			$cb_print_link = '/new/ajax/file_download/file_download.php?filename='.urlencode(str_replace(' ', '_', $form[$f]['description'])).'.pdf&fileloc=https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path'];
                    		} else if($form[$f]['view_type'] == 'edit') {
                    			$cb_editable_link = '/new/ajax/file_download/file_download.php?filename='.urlencode(str_replace(' ', '_', $form[$f]['description'])).'.pdf&fileloc=https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path'];
                    		}
                    	}

                    }
            ?>

                        <div class="forms_header">Commission Breakdowns</div>
                        <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Type</th>
                                    <th>Sale/Rental</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><a class="button button_download" href="<?php echo $cb_editable_link; ?>" target="_blank">Download </a></td>
                                    <td>Editable/Auto Calculate</td>
                                    <td>Sale and Rental</td>
                                    <td>Editable Commission Breakdown</td>
                                </tr>
                                <tr>
                                    <td><a class="button button_download" href="<?php echo $cb_print_link; ?>" target="_blank">Download </a></td>
                                    <td>Print Only</td>
                                    <td>Sale and Rental</td>
                                    <td>Print Only Commission Breakdown</td>
                                </tr>
                            </tbody>
                        </table>



                </div>

                <div id="misc" class="form_div">
                    <?php

            $formQuery = "select * from company.tbl_forms where category = 'misc' order by state, county, description";
            $form = $db -> select($formQuery);
            $formCount = count($form);
            ?>

                        <div class="forms_header">Miscellaneous Forms</div>
                        <table border="0" cellpadding="0" cellspacing="0" class="form_table" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Region</th>
                                    <th>Sale/Rental</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php for($f=0;$f<$formCount;$f++) { ?>
                                <tr>
                                    <td><a class="button button_download" href="/new/ajax/file_download/file_download.php?filename=<?php echo urlencode(str_replace(' ', '_', $form[$f]['description'])); ?>.pdf&fileloc=<?php echo urlencode('https://annearundelproperties.net/new/real_estate/forms/'.$form[$f]['file_path']); ?>" target="_blank">Download </a></td>
                                    <td>
                                        <?php echo $form[$f]['description']; ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['view_type'] == 'print') { echo 'Print Only'; } else { echo 'Editable'; } ?>
                                    </td>
                                    <td>
                                        <?php echo $form[$f]['state'].' - '; if($form[$f]['county'] != '') { echo $form[$f]['county']; } else { echo 'All Counties'; } ?>
                                    </td>
                                    <td>
                                        <?php if($form[$f]['prop_type'] != '') { echo $form[$f]['prop_type']; } else { echo 'Sale and Rental'; } ?>
                                    </td>

                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>


                </div>


            </div>
        </div>


    </div>

    <?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $.getScript('/new/scripts/common.js');

            $('#show_zipforms_info').click(function() {
                $('#zipforms_info').toggle();
            });

            $('.form_page_link').click(function() {

                $('.form_page_link').removeClass('active');
                $(this).addClass('active');
            });

            $('.form_div').not('#cb').hide();

            $('.form_page_link').click(function(){
                $('.form_div').hide();
            });

            $('#show_lea').click(function() {
                $('#lea').fadeIn('fast');
            });
            $('#show_cb').click(function() {
                $('#cb').fadeIn('fast');
            });
            $('#show_la').click(function() {
                $('#la').fadeIn('fast');
            });
            $('#show_rf').click(function() {
                $('#rf').fadeIn('fast');
            });
            $('#show_rc').click(function() {
                $('#rc').fadeIn('fast');
            });
            $('#show_misc').click(function() {
                $('#misc').fadeIn('fast');
            });



        });

    </script>
</body>

</html>
