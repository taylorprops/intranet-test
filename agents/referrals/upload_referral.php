<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');


?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Upload Referral Agreement</title>

    <?php
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
    <script src="https://annearundelproperties.net/new/scripts/upload/jquery.uploadifive.js" type="text/javascript"></script>
    <link href="https://annearundelproperties.net/new/scripts/upload/uploadifive.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .upload_div {
            width: 800px;
            margin: auto;
            padding-top: 20px;
        }

        #upload_table td {
            padding: 6px;
        }

        #zip_error {
            display: none;
            background: #C65155;
            color: #fff;
            width: 110px;
            padding: 3px;
            text-align: center;
            margin-top: 5px
        }
        #street_error, .upload_tr {  display: none; }
        .street_error { background: #C65155; color: #fff; padding: 10px; width: 380px; }
        .instructions { font-size: 12px; margin-bottom: 5px; }
        .other_instructions { font-weight: bold; color: #516A89; width: 600px; margin: 10px auto; }
        #add_docs_div { display: none; width: 500px; height: auto; border: 5px solid #516A89; position: fixed; top: 150px; left:0px; right: 0px; margin: auto; background: #fff; }
        .add_upload { text-align: center; padding: 15px; }
        #continue_add { display: none; }

    </style>

</head>

<body>

    <?php
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

    <div class="body_container">
        <h2>Upload Referral Agreement</h2>
        <br><br>
        <h3>Upload Here</h3>
        <div class="other_instructions">Enter the property details and upload your Referral Agreement and Commission Breakdown</div>
        <div class="upload_div">
            <table width="100%" cellpadding="0" cellspacing="0" id="upload_table">
                <tr>
                    <td align="right">Property Street Address</td>
                    <td colspan="2"><input type="text" id="referral_street" style="width: 380px;"></td>
                </tr>
                <tr>
                    <td align="right">Property Zip Code</td>
                    <td><input type="text" id="referral_zip" style="width: 380px;" maxlength="5"></td>
                    <td width="120">
                        <div id="zip_ok" style="display:none; margin-top: 10px"><img src="/new/images/icons/check_green_new.png" height="19"></div>
                        <div id="zip_error" class="round5"><img src="/new/images/icons/x_white.png" height="10"> Invalid Zip</div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" id="city_state"></td>
                </tr>
                <tr>
                    <td align="right">Check Amount</td>
                    <td colspan="2"><input type="text" id="recip1_check_amount" class="money numbers_only" style="width: 380px;"></td>
                </tr>
                <tr class="continue">
                    <td colspan="2" align="center"><a href="javascript: void(0)" id="continue" class="button button_normal">Continue</a></td>
                </tr>
                <tr class="upload_tr">
                    <td align="right">Upload Referral Agreement<br>and Commission Breakdown</td>
                    <td colspan="2"><div class="instructions">Select one or more documents</div>
                        <input type="file" id="upload">
                        <br>
                        <div id="queue"></div>
                    </td>
                </tr>
                <tr id="upload_error" style="display: none;">
                    <td></td>
                    <td colspan="2" style="color: #8C4E50;"><span style="font-size: 1.5em;">&#x2191;</span> You must upload your referral agreement and commission breakdown</td>
                </tr>
                <tr class="upload_tr">
                    <td colspan="3" align="center" style="padding-top: 10px"><a href="javascript: void(0)" id="save_referral_commission" class="button button_save" style="font-size: 18px;">Submit</a></td>
                </tr>

                <input type="hidden" id="referral_city">
                <input type="hidden" id="referral_state">
                <input type="hidden" id="recip1_id" value="<?php echo $_SESSION['S_ID']; ?>">
            </table>
        </div>
        <input type="hidden" id="doc_count">
        <hr>

        <h3>Uploaded Referrals</h3>

        <div class="other_instructions">** You can delete uploaded referrals before they are processed if done in error.</div>
        <div class="datatable_container" style="width: 1000px; margin: 0 auto; font-size: 15px !important;" id="upload_div">

        </div>

    </div>

    <input type="hidden" id="referral_id">

    <div id="add_docs_div" class="shadow">
        <table width="100%">
        	<tr>
            	<td align="center" width="90%" style="font-size: 17px; font-weight:bold">Add Docs</td>
            	<td align="right"><a href="javascript: void(0)" id="close_add_docs_div" class="button button_cancel"></a></td>
            </tr>
        </table>
        <div class="add_upload">
            <br><br>
            <input type="file" id="add_uploads">
            <br><br>
            <div id="add_queue"></div>
            <br><br>
            <a href="javascript: void(0)" id="continue_add" class="button button_normal">Submit Uploads</a>
        </div>

    </div>

    <?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $.getScript('/new/scripts/common.js');

            get_referrals();

            $('.chosen-custom').chosen();

            $('#referral_zip').bind('keyup change', function() {
                $('#zip_ok').hide();
                if ($(this).val().length == 5) {
                    set_loc();
                } else {
                    $('#zip_error').hide();
                    $('#city_state').html('');
                }
            });
            $('#continue').click(function() {
                continue_upload();
            });
            $('#save_referral_commission').click(save_referral);

            $('.money').keyup(function(e){
                $(this).val(format_money($(this).val()));
            });



        });

        function continue_upload() {
            var cont = 'yes';
            $('#referral_street, #referral_zip, #recip1_check_amount').each(function() {
                if ($(this).val() == '' && cont == 'yes') {

                    $(this).prop('placeholder', 'Required').css({
                        border: '1px solid #900'
                    }).click(function() {
                        $(this).css({
                            border: '1px solid #AAA4A4'
                        });
                    });
                    cont = 'no';

                }
            });

            if(cont == 'yes') {
                var agent_id = $('#recip1_id').val();
                var street = $('#referral_street').val();
                var city = $('#referral_city').val();
                var state = $('#referral_state').val();
                var zip = $('#referral_zip').val();
                var amount = $('#recip1_check_amount').val();
                $.ajax({
                    type: 'POST',
                    url: '/new/agents/ajax/referrals/add_referral.php',
                    data: { agent_id: agent_id, street: street, city: city, state: state, zip: zip, amount: amount },
                    success: function(response) {
                        $('.continue').hide();
                        $('.upload_tr').show();
                        $('#referral_id').val(response);
                    }
                });
            }
        }

        function get_referrals() {
            $.ajax( {
                type: 'POST',
                url: '/new/agents/ajax/referrals/get_referrals.php',
                success: function ( results ) {
                    $('#upload_div').html(results);
                    dtables();
                }
            } );
		}

        function open_edit() {
            $('#add_docs_div').fadeIn();
            $('#referral_id').val($(this).data('referral_id'));
            $('#continue_add').click(function() {
                $('#add_uploads').uploadifive('upload');
            });
            $('#close_add_docs_div').click(function() {
                $('#add_docs_div').fadeOut();
            });
        }


        function delete_referral_commission() {
			var referral_id = $(this).data('referral_id');
            var commission_id = $(this).data('commission_id');
            var t = $(this);
			if(confirm('Are you sure you want to delete this referral commission?')) {
				$.ajax( {
					type: 'POST',
					data: { referral_id: referral_id, commission_id: commission_id },
					url: '/new/agents/ajax/referrals/delete_referral.php',
					success: function ( results ) {
						t.closest('tr').fadeOut('slow');
					}
				} );
			}
		}


        function save_referral() {
            var cont = 'yes';
            $('#upload_error').fadeOut();


            if(parseInt($('#doc_count').val()) == 0 || $('#doc_count').val() == '') {
                $('#upload_error').show();
                cont = 'no';
            }
            if (cont == 'yes') {
                $('#upload').uploadifive('upload');


            }
        }

        <?php $timestamp = time();  ?>

        $('#upload').uploadifive({
            'auto'          : false,
            'multi'			: true,
            'fileSizeLimit' : '100MB',
            'method'   		: 'post',
            'removeCompleted' : true,
            'onAddQueueItem' : function(file) {
                var fileName = file.name;
                var ext = fileName.substring(fileName.lastIndexOf('.') + 1); // Extract EXT
                ext = ext.toLowerCase();
                if(ext != 'pdf') {
                    alert('All Uploads Must Be in PDF Format');
                    $('#upload').uploadifive('cancel', file);
                };
                this.data('uploadifive').settings.formData = {
                    'timestamp' : '<?php echo $timestamp;?>',
                    'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
                    'agent_id'  : $('#recip1_id').val(),
                    'street'	: $('#referral_street').val(),
                    'city'	    : $('#referral_city').val(),
                    'state' 	: $('#referral_state').val(),
                    'zip'       : $('#referral_zip').val(),
                    'amount'    : $('#recip1_check_amount').val(),
                    'referral_id': $('#referral_id').val()
                };
            },
            'queueID'          : 'queue',
            'uploadScript'     : '/new/agents/ajax/referrals/upload_script.php',
            'onSelect' : function(queue) {
                $('#doc_count').val(queue.queued);
            },
            'onCancel'     : function() {
                $('#doc_count').val(parseInt($('#doc_count').val() - 1));
            },
            'onQueueComplete' : function(){
                get_referrals();
                success('Referral Successfully Added');
                $('#referral_id, #referral_street, #referral_zip, #recip1_check_amount').val('');
                $('.continue').show();
                $('.upload_tr').hide();
                $('#city_state').html('');
                $('#zip_error').hide();
            }

        });

        $('#add_uploads').uploadifive({
            'auto'          : false,
            'multi'			: true,
            'fileSizeLimit' : '100MB',
            'method'   		: 'post',
            'removeCompleted' : true,
            'onAddQueueItem' : function(file) {
                var fileName = file.name;
                var ext = fileName.substring(fileName.lastIndexOf('.') + 1); // Extract EXT
                ext = ext.toLowerCase();
                if(ext != 'pdf') {
                    alert('All Uploads Must Be in PDF Format');
                    $('#add_upload').uploadifive('cancel', file);
                };
                this.data('uploadifive').settings.formData = {
                    'timestamp' : '<?php echo $timestamp;?>',
                    'token'     : '<?php echo md5('unique_salt' . $timestamp);?>',
                    'referral_id': $('#referral_id').val(),
                    'agent_id'  : $('#recip1_id').val()
                };
                $('#continue_add').show();
            },
            'queueID'          : 'add_queue',
            'uploadScript'     : '/new/agents/ajax/referrals/upload_script_add.php',
            'onQueueComplete' : function(){
                $('#add_docs_div').fadeOut();
                get_referrals();
                success('Documents Successfully Added');
                $('#continue_add').hide();
            }

        });




        function set_loc() {
            $('#zip_error').hide();
            var r = add_loc($('#referral_zip').val());
            setTimeout(function() {
                if (r['city'] != 'error') {
                    $('#referral_city').val(r['city']);
                    $('#referral_state').val(r['state']);
                    $('#city_state').html(r['city'] + ', ' + r['state']);
                    $('#zip_ok').show();
                } else {
                    $('#zip_error').show();
                }
            }, 300);
        }

        function dtables() {
			var dt = $( "#commission_table" ).dataTable( {
				"bDestroy": true,
				"sPaginationType": "full_numbers",
				"bJQueryUI": true,
				"aaSorting": [],
				"bPaginate": true,
				"bLengthChange": true,
				"bFilter": true,
				"bSort": true,
				"bInfo": true,
				"bAutoWidth": false,
				"aLengthMenu": [
					[ 50, 100, 200, -1 ],
					[ 50, 100, 200, "All" ]
				],
				"iDisplayLength": 50,
				"aoColumnDefs": [ {
					"bSortable": false,
					"aTargets": [ 3 ]
				} ],
				"sDom": '<"H"flip<"clear">>rt<"F"ip<"clear">>',
				"oLanguage": {
					"sSearch": ""
				}
			} );
			setTimeout( function () {
				$( '.dataTables_length' ).children( 'label' ).children( 'select' ).addClass( 'chosen-custom' ).css( {
					width: '80px',
					padding: '4px'
				} ).chosen( {
					allow_single_deselect: false,
					disable_search: true
				} );
				$( '.dataTables_filter input' ).css( {
					padding: '10px',
					width: '120px'
				} ).attr( "placeholder", "Search" );
			}, 500 );


			$('.delete_referral_commission_button').unbind( 'click' ).bind( 'click', delete_referral_commission );
            $('.edit_referral').unbind( 'click' ).bind( 'click', open_edit );

			$( dt ).bind( 'draw page', function () {
				$('.edit_referral').unbind( 'click' ).bind( 'click', open_edit );
			} );
		}

    </script>
</body>

</html>
