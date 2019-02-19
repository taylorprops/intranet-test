<?php 

include('/var/www/annearundelproperties.net/new/includes/global.php');
include('/var/www/annearundelproperties.net/new/includes/logged.php');

$faqQuery = "Select *, date_format(date_added, '%b %D %Y') as date_asked from company.faq order by date_added DESC";
$faq = $db -> select($faqQuery);
$faqCount = count($faq);

$catQuery = "Select * from company.faq_categories where category in (select category from company.faq) order by category ASC";
$cat = $db -> select($catQuery);
$catCount = count($cat);


?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>FAQ's</title>

    <?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
    <script type="text/javascript" src="https://annearundelproperties.net/new/scripts/upload/jquery.uploadifive.js"></script>
    <link href="https://annearundelproperties.net/new/scripts/upload/uploadifive.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        #faq_container {
            width: 1200px;
            margin: 0 auto
        }
        
        .question_table td {
            padding: 12px;
        }
        
        .faq_div {
            border: 2px solid #D18359;
            margin: 15px 0;
            padding: 20px;
            color: #516A89
        }
        
        .faq_table td {
            padding: 5px;
        }
        
        .faq_question {
            font-size: 18px;
            color: #516A89;
        }
        
        .faq_answer {
            font-size: 17px;
            color: #000;
            margin: 25px;
        }
        
        .long_answer {
            display: none;
        }
        
        .orange_a {
            color: #D18359;
            font-size: 15px;
        }
        
        .orange_a:hover {
            color: #CE655F;
        }
        
        .form_head {
            font-size: 16px;
            color: #d18359;
            font-weight: bold;
        }
        
        .docs_header {
            font-size: 15px;
            font-weight: bold;
        }
        
        mark {
            background: rgba(209,131,89,.3);
            color: black;
        }
        
        #search_faq {
            width: 250px;
            padding: 11px 0 11px 5px;
            background: white url(/new/images/header/icon_search.png) right center no-repeat
        }
        
        .category_div {
            font-size: 18px;
            font-weight: bold;
            color: #3E5168;
            margin-bottom: 15px;
        }

    </style>
</head>

<body>

<?php 
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

    <div class="body_container">
        <h2>FAQ's</h2>
        <?php echo breadcrumbs('', '', '', '', 'FAQ\'s'); ?>
        <br><br>
        <div id="faq_container">
            
            <table>
                <tr>
                    <td><input type="text" id="search_faq" placeholder="Search"></td>
                    <td width="100"></td>
                    <td>Filter by Category
                        <select id="filter" class="chosen-custom" style="width: 300px">
                            <option value=""></option>
                            <?php for($c=0;$c<$catCount;$c++) { ?>
                            <option value="<?php echo $cat[$c]['category']; ?>"><?php echo $cat[$c]['category']; ?></option>
                            <?php } ?>
                        </select>
                </tr>
            </table>
            

            <div id="faq_div"></div>
        </div>

    </div>

    <input type="hidden" id="question_id">
    <?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $.getScript('/new/scripts/common.js');
            
            $('.chosen-custom').chosen();

            get_faq();
            
            $('#filter').change(filter);
        });
        
        function filter(){
            var v = $('#filter').val();
            if(v == '') {
                $('.faq_div').show(); 
            } else {
                $('.faq_div').hide().each(function(){
                    if($(this).data('cat') == v) {
                        $(this).show();
                    }
                });
                   
            }
        }

        function get_faq() {
            $.ajax({
                type: 'POST',
                url: '/new/ajax/faq/get_faq_agent.php',
                success: function(response) {
                    $('#faq_div').html(response);

                    $('.show_more').click(function() {
                        $(this).closest('.short_answer').slideFadeToggle();
                        $(this).closest('.short_answer').next('div').slideFadeToggle();
                    });
                    $('.show_less').click(function() {
                        $(this).closest('.long_answer').slideFadeToggle();
                        $(this).closest('.long_answer').prev('div.short_answer').slideFadeToggle();
                    });

                    $('#search_faq').keyup(function() {
                        var val = $(this).val().trim();
                        var v = RegExp(val, "i");
                        $('.faq_div').each(function() {
                            if ($(this).text().match(v)) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });

                        $(".faq_answer, .faq_question").unmark({
                            done: function() {
                                $(".faq_answer, .faq_question").mark(val);
                            }
                        });

                    });
                }
            });
        }

    </script>
</body>

</html>
