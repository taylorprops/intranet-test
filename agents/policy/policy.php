<?php

include_once('/var/www/annearundelproperties.net/new/includes/global.php');
include_once('/var/www/annearundelproperties.net/new/includes/global_agent.php');
include_once('/var/www/annearundelproperties.net/new/includes/logged.php');

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Policy/Legal</title>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/head.php');
?>
<style type="text/css">
.div_header {
	width: 700px;
	height: auto;
	padding: 10px 0;
	font-size: 18px;
	font-weight: bold;
	background: #3A4A63;
	border: 3px solid #516A89;
	color: #fff;
	text-align: center;
	margin-bottom: 15px;
}
.policy_table {
	width: 700px;
	margin: 15px 0;
}
.policy_table a { font-size:16px; }
.policy_table td { font-size:16px; font-weight: bold; padding: 6px; }
</style>
</head>
<body>

<?php
include_once('/var/www/annearundelproperties.net/new/page_includes/header.php');
?>

<div class="body_container">
	<h2>Policy/Legal</h2>
	<br>
    <br>
    <div class="div_header">Advertising</div>
    <table class="policy_table">
    	<tr>
        	<td width="250">Advertising Guidelines</td>
            <td><a class="button button_normal" href="https://annearundelproperties.net/new/agents/docs/AdvertisingGuidelines.pdf" target="_blank">View</a></td>
        </tr>
		<tr>
        	<td width="250">Advertising Checklist</td>
            <td><a class="button button_normal" href="https://annearundelproperties.net/new/agents/docs/Advertising_Checklist.pdf" target="_blank">View</a></td>
        </tr>
		<tr>
        	<td width="250">NAR Advertising Policy</td>
            <td><a class="button button_normal" href="https://annearundelproperties.net/new/agents/docs/NAR_Internet_Advertising_Policy.pdf" target="_blank">View</a></td>
        </tr>
		<tr>
        	<td width="250">Teams - Do's and Dont's</td>
            <td><a class="button button_normal" href="https://annearundelproperties.net/new/agents/docs/Teams_Dos_and_donts.pdf" target="_blank">View</a></td>
        </tr>
        <tr>
        	<td width="250">Regulation Z</td>
            <td><a class="button button_normal" href="https://annearundelproperties.net/new/agents/docs/RegulationZ.pdf" target="_blank">View</a></td>
        </tr>
    </table>

    <div class="div_header">Legal / Ethics</div>
    <table class="policy_table">
    	<tr>
        	<td width="250">Code of Ethics</td>
            <td><a class="button button_normal" href="https://annearundelproperties.net/new/agents/docs/2016_Code_of_Ethics.pdf" target="_blank">View</a></td>
        </tr>
        <tr>
        	<td width="250">Legal And Ethical Obligations</td>
            <td><a class="button button_normal" href="https://annearundelproperties.net/new/agents/docs/Legal_and_Ethical_Obligations.pdf" target="_blank">View</a></td>
        </tr>
    </table>

</div>

<?php include_once('/var/www/annearundelproperties.net/new/page_includes/footer.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
	$.getScript('/new/scripts/common.js');
});

</script>
</body>
</html>